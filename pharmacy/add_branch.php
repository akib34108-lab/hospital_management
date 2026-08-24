<?php
require_once "../component/connection.php";
require_once "../component/header.php";
require_once "../component/sidebar.php";

if (isset($_POST['add_branch'])) {
    $branch_name = trim($_POST['branch_name']);
    $branch_code = trim($_POST['branch_code']);
    $location = trim($_POST['location']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $status = $_POST['status'];

    if (empty($branch_name) || empty($branch_code) || empty($location) || empty($address) || empty($phone)) {
        echo "<script>alert('Please fill all required fields');</script>";
    } else {
        $check = $crud->common_select("pharmacy_branches", "*", ["branch_code" => $branch_code]);

        if ($check["status"]) {
            echo "<script>alert('Branch code already exists');</script>";
        } else {
            $data = [
                "branch_name" => $branch_name,
                "branch_code" => $branch_code,
                "location" => $location,
                "address" => $address,
                "phone" => $phone,
                "status" => $status
            ];

            $insert_result = $crud->common_insert("pharmacy_branches", $data);

            if ($insert_result["status"]) {
                echo "<script>alert('Branch added successfully'); window.location.href='branches.php';</script>";
                exit;
            } else {
                echo "<script>alert('Error: " . addslashes($insert_result["message"]) . "');</script>";
            }
        }
    }
}
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Add Pharmacy Branch</h4>
            </div>
            <div class="col-sm-5 col-6 text-right">
                <a href="branches.php" class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i> Back to Branches
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fa fa-hospital-o"></i> Branch Information
                </h4>
                <p class="text-muted mb-0">Add a new pharmacy branch.</p>
            </div>

            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Branch Name <span class="text-danger">*</span></label>
                                <input type="text" name="branch_name" class="form-control" placeholder="e.g. SHIFA Agrabad Branch" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Branch Code <span class="text-danger">*</span></label>
                                <input type="text" name="branch_code" class="form-control" placeholder="e.g. SHIFA-AGR" required>
                                <small class="text-muted">Use a unique code for each branch.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" placeholder="e.g. Agrabad, Chattogram" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" placeholder="e.g. 01911111111" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Full Address <span class="text-danger">*</span></label>
                                <textarea name="address" rows="4" class="form-control" placeholder="Enter full branch address" required></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <a href="branches.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="add_branch" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save Branch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once "../component/footer.php"; ?>
</div>