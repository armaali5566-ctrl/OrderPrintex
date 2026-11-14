<?php
/**
 * OrderPrintex - Universal Contact Popup Modal
 * Reused across all public pages and the Expanding Hover Gallery.
 */
?>
<!-- Universal Studio Contact Popup Modal -->
<div class="gallery-contact-modal" id="galleryContactModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="galleryModalTitle" data-contact-url="<?= url('contact.php') ?>">
  <div class="gallery-modal-backdrop" data-close-modal></div>
  <div class="gallery-modal-dialog">
    <button type="button" class="gallery-modal-close" data-close-modal aria-label="Close dialog">&times;</button>
    <div class="gallery-modal-header">
      <span class="gallery-modal-badge">DIRECT ENQUIRY</span>
      <h3 id="galleryModalTitle" class="gallery-modal-title">Get a Quote for <span id="galleryModalServiceTitle" class="modal-highlight-gold">Custom Printing</span></h3>
      <p class="gallery-modal-subtitle">Share your details and continue to our Contact Us page to finalize your inquiry.</p>
    </div>
    <form class="gallery-modal-form" id="galleryContactForm" novalidate>
      <div class="modal-form-row">
        <label for="popupFullName" class="modal-label">Full Name <span class="modal-req">*</span></label>
        <input type="text" id="popupFullName" name="fullName" class="modal-input" placeholder="e.g. Rahul Sharma" autocomplete="name" required>
        <span class="modal-error" id="errorFullName" aria-live="polite"></span>
      </div>

      <div class="modal-form-grid">
        <div class="modal-form-row">
          <label for="popupMobile" class="modal-label">Mobile Number <span class="modal-req">*</span></label>
          <input type="tel" id="popupMobile" name="mobile" class="modal-input" placeholder="10-digit mobile number" autocomplete="tel" required>
          <span class="modal-error" id="errorMobile" aria-live="polite"></span>
        </div>

        <div class="modal-form-row">
          <label for="popupEmail" class="modal-label">Email Address <span class="modal-req">*</span></label>
          <input type="email" id="popupEmail" name="email" class="modal-input" placeholder="you@example.com" autocomplete="email" required>
          <span class="modal-error" id="errorEmail" aria-live="polite"></span>
        </div>
      </div>

      <div class="modal-form-row">
        <label for="popupService" class="modal-label">Interested Application / Service <span class="modal-req">*</span></label>
        <div class="modal-select-wrap">
          <select id="popupService" name="service" class="modal-select" required>
            <option value="" disabled selected>Select a Service</option>
            <option value="Personal Gifts">Personal Gifts</option>
            <option value="Couple T-Shirts">Couple T-Shirts</option>
            <option value="Occasion Gifts">Occasion Gifts</option>
            <option value="Wedding Packages">Wedding Packages</option>
            <option value="Corporate Gifts">Corporate Gifts</option>
            <option value="Custom T-Shirts">Custom T-Shirts</option>
            <option value="Printed Mugs">Printed Mugs</option>
            <option value="Branded Merchandise">Branded Merchandise</option>
          </select>
        </div>
        <span class="modal-error" id="errorService" aria-live="polite"></span>
      </div>

      <div class="modal-form-row">
        <label for="popupMessage" class="modal-label">Message / Requirements <span class="modal-optional">(Optional)</span></label>
        <textarea id="popupMessage" name="message" class="modal-textarea" rows="3" placeholder="Brief details: estimated quantity, product sizes, or questions..."></textarea>
      </div>

      <div class="modal-form-actions">
        <button type="submit" class="modal-submit-btn">
          <span>Continue to Contact Us</span>
          <span aria-hidden="true">&rarr;</span>
        </button>
      </div>
      <p class="modal-disclaimer">You will review and submit your final inquiry on the Contact page.</p>
    </form>
  </div>
</div>
