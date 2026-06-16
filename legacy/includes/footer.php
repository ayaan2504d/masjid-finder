    </main>
    <!-- ═══════════ FOOTER ═══════════ -->
    <footer class="site-footer">
        <div class="footer-wave">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path fill="currentColor" d="M0,40 C360,100 720,0 1080,60 C1260,90 1380,50 1440,40 L1440,100 L0,100 Z"></path>
            </svg>
        </div>
        <div class="footer-content">
            <div class="container">
                <div class="row g-4">
                    <!-- About Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-brand">
                            <i class="fas fa-mosque"></i>
                            <span>Masjid<span class="highlight">Locator</span></span>
                        </div>
                        <p class="footer-desc">
                            Find the nearest masjid, check prayer timings, and navigate to your destination. 
                            Our smart GPS-powered system makes it easy to never miss a prayer.
                        </p>
                        <div class="footer-social">
                            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    <!-- Quick Links -->
                    <div class="col-lg-4 col-md-6">
                        <h5 class="footer-title">Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="<?php echo $base_url; ?>/"><i class="fas fa-chevron-right"></i> Home</a></li>
                            <li><a href="<?php echo $base_url; ?>/masjids/"><i class="fas fa-chevron-right"></i> Masajid</a></li>
                            <li><a href="<?php echo $base_url; ?>/map/"><i class="fas fa-chevron-right"></i> Map</a></li>
                            <li><a href="<?php echo $base_url; ?>/timings/"><i class="fas fa-chevron-right"></i> Prayer Timings</a></li>
                            <li><a href="<?php echo $base_url; ?>/timings/juma-eid.php"><i class="fas fa-chevron-right"></i> Juma & Eid</a></li>
                            <li><a href="<?php echo $base_url; ?>/about/"><i class="fas fa-chevron-right"></i> About Us</a></li>
                            <li><a href="<?php echo $base_url; ?>/contact/"><i class="fas fa-chevron-right"></i> Contact</a></li>
                        </ul>
                    </div>
                    <!-- Contact Info -->
                    <div class="col-lg-4 col-md-6">
                        <h5 class="footer-title">Contact Info</h5>
                        <ul class="footer-contact">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo isset($conn) ? getSetting($conn, 'contact_address', 'Karachi, Pakistan') : 'Karachi, Pakistan'; ?></span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span><?php echo isset($conn) ? getSetting($conn, 'contact_phone', '+92 300 1234567') : '+92 300 1234567'; ?></span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span><?php echo isset($conn) ? getSetting($conn, 'contact_email', 'info@masjidlocator.com') : 'info@masjidlocator.com'; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container text-center">
                <p>&copy; <?php echo date('Y'); ?> Masjid Locator. All Rights Reserved. Built with <i class="fas fa-heart text-danger"></i> for the Ummah.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/app.js"></script>
</body>
</html>
