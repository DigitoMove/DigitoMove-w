<footer class="content-footer footer bg-footer-theme">
  <div class="{{ (!empty($containerNav) ? $containerNav : 'container-fluid') }} d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
    <div class="mb-2 mb-md-0">
      &copy; {{ now()->year }} Digito Move. Content and operations workspace.
    </div>
    <div>
      <a href="{{ route('home') }}" class="footer-link me-4">View website</a>
      <a href="{{ route('admin.profile.edit') }}" class="footer-link">Account settings</a>
    </div>
  </div>
</footer>
