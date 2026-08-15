<?php

require_once "../component/connection.php";
require_once "../component/header.php";
require_once "../component/sidebar.php";


/* =========================
   Get Medicine ID
   ========================= */

$medicine_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


/* =========================
   Check ID
   ========================= */

if ($medicine_id <= 0) {

    echo "<script>
            alert('Invalid medicine ID');
            window.location.href='medicines.php';
          </script>";

    exit;
}


/* =========================
   Get Medicine
   ========================= */

$result = $crud->common_select(
    "medicines",
    "*",
    ["medicine_id" => $medicine_id]
);


if (!$result["status"]) {

    echo "<script>
            alert('Medicine not found');
            window.location.href='medicines.php';
          </script>";

    exit;
}


$medicine = $result["data"][0];

?>

<div class="page-wrapper">

    <div class="content">


        <!-- Page Header -->
        <div class="row">

            <div class="col-sm-7 col-6">

                <h4 class="page-title">
                    Medicine Details
                </h4>

            </div>


            <div class="col-sm-5 col-6 text-right">

                <a href="medicines.php"
                   class="btn btn-secondary btn-rounded">

                    <i class="fa fa-arrow-left"></i>

                    Back to Medicines

                </a>

            </div>

        </div>


        <!-- Medicine Details Card -->
        <div class="card">


            <!-- Card Header -->
            <div class="card-header">

                <div class="row align-items-center">


                    <div class="col-md-8">

                        <h4 class="card-title mb-0">

                            <i class="fa fa-medkit"></i>

                            <?php
                            echo htmlspecialchars(
                                $medicine->medicine_name
                            );
                            ?>

                        </h4>

                    </div>


                    <div class="col-md-4 text-right">

                        <?php if ($medicine->status == "Active"): ?>

                            <span class="badge badge-success">

                                <i class="fa fa-check-circle"></i>

                                Active

                            </span>

                        <?php else: ?>

                            <span class="badge badge-warning">

                                <i class="fa fa-exclamation-circle"></i>

                                Inactive

                            </span>

                        <?php endif; ?>

                    </div>


                </div>

            </div>


            <!-- Card Body -->
            <div class="card-body">


                <!-- Basic Information -->
                <div class="row">


                    <div class="col-md-12">

                        <h5 class="mb-3">

                            <i class="fa fa-info-circle text-info"></i>

                            Basic Information

                        </h5>

                    </div>


                    <!-- Medicine Name -->
                    <div class="col-md-6">

                        <div class="form-group">

                            <label class="text-muted">
                                Medicine Name
                            </label>

                            <h5>

                                <?php
                                echo htmlspecialchars(
                                    $medicine->medicine_name
                                );
                                ?>

                            </h5>

                        </div>

                    </div>


                    <!-- Generic Name -->
                    <div class="col-md-6">

                        <div class="form-group">

                            <label class="text-muted">
                                Generic Name
                            </label>

                            <h5>

                                <?php
                                echo htmlspecialchars(
                                    $medicine->generic_name
                                );
                                ?>

                            </h5>

                        </div>

                    </div>


                    <!-- Manufacturer -->
                    <div class="col-md-6">

                        <div class="form-group">

                            <label class="text-muted">
                                Brand / Manufacturer
                            </label>

                            <h5>

                                <?php
                                echo htmlspecialchars(
                                    $medicine->manufacturer
                                );
                                ?>

                            </h5>

                        </div>

                    </div>


                    <!-- Category -->
                    <div class="col-md-6">

                        <div class="form-group">

                            <label class="text-muted">
                                Category
                            </label>

                            <h5>

                                <span class="badge badge-info">

                                    <?php
                                    echo htmlspecialchars(
                                        $medicine->category
                                    );
                                    ?>

                                </span>

                            </h5>

                        </div>

                    </div>


                    <!-- Dosage Form -->
                    <div class="col-md-4">

                        <div class="form-group">

                            <label class="text-muted">
                                Dosage Form
                            </label>

                            <h5>

                                <?php
                                echo htmlspecialchars(
                                    $medicine->dosage_form
                                );
                                ?>

                            </h5>

                        </div>

                    </div>


                    <!-- Strength -->
                    <div class="col-md-4">

                        <div class="form-group">

                            <label class="text-muted">
                                Strength
                            </label>

                            <h5>

                                <?php
                                echo htmlspecialchars(
                                    $medicine->strength
                                );
                                ?>

                            </h5>

                        </div>

                    </div>


                    <!-- Unit -->
                    <div class="col-md-4">

                        <div class="form-group">

                            <label class="text-muted">
                                Unit
                            </label>

                            <h5>

                                <?php
                                echo htmlspecialchars(
                                    $medicine->unit
                                );
                                ?>

                            </h5>

                        </div>

                    </div>


                </div>


                <hr>


                <!-- Pricing & Stock Information -->
                <div class="row">


                    <div class="col-md-12">

                        <h5 class="mb-3">

                            <i class="fa fa-money text-success"></i>

                            Pricing & Stock Information

                        </h5>

                    </div>


                    <!-- Unit Price -->
                    <div class="col-md-4">

                        <div class="card bg-light">

                            <div class="card-body text-center">

                                <i class="fa fa-money"
                                   style="
                                   font-size:25px;
                                   color:#28a745;
                                   ">
                                </i>

                                <p class="text-muted mb-1">
                                    Unit Price
                                </p>

                                <h4>

                                    ৳<?php
                                    echo number_format(
                                        (float)$medicine->unit_price,
                                        2
                                    );
                                    ?>

                                </h4>

                            </div>

                        </div>

                    </div>


                    <!-- Reorder Level -->
                    <div class="col-md-4">

                        <div class="card bg-light">

                            <div class="card-body text-center">

                                <i class="fa fa-level-down"
                                   style="
                                   font-size:25px;
                                   color:#f5a623;
                                   ">
                                </i>

                                <p class="text-muted mb-1">
                                    Reorder Level
                                </p>

                                <h4>

                                    <?php
                                    echo htmlspecialchars(
                                        $medicine->reorder_level
                                    );
                                    ?>

                                </h4>

                            </div>

                        </div>

                    </div>


                    <!-- Expiry Date -->
                    <div class="col-md-4">

                        <div class="card bg-light">

                            <div class="card-body text-center">

                                <i class="fa fa-calendar"
                                   style="
                                   font-size:25px;
                                   color:#009efb;
                                   ">
                                </i>

                                <p class="text-muted mb-1">
                                    Expiry Date
                                </p>

                                <h4>

                                    <?php

                                    if (!empty($medicine->expiry_date)) {

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $medicine->expiry_date
                                            )
                                        );

                                    } else {

                                        echo "Not specified";

                                    }

                                    ?>

                                </h4>

                            </div>

                        </div>

                    </div>


                </div>


                <hr>


                <!-- Description -->
                <div class="row">


                    <div class="col-md-12">

                        <h5 class="mb-3">

                            <i class="fa fa-file-text-o text-info"></i>

                            Description

                        </h5>


                        <?php if (!empty($medicine->description)): ?>

                            <p class="text-muted">

                                <?php
                                echo nl2br(
                                    htmlspecialchars(
                                        $medicine->description
                                    )
                                );
                                ?>

                            </p>

                        <?php else: ?>

                            <p class="text-muted">

                                No description available.

                            </p>

                        <?php endif; ?>


                    </div>

                </div>


                <hr>


                <!-- Actions -->
                <div class="text-right">


                    <a href="medicines.php"
                       class="btn btn-secondary">

                        <i class="fa fa-arrow-left"></i>

                        Back

                    </a>


                    <a href="edit_medicine.php?id=<?php echo $medicine->medicine_id; ?>"
                       class="btn btn-primary">

                        <i class="fa fa-pencil"></i>

                        Edit Medicine

                    </a>


                    <a href="medicine_availability.php?medicine_id=<?php echo $medicine->medicine_id; ?>"
                       class="btn btn-info">

                        <i class="fa fa-search"></i>

                        Check Availability

                    </a>


                </div>


            </div>

        </div>


    </div>


    <?php
    require_once "../component/footer.php";
    ?>

</div>