@extends('layouts/contentNavbarLayout')
@section('title', $invoice->number)
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/invoices.css') }}">
<div class="admin-page"><div class="page-heading"><div><a class="back-link" href="{{ route('admin.invoices.index') }}">← Invoices</a><h1>{{ $invoice->number }}</h1><p>{{ $invoice->title }}</p></div>@if($invoice->status === 'draft')<a class="btn btn-outline-primary" href="{{ route('admin.invoices.edit', $invoice) }}">Edit draft</a>@endif</div>
@include('content.shared.flash')
<div class="row g-4"><div class="col-xl-8">@include('invoices.details')</div><div class="col-xl-4"><div class="card admin-card"><div class="card-body">
@if($invoice->status === 'paid')<div class="receipt-admin"><span class="soft-badge">Payment received</span><h5 class="mt-3">Receipt ready</h5><p>Your client can download their receipt from the invoice link.</p><a class="btn btn-primary w-100 mb-3" href="{{ route('admin.invoices.receipt', $invoice) }}">Download receipt · PDF</a></div><hr>@endif
<h5>{{ $invoice->status === 'draft' ? 'Ready to send?' : 'Payment link' }}</h5>
@if($invoice->status === 'draft')<p>Review the details, then issue the invoice. Issuing locks the client details and amount.</p><form method="POST" action="{{ route('admin.invoices.issue', $invoice) }}">@csrf<button class="btn btn-primary w-100">Issue invoice</button></form>
@else<label for="share-url" class="form-label">Share with your client</label><input class="form-control mb-2" readonly id="share-url" value="{{ $invoice->share_url }}"><button type="button" class="btn btn-primary w-100 mb-2" id="copy-invoice-link">Copy invoice link</button><a class="btn btn-outline-primary w-100" href="{{ $invoice->share_url }}" target="_blank" rel="noopener noreferrer">Open client view</a><p class="small mt-3">Anyone with this link can view the invoice. Share it directly with your client.</p>@endif
@if($invoice->status === 'issued')<hr><p>Payment: <strong>{{ ucfirst($invoice->payment_status ?? 'Awaiting payment') }}</strong></p>@if(in_array($invoice->checkout_state, ['uncertain','creating']))<div class="alert alert-warning">Checkout needs reconciliation. Check reference <strong>{{ $invoice->payment_reference }}</strong> in Nylon Pay before creating any replacement invoice.</div>@endif @endif
@if(in_array($invoice->status, ['draft','issued']) && $invoice->checkout_state === 'not_started')<hr><form method="POST" action="{{ route('admin.invoices.void', $invoice) }}" onsubmit="return confirm('Void this invoice? Its payment link will no longer accept payment.');">@csrf<button class="btn btn-outline-danger w-100">Void invoice</button></form>@endif
</div></div></div></div></div>
<script>document.getElementById('copy-invoice-link')?.addEventListener('click', async function () { const input = document.getElementById('share-url'); try { await navigator.clipboard.writeText(input.value); this.textContent = 'Link copied'; } catch { input.select(); this.textContent = 'Select and copy the link above'; } });</script>
@endsection
