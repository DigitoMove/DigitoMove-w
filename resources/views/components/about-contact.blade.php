<div>
    <!-- Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci -->
    <div class="card">
        <div class="card-body">
            <h3 class="card-title text-center">Our Contacts</h3>
            <div class="text-center">
                <div>
                    <a href="tel:{{ env('PHONE_01') }}" target="_blank" class="btn btn-sm btn-dark mx-1 my-1">
                        <i class="bx bx-phone"></i> +256701-822-382
                    </a>
                    <a href="tel:{{ env('PHONE_02') }}" target="_blank" class="btn btn-sm btn-dark mx-1 my-1">
                        <i class="bx bx-phone"></i> +256777-522-214
                    </a>

                </div>
                <div>
                    <a href="{{ env('SOCIAL_WHATSAPP') }}" target="_blank" class="btn btn-sm btn-success mx-1 my-1">
                        <i class="bx bxl-whatsapp"></i> +256777-522-214
                    </a>
                    <a href="{{ env('SOCIAL_FACEBOOK') }}" target="_blank" class="btn btn-sm btn-primary mx-1 my-1">
                        <i class="bx bxl-facebook"></i> @DigitoMove
                    </a>
                    <a href="{{ env('SOCIAL_TWITTER') }}" target="_blank" class="btn btn-sm btn-dark mx-1 my-1">
                        <i class="bx bxl-twitter"></i> @DigitoMove
                    </a>
                    <a href="{{ env('SOCIAL_YOUTUBE') }}" target="_blank" class="btn btn-sm btn-danger mx-1 my-1">
                        <i class="bx bxl-youtube"></i> @DigitoMove
                    </a>
                    <a href="{{ env('SOCIAL_INSTAGRAM') }}" target="_blank" class="btn btn-sm btn-danger mx-1 my-1">
                        <i class="bx bxl-instagram"></i> @DigitoMove
                    </a>

                </div>

                <div>
                    <a href="mailto:{{ env('MAIL_INFO') }}" target="_blank" class="btn btn-sm btn-dark mx-1 my-1">
                        <i class="bx bx-envelope"></i> {{ env('MAIL_INFO') }}
                    </a>
                    <a href="mailto:{{ env('MAIL_GMAIL_01') }}" target="_blank" class="btn btn-sm btn-danger mx-1 my-1">
                        <i class="bx bx-mail-send"></i> {{ env('MAIL_GMAIL_01') }}
                    </a>
                    <a href="mailto:{{ env('MAIL_GMAIL_02') }}" target="_blank"
                        class="btn btn-sm btn-danger mx-1 my-1">
                        <i class="bx bx-mail-send"></i> {{ env('MAIL_GMAIL_02') }}
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
