<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>
<?php $conn = $crud->conn; ?>

<?php 

if(isset($_GET['delete'])){
    $del_id = intval($_GET['delete']);
    $conn->query("DELETE FROM payments WHERE id='$del_id'");
    echo "<script>window.location='payments_list.php';</script>";
}


$sql = "SELECT p.*, i.id as invoice_id, pt.name as patient_name 
        FROM payments p 
        LEFT JOIN invoices i ON p.invoice_id = i.id
        LEFT JOIN patients pt ON i.patient_id = pt.id
        ORDER BY p.payment_date DESC, p.id DESC";
$result = $conn->query($sql);
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row"> 
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Payments</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Patient</th>
                                    <th>Payment Type</th>
                                    <th>Paid Date</th>
                                    <th class="text-center">Paid Amount</th>
                                    <th>Trx ID</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if($result->num_rows > 0){
                                    while($row = $result->fetch_object()){ ?>
                                    <tr>
                                        <td>
                                            <a href="../invoices/invoice_view.php?id=<?php echo $row->invoice_id; ?>">
                                                #INV-<?php echo str_pad($row->invoice_id, 4, '0', STR_PAD_LEFT); ?>
                                            </a>
                                        </td>
                                        <td><?php echo $row->patient_name; ?></td>
                                        <td><?php echo $row->payment_method; ?></td>
                                        <td><?php echo date('d M Y', strtotime($row->payment_date)); ?></td>
                                        <td class="text-center"><b><?php echo number_format($row->amount, 2); ?></b></td>
                                        <td><?php echo $row->transaction_id; ?></td>
                                        <td class="text-center">
                                            <a href="../invoices/invoice_view.php?id=<?php echo $row->invoice_id; ?>" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <a href="payments_list.php?delete=<?php echo $row->id; ?>" 
                                               onclick="return confirm('Are you sure to delete this payment?')" 
                                               class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php } 
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No Payments Found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../component/footer.php" ?>