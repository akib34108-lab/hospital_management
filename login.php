<?php 
    require_once "component/header_auth.php";
?>
    <div class="main-wrapper account-wrapper" style="background-image: url('<?= $base_url; ?>assets/assets/img/login-bg.png'); background-size: cover; background-position: center; min-height: 100vh;">
        <div class="account-page">
			<div class="account-center">
				<div class="account-box" style="border: 1px solid #0b5884; padding: 20px; border-radius: 10px; background-color: rgba(255, 255, 255, 0.9); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <form action="" method="post" class="form-signin">
						<div class="account-logo">
                            <a href="index-2.html"><img src="<?= $base_url; ?>assets/assets/img/logo-dark.png" alt=""></a>
                            <div style="margin-top: 15px;">
                                <span class="font-weight-bold">SHIFA</span><br>
                                <span style="font-size: 12px;">Hospital Management</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" style="color: #0b5884;">Email</label>
                            <input type="text" name="email" autofocus class="form-control" style="border: 1px solid #d9e2e8; border-radius: 50px; padding: 10px 15px; font-size: 15px; background: #f8fafc; transition: 0.3s;" onfocus="this.style.borderColor='#0b5884'; this.style.boxShadow='0 0 0 3px rgba(11,88,132,0.15)'; this.style.background='#fff';" onblur="this.style.borderColor='#d9e2e8'; this.style.boxShadow='none'; this.style.background='#f8fafc';">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" style="color: #0b5884;">Password</label>
                            <input type="password" name="password" autofocus class="form-control" style="border: 1px solid #d9e2e8; border-radius: 50px; padding: 10px 15px; font-size: 15px; background: #f8fafc; transition: 0.3s;" onfocus="this.style.borderColor='#0b5884'; this.style.boxShadow='0 0 0 3px rgba(11,88,132,0.15)'; this.style.background='#fff';" onblur="this.style.borderColor='#d9e2e8'; this.style.boxShadow='none'; this.style.background='#f8fafc';">
                        </div>
                        <div class="form-group text-right">
                            <a href="forgot-password.html"  class="font-weight-bold" style="color: #0b5884;">Forgot your password?</a>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary account-btn font-weight-bold">Login</button>
                        </div>
                        <div class="text-left register-link font-weight-bold" style="color: #0b5884;">
                            Don’t have an account? <a href="signup.php"  class="font-weight-bold" style="color: #0b5884;">Register Now</a>
                        </div>
                    </form>
                    <?php
                    if ($_POST) {
                        $_POST['password'] = sha1($_POST['password']);

                        // Prepare and execute the SQL statement
                        $rs = $crud->common_query("
                                                    SELECT 
                                                    users.*, roles.role_name, roles.access
                                                    FROM `users`
                                                    join roles on roles.id=users.role_id
                                                    WHERE
                                                    users.email = '{$_POST['email']}'
                                                    AND
                                                    users.password = '{$_POST['password']}'
                                                ");

                        if ($rs['status']) {
                            // User found, set session variables
                            $user = $rs['data'][0];
                            $_SESSION['user_id'] = $user->id; // Store user ID in session
                            $_SESSION['user_name'] = $user->full_name; // Store user name in session
                            $_SESSION['user_email'] = $user->email; // Store user email in session
                            $_SESSION['user_phone'] = $user->phone; // Store user phone in session
                            $_SESSION['user_role'] = $user->role_name; // Store user role in session
                            $_SESSION['access'] = $user->access; // Store user access in session
                            $_SESSION['is_logged_in'] = true; // Set a flag to indicate the user is logged in
                            // Redirect to dashboard or home page
                            echo '<script>window.location.href = "index.php";</script>';
                        } else {
                            echo '<div class="alert alert-danger">Invalid email or password.</div>';
                        }

                        
                    }
                    ?>
                </div>
			</div>
        </div>
    </div>
<?php require_once "component/footer.php" ?>