@extends('layouts/contentNavbarLayout')
@section('title', 'Inquiries')

@section('content')
<div class="admin-page reveal">
  <div class="page-heading"><div><span class="eyebrow">Opportunity pipeline</span><h1>Inquiries</h1><p>Move every conversation toward a clear next step.</p></div></div>
  @include('content.shared.flash')
  <div class="card admin-card">
    <div class="card-header filter-bar"><div><h5 class="mb-1">Inbound conversations</h5><small>{{ $inquiries->total() }} total inquiries</small></div>
      <form><select name="status" class="form-select" onchange="this.form.submit()"><option value="">All statuses</option>@foreach(['new','contacted','qualified','closed'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></form>
    </div>
    <div class="table-responsive"><table class="table align-middle admin-table">
      <thead><tr><th>Contact</th><th>Subject</th><th>Source</th><th>Received</th><th>Status</th></tr></thead>
      <tbody>@forelse($inquiries as $inquiry)<tr>
        <td><strong>{{ $inquiry->name }}</strong><small>{{ $inquiry->email }}</small></td>
        <td><strong>{{ $inquiry->subject ?: 'General inquiry' }}</strong><small>{{ \Illuminate\Support\Str::limit($inquiry->message, 64) }}</small></td>
        <td><span class="soft-badge">{{ ucfirst($inquiry->source) }}</span></td><td>{{ $inquiry->created_at->diffForHumans() }}</td>
        <td><form method="POST" action="{{ route('inquiries.update', $inquiry) }}">@csrf @method('PATCH')<select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">@foreach(['new','contacted','qualified','closed'] as $status)<option value="{{ $status }}" @selected($inquiry->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select></form></td>
      </tr>@empty<tr><td colspan="5" class="empty-state">No inquiries match this view.</td></tr>@endforelse</tbody>
    </table></div>
    @if($inquiries->hasPages())<div class="card-footer">{{ $inquiries->links() }}</div>@endif
  </div>
</div>
@endsection
