@extends('layouts/contentNavbarLayout')
@section('title', $invoice->number)
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/invoices.css') }}">
<div class="admin-page"><div class="page-heading"><div><a class="back-link" href="{{ route('admin.invoices.index') }}">← Invoices</a><h1>{{ $invoice->number }}</h1><p>{{ $invoice->title }}</p></div>@if($invoice->status === 'draft')<a class="btn btn-outline-primary" href="{{ route('admin.invoices.edit', $invoice) }}">Edit draft</a>@endif</div>
@include('content.shared.flash')
@if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
<div class="row g-4"><div class="col-xl-8">@include('invoices.details')</div><div class="col-xl-4"><div class="card admin-card"><div class="card-body">
@if($invoice->status === 'paid')<div class="receipt-admin"><span class="soft-badge">Payment received</span><h5 class="mt-3">Receipt ready</h5><p>Your client can download their receipt from the invoice link.</p><a class="btn btn-primary w-100 mb-3" href="{{ route('admin.invoices.receipt', $invoice) }}">Download receipt · PDF</a><a class="btn btn-outline-primary w-100 mb-3" href="{{ route('admin.invoices.receipt', $invoice) }}?print=1" target="_blank" rel="noopener">Print receipt</a></div><hr>@endif
@if(in_array($invoice->status, ['draft','issued']))
<details class="mb-4" @if($errors->any()) open @endif><summary class="btn btn-primary w-100">Mark as paid</summary>
<form class="mt-3" method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}">@csrf
<p>Record full payment of <strong>{{ $invoice->currency }} {{ number_format($invoice->total) }}</strong> and issue the receipt.</p>
@if($invoice->checkout_state !== 'not_started')<div class="alert alert-warning">A Nylon Pay checkout has already started. Confirm the payment and resolve any active checkout with Nylon Pay to avoid collecting twice.</div>@endif
<label for="payment-method" class="form-label">Payment method</label><select id="payment-method" name="payment_method" class="form-select mb-3" required>@foreach(['cash'=>'Cash','bank_transfer'=>'Bank transfer','mobile_money'=>'Mobile money','other'=>'Other'] as $value=>$label)<option value="{{ $value }}" @selected(old('payment_method') === $value)>{{ $label }}</option>@endforeach</select>
<label for="payment-reference" class="form-label">Payment reference (optional)</label><input id="payment-reference" name="payment_reference" class="form-control mb-3" maxlength="180" value="{{ old('payment_reference') }}">
<label for="payment-note" class="form-label">Internal payment note</label><textarea id="payment-note" name="payment_note" class="form-control mb-3" rows="3" maxlength="2000" placeholder="How was the payment received or verified?" required>{{ old('payment_note') }}</textarea>
<label class="d-flex gap-2 mb-3"><input type="checkbox" name="confirm_payment" value="1" required><span>I confirm the full amount has been received.</span></label>
<button class="btn btn-primary w-100">Confirm payment & issue receipt</button>
</form></details>
@endif
@if($invoice->marked_paid_by)<p class="small text-muted">Recorded manually · {{ ucwords(str_replace('_',' ',$invoice->manual_payment_method)) }}<br>{{ $invoice->paid_at->format('d M Y, H:i') }}</p><p class="small">{{ $invoice->manual_payment_note }}</p>@endif
<h5>{{ $invoice->status === 'draft' ? 'Ready to send?' : 'Payment link' }}</h5>
@if($invoice->status === 'draft')<p>Review the details, then issue the invoice. Issuing locks the client details and amount.</p><form method="POST" action="{{ route('admin.invoices.issue', $invoice) }}">@csrf<button class="btn btn-primary w-100">Issue invoice</button></form>
@else<label for="share-url" class="form-label">Share with your client</label><input class="form-control mb-2" readonly id="share-url" value="{{ $invoice->share_url }}"><button type="button" class="btn btn-primary w-100 mb-2" id="copy-invoice-link">Copy invoice link</button><p class="small text-muted">Shared links include a Digito Move preview card in supported messaging apps.</p><a class="btn btn-outline-primary w-100" href="{{ $invoice->share_url }}" target="_blank" rel="noopener noreferrer">Open client view</a><p class="small mt-3">Anyone with this link can view the invoice. Share it directly with your client.</p>@endif
@if($invoice->status === 'issued')<hr><p>Payment: <strong>{{ ucfirst($invoice->payment_status ?? 'Awaiting payment') }}</strong></p>@if(in_array($invoice->checkout_state, ['uncertain','creating']))<div class="alert alert-warning">Checkout needs reconciliation. Check reference <strong>{{ $invoice->payment_reference }}</strong> in Nylon Pay before creating any replacement invoice.</div>@endif @endif
@if(in_array($invoice->status, ['draft','issued']) && $invoice->checkout_state === 'not_started')<hr><form method="POST" action="{{ route('admin.invoices.void', $invoice) }}" onsubmit="return confirm('Void this invoice? Its payment link will no longer accept payment.');">@csrf<button class="btn btn-outline-danger w-100">Void invoice</button></form>@endif
</div></div></div></div></div>
<script>document.getElementById('copy-invoice-link')?.addEventListener('click', async function () { const input = document.getElementById('share-url'); try { await navigator.clipboard.writeText(input.value); this.textContent = 'Link copied'; } catch { input.select(); this.textContent = 'Select and copy the link above'; } });</script>
@endsection
