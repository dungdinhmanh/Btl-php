<?php
declare(strict_types=1);

$saleDir = 'assets/img/banner/sale';
$salePath = dirname(__DIR__) . '/' . $saleDir;

$saleLinks = [
    // 'ten-file.jpg' => 'products.php?category=pc',
];

$saleSlides = [];
foreach (is_dir($salePath) ? (scandir($salePath) ?: []) : [] as $file) {
    if (preg_match('/\.(jpe?g|png|webp|avif)$/i', $file)) {
        $name = preg_replace('/^(?:\d+[-_]+)?(?:banner[-_]+)?/i', '', pathinfo($file, PATHINFO_FILENAME));
        $saleSlides[$file] = ucfirst(str_replace(['-', '_'], ' ', (string) $name));
    }
}
uksort($saleSlides, 'strnatcasecmp');

if ($saleSlides !== []):
?>
<section class="banner-sale">
	<div class="container">
		<div class="snap-carousel" data-snap-carousel data-autoplay="1500">
			<div class="snap-track" data-snap-track>
				<?php foreach ($saleSlides as $file => $label): ?>
					<a class="snap-item" href="<?= htmlspecialchars($saleLinks[$file] ?? 'products.php', ENT_QUOTES) ?>">
						<img
							src="<?= $saleDir . '/' . rawurlencode($file) ?>"
							alt="<?= htmlspecialchars($label, ENT_QUOTES) ?>"
							loading="lazy"
						/>
					</a>
				<?php endforeach; ?>
			</div>
			<button type="button" class="snap-nav snap-prev" data-snap-nav="prev" aria-label="Banner trước">
				<i class="bi bi-chevron-left" aria-hidden="true"></i>
			</button>
			<button type="button" class="snap-nav snap-next" data-snap-nav="next" aria-label="Banner kế tiếp">
				<i class="bi bi-chevron-right" aria-hidden="true"></i>
			</button>
		</div>
	</div>
</section>
<script src="js/snap-sale.js" defer></script>
<?php endif; ?>