<?php
/**
 * Technical Specs & FAQ Page — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Technical specifications details (Leaflet, Haversine, PHP/MySQL)
 * - FAQ Accordion with common user queries
 * - Site data usage policy details
 */

$page_title = 'Technical Specs & FAQ';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- ═══════════ BREADCRUMB SECTION ═══════════ -->
<section class="breadcrumb-section">
    <div class="container">
        <h2 class="breadcrumb-title">Technical Details & FAQ</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/about/">About Us</a></li>
                <li class="breadcrumb-item active" aria-current="page">Specs & FAQ</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ═══════════ DETAILS CONTENT ═══════════ -->
<div class="container my-5 animate-fadeInUp">
    <!-- Tech Specs Block -->
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="card p-4 h-100 border-0 shadow-sm rounded-4">
                <h5 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="fas fa-microchip text-success"></i> Technical Architecture</h5>
                <p class="text-secondary small">This application runs on a clean, framework-free native PHP stack backed by a relational MySQL database server. The front-end is assembled with Bootstrap 5 templates and styled with customized vanilla CSS declarations.</p>
                <ul class="text-secondary small ps-3">
                    <li><strong>Haversine Formula:</strong> Trigonometrical calculation implemented on both SQL queries and client-side JavaScript to determine exact geographical distances in kilometers.</li>
                    <li><strong>OpenStreetMap Integration:</strong> Rendered client-side using Leaflet.js maps, enabling pins, clustering, popups, and radius parameters.</li>
                    <li><strong>No Login Required:</strong> Full administrative CRUD options configured directly on paths without session gates for demonstration convenience.</li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4 h-100 border-0 shadow-sm rounded-4">
                <h5 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="fas fa-shield-alt text-success"></i> Privacy & Data Policy</h5>
                <p class="text-secondary small">Your privacy is extremely important. We do not store or transmit any of your geographical location metadata to external servers or telemetry databases.</p>
                <ul class="text-secondary small ps-3">
                    <li><strong>Local GPS Usage:</strong> Latitude and longitude coordinates are processed directly within your browser window using standard JavaScript APIs.</li>
                    <li><strong>Session Cache:</strong> Geolocation coordinates are cached temporarily in local storage contexts to avoid repeatedly popping up browser permission requests.</li>
                    <li><strong>Inquiry Storage:</strong> The contact form details (name, email, and messages) are written to our secure MySQL server solely for communication replies.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- FAQ Accordion -->
    <h3 class="section-title text-center d-block mb-2">Frequently Asked Questions</h3>
    <p class="section-subtitle text-center mb-5">Common queries regarding our locator system</p>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="accordion" id="faqAccordion">
                <!-- FAQ 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            How does the GPS locator determine my distance?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            The application requests your GPS coordinates using the standard browser `navigator.geolocation` API. Once accepted, your latitude and longitude are calculated against the coordinates of the mosques in our database using the Haversine formula:
                            <br><code class="d-block bg-light p-2 mt-2 border rounded">d = 2R × arcsin(√[sin²(Δlat/2) + cos(lat1) × cos(lat2) × sin²(Δlon/2)])</code>
                            This gives a highly accurate straight-line distance in kilometers.
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            Why does the map ask for location permissions?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Location permission is required for the map to display your current position as a pulsing blue dot and calculate which mosques are nearest to you. If you deny the request, the application will default to Karachi city center coordinates, and you will not see calculated distances.
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            How are the prayer timings updated?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Prayer timings (Fajr, Zuhr, Asr, Maghrib, Isha, Juma, and Eid) are updated by local mosque administrators through the Admin Panel dashboard. Timings are displayed in local 12-hour AM/PM formats, with countdown timers indicating the remaining time.
                        </div>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                            What is the difference between Sunni and Shia listings?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Our directory serves all members of the Muslim Ummah. We categorize mosques by their school of thought (Sect: Sunni / Shia) because minor differences exist in congregational prayer timings (such as Maghrib) and Juma details, allowing users to find locations that align with their practice.
                        </div>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                            Can I add a new masjid to the database?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Currently, you can add new mosques directly through the Admin Panel using the "Add New Masjid" page. An interactive map pin picker is provided, allowing you to click on any location to automatically populate the latitude and longitude fields.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
