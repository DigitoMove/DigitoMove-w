@extends('layouts.public')
@section('title', 'Start a project')
@section('content')
<section class="contact-layout section-shell">
  <div class="contact-copy"><span class="kicker">Start a project</span><h1>Tell us what you’re trying to <em>move forward.</em></h1><p>Share the challenge, opportunity, or rough idea. We will help identify the clearest next step.</p><div class="contact-detail"><span>Email</span><a href="mailto:{{ config('variables.email') }}">{{ config('variables.email') }}</a></div><div class="contact-detail"><span>Call</span><a href="tel:{{ config('variables.phone') }}">{{ config('variables.phoneDisplay') }}</a><a href="tel:{{ config('variables.phoneSecondary') }}">{{ config('variables.phoneSecondaryDisplay') }}</a></div><div class="contact-detail"><span>Based in</span><strong>{{ config('variables.location') }}</strong></div><a href="{{ config('variables.whatsappUrl') }}" target="_blank" rel="noopener" class="button whatsapp-button"><i class="bx bxl-whatsapp"></i> Chat on WhatsApp</a></div>
  <form method="POST" action="{{ route('contact.submit') }}" class="contact-form">@csrf
    @if(session('success'))<div class="form-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="form-error">{{ $errors->first() }}</div>@endif
    <div class="form-grid"><label><span>Your name</span><input name="name" value="{{ old('name') }}" required></label><label><span>Email address</span><input type="email" name="email" value="{{ old('email') }}" required></label></div>
    <div class="form-grid"><label><span>Phone <small>Optional</small></span><input name="phone" value="{{ old('phone') }}"></label><label><span>What can we help with?</span><select name="subject" required><option value="">Choose a focus</option>@foreach(['Website or web platform','Mobile application','Custom business software','Technology support','Training or technology support','Something else'] as $subject)<option @selected(old('subject', request('subject')) === $subject)>{{ $subject }}</option>@endforeach</select></label></div>
    <label><span>Tell us about the opportunity</span><textarea name="message" rows="7" required>{{ old('message') }}</textarea></label>
    <button class="button primary" type="submit">Send inquiry <i class="bx bx-right-arrow-alt"></i></button>
  </form>
</section>
<section class="section-shell location-panel"><div><span class="kicker">Visit Digito Move</span><h2>Find us in Kampala.</h2><p>Use the map for directions, or contact us before visiting so the right person is available.</p></div><iframe src="{{ config('variables.mapEmbedUrl') }}" width="100%" height="420" style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Digito Move location"></iframe></section>
@endsection
