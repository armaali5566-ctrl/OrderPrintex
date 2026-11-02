<?php
/**
 * OrderPrintex - Contact Us Page
 */

$pageTitle = "Contact OrderPrintex — Delhi Printing Studio & Manufacturing Unit";
$pageDesc  = "Get in touch with OrderPrintex: Delhi production address, direct phone, WhatsApp prepress support, Google Maps directions, and general inquiries.";

require_once __DIR__ . '/includes/header.php';

$phone = get_setting('phone', '+91 93540 28199');
$phoneRaw = get_setting('phone_raw', '919354028199');
$email = get_setting('email', 'sales@orderprintex.com');
$address = get_setting('address', 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh, Delhi, 110059');
$mapsUrl = get_setting('google_maps_url', 'https://share.google/MWOVYne70asGLsYXn');
$waUrl = get_setting('whatsapp_url', 'https://wa.me/919354028199');

// Handle Contact Form Submission
$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact_submit') {
    if (csrf_verify()) {
        $cName = trim($_POST['name'] ?? '');
        $cEmail = trim($_POST['email'] ?? '');
        $cPhone = trim($_POST['phone'] ?? '');
        $cSubject = trim($_POST['subject'] ?? 'General Query');
        $cMessage = trim($_POST['message'] ?? '');

        if ($cName && $cEmail && $cMessage) {
            $ref = generate_ref('OP-MSG');
            try {
                db_insert('enquiries', [
                    'ref_number'     => $ref,
                    'customer_name'  => $cName,
                    'customer_email' => $cEmail,
                    'customer_phone' => $cPhone,
                    'service_name'   => 'Contact Form: ' . $cSubject,
                    'customer_notes' => $cMessage,
                    'status'         => 'received'
                ]);
                set_flash('success', "Thank you, {$cName}! Your inquiry has been received. Our team will contact you shortly.");
                $messageSent = true;
            } catch (Exception $e) {
                set_flash('error', "Could not submit inquiry: " . $e->getMessage());
            }
        } else {
            set_flash('error', "Please fill in all required fields.");
        }
    } else {
        set_flash('error', "Security verification failed. Please try again.");
    }
}
?>

<section class="section section-navy" style="padding-top:4.5rem; padding-bottom:4.5rem;">
  <div class="container text-center reveal slide-up">
    <div class="section-label" style="justify-content:center;">Direct Connection</div>
    <h1 style="font-size:3.2rem; margin-bottom:1rem;">Contact Our Delhi Production Studio</h1>
    <p class="section-subtitle" style="color:rgba(255,255,255,0.75); margin:0 auto;">
      Whether you need prepress guidance, gang sheet quotations, sample swatches, or bulk supply agreements, our customer engineering team is ready to assist.
    </p>
  </div>
</section>

<section class="section section-white">
  <div class="container">
    <?= render_flash() ?>

    <?php if (isset($_GET['submitted'])): ?>
      <div class="alert alert-success">
        <div class="alert-content">
          <strong>Thank you!</strong> Your enquiry reference <strong><?= e($_GET['submitted']) ?></strong> has been received by our prepress studio. We will contact you with pricing shortly.
        </div>
      </div>
    <?php endif; ?>

    <div style="display:grid; grid-template-columns:1fr 1.25fr; gap:3.5rem; align-items:flex-start;">
      <!-- Contact Cards -->
      <div class="reveal slide-right">
        <div class="section-label">Delhi Office</div>
        <h2 style="margin-bottom:1.25rem;">Reach Out Directly</h2>
        <p style="margin-bottom:2rem;">
          We operate an active production facility in Delhi. You can reach out by phone, WhatsApp, email, or visit during standard operating hours.
        </p>

        <div style="display:flex; flex-direction:column; gap:1.5rem;">
          <div style="display:flex; gap:1rem; align-items:flex-start;">
            <div style="width:40px; height:40px; border-radius:50%; background-color:var(--gold-light); color:var(--gold); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <svg style="width:20px;height:20px;fill:currentColor;" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h4 style="font-size:1.05rem; margin-bottom:0.25rem;">Studio &amp; Works Address</h4>
              <p style="font-size:0.9rem; margin:0;"><?= e($address) ?></p>
              <div style="margin-top:0.5rem;">
                <a href="<?= e($mapsUrl) ?>" target="_blank" rel="noopener" class="text-gold" style="font-weight:600; font-size:0.85rem;">
                  Open Direction on Google Maps &rarr;
                </a>
              </div>
            </div>
          </div>

          <div style="display:flex; gap:1rem; align-items:flex-start;">
            <div style="width:40px; height:40px; border-radius:50%; background-color:var(--gold-light); color:var(--gold); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <svg style="width:20px;height:20px;fill:currentColor;" viewBox="0 0 20 20">
                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
              </svg>
            </div>
            <div>
              <h4 style="font-size:1.05rem; margin-bottom:0.25rem;">Phone &amp; Prepress Helpline</h4>
              <p style="font-size:0.9rem; margin:0;">
                <a href="tel:+<?= e($phoneRaw) ?>" style="font-weight:600; color:var(--navy);"><?= e($phone) ?></a>
              </p>
            </div>
          </div>

          <div style="display:flex; gap:1rem; align-items:flex-start;">
            <div style="width:40px; height:40px; border-radius:50%; background-color:rgba(37,211,102,0.15); color:#25D366; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <svg style="width:22px;height:22px;fill:currentColor;" viewBox="0 0 24 24">
                <path d="M12.031 0C5.394 0 0 5.393 0 12.031c0 2.12.553 4.188 1.603 6.012L0 24l6.143-1.611A12.012 12.012 0 0 0 12.031 24c6.638 0 12.031-5.393 12.031-12.031C24.062 5.393 18.669 0 12.031 0zm0 22.029c-1.802 0-3.569-.485-5.114-1.403l-.367-.218-3.805.998 1.016-3.708-.239-.38a9.99 9.99 0 0 1-1.533-5.287c0-5.526 4.496-10.022 10.042-10.022 5.546 0 10.042 4.496 10.042 10.022 0 5.526-4.496 10.018-10.042 10.018z"/>
              </svg>
            </div>
            <div>
              <h4 style="font-size:1.05rem; margin-bottom:0.25rem;">Instant WhatsApp Chat</h4>
              <p style="font-size:0.9rem; margin:0;">
                <a href="<?= e($waUrl) ?>" target="_blank" rel="noopener" style="font-weight:600; color:#25D366;">Chat with Prepress Specialist &rarr;</a>
              </p>
            </div>
          </div>

          <div style="display:flex; gap:1rem; align-items:flex-start;">
            <div style="width:40px; height:40px; border-radius:50%; background-color:var(--gold-light); color:var(--gold); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <svg style="width:20px;height:20px;fill:currentColor;" viewBox="0 0 20 20">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
              </svg>
            </div>
            <div>
              <h4 style="font-size:1.05rem; margin-bottom:0.25rem;">Official Commercial Email</h4>
              <p style="font-size:0.9rem; margin:0;">
                <a href="mailto:<?= e($email) ?>" style="font-weight:600; color:var(--navy);"><?= e($email) ?></a>
              </p>
            </div>
          </div>
        </div>

        <div style="margin-top:2rem; padding:1.25rem; background-color:var(--ivory); border-radius:var(--radius-md); font-size:0.85rem;">
          <strong>Studio Operating Hours:</strong><br>
          Monday &ndash; Saturday: 10:00 AM &ndash; 7:30 PM IST<br>
          Sunday: Closed for maintenance &amp; machine calibration
        </div>
      </div>

      <!-- Working Contact Form -->
      <div class="reveal slide-left">
        <div class="upload-card">
          <h3 style="margin-bottom:0.5rem;">Send an Inquiry</h3>
          <p style="font-size:0.9rem; color:var(--slate); margin-bottom:1.5rem;">
            Have a question about gang sheet specifications, custom sizes, or delivery schedules? Send a message directly to our desk.
          </p>

          <form action="<?= url('contact.php') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="contact_submit">

            <div class="form-grid">
              <div class="form-group">
                <label class="form-label" for="contactName">Your Name <span class="required">*</span></label>
                <input type="text" name="name" id="contactName" class="form-control" placeholder="Your Name" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="contactEmail">Email Address <span class="required">*</span></label>
                <input type="email" name="email" id="contactEmail" class="form-control" placeholder="Your Email" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="contactPhone">Phone Number</label>
                <input type="tel" name="phone" id="contactPhone" class="form-control" placeholder="+91 93540 28199">
              </div>

              <div class="form-group">
                <label class="form-label" for="contactSubject">Subject <span class="required">*</span></label>
                <input type="text" name="subject" id="contactSubject" class="form-control" placeholder="e.g. Bulk Gang Sheet Rates" required>
              </div>

              <div class="form-group full-width">
                <label class="form-label" for="contactMessage">Message Details <span class="required">*</span></label>
                <textarea name="message" id="contactMessage" class="form-control" placeholder="Type your inquiry or order requirements..." required></textarea>
              </div>
            </div>

            <div style="margin-top:1.5rem; text-align:right;">
              <button type="submit" class="btn btn-primary btn-lg">
                <span>Send Message</span> &rarr;
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Embedded Interactive Map -->
<section style="height:420px; width:100%; position:relative; overflow:hidden; border-top:1px solid var(--border-light);">
  <iframe 
    src="https://maps.google.com/maps?q=Shop+E-6+Chanakya+Palace+Street+No+21+Pankha+Rd+Delhi+110059&t=m&z=14&output=embed&iwloc=near"
    width="100%" 
    height="100%" 
    style="border:0; display:block;" 
    allowfullscreen="" 
    loading="lazy" 
    title="OrderPrintex Delhi Studio Location Map">
  </iframe>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
