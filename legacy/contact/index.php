<?php
/**
 * Contact Us Page — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Contact information display loaded from database settings
 * - Validated contact message form
 * - Database submission writing to `contacts` table using prepared statements
 * - Flash notification message responses
 */

$page_title = 'Contact Us';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

// Handle Contact Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    $errors = [];

    // Validations
    if ($name === '') $errors[] = 'Your name is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
    if ($message === '') $errors[] = 'A message cannot be empty.';

    if (empty($errors)) {
        // Insert message into database table
        $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            setFlashMessage('success', 'Thank you! Your inquiry has been received. We will get back to you shortly.');
            header('Location: ' . $base_url . '/contact/index.php');
            exit;
        } else {
            $errors[] = 'Database save error: ' . $conn->error;
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        setFlashMessage('danger', implode('<br>', $errors));
    }
}

// Retrieve contact configurations from settings
$contact_email = getSetting($conn, 'contact_email', 'info@masjidlocator.com');
$contact_phone = getSetting($conn, 'contact_phone', '+92 21 1234567');
$contact_address = getSetting($conn, 'contact_address', 'Karachi, Sindh, Pakistan');
$default_lat = getSetting($conn, 'default_lat', '24.8607');
$default_lng = getSetting($conn, 'default_lng', '67.0011');
?>

<!-- ═══════════ BREADCRUMB SECTION ═══════════ -->
<section class="breadcrumb-section">
    <div class="container">
        <h2 class="breadcrumb-title">Contact Our Team</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ═══════════ CONTACT CONTENT ═══════════ -->
<div class="container my-5 animate-fadeInUp">
    <div class="row g-5">
        <!-- Left Column: Contact Form -->
        <div class="col-lg-7">
            <div class="card p-4 border-0 shadow-sm rounded-4 contact-form">
                <h4 class="fw-bold text-success mb-3"><i class="fas fa-paper-plane text-success"></i> Send a Message</h4>
                <p class="text-secondary small mb-4">Have questions about adding a mosque, correcting timings, or general suggestions? Drop us a line using the form below.</p>
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Ahmad Ali" value="<?php echo isset($_POST['name']) ? sanitize($_POST['name']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. ahmad@example.com" value="<?php echo isset($_POST['email']) ? sanitize($_POST['email']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="e.g. Timing correction query" value="<?php echo isset($_POST['subject']) ? sanitize($_POST['subject']) : ''; ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Message Details *</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Write details here..." required><?php echo isset($_POST['message']) ? sanitize($_POST['message']) : ''; ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-bold">
                        <i class="fas fa-paper-plane me-1"></i> Send Message Inquiry
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Contact info cards & map -->
        <div class="col-lg-5">
            <!-- Contact channels -->
            <div class="card p-4 border-0 shadow-sm rounded-4 mb-4">
                <h5 class="fw-bold text-success mb-4"><i class="fas fa-address-book"></i> Support Channels</h5>
                
                <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                    <div class="text-success fs-4 mt-1"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h6 class="fw-bold text-success mb-0">Our Office Address</h6>
                        <p class="small text-secondary mb-0"><?php echo sanitize($contact_address); ?></p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                    <div class="text-success fs-4 mt-1"><i class="fas fa-phone"></i></div>
                    <div>
                        <h6 class="fw-bold text-success mb-0">Phone Number</h6>
                        <p class="small text-secondary mb-0"><?php echo sanitize($contact_phone); ?></p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <div class="text-success fs-4 mt-1"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h6 class="fw-bold text-success mb-0">Email Support</h6>
                        <p class="small text-secondary mb-0"><?php echo sanitize($contact_email); ?></p>
                    </div>
                </div>
            </div>

            <!-- Embedded Office Map Picker -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden" style="height: 250px;">
                <div id="officeMap" style="height: 100%; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ SCRIPTS ═══════════ -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const defaultLat = <?php echo floatval($default_lat); ?>;
    const defaultLng = <?php echo floatval($default_lng); ?>;

    // Initialize map focused on office locations
    const map = L.map('officeMap', { zoomControl: false }).setView([defaultLat, defaultLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    L.marker([defaultLat, defaultLng], { icon: MapHelper.getMosqueIcon() })
        .bindPopup('<strong>Masjid Locator Headquarters</strong>')
        .addTo(map);
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
