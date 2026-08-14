<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

$id = $_GET['id'] ?? 0;

/* Demo Sale */
$sale = [
    "id" => 1001,
    "invoice" => "PH-SALE-1001",
    "branch" => "SHIFA Main Pharmacy",
    "medicine" => "Napa",
    "generic" => "Paracetamol",
    "strength" => "500mg",
    "quantity" => 5,
    "unit_price" => 10,
    "discount" => 0,
    "total" => 50,
    "payment_method" => "Cash",
    "payment_status" => "Paid",
    "customer" => "Walk-in Customer",
    "phone" => "01700000000",
    "date" => "14 August 2026, 10:30 AM"
];
?>

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-8">
                <h4 class="page-title">Sale Details</h4>
            </div>

            <div class="col-sm-4 text-right">
                <a href="sales.php" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>

                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
        </div>


        <!-- Invoice -->
        <div class="card" id="invoice">

            <div class="card-body">

                <!-- Invoice Header -->
                <div class="row mb-4">

                    <div class="col-md-6">
                        <h3 class="text-primary">
                            SHIFA
                        </h3>

                        <p class="text-muted">
                            Hospital Pharmacy
                        </p>
                    </div>

                    <div class="col-md-6 text-right">
                        <h4>
                            Pharmacy Invoice
                        </h4>

                        <p>
                            <strong>Invoice:</strong>
                            <?= $sale["invoice"]; ?>
                        </p>

                        <p>
                            <strong>Date:</strong>
                            <?= $sale["date"]; ?>
                        </p>
                    </div>

                </div>


                <hr>


                <!-- Customer & Branch -->
                <div class="row mb-4">

                    <div class="col-md-6">
                        <h5>Customer Information</h5>

                        <p class="mb-1">
                            <strong>Name:</strong>
                            <?= $sale["customer"]; ?>
                        </p>

                        <p>
                            <strong>Phone:</strong>
                            <?= $sale["phone"]; ?>
                        </p>
                    </div>


                    <div class="col-md-6">
                        <h5>Pharmacy Information</h5>

                        <p class="mb-1">
                            <strong>Branch:</strong>
                            <?= $sale["branch"]; ?>
                        </p>

                        <p>
                            <strong>Payment:</strong>

                            <?php if($sale["payment_status"] == "Paid"): ?>

                                <span class="badge badge-success">
                                    Paid
                                </span>

                            <?php else: ?>

                                <span class="badge badge-warning">
                                    Pending
                                </span>

                            <?php endif; ?>

                        </p>
                    </div>

                </div>


                <!-- Medicine -->
                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Strength</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>

                                <td>
                                    <strong>
                                        <?= $sale["medicine"]; ?>
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        <?= $sale["generic"]; ?>
                                    </small>
                                </td>

                                <td>
                                    <?= $sale["strength"]; ?>
                                </td>

                                <td>
                                    <?= $sale["quantity"]; ?>
                                </td>

                                <td>
                                    ৳<?= number_format(
                                        $sale["unit_price"], 2
                                    ); ?>
                                </td>

                                <td>
                                    ৳<?= number_format(
                                        $sale["quantity"] *
                                        $sale["unit_price"], 2
                                    ); ?>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- Amount -->
                <div class="row">

                    <div class="col-md-7"></div>

                    <div class="col-md-5">

                        <table class="table">

                            <tr>
                                <th>Subtotal</th>
                                <td class="text-right">
                                    ৳<?= number_format(
                                        $sale["quantity"] *
                                        $sale["unit_price"], 2
                                    ); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Discount</th>
                                <td class="text-right">
                                    ৳<?= number_format(
                                        $sale["discount"], 2
                                    ); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <h4>Grand Total</h4>
                                </th>

                                <td class="text-right">
                                    <h4 class="text-primary">
                                        ৳<?= number_format(
                                            $sale["total"], 2
                                        ); ?>
                                    </h4>
                                </td>
                            </tr>

                        </table>

                    </div>

                </div>


                <!-- Payment -->
                <div class="alert alert-info">

                    <strong>
                        Payment Method:
                    </strong>

                    <?= $sale["payment_method"]; ?>

                </div>


                <div class="text-center mt-4">

                    <p class="text-muted">
                        Thank you for choosing SHIFA Pharmacy.
                    </p>

                </div>

            </div>

        </div>

    </div>


    <?php require_once "../component/footer.php"; ?>

</div>


<style>
@media print {

    .header,
    .sidebar,
    .page-title,
    .btn,
    footer {
        display: none !important;
    }

    .page-wrapper {
        margin: 0 !important;
    }

    .card {
        border: none !important;
    }

}
</style>