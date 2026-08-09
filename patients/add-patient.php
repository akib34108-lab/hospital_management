<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->
 

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <h4 class="page-title">Add Patient</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form action="<?= $base_url; ?>patients/store_patient.php" method="post">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control" name="name" type="text">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="form-group gender-select">
                                    <label class="gen-label">Gender:</label>

                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" name="gender" class="form-check-input" value="Male">Male
                                        </label>
                                    </div>

                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" name="gender" class="form-check-input" value="Female">Female
                                        </label>
                                    </div>

                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" name="gender" class="form-check-input" value="Others">Others
                                        </label>
                                    </div>
                                </div>
                        </div>
                       <div class="col-sm-3">
                        <div class="form-group">
                            <label>Blood Group</label>

                            <select name="blood_group" class="form-control">
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Age</label>
                                <input name="age" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input name="phone" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input name="email" class="form-control" type="text">
                            </div>
                        </div>  
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Address</label>
                                <input name="address" class="form-control" type="text">
                            </div>
                        </div>  
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Emergency contact</label>
                                <input name="emergency_contact" class="form-control" type="text">
                            </div>
                        </div>  
                                
                    <div class="m-t-20 text-center col-sm-12">
                    <button class="btn btn-primary submit-btn">Create Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once "../component/footer.php" ?> 