@extends('layouts.public')
@section('title', 'About us')
@section('content')
<section class="page-hero section-shell"><span class="kicker">About Digito Move</span><h1>Moving good ideas from <em>possible</em> to practical.</h1><p>We are a Kampala-based digital product company building technology that helps organisations work better and people move forward.</p></section>
<section class="section-shell narrative-grid"><div><span class="kicker">Our story</span><h2>Built from resilience, curiosity, and a bias toward action.</h2></div><div><p>Digito Move began in May 2021 as an independent software development practice. What started with focused contracts has grown into a multidisciplinary company delivering websites, mobile apps, management systems, and technology support.</p><p>We believe useful technology should be clear, accessible, and grounded in the realities of the people using it.</p></div></section>
<section class="section-shell value-grid">@foreach([['Clarity over complexity','We make decisions understandable and experiences intuitive.'],['Progress over theatre','We value useful outcomes more than impressive jargon.'],['Partnership over handoff','The strongest products are made with clients, not merely for them.']] as [$title,$copy])<article><span class="kicker">Principle</span><h3>{{ $title }}</h3><p>{{ $copy }}</p></article>@endforeach</section>
@endsection
