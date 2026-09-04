<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Services\Billing\InvoiceService;
use App\Services\Billing\NylonPayGateway;
use Illuminate\Http\Request;

class AdminInvoiceController extends Controller
{
    public function index(Request $request, NylonPayGateway $gateway)
    {
        $filters = $request->validate(['status' => 'nullable|in:draft,issued,paid,void,overdue', 'q' => 'nullable|string|max:180']);
        $query = Invoice::query()->latest();
        if ($status = $filters['status'] ?? null) {
            if ($status === 'overdue') { $query->where('status', 'issued')->whereDate('due_date', '<', today()); }
            else { $query->where('status', $status); }
        }
        if ($q = $filters['q'] ?? null) {
            $query->where(fn ($query) => $query->where('number', 'like', "%{$q}%")->orWhere('client_name', 'like', "%{$q}%")->orWhere('client_business', 'like', "%{$q}%"));
        }
        $invoices = $query->with('items')->paginate(15)->withQueryString();
        if ($request->is('api/*')) { return InvoiceResource::collection($invoices); }
        return view('admin.invoices.index', [
            'invoices' => $invoices, 'configured' => $gateway->configured(),
            'outstanding' => Invoice::where('status', 'issued')->sum('total'),
            'paid' => Invoice::where('status', 'paid')->sum('total'),
            'drafts' => Invoice::where('status', 'draft')->count(),
        ]);
    }

    public function receipt(Invoice $invoice, \App\Services\Billing\ReceiptService $receipts)
    {
        return $receipts->download($invoice);
    }
    public function create() { return view('admin.invoices.form', ['invoice' => new Invoice(['currency' => 'UGX'])]); }
    public function edit(Invoice $invoice)
    {
        abort_unless($invoice->status === 'draft', 409, 'Only drafts can be edited.');
        return view('admin.invoices.form', ['invoice' => $invoice->load('items')]);
    }
    public function show(Request $request, Invoice $invoice)
    {
        $invoice->load('items');
        return $request->is('api/*') ? new InvoiceResource($invoice) : view('admin.invoices.show', compact('invoice'));
    }
    public function store(SaveInvoiceRequest $request, InvoiceService $service)
    {
        $invoice = $service->save($request->validated(), $request->user()->id);
        return $request->is('api/*') ? (new InvoiceResource($invoice))->response()->setStatusCode(201)
            : redirect()->route('admin.invoices.show', $invoice)->with('success', 'Draft invoice created. Review it, then issue a payment link.');
    }
    public function update(SaveInvoiceRequest $request, Invoice $invoice, InvoiceService $service)
    {
        $invoice = $service->save($request->validated(), $request->user()->id, $invoice);
        return $request->is('api/*') ? new InvoiceResource($invoice)
            : redirect()->route('admin.invoices.show', $invoice)->with('success', 'Draft updated.');
    }
    public function issue(Request $request, Invoice $invoice, InvoiceService $service)
    {
        $invoice = $service->issue($invoice);
        return $request->is('api/*') ? new InvoiceResource($invoice->load('items'))
            : back()->with('success', 'Invoice issued. Copy the link to share it with your client.');
    }
    public function void(Request $request, Invoice $invoice, InvoiceService $service)
    {
        $invoice = $service->void($invoice);
        return $request->is('api/*') ? new InvoiceResource($invoice->load('items')) : back()->with('success', 'Invoice voided.');
    }
}
