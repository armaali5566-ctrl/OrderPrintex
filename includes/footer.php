<?php
/**
 * OrderPrintex - Full-Width Studio Footer Partial
 */
$phone = get_setting('phone', '+91 93540 28199');
$phoneRaw = get_setting('phone_raw', '919354028199');
$email = get_setting('email', 'sales@orderprintex.com');
$address = get_setting('address', 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh, Delhi, 110059');
$mapsUrl = get_setting('google_maps_url', 'https://share.google/MWOVYne70asGLsYXn');
$waUrl = get_setting('whatsapp_url', 'https://wa.me/919354028199');
$igUrl = get_setting('instagram_url', 'https://www.instagram.com/order_printex');
$ytUrl = get_setting('youtube_url', 'https://www.youtube.com/@orderprintex');
?>
</div><!-- /.site-wrapper -->

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <!-- Col 1: Brand & Bio -->
      <div class="footer-brand">
        <a href="<?= url('') ?>" style="display:inline-block; margin-bottom:1.25rem; background:transparent; padding:0;">
          <img src="<?= url('assets/images/logo.png') ?>" alt="OrderPrintex" width="64" height="64" loading="lazy" style="height:64px; width:64px; border-radius:50%; object-fit:cover; display:block; border:1.5px solid rgba(212,175,55,0.6); box-shadow:0 0 16px rgba(212,175,55,0.35);">
        </a>
        <p>
          PRINTEX is India's leading manufacturing studio specializing in industrial Direct-to-Film (DTF) and UV DTF transfers. Supplying high-durability custom print solutions across India with calibrated color fidelity, prepress verification, and rapid bulk fulfillment.
        </p>
        <div style="display:flex; align-items:center; gap:1rem; margin-top:1.5rem;">
          <a href="<?= e($igUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram" style="color:var(--gold); display:flex; align-items:center;">
            <svg style="width:22px;height:22px;fill:currentColor;" viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
          </a>
          <a href="<?= e($ytUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube" style="color:var(--gold); display:flex; align-items:center;">
            <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 24 24">
              <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
            </svg>
          </a>
          <a href="<?= e($waUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" style="color:var(--gold); display:flex; align-items:center;" class="footer-social-link">
            <svg style="width:22px;height:22px;fill:currentColor;" viewBox="0 0 24 24">
              <path d="M12.031 2c-5.509 0-9.986 4.477-9.986 9.986 0 1.761.459 3.479 1.331 4.991l-1.376 5.023 5.143-1.348c1.464.799 3.111 1.22 4.888 1.22 5.509 0 9.986-4.477 9.986-9.986 0-5.509-4.477-9.986-9.986-9.986zm5.828 14.153c-.244.686-1.42 1.312-1.956 1.393-.518.077-1.196.111-3.666-.913-2.923-1.213-4.81-4.186-4.954-4.38-.146-.194-1.187-1.58-1.187-3.013 0-1.433.748-2.14 1.015-2.433.267-.293.582-.366.777-.366.195 0 .389.002.559.01.18.008.423-.068.662.506.244.585.83 2.025.903 2.172.073.146.122.317.024.512-.097.195-.146.317-.292.488-.146.17-.308.38-.44.51-.146.145-.3.303-.13.595.17.292.756 1.246 1.623 2.018 1.116.994 2.057 1.303 2.349 1.449.293.146.463.122.634-.073.17-.195.731-.853.926-1.145.195-.292.39-.244.658-.146.268.098 1.706.804 1.998.951.293.146.488.219.56.341.073.122.073.707-.171 1.393z"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Col 2: Printing Services -->
      <div>
        <h4 class="footer-title">Printing Services</h4>
        <div class="footer-links">
          <a href="<?= service_url('dtf-printing') ?>">DTF Garment Transfers</a>
          <a href="<?= service_url('uv-dtf-printing') ?>">UV DTF Hard Decals</a>
          <a href="<?= service_url('t-shirt-printing') ?>">T-Shirt Printing</a>
          <a href="<?= service_url('corporate-printing') ?>">Corporate Kits &amp; Merch</a>
          <a href="<?= url('services') ?>">All Printing Capabilities</a>
          <a href="<?= url('get-quote') ?>">Custom Quotation Studio</a>
        </div>
      </div>

      <!-- Col 3: Quick Navigation -->
      <div>
        <h4 class="footer-title">Explore Printex</h4>
        <div class="footer-links">
          <a href="<?= url('about') ?>">About Delhi Studio</a>
          <a href="<?= url('products') ?>">Catalog &amp; Gang Sheets</a>
          <a href="<?= pricing_url() ?>">Volume Pricing</a>
          <a href="<?= blog_url() ?>">Printing Knowledge Base</a>
          <a href="<?= url('gallery') ?>">Production Showcase</a>
          <a href="<?= url('faq') ?>">Prepress &amp; FAQs</a>
          <a href="<?= url('contact') ?>">Workshop Directions</a>
          <a href="<?= account_url() ?>">Client Portal</a>
        </div>
      </div>

      <!-- Col 4: Verified Contact -->
      <div>
        <h4 class="footer-title">Delhi Production Studio</h4>
        <div class="footer-contact-item">
          <svg style="width:18px;height:18px;fill:var(--gold);flex-shrink:0;margin-top:3px;" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
          </svg>
          <span><?= e($address) ?></span>
        </div>
        <div class="footer-contact-item">
          <svg style="width:18px;height:18px;fill:var(--gold);flex-shrink:0;" viewBox="0 0 20 20">
            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
          </svg>
          <a href="tel:+<?= e($phoneRaw) ?>"><?= e($phone) ?></a>
        </div>
        <div class="footer-contact-item">
          <svg style="width:18px;height:18px;fill:var(--gold);flex-shrink:0;" viewBox="0 0 20 20">
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
          </svg>
          <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
        </div>
        <div style="margin-top:1.25rem;">
          <a href="<?= e($mapsUrl) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-gold">
            <span>Get Directions on Map</span> &rarr;
          </a>
        </div>
      </div>
    </div>

    <!-- Bottom Copyright -->
    <div class="footer-bottom">
      <div>
        &copy; <?= date('Y') ?> <strong>ORDERPRINTEX</strong>. All Rights Reserved. Delhi, India.
      </div>
      <div style="display:flex; gap:1.5rem;">
        <span style="color:var(--gold);">Precision DTF &bull; UV DTF &bull; Pan-India Shipping</span>
      </div>
    </div>
  </div>
</footer>

<?php require_once __DIR__ . '/contact-modal.php'; ?>

<!-- Floating Contact Actions: Call & WhatsApp (Bottom-Right Corner) -->
<div class="floating-contact-actions" id="floatingContactActions" aria-label="Quick Contact Options">
  <!-- Call Us Floating Button -->
  <a href="tel:+<?= e($phoneRaw) ?>" class="floating-btn floating-btn-call" aria-label="Call Us: <?= e($phone) ?>" title="Call Us: <?= e($phone) ?>">
    <span class="floating-btn-icon">
      <svg viewBox="0 0 24 24" fill="currentColor">
        <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-1.57 1.97c-2.83-1.44-5.15-3.75-6.59-6.59l1.97-1.57c.28-.28.37-.67.25-1.02A11.36 11.36 0 0 1 8.58 4.31c0-.55-.45-1-1-1H4.11c-.55 0-1 .45-1 1C3.11 13.91 10.09 20.89 18.99 20.89c.55 0 1-.45 1-1v-3.51c0-.55-.45-1-.98-1z"/>
      </svg>
    </span>
    <span class="floating-btn-label">Call Us</span>
  </a>

  <!-- WhatsApp Floating Button -->
  <a href="https://wa.me/<?= e($phoneRaw) ?>?text=<?= urlencode('Hi, I would like to enquire about your printing services.') ?>" target="_blank" rel="noopener noreferrer" class="floating-btn floating-btn-wa" aria-label="WhatsApp Prepress Consultation" title="Chat on WhatsApp">
    <span class="floating-btn-icon">
      <svg viewBox="0 0 24 24" fill="currentColor">
        <path d="M12.031 2c-5.509 0-9.986 4.477-9.986 9.986 0 1.761.459 3.479 1.331 4.991l-1.376 5.023 5.143-1.348c1.464.799 3.111 1.22 4.888 1.22 5.509 0 9.986-4.477 9.986-9.986 0-5.509-4.477-9.986-9.986-9.986zm5.828 14.153c-.244.686-1.42 1.312-1.956 1.393-.518.077-1.196.111-3.666-.913-2.923-1.213-4.81-4.186-4.954-4.38-.146-.194-1.187-1.58-1.187-3.013 0-1.433.748-2.14 1.015-2.433.267-.293.582-.366.777-.366.195 0 .389.002.559.01.18.008.423-.068.662.506.244.585.83 2.025.903 2.172.073.146.122.317.024.512-.097.195-.146.317-.292.488-.146.17-.308.38-.44.51-.146.145-.3.303-.13.595.17.292.756 1.246 1.623 2.018 1.116.994 2.057 1.303 2.349 1.449.293.146.463.122.634-.073.17-.195.731-.853.926-1.145.195-.292.39-.244.658-.146.268.098 1.706.804 1.998.951.293.146.488.219.56.341.073.122.073.707-.171 1.393z"/>
      </svg>
    </span>
    <span class="floating-btn-label">WhatsApp</span>
  </a>
</div>

<!-- Native Vanilla JavaScript (No External CDNs) -->
<script src="<?= url('assets/js/main.js') ?>"></script>
<script src="<?= url('assets/js/animations.js') ?>"></script>
<script src="<?= url('assets/js/upload.js') ?>"></script>
<script src="<?= url('assets/js/dynamic-other.js') ?>"></script>
</body>
</html>
