@extends('layouts/contentNavbarLayout')
@section('title', $invoice->exists ? 'Edit invoice' : 'Create invoice')
@section('content')
<div class="admin-page"><div class="page-heading"><div><a class="back-link" href="{{ route('admin.invoices.index') }}">← Invoices</a><h1>{{ $invoice->exists ? 'Edit draft' : 'Create invoice' }}</h1><p>Add your client and the work you’re billing for. Review before sharing.</p></div><span class="soft-badge">Draft</span></div>
@if($errors->any())<div class="alert alert-danger" role="alert"><strong>Please check the invoice.</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ $invoice->exists ? route('admin.invoices.update', $invoice) : route('admin.invoices.store') }}" id="invoice-form">
@csrf @if($invoice->exists) @method('PUT') @endif
<div class="row g-4"><div class="col-xl-8">
  <div class="card admin-card mb-4"><div class="card-header"><h5 class="mb-0">Bill to</h5></div><div class="card-body row g-3">
  @foreach(['client_name' => 'Client name', 'client_email' => 'Email address', 'client_business' => 'Business / organization', 'client_phone' => 'Phone number'] as $field => $label)
    <div class="col-md-6"><label class="form-label" for="{{ $field }}">{{ $label }}{{ in_array($field, ['client_name','client_email']) ? ' *' : '' }}</label><input id="{{ $field }}" name="{{ $field }}" type="{{ $field === 'client_email' ? 'email' : 'text' }}" class="form-control" value="{{ old($field, $invoice->$field) }}" @required(in_array($field, ['client_name','client_email']))></div>
  @endforeach
  <div class="col-12"><label class="form-label" for="client_address">Billing address</label><textarea class="form-control" id="client_address" name="client_address" rows="2">{{ old('client_address', $invoice->client_address) }}</textarea></div>
  </div></div>
  <div class="card admin-card"><div class="card-header"><h5 class="mb-0">Invoice details</h5></div><div class="card-body">
    <label class="form-label" for="title">What is this invoice for? *</label><input class="form-control mb-4" id="title" name="title" placeholder="e.g. Website design and development" value="{{ old('title', $invoice->title) }}" required maxlength="180">
    <div id="invoice-items">
    @foreach(old('items', $invoice->exists ? $invoice->items->toArray() : [['description'=>'','quantity'=>1,'unit_price'=>'']]) as $item)
    <div class="invoice-item border rounded p-3 mb-3"><div class="row g-2">
      <div class="col-md-6"><label class="form-label">Item description<input data-field="description" name="items[{{ $loop->index }}][description]" class="form-control" value="{{ $item['description'] ?? '' }}" required maxlength="180"></label></div>
      <div class="col-5 col-md-2"><label class="form-label">Quantity<input data-field="quantity" name="items[{{ $loop->index }}][quantity]" class="form-control" type="number" min="1" max="10000" step="1" value="{{ $item['quantity'] ?? 1 }}" required></label></div>
      <div class="col-7 col-md-4"><label class="form-label">Unit price · UGX<input data-field="unit_price" name="items[{{ $loop->index }}][unit_price]" class="form-control" type="number" min="1" max="1000000000" step="1" value="{{ $item['unit_price'] ?? '' }}" required></label></div>
    </div><div class="d-flex justify-content-between align-items-center"><button type="button" class="btn btn-sm btn-outline-danger remove-item">Remove item</button><strong class="line-total">UGX 0</strong></div></div>
    @endforeach
    </div>
    <button type="button" id="add-item" class="btn btn-outline-primary"><i class="bx bx-plus"></i> Add item</button>
    <div class="mt-4"><label class="form-label" for="notes">Notes / payment terms</label><textarea id="notes" name="notes" class="form-control" rows="4" placeholder="Any details your client should know">{{ old('notes', $invoice->notes) }}</textarea></div>
  </div></div>
</div><div class="col-xl-4"><div class="card admin-card"><div class="card-header"><h5 class="mb-0">Summary</h5></div><div class="card-body">
  <label class="form-label" for="currency">Currency</label><select id="currency" name="currency" class="form-select mb-3"><option value="UGX">UGX — Ugandan shilling</option></select>
  <label class="form-label" for="due_date">Due date</label><input id="due_date" type="date" name="due_date" class="form-control mb-4" min="{{ today()->format('Y-m-d') }}" value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}">
  <div class="border-top pt-3"><span class="text-muted">Total due</span><h2 id="invoice-total" class="mt-2" aria-live="polite">UGX 0</h2><small>Minimum invoice amount: UGX 500. Prices are entered in whole shillings.</small></div>
  <button class="btn btn-primary w-100 mt-4">Save draft</button>
  <p class="small text-muted mt-3 mb-0">You’ll review the invoice before issuing its payment link.</p>
</div></div></div></div></form></div>
<script src="{{ asset('assets/js/invoice-editor.js') }}" defer></script>
@endsection
