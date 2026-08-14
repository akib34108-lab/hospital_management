<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

// Demo medicine ID
$medicine_id = isset($_GET['id']) ? $_GET['id'] : 1;

// Demo medicine data
$medicine = [
    "medicine_name" => "Napa",
    "generic_name" => "Paracetamol",
    "brand_name" => "Beximco",
    "category" => "Painkiller",
    "dosage_form" => "Tablet",
    "strength" => "500mg",
    "unit" => "Piece",
    "description" => "Used for the temporary relief of mild to moderate pain and fever.",
    "status" => "Active"
];

// Demo branch availability
$branch_availability = [
    [
        "branch_name" => "SHIFA Main Branch",
        "location" => "Dhaka",
        "quantity" => 120,
        "status" => "Available"
    ],
    [
        "branch_name" => "SHIFA Chattogram Branch",
        "location" => "Chattogram",
        "quantity" => 75,
        "status" => "Available"
    ],
    [
        "branch_name" => "SHIFA Agrabad Branch",
        "location" => "Agrabad",
        "quantity" => 0,
        "status" => "Not Available"
    ]
];
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Medicine Details</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">

                <a href="edit_medicine.php?id=<?= $medicine_id; ?>"
                   class="btn btn-primary btn-rounded">
                    <i class="fa fa-pencil"></i>
                    Edit Medicine
                </a>

                <a href="medicines.php"
                   class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i>
                    Back
                </a>

            </div>
        </div>


        <!-- Medicine Overview -->
        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    <!-- Medicine Icon -->
                    <div class="col-md-2 text-center">

                        <div style="
                            width:100px;
                            height:100px;
                            margin:auto;
                            border-radius:15px;
                            background:#e8f7ff;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            ">

                            <i class="fa fa-medkit"
                               style="
                               font-size:50px;
                               color:#009efb;
                               ">
                            </i>

                        </div>

                    </div>


                    <!-- Main Information -->
                    <div class="col-md-7">

                        <h2 style="margin-bottom:5px;">
                            <?= htmlspecialchars($medicine['medicine_name']); ?>
                        </h2>

                        <h5 class="text-muted">
                            <?= htmlspecialchars($medicine['generic_name']); ?>
                        </h5>

                        <p class="mb-1">
                            <strong>Brand:</strong>
                            <?= htmlspecialchars($medicine['brand_name']); ?>
                        </p>

                        <p class="mb-0">
                            <strong>Strength:</strong>
                            <?= htmlspecialchars($medicine['strength']); ?>
                            &nbsp; | &nbsp;

                            <strong>Form:</strong>
                            <?= htmlspecialchars($medicine['dosage_form']); ?>
                        </p>

                    </div>


                    <!-- Status -->
                    <div class="col-md-3 text-right">

                        <span class="badge badge-success"
                              style="
                              font-size:14px;
                              padding:8px 15px;
                              ">
                            <i class="fa fa-check-circle"></i>
                            <?= htmlspecialchars($medicine['status']); ?>
                        </span>

                        <br><br>

                        <span class="badge badge-info"
                              style="
                              font-size:13px;
                              padding:7px 12px;
                              ">
                            <?= htmlspecialchars($medicine['category']); ?>
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <!-- Medicine Information -->
        <div class="row">

            <div class="col-md-8">

                <div class="card">

                    <div class="card-header">

                        <h4 class="card-title">
                            <i class="fa fa-info-circle"></i>
                            Medicine Information
                        </h4>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <small class="text-muted">
                                    Medicine Name
                                </small>

                                <h5>
                                    <?= htmlspecialchars($medicine['medicine_name']); ?>
                                </h5>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted">
                                    Generic Name
                                </small>

                                <h5>
                                    <?= htmlspecialchars($medicine['generic_name']); ?>
                                </h5>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted">
                                    Brand / Manufacturer
                                </small>

                                <h5>
                                    <?= htmlspecialchars($medicine['brand_name']); ?>
                                </h5>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted">
                                    Category
                                </small>

                                <h5>
                                    <span class="badge badge-info">
                                        <?= htmlspecialchars($medicine['category']); ?>
                                    </span>
                                </h5>

                            </div>


                            <div class="col-md-4 mb-3">

                                <small class="text-muted">
                                    Dosage Form
                                </small>

                                <h5>
                                    <?= htmlspecialchars($medicine['dosage_form']); ?>
                                </h5>

                            </div>


                            <div class="col-md-4 mb-3">

                                <small class="text-muted">
                                    Strength
                                </small>

                                <h5>
                                    <?= htmlspecialchars($medicine['strength']); ?>
                                </h5>

                            </div>


                            <div class="col-md-4 mb-3">

                                <small class="text-muted">
                                    Unit
                                </small>

                                <h5>
                                    <?= htmlspecialchars($medicine['unit']); ?>
                                </h5>

                            </div>


                            <div class="col-md-12">

                                <small class="text-muted">
                                    Description
                                </small>

                                <p class="mt-2">
                                    <?= htmlspecialchars($medicine['description']); ?>
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Quick Actions -->
            <div class="col-md-4">

                <div class="card">

                    <div class="card-header">

                        <h4 class="card-title">
                            Quick Actions
                        </h4>

                    </div>

                    <div class="card-body">

                        <a href="edit_medicine.php?id=<?= $medicine_id; ?>"
                           class="btn btn-primary btn-block">

                            <i class="fa fa-pencil"></i>
                            Edit Medicine

                        </a>


                        <a href="medicine_availability.php"
                           class="btn btn-info btn-block">

                            <i class="fa fa-search"></i>
                            Check Availability

                        </a>


                        <a href="new_sale.php"
                           class="btn btn-success btn-block">

                            <i class="fa fa-shopping-cart"></i>
                            Create New Sale

                        </a>


                        <a href="medicines.php"
                           class="btn btn-secondary btn-block">

                            <i class="fa fa-list"></i>
                            All Medicines

                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- Branch Availability -->
        <div class="card">

            <div class="card-header">

                <div class="row align-items-center">

                    <div class="col-md-7">

                        <h4 class="card-title mb-0">

                            <i class="fa fa-hospital-o"></i>
                            Branch Availability

                        </h4>

                    </div>

                    <div class="col-md-5 text-right">

                        <a href="medicine_availability.php"
                           class="btn btn-outline-info btn-sm">

                            <i class="fa fa-search"></i>
                            Search Other Medicines

                        </a>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped custom-table">

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Pharmacy Branch</th>
                                <th>Location</th>
                                <th>Available Quantity</th>
                                <th>Status</th>
                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($branch_availability as $index => $branch): ?>

                                <tr>

                                    <td>
                                        <?= $index + 1; ?>
                                    </td>


                                    <td>

                                        <strong>
                                            <?= htmlspecialchars($branch['branch_name']); ?>
                                        </strong>

                                    </td>


                                    <td>

                                        <i class="fa fa-map-marker text-muted"></i>

                                        <?= htmlspecialchars($branch['location']); ?>

                                    </td>


                                    <td>

                                        <?php if ($branch['quantity'] > 0): ?>

                                            <strong>
                                                <?= $branch['quantity']; ?>
                                            </strong>

                                            <?= htmlspecialchars($medicine['unit']); ?>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                0
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <?php if ($branch['status'] == "Available"): ?>

                                            <span class="badge badge-success">

                                                <i class="fa fa-check"></i>
                                                Available

                                            </span>

                                        <?php else: ?>

                                            <span class="badge badge-danger">

                                                <i class="fa fa-times"></i>
                                                Not Available

                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- Availability Highlight -->
        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-1 text-center">

                        <i class="fa fa-lightbulb-o"
                           style="
                           font-size:40px;
                           color:#f5a623;
                           ">
                        </i>

                    </div>

                    <div class="col-md-8">

                        <h5>
                            Branch-wise Medicine Tracking
                        </h5>

                        <p class="text-muted mb-0">
                            This feature allows pharmacy staff to quickly
                            identify which SHIFA branch has this medicine
                            available and how much is currently available.
                        </p>

                    </div>

                    <div class="col-md-3 text-right">

                        <a href="medicine_availability.php"
                           class="btn btn-primary">

                            <i class="fa fa-search"></i>
                            Find Branch

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