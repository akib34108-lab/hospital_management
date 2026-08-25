<?php
require_once "../component/header.php";
require_once "../component/sidebar.php";

$sale_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($sale_id <= 0) {
    echo "<script>window.location='sales.php';</script>";
    exit;
}

$sale_sql = "
    SELECT
        ps.sale_id,
        ps.invoice_no,
        ps.branch_id,
        ps.customer_name,
        ps.customer_phone,
        ps.sale_date,
        ps.total_amount,
        ps.payment_method,
        ps.status,
        pb.branch_name
    FROM pharmacy_sales ps
    INNER JOIN pharmacy_branches pb
        ON ps.branch_id = pb.branch_id
    WHERE ps.sale_id = '$sale_id'
    AND ps.deleted_at IS NULL
    LIMIT 1
";

$sale_result = $crud->common_query($sale_sql);

if (!isset($sale_result['status']) || $sale_result['status'] !== true) {
    echo "<script>
        alert('Sale not found.');
        window.location='sales.php';
    </script>";
    exit;
}

$sale = $sale_result['data'][0];

$item_sql = "
    SELECT
        psi.sale_item_id,
        psi.medicine_id,
        psi.quantity,
        psi.unit_price,
        psi.subtotal,
        m.medicine_name,
        m.generic_name
    FROM pharmacy_sale_items psi
    INNER JOIN medicines m
        ON psi.medicine_id = m.medicine_id
    WHERE psi.sale_id = '$sale_id'
    AND psi.deleted_at IS NULL
    ORDER BY psi.sale_item_id ASC
";

$items = $crud->common_query($item_sql);
?>

<style>
.sale-view-page{padding-bottom:40px}
.invoice-box{background:#fff;border:1px solid #ddd;padding:30px;border-radius:6px}
.invoice-header{border-bottom:2px solid #ddd;padding-bottom:20px;margin-bottom:25px}
.invoice-title{font-size:24px;font-weight:600}
.invoice-number{font-size:18px;font-weight:600}
.info-label{font-weight:600;margin-bottom:5px}
.info-value{color:#555}
.total-row{font-size:18px;font-weight:600}
.status-badge{padding:6px 12px;border-radius:4px;font-size:13px}
.status-completed{background:#e6f7ed;color:#198754}
.status-pending{background:#fff3cd;color:#856404}
.status-cancelled{background:#f8d7da;color:#842029}

@media print{
    .sidebar,.page-header,.no-print,footer{display:none!important}
    .page-wrapper{margin-left:0!important}
    .content{padding:0!important}
    .invoice-box{border:none}
}
</style>

<div class="page-wrapper sale-view-page">
    <div class="content">

        <div class="page-header no-print">
            <div class="page-title">
                <h4>Sale Details</h4>
                <h6>View pharmacy sale information</h6>
            </div>

            <div class="page-btn">
                <a href="sales.php" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i>
                    Back to Sales
                </a>
            </div>
        </div>

        <div class="invoice-box">

            <div class="invoice-header">
                <div class="row">

                    <div class="col-md-6">
                        <div class="invoice-title">
                            SHIFA Pharmacy
                        </div>
                        <p class="mb-0">
                            Hospital Pharmacy Management
                        </p>
                    </div>

                    <div class="col-md-6 text-md-right">
                        <div class="invoice-number">
                            Invoice No:
                            <?php echo htmlspecialchars($sale->invoice_no); ?>
                        </div>

                        <p class="mb-0">
                            Date:
                            <?php
                            echo date(
                                "d M Y, h:i A",
                                strtotime($sale->sale_date)
                            );
                            ?>
                        </p>
                    </div>

                </div>
            </div>

            <div class="row mb-4">

                <div class="col-md-4 mb-3">
                    <div class="info-label">Branch</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($sale->branch_name); ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-label">Customer Name</div>
                    <div class="info-value">
                        <?php
                        echo !empty($sale->customer_name)
                            ? htmlspecialchars($sale->customer_name)
                            : "Walk-in Customer";
                        ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-label">Customer Phone</div>
                    <div class="info-value">
                        <?php
                        echo !empty($sale->customer_phone)
                            ? htmlspecialchars($sale->customer_phone)
                            : "N/A";
                        ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-label">Payment Method</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($sale->payment_method); ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-label">Status</div>

                    <div>
                        <?php
                        $status_class = "status-completed";

                        if ($sale->status === "Pending") {
                            $status_class = "status-pending";
                        } elseif ($sale->status === "Cancelled") {
                            $status_class = "status-cancelled";
                        }
                        ?>

                        <span class="status-badge <?php echo $status_class; ?>">
                            <?php echo htmlspecialchars($sale->status); ?>
                        </span>
                    </div>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Medicine</th>
                            <th>Generic Name</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $counter = 1;

                        if (
                            isset($items['status']) &&
                            $items['status'] === true
                        ) {

                            foreach ($items['data'] as $item) {
                        ?>

                            <tr>
                                <td>
                                    <?php echo $counter++; ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $item->medicine_name
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo !empty($item->generic_name)
                                        ? htmlspecialchars($item->generic_name)
                                        : "N/A";
                                    ?>
                                </td>

                                <td>
                                    ৳
                                    <?php
                                    echo number_format(
                                        $item->unit_price,
                                        2
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php echo (int)$item->quantity; ?>
                                </td>

                                <td>
                                    ৳
                                    <?php
                                    echo number_format(
                                        $item->subtotal,
                                        2
                                    );
                                    ?>
                                </td>
                            </tr>

                        <?php
                            }

                        } else {
                        ?>

                            <tr>
                                <td colspan="6" class="text-center">
                                    No medicine items found.
                                </td>
                            </tr>

                        <?php
                        }
                        ?>

                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">
                                Total Amount
                            </th>

                            <th>
                                ৳
                                <?php
                                echo number_format(
                                    $sale->total_amount,
                                    2
                                );
                                ?>
                            </th>
                        </tr>
                    </tfoot>

                </table>

            </div>

            <div class="text-right mt-4 no-print">

                <button
                    type="button"
                    onclick="window.print()"
                    class="btn btn-primary"
                >
                    <i class="fa fa-print"></i>
                    Print Invoice
                </button>

                <a href="sales.php" class="btn btn-secondary">
                    Back
                </a>

            </div>

        </div>

    </div>
</div>

<?php
require_once "../component/footer.php";
?>