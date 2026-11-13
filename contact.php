<?php
/**
 * OrderPrintex - Contact Us Page
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "Contact OrderPrintex — Delhi Printing Studio & Manufacturing Unit";
$pageDesc  = "Get in touch with OrderPrintex: Delhi production address, direct phone, WhatsApp prepress support, Google Maps directions, and general inquiries.";
$canonical = url('contact');
$pageSchemaKey = 'contact';

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

<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>GET IN TOUCH</span>
      </div>
      <h1 class="page-intro-title">Talk to Our Delhi Team</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Need a quote, help with your artwork, or a sample before a bulk order? Call, WhatsApp or send us a message, and we’ll get back to you.
      </p>
    </div>
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

    <div class="contact-main-grid" style="display:grid; grid-template-columns:1fr 1.25fr; gap:3.5rem; align-items:flex-start;">
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
              <svg style="width:22px;height:22px;fill:currentColor;display:block;" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12.031 2c-5.509 0-9.986 4.477-9.986 9.986 0 1.761.459 3.479 1.331 4.991l-1.376 5.023 5.143-1.348c1.464.799 3.111 1.22 4.888 1.22 5.509 0 9.986-4.477 9.986-9.986 0-5.509-4.477-9.986-9.986-9.986zm5.828 14.153c-.244.686-1.42 1.312-1.956 1.393-.518.077-1.196.111-3.666-.913-2.923-1.213-4.81-4.186-4.954-4.38-.146-.194-1.187-1.58-1.187-3.013 0-1.433.748-2.14 1.015-2.433.267-.293.582-.366.777-.366.195 0 .389.002.559.01.18.008.423-.068.662.506.244.585.83 2.025.903 2.172.073.146.122.317.024.512-.097.195-.146.317-.292.488-.146.17-.308.38-.44.51-.146.145-.3.303-.13.595.17.292.756 1.246 1.623 2.018 1.116.994 2.057 1.303 2.349 1.449.293.146.463.122.634-.073.17-.195.731-.853.926-1.145.195-.292.39-.244.658-.146.268.098 1.706.804 1.998.951.293.146.488.219.56.341.073.122.073.707-.171 1.393z"/>
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
