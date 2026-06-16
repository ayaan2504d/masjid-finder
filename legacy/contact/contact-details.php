<?php
/**
 * Contact Details Specs — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Operating hours card
 * - Support reply time details FAQ
 * - Social media links card
 */

$page_title = 'Operating Channels';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- ═══════════ BREADCRUMB SECTION ═══════════ -->
<section class="breadcrumb-section">
    <div class="container">
        <h2 class="breadcrumb-title">Operating Channels</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/contact/">Contact Us</a></li>
                <li class="breadcrumb-item active" aria-current="page">Support Channels</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ═══════════ DETAILS CONTENT ═══════════ -->
<div class="container my-5 animate-fadeInUp">
    <div class="row g-4">
        <!-- Left: Operating Hours & Channel details -->
        <div class="col-lg-6">
            <!-- Operating hours -->
            <div class="card p-4 border-0 shadow-sm rounded-4 mb-4">
                <h5 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="fas fa-calendar-check text-success"></i> Office Response Hours</h5>
                <p class="text-secondary small mb-3">Our dedicated review team evaluates database submissions and correction inquiries during standard office hours:</p>
                
                <table class="table table-sm table-borderless small text-secondary">
                    <tr><td><strong>Monday - Friday</strong></td><td class="text-end">09:00 AM - 05:00 PM</td></tr>
                    <tr><td><strong>Saturday</strong></td><td class="text-end">10:00 AM - 02:00 PM</td></tr>
                    <tr class="table-success rounded"><td><strong>Sunday</strong></td><td class="text-end text-success"><strong>Closed</strong></td></tr>
                </table>
            </div>

            <!-- Social channels -->
            <div class="card p-4 border-0 shadow-sm rounded-4">
                <h5 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="fas fa-share-alt text-success"></i> Social Communities</h5>
                <p class="text-secondary small mb-4">Join our communities for moon sighting alerts, congregation announcements, and platform updates:</p>
                
                <div class="row g-2">
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-primary btn-sm w-100 py-2"><i class="fab fa-facebook me-1"></i> Facebook</a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-info btn-sm w-100 py-2"><i class="fab fa-twitter me-1"></i> Twitter</a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-danger btn-sm w-100 py-2"><i class="fab fa-instagram me-1"></i> Instagram</a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-dark btn-sm w-100 py-2"><i class="fab fa-github me-1"></i> GitHub</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: FAQ for messaging -->
        <div class="col-lg-6">
            <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
                <h5 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="fas fa-question-circle text-success"></i> Support FAQs</h5>
                
                <div class="accordion" id="contactFaq">
                    <!-- FAQ 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#c1">
                                How long does it take to review a timing correction?
                            </button>
                        </h2>
                        <div id="c1" class="accordion-collapse collapse show" data-bs-parent="#contactFaq">
                            <div class="accordion-body small text-secondary">
                                Submission timings are typically cross-referenced with official mosque timetables and updated within <strong>24 to 48 hours</strong> of verification.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c2">
                                Can I submit images of a new mosque?
                            </button>
                        </h2>
                        <div id="c2" class="accordion-collapse collapse" data-bs-parent="#contactFaq">
                            <div class="accordion-body small text-secondary">
                                Yes. You can send links to Google Drive directories or email attachments containing mosque exterior/interior photos directly to our support inbox.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c3">
                                Are there any fees for listing my mosque?
                            </button>
                        </h2>
                        <div id="c3" class="accordion-collapse collapse" data-bs-parent="#contactFaq">
                            <div class="accordion-body small text-secondary">
                                No. Masjid Locator is a completely free community service. We do not charge fees for directory submissions or timings updates.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
