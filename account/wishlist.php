<?php
/**
 * OrderPrintex - Customer Wishlist Page
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$user = auth_user();
$pageTitle = "My Saved Wishlist | OrderPrintex";
$canonical = url('account/wishlist.php');

$wishlistProducts = [];
if ($user) {
    try {
        $sql = "
            SELECT p.id, p.sku, p.title, p.slug, p.short_desc, p.image_url,
                   p.starting_price, p.price_unit, p.min_order, c.name as category_name
            FROM user_wishlists w
            JOIN products p ON w.product_id = p.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE w.user_id = :uid
            ORDER BY w.created_at DESC
        ";
        $wishlistProducts = db_fetch_all($sql, ['uid' => $user['id']]);
    } catch (Exception $e) {
        $wishlistProducts = [];
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header" style="background:linear-gradient(135deg, #0d0b0b 0%, #171212 100%); border-bottom:1px solid #40351E; padding:3.5rem 0 2.5rem;">
  <div class="container text-center">
    <div style="font-size:0.85rem; font-weight:700; color:#D4AF37; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.5rem;">
      Personal Collection
    </div>
    <h1 style="font-size:clamp(2rem, 3.5vw, 2.8rem); color:#F5F1E8; font-weight:800; margin:0 0 0.5rem;">
      My Saved Wishlist
    </h1>
    <p style="color:#B8B1A3; max-width:600px; margin:0 auto; font-size:0.95rem;">
      Save your favorite custom merchandise, gang sheet rolls, and printing specs for quick volume ordering.
    </p>
  </div>
</div>

<main style="background:#080808; padding:3.5rem 0 5rem; min-height:60vh;">
  <div class="container">
    
    <div id="wishlistContainer" class="products-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:1.75rem;">
      <?php if (!empty($wishlistProducts)): ?>
        <?php foreach ($wishlistProducts as $p): ?>
          <article class="product-card" id="wishItem_<?= $p['id'] ?>" style="background:#181818; border:1px solid #40351E; border-radius:10px; overflow:hidden; display:flex; flex-direction:column;">
            <div class="product-media" style="position:relative; background:#111111; height:240px; display:flex; align-items:center; justify-content:center; padding:1.25rem;">
              <button type="button" class="btn-wishlist is-active" onclick="toggleWishlist(<?= $p['id'] ?>, this)" title="Remove from wishlist" style="position:absolute; top:12px; right:12px; z-index:5;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
              </button>
              <img src="<?= url(e($p['image_url'])) ?>" alt="<?= e($p['title']) ?>" style="max-height:100%; max-width:100%; object-fit:contain;">
            </div>

            <div class="product-body" style="padding:1.25rem; display:flex; flex-direction:column; flex:1;">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                <span class="product-category-label"><?= e($p['category_name']) ?></span>
                <span class="sku-badge">SKU: <?= e($p['sku']) ?></span>
              </div>

              <h3 class="product-title" style="margin-bottom:0.5rem; font-size:1.05rem;">
                <a href="<?= product_url($p['slug']) ?>" style="color:#F5F1E8; text-decoration:none;">
                  <?= e($p['title']) ?>
                </a>
              </h3>

              <div class="product-pricing-row" style="margin-top:auto; padding-top:0.75rem; border-top:1px solid #28221b; display:flex; justify-content:space-between; align-items:baseline;">
                <div class="product-price-box">
                  <span class="price-val" style="color:#FFD76A; font-weight:800; font-size:1.15rem;"><?= e($p['starting_price'] ?? '₹249') ?></span>
                  <span class="price-unit" style="color:#B8B1A3; font-size:0.8rem;"><?= e($p['price_unit'] ?? '') ?></span>
                </div>
                <?php if (!empty($p['min_order'])): ?>
                  <span class="product-min-order" style="font-size:0.75rem; color:#B8B1A3;"><?= e($p['min_order']) ?></span>
                <?php endif; ?>
              </div>

              <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.6rem; margin-top:1rem;">
                <a href="<?= product_url($p['slug']) ?>" class="btn btn-primary btn-sm" style="text-align:center;">
                  Order / View
                </a>
                <button type="button" class="btn btn-outline btn-sm" onclick="toggleWishlist(<?= $p['id'] ?>, this)" style="border-color:#40351E; color:#B8B1A3;">
                  Remove
                </button>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Empty Wishlist State -->
    <div id="wishlistEmptyState" style="<?= !empty($wishlistProducts) ? 'display:none;' : '' ?> text-align:center; padding:4rem 1.5rem; background:#181818; border:1px solid #40351E; border-radius:12px; max-width:550px; margin:2rem auto;">
      <div style="width:64px; height:64px; border-radius:50%; background:#202020; border:1.5px solid #40351E; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; color:#D4AF37;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
      </div>
      <h3 style="color:#F5F1E8; font-size:1.3rem; margin-bottom:0.5rem; font-weight:700;">Your wishlist is currently empty</h3>
      <p style="color:#B8B1A3; font-size:0.9rem; margin-bottom:1.5rem;">
        Browse our catalog of custom DTF gang sheets, apparel, and commercial print items to save your preferred products.
      </p>
      <a href="<?= url('products/') ?>" class="btn btn-primary">
        Explore Products &rarr;
      </a>
    </div>

  </div>
</main>

<script>
// If guest, render wishlisted items from localStorage if DB items empty
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('wishlistContainer');
  const emptyState = document.getElementById('wishlistEmptyState');
  
  if (container && container.children.length === 0) {
    try {
      const saved = JSON.parse(localStorage.getItem('orderprintex_wishlist') || '[]');
      if (saved.length > 0) {
        fetch('<?= url("api/compare_products.php?ids=") ?>' + saved.join(','))
          .then(r => r.json())
          .then(res => {
            if (res.success && res.products.length > 0) {
              emptyState.style.display = 'none';
              res.products.forEach(p => {
                const art = document.createElement('article');
                art.className = 'product-card';
                art.id = 'wishItem_' + p.id;
                art.style.cssText = 'background:#181818; border:1px solid #40351E; border-radius:10px; overflow:hidden; display:flex; flex-direction:column;';
                art.innerHTML = `
                  <div class="product-media" style="position:relative; background:#111111; height:240px; display:flex; align-items:center; justify-content:center; padding:1.25rem;">
                    <button type="button" class="btn-wishlist is-active" onclick="toggleWishlist(${p.id}, this)" title="Remove" style="position:absolute; top:12px; right:12px; z-index:5;">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </button>
                    <img src="${p.image_url}" alt="${p.title}" style="max-height:100%; max-width:100%; object-fit:contain;">
                  </div>
                  <div class="product-body" style="padding:1.25rem; display:flex; flex-direction:column; flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                      <span class="product-category-label">${p.category_name}</span>
                      <span class="sku-badge">SKU: ${p.sku}</span>
                    </div>
                    <h3 class="product-title" style="margin-bottom:0.5rem; font-size:1.05rem;">
                      <a href="${p.url}" style="color:#F5F1E8; text-decoration:none;">${p.title}</a>
                    </h3>
                    <div class="product-pricing-row" style="margin-top:auto; padding-top:0.75rem; border-top:1px solid #28221b; display:flex; justify-content:space-between; align-items:baseline;">
                      <div class="product-price-box">
                        <span class="price-val" style="color:#FFD76A; font-weight:800; font-size:1.15rem;">${p.starting_price}</span>
                        <span class="price-unit" style="color:#B8B1A3; font-size:0.8rem;">${p.price_unit}</span>
                      </div>
                      <span class="product-min-order" style="font-size:0.75rem; color:#B8B1A3;">${p.min_order}</span>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.6rem; margin-top:1rem;">
                      <a href="${p.url}" class="btn btn-primary btn-sm" style="text-align:center;">Order / View</a>
                      <button type="button" class="btn btn-outline btn-sm" onclick="toggleWishlist(${p.id}, this)" style="border-color:#40351E; color:#B8B1A3;">Remove</button>
                    </div>
                  </div>
                `;
                container.appendChild(art);
              });
            }
          });
      }
    } catch(e) {}
  }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

