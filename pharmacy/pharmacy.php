<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Pharmacy</h4>
            </div>

            <div class="col-sm-5 col-6 text-right">
                <a href="new_sale.php" class="btn btn-primary btn-rounded">
                    <i class="fa fa-plus"></i> New Sale
                </a>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card"
                     style="
                     border-radius: 10px;
                     background: linear-gradient(135deg, #009efb, #00c6ff);
                     color: white;
                     ">

                    <div class="card-body">
                        <div class="row align-items-center">

                            <div class="col-md-8">

                                <h3 style="color:white; margin-bottom:10px;">
                                    <i class="fa fa-medkit"></i>
                                    Pharmacy Management
                                </h3>

                                <p style="font-size:15px; margin-bottom:0;">
                                    Manage medicines, pharmacy branches,
                                    availability and sales from one place.
                                </p>

                            </div>

                            <div class="col-md-4 text-right">

                                <a href="medicine_availability.php"
                                   class="btn btn-light">
                                    <i class="fa fa-search"></i>
                                    Find Medicine
                                </a>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Statistics -->
        <div class="row">

            <!-- Total Medicines -->
            <div class="col-md-3 col-sm-6">
                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-info">
                            <i class="fa fa-medkit"></i>
                        </span>

                        <div class="dash-widget-info">
                            <h3>0</h3>
                            <span>Total Medicines</span>
                        </div>

                    </div>

                </div>
            </div>


            <!-- Branches -->
            <div class="col-md-3 col-sm-6">
                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-success">
                            <i class="fa fa-hospital-o"></i>
                        </span>

                        <div class="dash-widget-info">
                            <h3>0</h3>
                            <span>Pharmacy Branches</span>
                        </div>

                    </div>

                </div>
            </div>


            <!-- Available -->
            <div class="col-md-3 col-sm-6">
                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-primary">
                            <i class="fa fa-check-circle"></i>
                        </span>

                        <div class="dash-widget-info">
                            <h3>0</h3>
                            <span>Available Medicines</span>
                        </div>

                    </div>

                </div>
            </div>


            <!-- Today's Sales -->
            <div class="col-md-3 col-sm-6">
                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-warning">
                            <i class="fa fa-money"></i>
                        </span>

                        <div class="dash-widget-info">
                            <h3>৳0.00</h3>
                            <span>Today's Sales</span>
                        </div>

                    </div>

                </div>
            </div>

        </div>


        <!-- Quick Actions -->
        <div class="row">

            <div class="col-md-12">

                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-bolt"></i>
                            Quick Actions
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Add Medicine -->
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="add_medicine.php"
                                   class="btn btn-outline-primary btn-block"
                                   style="padding:15px;">

                                    <i class="fa fa-plus-circle"></i>
                                    <br>
                                    <span>Add Medicine</span>

                                </a>
                            </div>


                            <!-- New Sale -->
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="new_sale.php"
                                   class="btn btn-outline-success btn-block"
                                   style="padding:15px;">

                                    <i class="fa fa-shopping-cart"></i>
                                    <br>
                                    <span>New Sale</span>

                                </a>
                            </div>


                            <!-- Availability -->
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="medicine_availability.php"
                                   class="btn btn-outline-info btn-block"
                                   style="padding:15px;">

                                    <i class="fa fa-search"></i>
                                    <br>
                                    <span>Find Medicine</span>

                                </a>
                            </div>


                            <!-- Reports -->
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="reports.php"
                                   class="btn btn-outline-warning btn-block"
                                   style="padding:15px;">

                                    <i class="fa fa-bar-chart"></i>
                                    <br>
                                    <span>View Reports</span>

                                </a>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Pharmacy Management -->
        <div class="row">

            <!-- Medicine Management -->
            <div class="col-md-4">

                <div class="card">
                    <div class="card-body text-center">

                        <div style="
                            width:60px;
                            height:60px;
                            margin:0 auto 15px;
                            border-radius:50%;
                            background:#e8f7ff;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            ">

                            <i class="fa fa-medkit"
                               style="font-size:28px;color:#009efb;">
                            </i>

                        </div>

                        <h4>Medicine Management</h4>

                        <p class="text-muted">
                            Add, edit and view medicine information.
                        </p>

                        <a href="medicines.php"
                           class="btn btn-primary btn-sm">
                            Manage Medicines
                        </a>

                    </div>
                </div>

            </div>


            <!-- Branch Management -->
            <div class="col-md-4">

                <div class="card">
                    <div class="card-body text-center">

                        <div style="
                            width:60px;
                            height:60px;
                            margin:0 auto 15px;
                            border-radius:50%;
                            background:#e9fff3;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            ">

                            <i class="fa fa-hospital-o"
                               style="font-size:28px;color:#28a745;">
                            </i>

                        </div>

                        <h4>Branch Management</h4>

                        <p class="text-muted">
                            Manage all pharmacy branches and locations.
                        </p>

                        <a href="branches.php"
                           class="btn btn-success btn-sm">
                            Manage Branches
                        </a>

                    </div>
                </div>

            </div>


            <!-- Availability -->
            <div class="col-md-4">

                <div class="card">
                    <div class="card-body text-center">

                        <div style="
                            width:60px;
                            height:60px;
                            margin:0 auto 15px;
                            border-radius:50%;
                            background:#fff8e6;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            ">

                            <i class="fa fa-search"
                               style="font-size:28px;color:#f5a623;">
                            </i>

                        </div>

                        <h4>Medicine Availability</h4>

                        <p class="text-muted">
                            Find which branch has a particular medicine.
                        </p>

                        <a href="medicine_availability.php"
                           class="btn btn-warning btn-sm">
                            Check Availability
                        </a>

                    </div>
                </div>

            </div>

        </div>


        <!-- Sales Overview -->
        <div class="row">

            <div class="col-md-8">

                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-shopping-cart"></i>
                            Recent Sales
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-striped custom-table mb-0">

                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Customer</th>
                                        <th>Branch</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <td colspan="5"
                                            class="text-center text-muted"
                                            style="padding:25px;">

                                            No sales data available yet.

                                        </td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Pharmacy Information -->
            <div class="col-md-4">

                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">
                            Pharmacy Overview
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <div class="d-flex justify-content-between">
                                <span>Medicines</span>
                                <strong>0</strong>
                            </div>

                            <div class="progress"
                                 style="height:6px;">
                                <div class="progress-bar"
                                     style="width:0%;">
                                </div>
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="d-flex justify-content-between">
                                <span>Branches</span>
                                <strong>0</strong>
                            </div>

                            <div class="progress"
                                 style="height:6px;">
                                <div class="progress-bar bg-success"
                                     style="width:0%;">
                                </div>
                            </div>

                        </div>


                        <div>

                            <div class="d-flex justify-content-between">
                                <span>Today's Sales</span>
                                <strong>৳0</strong>
                            </div>

                            <div class="progress"
                                 style="height:6px;">
                                <div class="progress-bar bg-warning"
                                     style="width:0%;">
                                </div>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- Find Medicine -->
                <div class="card">

                    <div class="card-body text-center">

                        <i class="fa fa-search"
                           style="
                           font-size:35px;
                           color:#009efb;
                           margin-bottom:10px;
                           ">
                        </i>

                        <h4>Looking for a Medicine?</h4>

                        <p class="text-muted">
                            Quickly check which pharmacy branch
                            has the medicine available.
                        </p>

                        <a href="medicine_availability.php"
                           class="btn btn-primary btn-block">

                            <i class="fa fa-search"></i>
                            Search Medicine

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