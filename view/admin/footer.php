<footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright © 2036 <a href="#">Cyborg Gaming</a> Company. All rights reserved. 
                    <br>Design: <a href="https://templatemo.com" target="_blank" title="free CSS templates">TemplateMo</a> | Admin Panel</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery FIRST (from CDN as fallback) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    
    <!-- Template Scripts -->
    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/tabs.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/custom.js"></script>
    
    <!-- DataTables JS (after jQuery) -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <?php if(isset($customJS)): ?>
        <script><?php echo $customJS; ?></script>
    <?php endif; ?>

</body>
</html>