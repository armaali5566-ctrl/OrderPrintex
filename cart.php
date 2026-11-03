<?php
/**
 * OrderPrintex - Shopping Cart Workbench
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "Shopping Cart & Prepress Orders | OrderPrintex";
$pageDesc  = "Review your selected print products, DTF transfer rolls, custom garments, and calculated GST breakdown.";
$canonical = cart_url();

require_once __DIR__ . '/includes/header.php';

// In a real session, items would be pulled from $_SESSION['cart']
$cartItems = $_SESSION['cart'] ?? [];
?>

<!-- Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Order Review</span>
      </div>
      <h1 class="page-intro-title">Your Shopping Cart</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Review your custom transfer orders, selected sizes, quantities, and digital proof options before checkout.
      </p>
    </div>
  </div>
</section>

<section class="section section-white" style="padding-top:3.5rem; padding-bottom:4.5rem;">
  <div class="container container-readable">

    <?php if (empty($cartItems)): ?>
      <div class="card" style="background:var(--bg-surface); border:1px dashed var(--border-color); border-radius:var(--radius-md); padding:3.5rem 2rem; text-align:center; box-shadow: var(--shadow-card);">
        <div style="font-size:3rem; margin-bottom:1rem; color:var(--royal);">🛒</div>
        <h2 style="font-size:1.5rem; color:var(--navy); font-weight:800; margin-bottom:0.5rem;">Your Cart is Currently Empty</h2>
        <p style="color:var(--slate); font-size:0.95rem; max-width:480px; margin:0 auto 2rem; line-height:1.6;">
          You haven't configured any custom DTF gang rolls, UV decals, or finished garments yet. Explore our commercial catalog to configure size, materials, and quantity tiers.
        </p>
        <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
          <a href="<?= url('products/') ?>" class="btn btn-primary btn-lg">
            <span>Browse Products Catalog</span> &rarr;
          </a>
          <a href="<?= url('get-quote/') ?>" class="btn btn-outline btn-lg">
            Upload Custom Artwork
          </a>
        </div>
      </div>
    <?php else: ?>
      <!-- Cart Table & Checkout Summary -->
      <div class="cart-layout-grid">
        <div class="card" style="background:#181818; border:1px solid #40351E; border-radius:10px; padding:1.75rem; box-shadow: 0 8px 24px rgba(0,0,0,0.7);">
          <h3 style="font-size:1.15rem; color:#F5F1E8; font-weight:700; margin-bottom:1rem;">Selected Print Products</h3>
          <!-- Items List -->
        </div>

        <div class="card" style="background:#181818; border:1px solid #40351E; border-radius:10px; padding:1.75rem; box-shadow: 0 8px 24px rgba(0,0,0,0.7);">
          <h3 style="font-size:1.15rem; color:#F5F1E8; font-weight:700; margin-bottom:1rem;">Order Summary</h3>
          <a href="<?= checkout_url() ?>" class="btn btn-primary btn-block" style="text-align:center; justify-content:center;">
            Proceed to Checkout &rarr;
          </a>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

