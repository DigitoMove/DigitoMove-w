<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $inquiries = Inquiry::when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()->paginate(12)->withQueryString();

        return view('content.inquiries.index', compact('inquiries'));
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $data = $request->validate(['status' => ['required', Rule::in(['new', 'contacted', 'qualified', 'closed'])]]);
        $data['read_at'] = $inquiry->read_at ?: now();
        $inquiry->update($data);

        return back()->with('success', 'Inquiry status updated.');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return back()->with('success', 'Inquiry deleted.');
    }
}
