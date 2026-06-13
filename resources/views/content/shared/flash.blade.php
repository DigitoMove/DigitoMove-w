@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
