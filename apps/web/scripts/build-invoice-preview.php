<?php
// Build a static sharing card; deploy the PNG, not this development script.
// Usage: php scripts/build-invoice-preview.php /path/to/regular.ttf /path/to/bold.ttf
$regular = $argv[1] ?? '';
$bold = $argv[2] ?? '';
if (!is_file($regular) || !is_file($bold)) { fwrite(STDERR, "Provide regular and bold TrueType font paths.\n"); exit(1); }
$im = imagecreatetruecolor(1200, 630);
$color = fn ($r, $g, $b) => imagecolorallocate($im, $r, $g, $b);
$bg = $color(20, 48, 51); $green = $color(29, 80, 69); $white = $color(255, 255, 255);
$muted = $color(184, 214, 204); $ink = $color(29, 60, 65); $pale = $color(232, 242, 237);
imagefill($im, 0, 0, $bg);
imagefilledellipse($im, 1200, 5, 850, 850, $green);
imagefilledrectangle($im, 65, 75, 71, 113, $muted);
imagettftext($im, 27, 0, 89, 107, $white, $bold, 'Digito Move');
imagettftext($im, 16, 0, 66, 220, $muted, $bold, 'CLIENT INVOICE');
imagettftext($im, 53, 0, 63, 305, $white, $bold, 'Your invoice,');
imagettftext($im, 53, 0, 63, 380, $white, $bold, 'ready to view.');
imagettftext($im, 22, 0, 66, 442, $muted, $regular, 'View the details. Pay with confidence.');
imagettftext($im, 17, 0, 66, 560, $muted, $regular, 'digitomove.com');
imagefilledrectangle($im, 807, 143, 1103, 502, $white);
imagefilledrectangle($im, 839, 180, 1071, 186, $green);
imagettftext($im, 23, 0, 839, 235, $ink, $bold, 'Invoice');
foreach ([278, 313, 348] as $y) {
 imagefilledrectangle($im, 839, $y, 984, $y + 8, $pale);
 imagefilledrectangle($im, 1018, $y, 1071, $y + 8, $pale);
}
imagefilledrectangle($im, 839, 406, 1071, 464, $green);
imagettftext($im, 17, 0, 868, 443, $white, $bold, 'View invoice');
imagepng($im, __DIR__.'/../public/assets/img/social/invoice-preview.png');
imagedestroy($im);
