        <footer>
                <div class="row" style=" border-top: 3px solid #ccc; padding-top: 20px; margin-top: 50px;">
                    <div class="col-sm-4 offset-sm-2">
                        <div class="font-weight-bold">SHIFA Hospital Management</div>
                            <p class="footer-description">Empowering healthcare providers with smart management tools to deliver efficient, seamless, and patient-centered care.</p>
                        <div class="social-icons">
                            <a href="#" style="font-size: 20px;"><i class="fa fa-facebook p-1 "></i></a>
                            <a href="#" style="font-size: 20px;"><i class="fa fa-linkedin p-1"></i></a>
                            <a href="#" style="font-size: 20px;"><i class="fa fa-twitter p-1"></i></a>
                            <a href="#" style="font-size: 20px;"><i class="fa fa-instagram p-1"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h6 class="font-weight-bold">RESOURCES</h6>
                        <ul class="footer-links">
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Support</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-3">
                    <h6 class="font-weight-bold">LEGAL</h6>
                        <ul class="footer-links">
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">License & Consent</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center" style="font-size: 16px; color: #fff;background-color: #009efb; margin-bottom: 20px; padding: 10px; border-radius: 5px;">
                    <span style="margin-right: 20px;">Copyright © 2026 SHIFA</span> | <span style="margin-left: 20px;">All Rights Reserved by SHIFA Hospital Management Team</span>
                </div>
            </div>
        </div>
    </footer>
    
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