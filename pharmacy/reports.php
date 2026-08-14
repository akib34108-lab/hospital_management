<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

/*
|--------------------------------------------------------------------------
| Demo Report Data
|--------------------------------------------------------------------------
*/

$branches = [
    [
        "name" => "SHIFA Main Pharmacy",
        "sales" => 120,
        "revenue" => 18500
    ],
    [
        "name" => "SHIFA Chattogram Pharmacy",
        "sales" => 85,
        "revenue" => 12400
    ],
    [
        "name" => "SHIFA Agrabad Pharmacy",
        "sales" => 65,
        "revenue" => 9100
    ]
];


$medicines = [
    [
        "name" => "Napa",
        "sold" => 150,
        "revenue" => 1500
    ],
    [
        "name" => "Seclo",
        "sold" => 95,
        "revenue" => 5700
    ],
    [
        "name" => "Napa Extra",
        "sold" => 70,
        "revenue" => 1050
    ],
    [
        "name" => "Fexo",
        "sold" => 45,
        "revenue" => 2250
    ]
];


$total_sales = 270;
$total_revenue = 40000;
$paid_amount = 35000;
$pending_amount = 5000;
?>

<div class="page-wrapper">

    <div class="content">

        <!-- Page Header -->
        <div class="row align-items-center mb-3">

            <div class="col-md-7">

                <h4 class="page-title mb-1">
                    Pharmacy Reports
                </h4>

                <p class="text-muted mb-0">
                    Analyze pharmacy sales and performance
                </p>

            </div>

            <div class="col-md-5 text-right">

                <button onclick="window.print()"
                        class="btn btn-primary">

                    <i class="fa fa-print"></i>
                    Print Report

                </button>

            </div>

        </div>


        <!-- Filter -->
        <div class="card">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label>
                            Report Period
                        </label>

                        <select class="form-control">

                            <option>
                                Today
                            </option>

                            <option selected>
                                This Month
                            </option>

                            <option>
                                Last Month
                            </option>

                            <option>
                                This Year
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label>
                            Pharmacy Branch
                        </label>

                        <select class="form-control">

                            <option>
                                All Branches
                            </option>

                            <?php foreach ($branches as $branch): ?>

                                <option>
                                    <?= $branch["name"]; ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label>
                            Report Type
                        </label>

                        <select class="form-control">

                            <option>
                                Sales Report
                            </option>

                            <option>
                                Medicine Report
                            </option>

                            <option>
                                Branch Report
                            </option>

                            <option>
                                Payment Report
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        <!-- Summary Cards -->
        <div class="row">

            <!-- Sales -->
            <div class="col-md-3 col-sm-6">

                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-info">

                            <i class="fa fa-shopping-cart"></i>

                        </span>

                        <div class="dash-widget-info">

                            <h3>
                                <?= $total_sales; ?>
                            </h3>

                            <span>
                                Total Sales
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Revenue -->
            <div class="col-md-3 col-sm-6">

                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-success">

                            <i class="fa fa-money"></i>

                        </span>

                        <div class="dash-widget-info">

                            <h3>
                                ৳<?= number_format(
                                    $total_revenue,
                                    2
                                ); ?>
                            </h3>

                            <span>
                                Total Revenue
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Paid -->
            <div class="col-md-3 col-sm-6">

                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-primary">

                            <i class="fa fa-check-circle"></i>

                        </span>

                        <div class="dash-widget-info">

                            <h3>
                                ৳<?= number_format(
                                    $paid_amount,
                                    2
                                ); ?>
                            </h3>

                            <span>
                                Paid Amount
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Pending -->
            <div class="col-md-3 col-sm-6">

                <div class="card dash-widget">

                    <div class="card-body">

                        <span class="dash-widget-icon bg-warning">

                            <i class="fa fa-clock-o"></i>

                        </span>

                        <div class="dash-widget-info">

                            <h3>
                                ৳<?= number_format(
                                    $pending_amount,
                                    2
                                ); ?>
                            </h3>

                            <span>
                                Pending Amount
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Branch Performance -->
        <div class="row">

            <div class="col-md-7">

                <div class="card">

                    <div class="card-header">

                        <h4 class="card-title">

                            <i class="fa fa-hospital-o text-primary"></i>

                            Branch Performance

                        </h4>

                    </div>


                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-striped">

                                <thead>

                                    <tr>

                                        <th>
                                            Branch
                                        </th>

                                        <th>
                                            Sales
                                        </th>

                                        <th>
                                            Revenue
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php foreach (
                                        $branches as $branch
                                    ): ?>

                                        <tr>

                                            <td>

                                                <i class="fa fa-hospital-o
                                                          text-muted">
                                                </i>

                                                <?= $branch["name"]; ?>

                                            </td>


                                            <td>

                                                <span class="badge badge-info">

                                                    <?= $branch["sales"]; ?>

                                                </span>

                                            </td>


                                            <td>

                                                <strong>

                                                    ৳<?= number_format(
                                                        $branch["revenue"],
                                                        2
                                                    ); ?>

                                                </strong>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Payment Summary -->
            <div class="col-md-5">

                <div class="card">

                    <div class="card-header">

                        <h4 class="card-title">

                            <i class="fa fa-credit-card text-primary"></i>

                            Payment Summary

                        </h4>

                    </div>


                    <div class="card-body">

                        <div class="row text-center">

                            <div class="col-6">

                                <div style="
                                    padding:20px;
                                    border-radius:8px;
                                    background:#eaf8ef;
                                ">

                                    <i class="fa fa-check-circle
                                              text-success"
                                       style="font-size:30px;">
                                    </i>

                                    <h4 class="mt-2">

                                        ৳<?= number_format(
                                            $paid_amount,
                                            2
                                        ); ?>

                                    </h4>

                                    <span class="text-muted">
                                        Paid
                                    </span>

                                </div>

                            </div>


                            <div class="col-6">

                                <div style="
                                    padding:20px;
                                    border-radius:8px;
                                    background:#fff7e6;
                                ">

                                    <i class="fa fa-clock-o
                                              text-warning"
                                       style="font-size:30px;">
                                    </i>

                                    <h4 class="mt-2">

                                        ৳<?= number_format(
                                            $pending_amount,
                                            2
                                        ); ?>

                                    </h4>

                                    <span class="text-muted">
                                        Pending
                                    </span>

                                </div>

                            </div>

                        </div>


                        <hr>


                        <div class="text-center">

                            <span class="text-muted">
                                Payment Collection Rate
                            </span>

                            <h3 class="text-success">

                                <?= round(
                                    ($paid_amount /
                                    $total_revenue) * 100
                                ); ?>%

                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Medicine Performance -->
        <div class="card">

            <div class="card-header">

                <div class="row">

                    <div class="col-md-8">

                        <h4 class="card-title">

                            <i class="fa fa-medkit text-primary"></i>

                            Medicine Sales Performance

                        </h4>

                    </div>

                    <div class="col-md-4 text-right">

                        <span class="badge badge-primary">

                            Best Selling Medicines

                        </span>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped">

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Medicine
                                </th>

                                <th>
                                    Units Sold
                                </th>

                                <th>
                                    Revenue
                                </th>

                                <th>
                                    Performance
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php
                            $rank = 1;

                            foreach (
                                $medicines as $medicine
                            ):
                            ?>

                                <tr>

                                    <td>

                                        <?php if ($rank == 1): ?>

                                            <span class="badge badge-warning">
                                                #1
                                            </span>

                                        <?php elseif ($rank == 2): ?>

                                            <span class="badge badge-info">
                                                #2
                                            </span>

                                        <?php else: ?>

                                            #<?= $rank; ?>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <i class="fa fa-medkit
                                                  text-muted">
                                        </i>

                                        <strong>
                                            <?= $medicine["name"]; ?>
                                        </strong>

                                    </td>


                                    <td>

                                        <?= $medicine["sold"]; ?>

                                        units

                                    </td>


                                    <td>

                                        <strong>

                                            ৳<?= number_format(
                                                $medicine["revenue"],
                                                2
                                            ); ?>

                                        </strong>

                                    </td>


                                    <td style="width:30%;">

                                        <div class="progress"
                                             style="height:8px;">

                                            <div class="progress-bar"
                                                 style="
                                                 width:
                                                 <?= min(
                                                     $medicine["sold"] /
                                                     150 * 100,
                                                     100
                                                 ); ?>%;
                                                 ">
                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            <?php

                            $rank++;

                            endforeach;

                            ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- Report Insight -->
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


                    <div class="col-md-11">

                        <h5>
                            Pharmacy Performance Insight
                        </h5>

                        <p class="text-muted mb-0">

                            The report shows branch performance,
                            revenue collection and best-selling
                            medicines. This information can help
                            pharmacy management make better decisions.

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


<style>

@media print {

    .header,
    .sidebar,
    .btn,
    footer {
        display: none !important;
    }

    .page-wrapper {
        margin: 0 !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

}

</style>