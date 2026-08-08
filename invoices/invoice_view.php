<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>
<?php $conn = $crud->conn; ?>

<?php 
if(!isset($_GET['id'])){
    echo "<script>alert('Invalid Invoice'); window.location='invoice_list.php';</script>";
    exit;
}
$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT i.*, p.name as patient_name, p.phone, p.address, p.age, p.gender FROM invoices i 
        LEFT JOIN patients p ON i.patient_id = p.id 
        WHERE i.id='$id'";
$invoice_info = $conn->query($sql)->fetch_object();
if(!$invoice_info){ echo "<script>alert('Invoice Not Found'); window.location='invoice_list.php';</script>"; exit; }

$phone = isset($invoice_info->phone) ? $invoice_info->phone : 'N/A';
$address = isset($invoice_info->address) ? $invoice_info->address : 'N/A';
$age = isset($invoice_info->age) ? $invoice_info->age : 'N/A';
$gender = ($invoice_info->gender == 1) ? 'Male' : 'Female';
$details_data = $conn->query("SELECT * FROM invoice_details WHERE invoice_id='$id'");
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row no-print"> 
            <div class="col-sm-8 col-7">
                <h4 class="page-title">Invoice View</h4>
            </div>
            <div class="col-sm-4 col-5 text-right m-b-20">
                <a href="invoice_list.php" class="btn btn-primary btn-rounded"><i class="fa fa-arrow-left"></i> Back</a>
                <button onclick="printInvoice()" class="btn btn-success btn-rounded"><i class="fa fa-print"></i> Print</button> 
            </div>
        </div>

        
        <div class="row">
            <div class="col-md-12">
                <div class="card-box" id="printableArea"> 
                    <div class="row invoice-info">
                        <div class="col-md-6">
                            <h5 class="text-muted"><b>From:</b></h5>
                            <h4>SHIFA Hospital</h4>
                            <p>Chittagong, Bangladesh</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <h5 class="text-muted"><b>Invoice To:</b></h5>
                            <h4><?php echo $invoice_info->patient_name; ?></h4>
                            <p><b>Mobile:</b> <?php echo $phone; ?></p>
                            <p><b>Address:</b> <?php echo $address; ?></p>
                        </div>
                    </div>

                    <div class="row m-t-20">
                        <div class="col-md-6">
                            <p><b>Patient Age:</b> <?php echo $age; ?> | <b>Gender:</b> <?php echo $gender; ?></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><b>Invoice No:</b> INV-<?php echo $invoice_info->id; ?></p>
                            <p><b>Date:</b> <?php echo date('d-m-Y', strtotime($invoice_info->invoice_date)); ?></p>
                        </div>
                    </div>

                    <div class="table-responsive m-t-20">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item Name</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Discount</th>
                                    <th class="text-right">Tax %</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sl=1; 
                                while($d = $details_data->fetch_object()){
                                    $tax_tk = ($d->price * $d->tax) / 100;
                                    $item_total = ($d->price - $d->discount) + $tax_tk;
                                ?>
                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td><?php echo $d->Name; ?></td>
                                    <td class="text-right"><?php echo number_format($d->price, 2); ?></td>
                                    <td class="text-right"><?php echo number_format($d->discount, 2); ?></td>
                                    <td class="text-right"><?php echo $d->tax; ?>%</td>
                                    <td class="text-right"><b><?php echo number_format($item_total, 2); ?></b></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="5" class="text-right"><h4><b>Grand Total</b></h4></td>
                                    <td class="text-right"><h4><b><?php 
                                        $grand = ($invoice_info->sub_amount - $invoice_info->discount) + (($invoice_info->sub_amount * $invoice_info->tax)/100);
                                        echo number_format($grand, 2); 
                                    ?></b></h4></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<script>
function printInvoice() {
    var printContents = document.getElementById('printableArea').innerHTML;
    var originalContents = document.body.innerHTML;
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload(); 
}
</script>

<style>
@media print {
    .no-print { display: none !important; } 
    body { background: #fff; }
    .card-box { border: none; box-shadow: none; }
}
</style>

<?php require_once "../component/footer.php" ?>