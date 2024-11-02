<div>
    <!-- Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci -->
    <div class="card">
        <div class="card-body">
            <h3 class="card-title text-center">Our Contacts</h3>
            <div class="text-center">
                <div>
                    <a href="tel:{{ config('variables.telNumber') }}" target="_blank"
                        class="btn btn-sm btn-dark mx-1 my-1">
                        <i class="bx bx-phone"></i> {{ config('variables.telNumber') }}
                    </a>
                    <a href="tel:{{ config('variables.telNumber2') }}" target="_blank"
                        class="btn btn-sm btn-dark mx-1 my-1">
                        <i class="bx bx-phone"></i> {{ config('variables.telNumber2') }}
                    </a>
                </div>
                <div>
                    <a href="{{ config('variables.whatsappURL') }}" target="_blank"
                        class="btn btn-sm btn-success mx-1 my-1">
                        <i class="bx bxl-whatsapp"></i> {{ config('variables.whatsappUsername') }}
                    </a>
                    <a href="{{ config('variables.facebookURL') }}" target="_blank"
                        class="btn btn-sm btn-primary mx-1 my-1">
                        <i class="bx bxl-facebook"></i> {{ config('variables.facebookUsername') }}
                    </a>
                    <a href="{{ config('variables.twitterURL') }}" target="_blank"
                        class="btn btn-sm btn-dark mx-1 my-1">
                        <i class="bx bxl-twitter"></i> {{ config('variables.twitterUsername') }}
                    </a>
                    <a href="{{ config('variables.youtubeURL') }}" target="_blank"
                        class="btn btn-sm btn-danger mx-1 my-1">
                        <i class="bx bxl-youtube"></i> {{ config('variables.youtubeUsername') }}
                    </a>
                    <a href="{{ config('variables.instagramURL') }}" target="_blank"
                        class="btn btn-sm btn-danger mx-1 my-1">
                        <i class="bx bxl-instagram"></i> {{ config('variables.instagramUsername') }}
                    </a>

                </div>

                <div>
                    <a href="mailto:{{ env('MAIL_INFO') }}" target="_blank" class="btn btn-sm btn-dark mx-1 my-1">
                        <i class="bx bx-envelope"></i> {{ env('MAIL_INFO') }}
                    </a>
                    <a href="mailto:{{ env('MAIL_GMAIL_01') }}" target="_blank"
                        class="btn btn-sm btn-danger mx-1 my-1">
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
