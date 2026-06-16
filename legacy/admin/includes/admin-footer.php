            </div><!-- /.admin-content -->
        </div><!-- /.admin-main -->
    </div><!-- /.admin-wrapper -->

    <!-- Bootstrap & Leaflet JavaScripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Sidebar Toggle Logic -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('active');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (sidebar.classList.contains('active') && !sidebar.contains(e.target) && e.target !== toggleBtn) {
                    sidebar.classList.remove('active');
                }
            });
        }
    });
    </script>
</body>
</html>
