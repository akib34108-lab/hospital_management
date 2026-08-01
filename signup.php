<?php require_once "component/header_auth.php"; ?>
    <div class="main-wrapper  account-wrapper">
        <div class="account-page">
            <div class="account-center">
                <div class="account-box">
                    <form action="" method="post" class="form-signin">
						<div class="account-logo">
                            <a href="index-2.html"><img src="assets/img/logo-dark.png" alt=""></a>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" required name="full_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" required name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" required name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" required name="password" class="form-control">
                        </div>
                        <div class="form-group checkbox">
                            <label>
                                <input type="checkbox"> I have read and agree the Terms & Conditions
                            </label>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary account-btn" type="submit">Signup</button>
                        </div>
                        <div class="text-center login-link">
                            Already have an account? <a href="login.php">Login</a>
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