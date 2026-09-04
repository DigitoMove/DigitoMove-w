<!DOCTYPE html>
<html><head><meta charset="utf-8"><style>
@page { margin: 42px 44px 54px; }
body { font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.6; color: #213b43; }
.brand { font-size: 16px; font-weight: bold; color: #175c50; }
.top { width: 100%; border-bottom: 3px solid #175c50; padding-bottom: 20px; }
.top td { vertical-align: top; }.right { text-align: right; }
h1 { font-size: 32px; line-height: 1.2; letter-spacing: -1px; margin: 16px 0 6px; }
.kicker { font-size: 8px; letter-spacing: 1.7px; text-transform: uppercase; color: #71848a; }
.badge { background: #e6f3ec; color: #17634f; padding: 7px 14px; font-size: 9px; font-weight: bold; }
.parties { width: 100%; margin: 25px 0; table-layout: fixed; }.parties td { width: 50%; vertical-align: top; padding-right: 18px; word-wrap: break-word; }
h3 { font-size: 12px; margin: 5px 0; } p { margin: 4px 0; }.muted { color: #667e86; }
.items { width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 18px; }
.items th { background: #f0f5f4; font-size: 8px; text-transform: uppercase; letter-spacing: .5px; padding: 11px 8px; text-align: left; }
.items td { border-bottom: 1px solid #e1e9e7; padding: 13px 8px; word-wrap: break-word; vertical-align: top; }.items .right { text-align:right; } tr { page-break-inside: avoid; } thead { display: table-header-group; }
.summary { margin-top: 20px; width: 100%; background: #175c50; color: #fff; padding: 18px; }.summary td { vertical-align: middle; }.summary strong { font-size: 24px; }
.closing { page-break-inside: avoid; }.reference { border: 1px solid #e1e9e7; padding: 14px 18px; margin-top: 18px; }.thanks { margin-top: 22px; font-size: 12px; }.footer { position: fixed; bottom: -32px; left: 0; right: 0; color: #71848a; font-size: 8px; border-top: 1px solid #e1e9e7; padding-top: 8px; }
</style></head><body>
<table class="top"><tr><td><div class="brand">{{ $details['business_name'] }}</div><h1>Payment receipt</h1><div class="muted">{{ $receipt->number }}</div></td><td class="right"><span class="badge">PAID IN FULL</span><p style="margin-top:22px" class="kicker">Payment received</p><strong>{{ $receipt->issued_at->format('d M Y') }}</strong></td></tr></table>
<table class="parties"><tr><td><div class="kicker">Received from</div><h3>{{ $details['client_business'] ?: $details['client_name'] }}</h3>@if($details['client_business'])<p>{{ $details['client_name'] }}</p>@endif<p>{{ $details['client_email'] }}</p><p class="muted">{!! nl2br(e($details['client_address'] ?? '')) !!}</p></td><td><div class="kicker">Received by</div><h3>{{ $details['business_name'] }}</h3><p>{{ $details['business_email'] }}</p><p>{{ $details['business_email_secondary'] ?? '' }}</p><p>{{ $details['business_phone'] ?? '' }}<br>{{ $details['business_phone_secondary'] ?? '' }}</p><p class="muted">{!! nl2br(e($details['business_address'] ?? '')) !!}</p></td></tr></table>
<div class="kicker">Invoice {{ $details['invoice_number'] }}</div><h3>{{ $details['title'] }}</h3>
<table class="items"><thead><tr><th style="width:44%">Description</th><th style="width:8%">Qty</th><th style="width:22%" class="right">Unit price</th><th style="width:26%" class="right">Amount ({{ $details['currency'] }})</th></tr></thead><tbody>@foreach($details['items'] as $item)<tr><td>{{ $item['description'] }}</td><td>{{ $item['quantity'] }}</td><td class="right">{{ number_format($item['unit_price']) }}</td><td class="right">{{ number_format($item['total']) }}</td></tr>@endforeach</tbody></table>
<div class="closing"><table class="summary"><tr><td>Amount paid<br><span style="font-size:9px">Balance remaining: {{ $details['currency'] }} 0</span></td><td class="right"><strong>{{ $details['currency'] }} {{ number_format($details['total']) }}</strong></td></tr></table>
<div class="reference"><div class="kicker">Payment record</div>@if(($details['confirmation_source'] ?? 'provider') === 'admin')<p>Payment recorded by {{ $details['business_name'] }}</p><p>Method: {{ ucwords(str_replace('_', ' ', $details['payment_method'])) }}</p>@else<p>Processed through {{ $details['provider'] }}</p>@endif<p>Reference: <strong>{{ $details['payment_reference'] }}</strong></p><p class="muted">Confirmed {{ $receipt->issued_at->format('d M Y, H:i') }} {{ $receipt->issued_at->timezoneName }}</p></div>
<p class="thanks">Thank you for your business.</p><p class="muted">Keep this receipt for your records. Your payment has been confirmed.</p></div>
<div class="footer">{{ $receipt->number }} &nbsp; | &nbsp; {{ $details['business_name'] }} &nbsp; | &nbsp; Payment receipt</div>
</body></html>
