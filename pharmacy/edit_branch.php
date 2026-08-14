<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

// Demo branch ID
$branch_id = isset($_GET['id']) ? $_GET['id'] : 1;

// Demo branch data
$branch = [
    "name" => "SHIFA Chattogram Pharmacy",
    "code" => "SHP-002",
    "location" => "Chattogram",
    "address" => "Agrabad Commercial Area, Chattogram",
    "phone" => "01700000002",
    "email" => "ctg@shifa.com",
    "manager" => "Branch Manager",
    "manager_phone" => "01800000002",
    "opening_time" => "08:00",
    "closing_time" => "22:00",
    "status" => "Active",
    "description" => "SHIFA pharmacy branch serving patients in Chattogram."
];
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Edit Pharmacy Branch</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">

                <a href="branches.php"
                   class="btn btn-secondary btn-rounded">

                    <i class="fa fa-arrow-left"></i>
                    Back to Branches

                </a>

            </div>
        </div>


        <!-- Branch Form -->
        <div class="card">

            <div class="card-header">

                <h4 class="card-title">

                    <i class="fa fa-pencil"></i>
                    Edit Branch Information

                </h4>

                <p class="text-muted mb-0">
                    Update pharmacy branch information.
                </p>

            </div>


            <div class="card-body">

                <form method="POST" action="">

                    <!-- Branch ID -->
                    <input type="hidden"
                           name="branch_id"
                           value="<?= htmlspecialchars($branch_id); ?>">


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
                                       value="<?= htmlspecialchars($branch['name']); ?>"
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
                                       value="<?= htmlspecialchars($branch['code']); ?>"
                                       required>

                                <small class="form-text text-muted">
                                    Branch code should be unique.
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
                                       value="<?= htmlspecialchars($branch['location']); ?>"
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
                                       value="<?= htmlspecialchars($branch['address']); ?>">

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
                                       value="<?= htmlspecialchars($branch['phone']); ?>"
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
                                       value="<?= htmlspecialchars($branch['email']); ?>">

                            </div>

                        </div>


                        <!-- Branch Manager -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Branch Manager
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="manager"
                                       class="form-control"
                                       value="<?= htmlspecialchars($branch['manager']); ?>"
                                       required>

                            </div>

                        </div>


                        <!-- Manager Contact -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Manager Contact
                                </label>

                                <input type="text"
                                       name="manager_phone"
                                       class="form-control"
                                       value="<?= htmlspecialchars($branch['manager_phone']); ?>">

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
                                       value="<?= htmlspecialchars($branch['opening_time']); ?>">

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
                                       value="<?= htmlspecialchars($branch['closing_time']); ?>">

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

                                    <option value="Active"
                                        <?= $branch['status'] == 'Active' ? 'selected' : ''; ?>>
                                        Active
                                    </option>

                                    <option value="Inactive"
                                        <?= $branch['status'] == 'Inactive' ? 'selected' : ''; ?>>
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
                                          placeholder="Write additional information..."><?= htmlspecialchars($branch['description']); ?></textarea>

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
                                name="update_branch"
                                class="btn btn-primary">

                            <i class="fa fa-save"></i>
                            Update Branch

                        </button>

                    </div>

                </form>

            </div>

        </div>


        <!-- Branch Medicine Shortcut -->
        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-1 text-center">

                        <i class="fa fa-medkit"
                           style="
                           font-size:38px;
                           color:#009efb;
                           ">
                        </i>

                    </div>


                    <div class="col-md-8">

                        <h5>
                            Medicines Available in This Branch
                        </h5>

                        <p class="text-muted mb-0">

                            Check which medicines are currently
                            available in this pharmacy branch.

                        </p>

                    </div>


                    <div class="col-md-3 text-right">

                        <a href="medicine_availability.php?branch_id=<?= $branch_id; ?>"
                           class="btn btn-info">

                            <i class="fa fa-search"></i>
                            View Medicines

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <?php
    require_once "../component/footer.php";
    ?>

</div>