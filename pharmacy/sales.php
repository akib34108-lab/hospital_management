<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

/*
|--------------------------------------------------------------------------
| Demo Sales Data
|--------------------------------------------------------------------------
| Database ready হলে এই অংশ SQL query দিয়ে replace করা হবে.
*/

$sales = [
    [
        "id" => 1001,
        "invoice" => "PH-SALE-1001",
        "branch" => "SHIFA Main Pharmacy",
        "medicine" => "Napa",
        "quantity" => 5,
        "amount" => 50,
        "payment" => "Paid",
        "date" => "2026-08-14 10:30 AM"
    ],
    [
        "id" => 1002,
        "invoice" => "PH-SALE-1002",
        "branch" => "SHIFA Chattogram Pharmacy",
        "medicine" => "Seclo",
        "quantity" => 3,
        "amount" => 180,
        "payment" => "Paid",
        "date" => "2026-08-14 11:15 AM"
    ],
    [
        "id" => 1003,
        "invoice" => "PH-SALE-1003",
        "branch" => "SHIFA Agrabad Pharmacy",
        "medicine" => "Napa Extra",
        "quantity" => 2,
        "amount" => 30,
        "payment" => "Pending",
        "date" => "2026-08-14 12:10 PM"
    ],
    [
        "id" => 1004,
        "invoice" => "PH-SALE-1004",
        "branch" => "SHIFA Main Pharmacy",
        "medicine" => "Seclo",
        "quantity" => 4,
        "amount" => 240,
        "payment" => "Paid",
        "date" => "2026-08-13 04:20 PM"
    ],
    [
        "id" => 1005,
        "invoice" => "PH-SALE-1005",
        "branch" => "SHIFA Chattogram Pharmacy",
        "medicine" => "Napa",
        "quantity" => 10,
        "amount" => 100,
        "payment" => "Pending",
        "date" => "2026-08-13 06:45 PM"
    ]
];


// Calculate summary
$total_sales = count($sales);

$total_revenue = 0;
$paid_amount = 0;
$pending_amount = 0;
$paid_sales = 0;
$pending_sales = 0;

foreach ($sales as $sale) {

    $total_revenue += $sale["amount"];

    if ($sale["payment"] == "Paid") {

        $paid_amount += $sale["amount"];
        $paid_sales++;

    } else {

        $pending_amount += $sale["amount"];
        $pending_sales++;

    }
}
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">

            <div class="col-sm-7 col-6">

                <h4 class="page-title">
                    Pharmacy Sales
                </h4>

            </div>

            <div class="col-sm-5 col-6 text-right">

                <a href="new_sale.php"
                   class="btn btn-primary btn-rounded">

                    <i class="fa fa-plus"></i>
                    New Sale

                </a>

            </div>

        </div>


        <!-- Summary Cards -->
        <div class="row">

            <!-- Total Sales -->
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
                                ৳<?= number_format($total_revenue, 2); ?>
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
                                ৳<?= number_format($paid_amount, 2); ?>
                            </h3>

                            <span>
                                Paid Sales
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
                                ৳<?= number_format($pending_amount, 2); ?>
                            </h3>

                            <span>
                                Pending Payment
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Sales Table -->
        <div class="card">

            <div class="card-header">

                <div class="row align-items-center">

                    <div class="col-md-6">

                        <h4 class="card-title mb-0">

                            <i class="fa fa-list"></i>
                            Sales History

                        </h4>

                    </div>

                    <div class="col-md-6 text-right">

                        <span class="badge badge-success"
                              style="padding:8px;">

                            Paid:
                            <?= $paid_sales; ?>

                        </span>

                        <span class="badge badge-warning"
                              style="padding:8px;">

                            Pending:
                            <?= $pending_sales; ?>

                        </span>

                    </div>

                </div>

            </div>


            <div class="card-body">


                <!-- Search & Filter -->
                <div class="row mb-3">

                    <div class="col-md-5">

                        <div class="input-group">

                            <input type="text"
                                   id="saleSearch"
                                   class="form-control"
                                   placeholder="Search invoice, branch or medicine...">

                            <div class="input-group-append">

                                <button class="btn btn-primary">

                                    <i class="fa fa-search"></i>

                                </button>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <select id="paymentFilter"
                                class="form-control">

                            <option value="">
                                All Payments
                            </option>

                            <option value="Paid">
                                Paid
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <select id="branchFilter"
                                class="form-control">

                            <option value="">
                                All Branches
                            </option>

                            <option value="SHIFA Main Pharmacy">
                                SHIFA Main Pharmacy
                            </option>

                            <option value="SHIFA Chattogram Pharmacy">
                                SHIFA Chattogram Pharmacy
                            </option>

                            <option value="SHIFA Agrabad Pharmacy">
                                SHIFA Agrabad Pharmacy
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Table -->
                <div class="table-responsive">

                    <table class="table table-striped custom-table"
                           id="salesTable">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>
                                    Invoice
                                </th>

                                <th>
                                    Branch
                                </th>

                                <th>
                                    Medicine
                                </th>

                                <th>
                                    Qty
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th>
                                    Date
                                </th>

                                <th class="text-right">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($sales as $sale): ?>

                                <tr>

                                    <td>
                                        <?= $sale["id"]; ?>
                                    </td>


                                    <td>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $sale["invoice"]
                                            ); ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <i class="fa fa-hospital-o text-muted"></i>

                                        <?= htmlspecialchars(
                                            $sale["branch"]
                                        ); ?>

                                    </td>


                                    <td>

                                        <i class="fa fa-medkit text-muted"></i>

                                        <?= htmlspecialchars(
                                            $sale["medicine"]
                                        ); ?>

                                    </td>


                                    <td>

                                        <strong>
                                            <?= $sale["quantity"]; ?>
                                        </strong>

                                    </td>


                                    <td>

                                        <strong>
                                            ৳<?= number_format(
                                                $sale["amount"],
                                                2
                                            ); ?>
                                        </strong>

                                    </td>


                                    <td>

                                        <?php if ($sale["payment"] == "Paid"): ?>

                                            <span class="badge badge-success">

                                                <i class="fa fa-check"></i>
                                                Paid

                                            </span>

                                        <?php else: ?>

                                            <span class="badge badge-warning">

                                                <i class="fa fa-clock-o"></i>
                                                Pending

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <small>

                                            <?= htmlspecialchars(
                                                $sale["date"]
                                            ); ?>

                                        </small>

                                    </td>


                                    <td class="text-right">

                                        <div class="dropdown dropdown-action">

                                            <a href="#"
                                               class="action-icon dropdown-toggle"
                                               data-toggle="dropdown">

                                                <i class="fa fa-ellipsis-v"></i>

                                            </a>


                                            <div class="dropdown-menu dropdown-menu-right">

                                                <a class="dropdown-item"
                                                   href="sale_view.php?id=<?= $sale["id"]; ?>">

                                                    <i class="fa fa-eye m-r-5"></i>
                                                    View Sale

                                                </a>


                                                <a class="dropdown-item"
                                                   href="sale_view.php?id=<?= $sale["id"]; ?>">

                                                    <i class="fa fa-print m-r-5"></i>
                                                    Print Invoice

                                                </a>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                            <tr id="noSaleMessage"
                                style="display:none;">

                                <td colspan="9"
                                    class="text-center text-muted"
                                    style="padding:30px;">

                                    <i class="fa fa-shopping-cart"
                                       style="font-size:30px;">
                                    </i>

                                    <br><br>

                                    No sales found.

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- Payment Summary -->
        <div class="card">

            <div class="card-header">

                <h4 class="card-title">
                    Payment Summary
                </h4>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <div class="alert alert-success">

                            <i class="fa fa-check-circle"></i>

                            <strong>
                                Paid Amount:
                            </strong>

                            ৳<?= number_format(
                                $paid_amount,
                                2
                            ); ?>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="alert alert-warning">

                            <i class="fa fa-clock-o"></i>

                            <strong>
                                Pending Amount:
                            </strong>

                            ৳<?= number_format(
                                $pending_amount,
                                2
                            ); ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Quick Action -->
        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-1 text-center">

                        <i class="fa fa-cart-plus"
                           style="
                           font-size:40px;
                           color:#009efb;
                           ">
                        </i>

                    </div>


                    <div class="col-md-8">

                        <h5>
                            Create a New Pharmacy Sale
                        </h5>

                        <p class="text-muted mb-0">

                            Select a medicine and pharmacy branch,
                            check availability and create a new sale.

                        </p>

                    </div>


                    <div class="col-md-3 text-right">

                        <a href="new_sale.php"
                           class="btn btn-primary">

                            <i class="fa fa-plus"></i>

                            New Sale

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


<script>

$(document).ready(function () {

    function filterSales() {

        var searchValue =
            $("#saleSearch").val().toLowerCase();

        var paymentValue =
            $("#paymentFilter").val().toLowerCase();

        var branchValue =
            $("#branchFilter").val().toLowerCase();

        var visibleRows = 0;


        $("#salesTable tbody tr").each(function () {

            if ($(this).attr("id") === "noSaleMessage") {
                return;
            }


            var rowText =
                $(this).text().toLowerCase();


            var paymentText =
                $(this)
                .find("td:eq(6)")
                .text()
                .trim()
                .toLowerCase();


            var branchText =
                $(this)
                .find("td:eq(2)")
                .text()
                .trim()
                .toLowerCase();


            var searchMatch =
                rowText.includes(searchValue);


            var paymentMatch =
                paymentValue === "" ||
                paymentText === paymentValue;


            var branchMatch =
                branchValue === "" ||
                branchText === branchValue;


            if (
                searchMatch &&
                paymentMatch &&
                branchMatch
            ) {

                $(this).show();

                visibleRows++;

            } else {

                $(this).hide();

            }

        });


        if (visibleRows === 0) {

            $("#noSaleMessage").show();

        } else {

            $("#noSaleMessage").hide();

        }

    }


    $("#saleSearch").on(
        "keyup",
        filterSales
    );


    $("#paymentFilter").on(
        "change",
        filterSales
    );


    $("#branchFilter").on(
        "change",
        filterSales
    );

});

</script>