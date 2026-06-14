@extends('layouts.public')
@section('title', 'Services')
@section('content')
<section class="page-hero section-shell"><span class="kicker">Capabilities</span><h1>One partner for your next <em>digital move.</em></h1><p>From a sharp first idea to a dependable product in people’s hands, we bring the disciplines together.</p></section>
<section class="section-shell capability-list">
@foreach([
 ['01','Web design & development','Fast, accessible marketing sites and web platforms designed around measurable goals.',['Strategy and UX','Interface design','Laravel development','Performance and SEO']],
 ['02','Mobile app development','Useful mobile experiences for customers, teams, and communities.',['Product discovery','Android and iOS','API integration','Launch support']],
 ['03','Custom business software','Systems designed around the way your organisation actually operates.',['Workflow design','Management systems','Dashboards and reporting','Automation']],
 ['04','Training & technology support','Practical capability-building that leaves your team more confident.',['Digital literacy','Programming training','Computer maintenance','Technology advisory']],
] as [$number,$title,$copy,$items])
<article class="capability-row reveal"><span>{{ $number }}</span><div><h2>{{ $title }}</h2><p>{{ $copy }}</p></div><ul>@foreach($items as $item)<li>{{ $item }}</li>@endforeach</ul><a href="{{ route('contact') }}" aria-label="Discuss {{ $title }}"><i class="bx bx-right-arrow-alt"></i></a></article>
@endforeach
</section>
@endsection
