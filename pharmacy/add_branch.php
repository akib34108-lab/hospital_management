<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Add Pharmacy Branch</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">
                <a href="branches.php" class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i>
                    Back to Branches
                </a>
            </div>
        </div>


        <!-- Branch Form -->
        <div class="card">

            <div class="card-header">
                <h4 class="card-title">
                    <i class="fa fa-hospital-o"></i>
                    Branch Information
                </h4>

                <p class="text-muted mb-0">
                    Add a new pharmacy branch to SHIFA.
                </p>
            </div>


            <div class="card-body">

                <form method="POST" action="">

                    <div class="row">

                        <!-- Branch Name -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Branch Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="branch_name"
                                       class="form-control"
                                       placeholder="e.g. SHIFA Agrabad Pharmacy"
                                       required>

                            </div>
                        </div>


                        <!-- Branch Code -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Branch Code
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="branch_code"
                                       class="form-control"
                                       placeholder="e.g. SHP-004"
                                       required>

                                <small class="form-text text-muted">
                                    Use a unique code for each branch.
                                </small>

                            </div>
                        </div>


                        <!-- Location -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Location
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="location"
                                       class="form-control"
                                       placeholder="e.g. Agrabad, Chattogram"
                                       required>

                            </div>
                        </div>


                        <!-- Address -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Full Address
                                </label>

                                <input type="text"
                                       name="address"
                                       class="form-control"
                                       placeholder="Enter complete branch address">

                            </div>
                        </div>


                        <!-- Phone -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Phone Number
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="phone"
                                       class="form-control"
                                       placeholder="e.g. 01700000000"
                                       required>

                            </div>
                        </div>


                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       placeholder="branch@example.com">

                            </div>
                        </div>


                        <!-- Manager -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Branch Manager
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="manager"
                                       class="form-control"
                                       placeholder="Enter branch manager name"
                                       required>

                            </div>
                        </div>


                        <!-- Manager Phone -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Manager Contact
                                </label>

                                <input type="text"
                                       name="manager_phone"
                                       class="form-control"
                                       placeholder="Manager phone number">

                            </div>
                        </div>


                        <!-- Opening Time -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Opening Time
                                </label>

                                <input type="time"
                                       name="opening_time"
                                       class="form-control"
                                       value="08:00">

                            </div>
                        </div>


                        <!-- Closing Time -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Closing Time
                                </label>

                                <input type="time"
                                       name="closing_time"
                                       class="form-control"
                                       value="22:00">

                            </div>
                        </div>


                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">

                                <label>
                                    Status
                                </label>

                                <select name="status"
                                        class="form-control">

                                    <option value="Active">
                                        Active
                                    </option>

                                    <option value="Inactive">
                                        Inactive
                                    </option>

                                </select>

                            </div>
                        </div>


                        <!-- Description -->
                        <div class="col-md-12">
                            <div class="form-group">

                                <label>
                                    Branch Description
                                </label>

                                <textarea name="description"
                                          rows="4"
                                          class="form-control"
                                          placeholder="Write any additional information about this branch..."></textarea>

                            </div>
                        </div>

                    </div>


                    <!-- Buttons -->
                    <div class="text-right">

                        <a href="branches.php"
                           class="btn btn-secondary">

                            Cancel

                        </a>

                        <button type="submit"
                                name="save_branch"
                                class="btn btn-primary">

                            <i class="fa fa-save"></i>
                            Save Branch

                        </button>

                    </div>

                </form>

            </div>

        </div>


        <!-- Important Information -->
        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-1 text-center">

                        <i class="fa fa-info-circle"
                           style="
                           font-size:38px;
                           color:#009efb;
                           ">
                        </i>

                    </div>


                    <div class="col-md-11">

                        <h5>
                            Why Branch Code is Important
                        </h5>

                        <p class="text-muted mb-0">

                            Each pharmacy branch should have a unique
                            branch code. This code will later help the
                            system identify medicine availability,
                            sales and reports for individual branches.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <?php
    require_once "../component/footer.php";
    ?>

</div>