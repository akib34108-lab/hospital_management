<?php 
    require_once "component/header_auth.php";
?>
    <div class="main-wrapper account-wrapper">
        <div class="account-page">
			<div class="account-center">
				<div class="account-box">
                    <form action="" method="post" class="form-signin">
						<div class="account-logo">
                            <a href="index-2.html"><img src="<?= $base_url; ?>assets/assets/img/logo-dark.png" alt=""></a>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" autofocus="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-group text-right">
                            <a href="forgot-password.html">Forgot your password?</a>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary account-btn">Login</button>
                        </div>
                        <div class="text-center register-link">
                            Don’t have an account? <a href="signup.php">Register Now</a>
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
                            echo '<script>window.location.href = "dashboard.php";</script>';
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