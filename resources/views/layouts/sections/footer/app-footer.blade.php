<footer class="py-5 bg-dark">
    <div
        class="{{ !empty($containerNav) ? $containerNav : 'container-fluid' }} d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            ©
            <script>
                document.write(new Date().getFullYear())
            </script>
            , made with ❤️ by <a
                href="{{ !empty(config('variables.creatorUrl')) ? config('variables.creatorUrl') : '' }}" target="_blank"
                class="footer-link fw-bolder">{{ !empty(config('variables.creatorName')) ? config('variables.creatorName') : '' }}</a>
        </div>
        <div>
            <a href="{{ config('variables.githubUrl') ? config('variables.githubUrl') : '#' }}" target="_blank"
                class="footer-link me-4">
                <span class="bi bi-github"></span>
                GitHub</a>
            <a href="{{ config('variables.twitterUrl') ? config('variables.twitterUrl') : '#' }}" target="_blank"
                class="footer-link me-4">Twitter</a>
            <a href="{{ config('variables.facebookUrl') ? config('variables.facebookUrl') : '#' }}" target="_blank"
                class="footer-link d-none d-sm-inline-block">Facebook</a>
        </div>
    </div>
</footer>
<!--/ Footer-->
