<?php
declare(strict_types=1);

$promoDir = 'assets/img/banner/promo';
$promoPath = dirname(__DIR__) . '/' . $promoDir;

$promoSlides = [];
foreach (is_dir($promoPath) ? (scandir($promoPath) ?: []) : [] as $file) {
    if (preg_match('/\.(jpe?g|png|webp|avif)$/i', $file)) {
        // "01-banner-back-to-school-pc-1.jpg" -> "Back to school pc 1" (dùng cho alt + aria-label)
        $name = preg_replace('/^(?:\d+[-_]+)?(?:banner[-_]+)?/i', '', pathinfo($file, PATHINFO_FILENAME));
        $promoSlides[$file] = ucfirst(str_replace(['-', '_'], ' ', (string) $name));
    }
}
uksort($promoSlides, 'strnatcasecmp');

if ($promoSlides !== []):
?>
<section class="promo-carousel-section">
	<div class="container">
		<div id="storePromoCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-indicators">
				<?php $i = 0; foreach ($promoSlides as $file => $label): ?>
					<button
						type="button"
						data-bs-target="#storePromoCarousel"
						data-bs-slide-to="<?= $i ?>"
						<?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
						aria-label="<?= htmlspecialchars($label, ENT_QUOTES) ?>"
					></button>
				<?php $i++; endforeach; ?>
			</div>
			<div class="carousel-inner">
				<?php $i = 0; foreach ($promoSlides as $file => $label): ?>
					<div class="carousel-item promo-slide<?= $i === 0 ? ' active' : '' ?>">
						<img
							src="<?= $promoDir . '/' . rawurlencode($file) ?>"
							alt="<?= htmlspecialchars($label, ENT_QUOTES) ?>"
							loading="<?= $i === 0 ? 'eager' : 'lazy' ?>"
						/>
					</div>
				<?php $i++; endforeach; ?>
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#storePromoCarousel" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#storePromoCarousel" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
		</div>
	</div>
</section>
<?php endif; ?>