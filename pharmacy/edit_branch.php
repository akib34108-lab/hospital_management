<?php

require_once "../component/connection.php";
require_once "../component/header.php";
require_once "../component/sidebar.php";


/* =========================
   Get Branch ID
   ========================= */

$branch_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


/* =========================
   Check Branch ID
   ========================= */

if ($branch_id <= 0) {

    echo "<script>
            alert('Invalid branch ID');
            window.location.href='branches.php';
          </script>";

    exit;
}


/* =========================
   Get Branch Data
   ========================= */

$result = $crud->common_select(
    "pharmacy_branches",
    "*",
    ["branch_id" => $branch_id]
);


if (!$result["status"]) {

    echo "<script>
            alert('Branch not found');
            window.location.href='branches.php';
          </script>";

    exit;
}


$branch = $result["data"][0];


/* =========================
   Update Branch
   ========================= */

if (isset($_POST['update_branch'])) {

    $branch_name = trim($_POST['branch_name']);
    $branch_code = trim($_POST['branch_code']);
    $location = trim($_POST['location']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $status = $_POST['status'];


    /* =========================
       Data to Update
       ========================= */

    $data = [

        "branch_name" => $branch_name,
        "branch_code" => $branch_code,
        "location" => $location,
        "address" => $address,
        "phone" => $phone,
        "status" => $status

    ];


    /* =========================
       Update Database
       ========================= */

    $update_result = $crud->common_update(
        "pharmacy_branches",
        $data,
        ["branch_id" => $branch_id]
    );


    /* =========================
       Check Update Result
       ========================= */

    if ($update_result["status"]) {

        echo "<script>
                alert('Branch updated successfully');
                window.location.href='branches.php';
              </script>";

        exit;

    } else {

        echo "<script>
                alert('Error: " . htmlspecialchars($update_result["message"]) . "');
              </script>";
    }


    /* =========================
       Update Form Values
       ========================= */

    $branch->branch_name = $branch_name;
    $branch->branch_code = $branch_code;
    $branch->location = $location;
    $branch->address = $address;
    $branch->phone = $phone;
    $branch->status = $status;
}

?>


<div class="page-wrapper">

    <div class="content">


        <!-- =========================
             Page Header
             ========================= -->

        <div class="row">

            <div class="col-sm-7 col-6">

                <h4 class="page-title">
                    Edit Pharmacy Branch
                </h4>

            </div>


            <div class="col-sm-5 col-6 text-right">

                <a href="branches.php"
                   class="btn btn-secondary btn-rounded">

                    <i class="fa fa-arrow-left"></i>

                    Back to Branches

                </a>

            </div>

        </div>


        <!-- =========================
             Edit Branch Card
             ========================= -->

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


                    <input type="hidden"
                           name="branch_id"
                           value="<?php echo $branch_id; ?>">


                    <div class="row">


                        <!-- =========================
                             Branch Name
                             ========================= -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Branch Name

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="text"
                                       name="branch_name"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($branch->branch_name ?? ''); ?>"
                                       placeholder="Enter branch name"
                                       required>

                            </div>

                        </div>


                        <!-- =========================
                             Branch Code
                             ========================= -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Branch Code

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="text"
                                       name="branch_code"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($branch->branch_code ?? ''); ?>"
                                       placeholder="Example: SHIFA-AGR"
                                       required>

                            </div>

                        </div>


                        <!-- =========================
                             Location
                             ========================= -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Location

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="text"
                                       name="location"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($branch->location ?? ''); ?>"
                                       placeholder="Example: Agrabad"
                                       required>

                            </div>

                        </div>


                        <!-- =========================
                             Phone
                             ========================= -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Phone

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="text"
                                       name="phone"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($branch->phone ?? ''); ?>"
                                       placeholder="Example: 01911111111"
                                       required>

                            </div>

                        </div>


                        <!-- =========================
                             Full Address
                             ========================= -->

                        <div class="col-md-12">

                            <div class="form-group">

                                <label>

                                    Full Address

                                    <span class="text-danger">*</span>

                                </label>


                                <textarea name="address"
                                          rows="4"
                                          class="form-control"
                                          placeholder="Enter full branch address"
                                          required><?php echo htmlspecialchars($branch->address ?? ''); ?></textarea>

                            </div>

                        </div>


                        <!-- =========================
                             Status
                             ========================= -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Status
                                </label>


                                <select name="status"
                                        class="form-control">


                                    <option value="Active"
                                        <?php
                                        echo (($branch->status ?? '') == 'Active')
                                            ? 'selected'
                                            : '';
                                        ?>>

                                        Active

                                    </option>


                                    <option value="Inactive"
                                        <?php
                                        echo (($branch->status ?? '') == 'Inactive')
                                            ? 'selected'
                                            : '';
                                        ?>>

                                        Inactive

                                    </option>


                                </select>

                            </div>

                        </div>


                    </div>


                    <!-- =========================
                         Buttons
                         ========================= -->

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

    </div>


    <?php require_once "../component/footer.php"; ?>

</div>