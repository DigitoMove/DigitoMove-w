@extends('layouts.public')
@section('title', 'Start a project')
@section('content')
<section class="contact-layout section-shell">
  <div class="contact-copy"><span class="kicker">Start a project</span><h1>Tell us what you’re trying to <em>move forward.</em></h1><p>Share the challenge, the opportunity, or even the rough idea. We will help you find the clearest next step.</p><div class="contact-detail"><span>Email</span><a href="mailto:hello@digitomove.com">hello@digitomove.com</a></div><div class="contact-detail"><span>Based in</span><strong>Kampala, Uganda</strong></div></div>
  <form method="POST" action="{{ route('contact.submit') }}" class="contact-form">@csrf
    @if(session('success'))<div class="form-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="form-error">{{ $errors->first() }}</div>@endif
    <div class="form-grid"><label><span>Your name</span><input name="name" value="{{ old('name') }}" required></label><label><span>Email address</span><input type="email" name="email" value="{{ old('email') }}" required></label></div>
    <div class="form-grid"><label><span>Phone <small>Optional</small></span><input name="phone" value="{{ old('phone') }}"></label><label><span>What can we help with?</span><select name="subject" required><option value="">Choose a focus</option><option>Website or web platform</option><option>Mobile application</option><option>Custom business software</option><option>Training or technology support</option><option>Something else</option></select></label></div>
    <label><span>Tell us about the opportunity</span><textarea name="message" rows="7" required>{{ old('message') }}</textarea></label>
    <button class="button primary" type="submit">Send inquiry <i class="bx bx-right-arrow-alt"></i></button>
  </form>
</section>
@endsection
