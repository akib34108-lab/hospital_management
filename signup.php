<?php require_once "component/header_auth.php"; ?>
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
                            <label class="font-weight-bold" style="color: #0b5884;">Username</label>
                            <input type="text" name="full_name" autofocus class="form-control" style="border: 1px solid #d9e2e8; border-radius: 50px; padding: 10px 15px; font-size: 15px; background: #f8fafc; transition: 0.3s;" onfocus="this.style.borderColor='#0b5884'; this.style.boxShadow='0 0 0 3px rgba(11,88,132,0.15)'; this.style.background='#fff';" onblur="this.style.borderColor='#d9e2e8'; this.style.boxShadow='none'; this.style.background='#f8fafc';">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" style="color: #0b5884;">Email</label>
                            <input type="text" name="email" autofocus class="form-control" style="border: 1px solid #d9e2e8; border-radius: 50px; padding: 10px 15px; font-size: 15px; background: #f8fafc; transition: 0.3s;" onfocus="this.style.borderColor='#0b5884'; this.style.boxShadow='0 0 0 3px rgba(11,88,132,0.15)'; this.style.background='#fff';" onblur="this.style.borderColor='#d9e2e8'; this.style.boxShadow='none'; this.style.background='#f8fafc';">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" style="color: #0b5884;">Phone</label>
                            <input type="tel" name="phone" autofocus class="form-control" style="border: 1px solid #d9e2e8; border-radius: 50px; padding: 10px 15px; font-size: 15px; background: #f8fafc; transition: 0.3s;" onfocus="this.style.borderColor='#0b5884'; this.style.boxShadow='0 0 0 3px rgba(11,88,132,0.15)'; this.style.background='#fff';" onblur="this.style.borderColor='#d9e2e8'; this.style.boxShadow='none'; this.style.background='#f8fafc';">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" style="color: #0b5884;">Password</label>
                            <input type="password" name="password" autofocus class="form-control" style="border: 1px solid #d9e2e8; border-radius: 50px; padding: 10px 15px; font-size: 15px; background: #f8fafc; transition: 0.3s;" onfocus="this.style.borderColor='#0b5884'; this.style.boxShadow='0 0 0 3px rgba(11,88,132,0.15)'; this.style.background='#fff';" onblur="this.style.borderColor='#d9e2e8'; this.style.boxShadow='none'; this.style.background='#f8fafc';">
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary account-btn font-weight-bold">Sign Up</button>
                        </div>
                        <div class="text-left font-weight-bold" style="color: #0b5884;">
                            Already have an account? <a href="login.php" class="font-weight-bold" style="color: #0b5884;">Login</a>
                        </div>
                    </form>
                    <?php
                    if ($_POST) {
                       
                        $_POST['password'] = sha1($_POST['password']);
                        $_POST['role_id'] = '1'; 

                        // Validate input
                        if (empty($_POST['full_name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['password'])) {
                            $_SESSION['message'] = array('danger','Error', 'All fields are required.');
                        } else {
                          
                            // check if the email already exists in the database
                            $existingUser = $crud->common_query("SELECT * FROM users WHERE email = '{$_POST['email']}'");
                            if ($existingUser['status'] && count($existingUser['data']) > 0) {
                                 $_SESSION['message'] = array('danger','Error', 'Email already exists. Please use a different email.');
                            } else {
                                // Prepare and execute the SQL statement
                                $rs = $crud->common_insert("users", $_POST);
                                
                                if ($rs['status']) {
                                    $_SESSION['message'] = array('success','Success', 'Registration successful! You can now login.');
                                    echo '<script>window.location.href = "login.php";</script>';
                                
                                } else {
                                    $_SESSION['message'] = array('danger','Error', 'Registration failed. Please try again.');
                                    echo '<script>window.location.href = "signup.php";</script>';
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php require_once "component/footer.php" ?>