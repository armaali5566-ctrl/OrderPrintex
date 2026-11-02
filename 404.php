<?php
/**
 * OrderPrintex - 404 Page Not Found
 */

http_response_code(404);

$pageTitle = "Page Not Found (404) | OrderPrintex Delhi";
$pageDesc  = "The page or printing product you are looking for could not be found. Explore our commercial DTF gang sheets, UV DTF decals, or contact our prepress team.";

require_once __DIR__ . '/includes/header.php';
?>

<section class="section section-navy text-center" style="padding-top: 5rem; padding-bottom: 5rem;">
  <div class="container container-narrow reveal scale-in">
    <div style="display:inline-flex; align-items:center; justify-content:center; width:80px; height:80px; border-radius:50%; background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.3); margin-bottom:1.5rem; color:#EF4444;">
      <svg style="width:40px; height:40px; fill:currentColor;" viewBox="0 0 24 24">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
      </svg>
    </div>
    <span class="section-badge" style="background:rgba(255,255,255,0.1); color:var(--gold); border:1px solid rgba(185,154,97,0.3);">Error 404</span>
    <h1 style="font-size:3rem; margin-top:0.75rem; margin-bottom:1rem; color:#FFFFFF;">Page or Product Not Found</h1>
    <p class="section-subtitle" style="color:rgba(255,255,255,0.8); max-width:640px; margin:0 auto 2.5rem;">
      The requested URL or product slug does not exist, has been moved, or is temporarily unavailable. Browse our verified commercial printing catalog below or contact our prepress studio.
    </p>

    <div style="display:flex; justify-content:center; gap:1.25rem; flex-wrap:wrap;">
      <a href="<?= url('products.php') ?>" class="btn btn-primary btn-lg">
        <span>Browse All Products</span> &rarr;
      </a>
      <a href="<?= url('services/') ?>" class="btn btn-outline-white btn-lg">
        <span>Printing Services</span>
      </a>
      <a href="<?= url('') ?>" class="btn btn-navy btn-lg" style="border:1px solid rgba(255,255,255,0.2);">
        <span>Back to Homepage</span>
      </a>
    </div>
  </div>
</section>

<!-- Suggested Popular Products -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <div class="section-label">Popular Catalog Items</div>
      <h2>Looking For Commercial Printing Transfers?</h2>
      <p class="section-subtitle">Jump directly to our most in-demand transfer gang sheets and merchandise items:</p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:2rem; margin-top:2rem;">
      <div class="product-card" style="padding:1.5rem; text-align:center;">
        <div style="height:180px; background:#F8F5F0; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; overflow:hidden;">
          <img src="<?= url('assets/images/dtf_fabric_print.jpg') ?>" alt="DTF Gang Sheet" style="max-height:160px; max-width:90%; object-fit:contain;">
        </div>
        <h3 style="font-size:1.15rem; margin-bottom:0.5rem; color:var(--navy);">Commercial DTF Gang Sheets</h3>
        <p style="font-size:0.88rem; color:var(--text-muted); margin-bottom:1rem;">22-inch roll with high-opacity white ink for dark & light garments.</p>
        <a href="<?= product_url('custom-dtf-gang-sheet') ?>" class="btn btn-sm btn-navy" style="width:100%;">View Details &rarr;</a>
      </div>

      <div class="product-card" style="padding:1.5rem; text-align:center;">
        <div style="height:180px; background:#F8F5F0; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; overflow:hidden;">
          <img src="<?= url('assets/images/uv_dtf_drinkware.jpg') ?>" alt="UV DTF Decals" style="max-height:160px; max-width:90%; object-fit:contain;">
        </div>
        <h3 style="font-size:1.15rem; margin-bottom:0.5rem; color:var(--navy);">UV DTF 3D Hard Decals</h3>
        <p style="font-size:0.88rem; color:var(--text-muted); margin-bottom:1rem;">Scratchproof, waterproof embossed decals for glass, metal, and plastic.</p>
        <a href="<?= product_url('custom-uv-dtf-decal-sheet') ?>" class="btn btn-sm btn-navy" style="width:100%;">View Details &rarr;</a>
      </div>

      <div class="product-card" style="padding:1.5rem; text-align:center;">
        <div style="height:180px; background:#F8F5F0; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; overflow:hidden;">
          <img src="<?= url('assets/images/custom_tshirt_apparel.jpg') ?>" alt="Streetwear Oversized Tee" style="max-height:160px; max-width:90%; object-fit:contain;">
        </div>
        <h3 style="font-size:1.15rem; margin-bottom:0.5rem; color:var(--navy);">240 GSM Streetwear Tee</h3>
        <p style="font-size:0.88rem; color:var(--text-muted); margin-bottom:1rem;">Drop-shoulder relaxed oversized t-shirts with photographic DTF prints.</p>
        <a href="<?= product_url('streetwear-oversized-tee') ?>" class="btn btn-sm btn-navy" style="width:100%;">View Details &rarr;</a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
