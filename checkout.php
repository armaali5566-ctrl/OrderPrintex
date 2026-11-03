<?php
/**
 * OrderPrintex - Checkout Workbench
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "Secure Checkout & Prepress Sign-Off | OrderPrintex";
$pageDesc  = "Complete your order with PAN-India express delivery, verified prepress proofs, and 18% GST tax invoice.";
$canonical = checkout_url();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Order Finalization</span>
      </div>
      <h1 class="page-intro-title">Secure Checkout</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Confirm delivery address, billing details, and production schedule. Binding invoice and digital prepress proof dispatched prior to printing.
      </p>
    </div>
  </div>
</section>

<section class="section section-white" style="padding-top:3.5rem; padding-bottom:4.5rem;">
  <div class="container container-readable">

    <div style="display:grid; grid-template-columns:1.8fr 1.2fr; gap:2.5rem; align-items:flex-start;">
      
      <!-- Left: Delivery & Billing Form -->
      <div class="card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:2rem; box-shadow: var(--shadow-card);">
        <h3 style="font-size:1.2rem; color:var(--navy); font-weight:800; margin-bottom:1.25rem; border-bottom:1px solid var(--border-light); padding-bottom:0.75rem;">
          1. Delivery &amp; Contact Information
        </h3>

        <form action="<?= url('api/submit-enquiry.php') ?>" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="service_name" value="checkout-order">

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
            <div>
              <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Full Name *</label>
              <input type="text" name="customer_name" required class="form-control" placeholder="e.g. Arun Sharma">
            </div>
            <div>
              <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Company / Brand Name</label>
              <input type="text" name="company_name" class="form-control" placeholder="e.g. Studio 9 Apparel">
            </div>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
            <div>
              <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Email Address *</label>
              <input type="email" name="customer_email" required class="form-control" placeholder="arun@brand.com">
            </div>
            <div>
              <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Phone / WhatsApp *</label>
              <input type="tel" name="customer_phone" required class="form-control" placeholder="+91 93540 28199">
            </div>
          </div>

          <div style="margin-bottom:1rem;">
            <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Shipping Address *</label>
            <input type="text" name="address" required class="form-control" placeholder="Street address, building, unit">
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
            <div>
              <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">City *</label>
              <input type="text" name="city" required class="form-control" placeholder="e.g. New Delhi">
            </div>
            <div>
              <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">State *</label>
              <input type="text" name="state" required class="form-control" placeholder="e.g. Delhi">
            </div>
            <div>
              <label style="display:block; font-size:0.825rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Pincode *</label>
              <input type="text" name="pincode" required class="form-control" placeholder="110059">
            </div>
          </div>

          <h3 style="font-size:1.2rem; color:var(--navy); font-weight:800; margin:1.5rem 0 1.25rem; border-top:1px solid var(--border-light); padding-top:1.25rem;">
            2. Payment Method
          </h3>

          <div style="display:flex; flex-direction:column; gap:0.75rem; margin-bottom:1.5rem;">
            <label style="display:flex; align-items:center; gap:0.65rem; padding:0.85rem 1rem; border:1px solid var(--border-light); border-radius:6px; cursor:pointer; background:var(--ivory);">
              <input type="radio" name="payment_method" value="upi" checked>
              <span style="font-weight:700; color:var(--navy); font-size:0.9rem;">Instant UPI / QR / Net Banking (Direct Bank Transfer)</span>
            </label>
            <label style="display:flex; align-items:center; gap:0.65rem; padding:0.85rem 1rem; border:1px solid var(--border-light); border-radius:6px; cursor:pointer;">
              <input type="radio" name="payment_method" value="corporate_invoice">
              <span style="font-weight:700; color:var(--navy); font-size:0.9rem;">Corporate GST Purchase Order (Post Proof Confirmation)</span>
            </label>
          </div>

          <button type="submit" class="btn btn-primary btn-lg" style="width:100%; justify-content:center;">
            <span>Place Commercial Order</span> &rarr;
          </button>
        </form>
      </div>

      <!-- Right: Summary Box -->
      <div class="card" style="background:var(--ivory); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
        <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin-bottom:1rem; border-bottom:1px solid var(--border-light); padding-bottom:0.75rem;">
          Order Guarantees
        </h3>

        <div style="display:flex; flex-direction:column; gap:0.75rem; font-size:0.85rem; color:var(--slate); margin-bottom:1.5rem;">
          <div style="display:flex; align-items:flex-start; gap:0.5rem;">
            <span style="color:var(--emerald); font-weight:800;">✓</span>
            <span><strong>Prepress Proof:</strong> Digital mockup emailed within 2–4 hours for approval before printing.</span>
          </div>
          <div style="display:flex; align-items:flex-start; gap:0.5rem;">
            <span style="color:var(--emerald); font-weight:800;">✓</span>
            <span><strong>Zero Risk:</strong> 100% money-back or free reprint guarantee if technical defect occurs.</span>
          </div>
          <div style="display:flex; align-items:flex-start; gap:0.5rem;">
            <span style="color:var(--emerald); font-weight:800;">✓</span>
            <span><strong>18% GST Input:</strong> Official B2B tax invoice issued with your GSTIN number.</span>
          </div>
        </div>

        <div style="background:var(--bg-surface); border-radius:8px; border:1px solid var(--border-light); padding:1rem; font-size:0.825rem; color:var(--slate);">
          Need technical assistance? Talk to prepress at <strong>+91 93540 28199</strong> or WhatsApp.
        </div>
      </div>

    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

