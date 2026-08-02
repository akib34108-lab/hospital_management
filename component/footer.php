   <div class="sidebar-overlay" data-reff=""></div>
    <script src="<?= $base_url ?>assets/assets/js/jquery-3.2.1.min.js"></script>
	<script src="<?= $base_url ?>assets/assets/js/popper.min.js"></script>
    <script src="<?= $base_url ?>assets/assets/js/bootstrap.min.js"></script>
    <script src="<?= $base_url ?>assets/assets/js/jquery.slimscroll.js"></script>
    <script src="<?= $base_url ?>assets/assets/js/Chart.bundle.js"></script>
    <script src="<?= $base_url ?>assets/assets/js/chart.js"></script>
    <script src="<?= $base_url ?>assets/assets/js/app.js"></script>
    <script src="<?= $base_url ?>assets/assets/plugins/jquery.toaster-master/jquery.toaster.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (isset($_SESSION['message'])): ?>
                $.toaster({
                    priority: '<?php echo $_SESSION['message'][0]; ?>',
                    title: '<?php echo $_SESSION['message'][1]; ?>',
                    message: '<?php echo $_SESSION['message'][2]; ?>'
                });
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
        });

      </script>
</body>



</html>