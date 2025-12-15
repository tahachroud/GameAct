<?php
// Define base URL for assets - IMPORTANT!
$base_url = '/GameAct/';
?>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright © 2025 <a href="#">GameAct Gaming Platform</a>. All rights reserved. 
                <br>Design: <a href="https://templatemo.com" target="_blank" title="free CSS templates">TemplateMo</a></p>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery FIRST (from CDN as fallback) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="<?php echo $base_url; ?>vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Template Scripts -->
<script src="<?php echo $base_url; ?>assets/js/isotope.min.js"></script>
<script src="<?php echo $base_url; ?>assets/js/owl-carousel.js"></script>
<script src="<?php echo $base_url; ?>assets/js/tabs.js"></script>
<script src="<?php echo $base_url; ?>assets/js/popup.js"></script>
<script src="<?php echo $base_url; ?>assets/js/custom.js"></script>

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