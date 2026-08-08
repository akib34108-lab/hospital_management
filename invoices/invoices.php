<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>
<?php $conn = $crud->conn; ?>

<?php 
$edit_id = isset($_GET['edit_id']) ? $conn->real_escape_string($_GET['edit_id']) : '';
$edit_data = null;
$edit_items = [];

if($edit_id != ''){
    
    $edit_data = $conn->query("SELECT * FROM invoices WHERE id='$edit_id'")->fetch_object();
    $edit_items_result = $conn->query("SELECT * FROM invoice_details WHERE invoice_id='$edit_id'");
    while($row = $edit_items_result->fetch_object()){ $edit_items[] = $row; }
}
?>

<div class="page-wrapper">
    <div class="content">
        
        <?php 
        
        if(isset($_POST['save'])){
            $patient_id = $conn->real_escape_string($_POST['patient_id']);
            $invoice_date = $conn->real_escape_string($_POST['invoice_date']);
            $status = 1;
            $invoice_id_post = $conn->real_escape_string($_POST['invoice_id']); 

            $sub_amount = 0;
            if(isset($_POST['item_total'])) foreach($_POST['item_total'] as $it) $sub_amount += $it;
            
            $discount = $conn->real_escape_string($_POST['discount']); 
            $tax = $conn->real_escape_string($_POST['tax']); // %

            if($invoice_id_post != ''){ 
                $sql = "UPDATE `invoices` SET `patient_id`='$patient_id',`sub_amount`='$sub_amount',`discount`='$discount',`tax`='$tax',`invoice_date`='$invoice_date' WHERE id='$invoice_id_post'";
                $conn->query($sql);
                $last_id = $invoice_id_post;
                
                $conn->query("DELETE FROM invoice_details WHERE invoice_id='$last_id'");
            } else { 
                $sql = "INSERT INTO `invoices`(`patient_id`, `sub_amount`, `discount`, `tax`, `invoice_date`, `status`) 
                        VALUES ('$patient_id','$sub_amount','$discount','$tax','$invoice_date','$status')";
                $conn->query($sql);
                $last_id = $conn->insert_id;
            }

            
            if(isset($_POST['item_name'])){
                for($i=0; $i<count($_POST['item_name']); $i++){
                    if(!empty($_POST['item_name'][$i])){
                        $name = $conn->real_escape_string($_POST['item_name'][$i]);
                        $price = $conn->real_escape_string($_POST['item_price'][$i]);
                        $dis = $conn->real_escape_string($_POST['item_discount'][$i]); // %
                        $item_tax = $conn->real_escape_string($_POST['item_tax'][$i]); // %
                        $conn->query("INSERT INTO `invoice_details` 
                        (`invoice_id`, `Name`, `price`, `discount`, `tax`) 
                        VALUES ('$last_id','$name','$price','$dis','$item_tax')");
                    }
                }
            }
            $msg = $invoice_id_post != '' ? 'Invoice Updated Successfully' : 'Invoice Saved Successfully';
            echo "<script>alert('$msg'); window.location='invoice_list.php';</script>";
            exit;
        }

        
        if(isset($_GET['delete_id'])){
            $del_id = $_GET['delete_id'];
            $conn->query("DELETE FROM invoice_details WHERE invoice_id='$del_id'");
            $conn->query("DELETE FROM invoices WHERE id='$del_id'");
            echo "<script>alert('Invoice Deleted'); window.location='invoices.php';</script>";
        }

        
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
            $inv = $conn->query("SELECT i.*, p.name as patient_name, p.address, p.phone 
                                 FROM invoices i 
                                 LEFT JOIN patients p ON i.patient_id = p.id 
                                 WHERE i.id='$id'")->fetch_object();
            $items = $conn->query("SELECT * FROM invoice_details WHERE invoice_id='$id'");
            
            $tax_tk = ($inv->sub_amount * $inv->tax) / 100;
            $grand_total = ($inv->sub_amount - $inv->discount) + $tax_tk;
        ?>
        
        
        <div class="row">
            <div class="col-sm-12 text-right m-b-20">
                <button onclick="window.print()" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
                <a href="invoices.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to List</a>
            </div>
        </div>

        <div class="row" id="print_area">
            <div class="col-md-12">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>SHIFA HOSPITAL</h3>
                            <p>Chittagong, Bangladesh<br>Phone: 01xxxxxxxxx</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <h4 class="text-blue">INVOICE</h4>
                            <p><b>Invoice No:</b> INV-<?php echo $inv->id; ?><br>
                               <b>Date:</b> <?php echo date('d-m-Y', strtotime($inv->invoice_date)); ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Bill To:</h5>
                            <p>
                                <b>Name:</b> <?php echo $inv->patient_name; ?><br>
                                <b>Phone:</b> <?php echo $inv->phone; ?><br>
                                <b>Address:</b> <?php echo $inv->address; ?>
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive m-t-20">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th><th>Item Name</th><th>Price</th><th>Disc %</th><th>Tax %</th><th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sl=1; while($row = $items->fetch_object()){ 
                                    $dis_tk = ($row->price * $row->discount) / 100;
                                    $tax_tk_item = ($row->price * $row->tax) / 100;
                                    $item_total = ($row->price - $dis_tk) + $tax_tk_item; 
                                ?>
                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td><?php echo $row->Name; ?></td>
                                    <td><?php echo number_format($row->price, 2); ?></td>
                                    <td><?php echo $row->discount; ?>%</td>
                                    <td><?php echo $row->tax; ?>%</td>
                                    <td class="text-right"><?php echo number_format($item_total, 2); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table">
                                <tr><td>Sub Amount:</td><td class="text-right"><?php echo number_format($inv->sub_amount, 2); ?></td></tr>
                                <tr><td>Discount:</td><td class="text-right"><?php echo number_format($inv->discount, 2); ?> TK</td></tr>
                                <tr><td>TAX (<?php echo $inv->tax; ?>%):</td><td class="text-right"><?php echo number_format($tax_tk, 2); ?> TK</td></tr>
                                <tr><td><h4>Grand Total:</h4></td><td class="text-right"><h4><?php echo number_format($grand_total, 2); ?> TK</h4></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php } else { ?>
        
        
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="card-title"><?php echo $edit_id ? 'Edit Invoice' : 'Add Invoice'; ?></h4> 
                    <form method="post" action="" id="invoiceForm">
                        <input type="hidden" name="invoice_id" value="<?php echo $edit_id; ?>"> 
                        
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Patient *</label>
                            <div class="col-md-4">
                                <select name="patient_id" class="form-control" required>
                                    <option value="">-- Select Patient --</option>
                                    <?php $patients = $crud->common_select("patients","*"); 
                                    if($patients['status']) foreach ($patients['data'] as $p){ 
                                        $selected = ($edit_data && $edit_data->patient_id == $p->id) ? 'selected' : ''; 
                                        echo "<option value='".$p->id."' $selected>".$p->name."</option>"; 
                                    } ?>
                                </select>
                            </div>
                            <label class="col-form-label col-md-2">Invoice Date *</label>
                            <div class="col-md-4">
                                <input type="date" name="invoice_date" value="<?php echo $edit_data ? $edit_data->invoice_date : date('Y-m-d');?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="card-box">
                            <h4 class="text-blue h4">Items</h4>
                            <button type="button" id="addItem" class="btn btn-primary mb-3">+ Add Item</button>
                            <div class="table-responsive">
                            <table class="table table-bordered" id="itemTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Price</th>
                                        <th>Discount %</th>
                                        <th>Tax %</th>
                                        <th>Total</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($edit_items)){ 
                                        foreach($edit_items as $item){ ?>
                                        <tr>
                                            <td><input type="text" name="item_name[]" value="<?php echo $item->Name; ?>" class="form-control"></td>
                                            <td><input type="number" name="item_price[]" value="<?php echo $item->price; ?>" class="form-control calc item_price"></td>
                                            <td><input type="number" name="item_discount[]" value="<?php echo $item->discount; ?>" class="form-control calc item_discount"></td>
                                            <td><input type="number" name="item_tax[]" value="<?php echo $item->tax; ?>" class="form-control calc item_tax"></td>
                                            <td><input type="number" name="item_total[]" class="form-control item_total" readonly></td>
                                            <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                        </tr>
                                        <?php } 
                                    } else {  ?>
                                    <tr>
                                        <td><input type="text" name="item_name[]" class="form-control" placeholder="Napa, CBC Test"></td>
                                        <td><input type="number" name="item_price[]" class="form-control calc item_price" value="0"></td>
                                        <td><input type="number" name="item_discount[]" value="0" class="form-control calc item_discount"></td>
                                        <td><input type="number" name="item_tax[]" value="0" class="form-control calc item_tax"></td>
                                        <td><input type="number" name="item_total[]" class="form-control item_total" readonly></td>
                                        <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-bordered">
                                    <tr><td>Sub Amount</td><td><input type="number" id="sub_amount" name="sub_amount" value="<?php echo $edit_data ? $edit_data->sub_amount : '0'; ?>" class="form-control" readonly></td></tr>
                                    <tr><td>Discount TK</td><td><input type="number" name="discount" id="discount" value="<?php echo $edit_data ? $edit_data->discount : '0'; ?>" class="form-control calc"></td></tr>
                                    <tr><td>TAX %</td><td><input type="number" name="tax" id="tax" value="<?php echo $edit_data ? $edit_data->tax : '0'; ?>" class="form-control calc"></td></tr>
                                    <tr><td><h5>Grand Total</h5></td><td><h5><input type="number" id="grand_total" class="form-control" readonly></h5></td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-right">
                                <button type="submit" name="save" class="btn btn-success btn-lg"><?php echo $edit_id ? 'Update Invoice' : 'Save Invoice'; ?></button> <!-- Button name change -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        
        <?php } ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    function calculateTotal(){
        let sub_amount = 0;
        $("#itemTable tbody tr").each(function(){
            let row = $(this);
            let price = parseFloat(row.find('.item_price').val()) || 0;
            let dis_per = parseFloat(row.find('.item_discount').val()) || 0;
            let tax_per = parseFloat(row.find('.item_tax').val()) || 0;

            let dis_tk = (price * dis_per) / 100;
            let tax_tk = (price * tax_per) / 100;
            
            let total = (price - dis_tk) + tax_tk;
            row.find('.item_total').val(total.toFixed(2));
            sub_amount += total;
        });

        $("#sub_amount").val(sub_amount.toFixed(2));

        let overall_discount = parseFloat($("#discount").val()) || 0;
        let overall_tax_per = parseFloat($("#tax").val()) || 0;
        
        let tax_tk = (sub_amount * overall_tax_per) / 100;

        let grand_total = (sub_amount - overall_discount) + tax_tk;
        
        $("#grand_total").val(grand_total.toFixed(2));
    }

    $("#addItem").click(function(){ 
        let newRow = $("#itemTable tbody tr:first").clone();
        newRow.find("input").val('0');
        newRow.find("input[type=text]").val('');
        $("#itemTable tbody").append(newRow); 
    });

    $(document).on('click', '.removeRow', function(){ 
        if($("#itemTable tbody tr").length > 1){ 
            $(this).closest('tr').remove(); 
        } 
        calculateTotal();
    });

    $(document).on('keyup change', '.calc', function(){ calculateTotal(); });
    
    calculateTotal(); 
});
</script>

<style>
@media print {
    .page-wrapper { margin: 0; }
    .btn, .sidebar, .header { display: none !important; }
}
</style>

<?php require_once "../component/footer.php" ?>