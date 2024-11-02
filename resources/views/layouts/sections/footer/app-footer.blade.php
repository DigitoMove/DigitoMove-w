<footer class="py-5 bg-dark">
    <div
        class="{{ !empty($containerNav) ? $containerNav : 'container-fluid' }} d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            ©
            <script>
                document.write(new Date().getFullYear())
            </script>
            , made with ❤️ by <a href="{{ config('variables.creatorURL') }}" target="_blank"
                class="footer-link fw-bolder">{{ config('variables.creatorName') }}</a>
        </div>
        <div>
            <a href="{{ config('variables.githubURL') }}" target="_blank" class="footer-link me-4">
                <span class="bi bi-github"></span>GitHub</a>
            <a href="{{ config('variables.twitterURL') }}" target="_blank" class="footer-link me-4">x.com</a>
            <a href="{{ config('variables.instagramURL') }}" target="_blank" class="footer-link me-4">Instagram</a>
            <a href="{{ config('variables.facebookUrl') }}" target="_blank"
                class="footer-link d-none d-sm-inline-block">Facebook</a>
        </div>
    </div>
</footer>
<!--/ Footer-->
