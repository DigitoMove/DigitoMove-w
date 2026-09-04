@extends('layouts/contentNavbarLayout')
@section('title', 'Invoices')
@section('content')
<div class="admin-page">
  <div class="page-heading"><div><span class="eyebrow">Billing</span><h1>Invoices</h1><p>From work delivered to payments received.</p></div><a class="btn btn-primary" href="{{ route('admin.invoices.create') }}"><i class="bx bx-plus me-1"></i>Create invoice</a></div>
  @include('content.shared.flash')
  @unless($configured)<div class="alert alert-warning">Nylon Pay is not connected yet. You can prepare and issue invoices; online payment will be available once credentials are configured.</div>@endunless
  <div class="row g-4 mb-4">
    <div class="col-md-4"><div class="card admin-card"><div class="card-body"><span class="text-muted">Outstanding · UGX</span><h2 class="mt-2 mb-0">{{ number_format($outstanding) }}</h2></div></div></div>
    <div class="col-md-4"><div class="card admin-card"><div class="card-body"><span class="text-muted">Received · UGX</span><h2 class="mt-2 mb-0">{{ number_format($paid) }}</h2></div></div></div>
    <div class="col-md-4"><div class="card admin-card"><div class="card-body"><span class="text-muted">Draft invoices</span><h2 class="mt-2 mb-0">{{ $drafts }}</h2></div></div></div>
  </div>
  <div class="card admin-card"><div class="card-body">
    <form method="GET" class="row g-2 mb-4"><div class="col-md-7"><label class="visually-hidden" for="q">Search invoices</label><input id="q" name="q" class="form-control" placeholder="Search by client, business or invoice number" value="{{ request('q') }}"></div><div class="col-md-3"><label class="visually-hidden" for="status">Status</label><select id="status" name="status" class="form-select"><option value="">All statuses</option>@foreach(['draft','issued','overdue','paid','void'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div><div class="col-md-2"><button class="btn btn-outline-primary w-100">Filter</button></div></form>
    <div class="table-responsive"><table class="table"><thead><tr><th>Invoice / client</th><th>Due date</th><th>Status</th><th class="text-end">Amount</th><th></th></tr></thead><tbody>
    @forelse($invoices as $invoice)<tr><td><a href="{{ route('admin.invoices.show', $invoice) }}"><strong>{{ $invoice->number }}</strong></a><div>{{ $invoice->client_business ?: $invoice->client_name }}</div><small class="text-muted">{{ $invoice->title }}</small></td><td>{{ $invoice->due_date?->format('d M Y') ?? '—' }}</td><td><span class="badge bg-label-{{ $invoice->status === 'paid' ? 'success' : ($invoice->is_overdue ? 'danger' : 'primary') }}">{{ $invoice->is_overdue ? 'Overdue' : ucfirst($invoice->status) }}</span></td><td class="text-end">UGX {{ number_format($invoice->total) }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.invoices.show', $invoice) }}">View</a></td></tr>
    @empty<tr><td colspan="5"><div class="empty-state"><h4>No invoices yet</h4><p>Create an invoice with your client details and the work you’re billing for.</p><a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">Create your first invoice</a></div></td></tr>@endforelse
    </tbody></table></div><div class="mt-3">{{ $invoices->links() }}</div>
  </div></div>
</div>
@endsection
