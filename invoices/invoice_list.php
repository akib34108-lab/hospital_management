<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>
<?php $conn = $crud->conn; ?>

<div class="page-wrapper">
    <div class="content">
        
        <?php 
        // DELETE LOGIC
        if(isset($_GET['delete_id'])){
            $del_id = $conn->real_escape_string($_GET['delete_id']);
            $conn->query("DELETE FROM invoice_details WHERE invoice_id='$del_id'");
            $conn->query("DELETE FROM invoices WHERE id='$del_id'");
            echo "<script>alert('Invoice Deleted Successfully'); window.location='invoice_list.php';</script>";
            exit;
        }
        ?>

        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Invoice List</h4>
            </div>
            <div class="col-sm-8 col-9 text-right m-b-20">
                <a href="invoices.php" class="btn btn-success btn-rounded"><i class="fa fa-plus"></i> Add Invoice</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice ID</th>
                                    <th>Patient Name</th>
                                    <th>Invoice Date</th>
                                    <th>Sub Amount</th>
                                    <th>Disc TK</th>
                                    <th>Tax TK</th>  <!-- % bad diye TK korlam -->
                                    <th>Grand Total</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sl=1;
                                $result = $conn->query("SELECT i.*, p.name as patient_name FROM invoices i 
                                                        LEFT JOIN patients p ON i.patient_id = p.id 
                                                        WHERE i.status=1 ORDER BY i.id DESC");
                                if($result && $result->num_rows > 0){
                                    while($row = $result->fetch_object()){ 
                                        // Ekhane r % hisab kora lagbena. DB tei TK save ase
                                        $grand_total = ($row->sub_amount - $row->discount) + $row->tax;
                                ?>
                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td>INV-<?php echo $row->id; ?></td>
                                    <td><?php echo $row->patient_name; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row->invoice_date)); ?></td>
                                    <td><?php echo number_format($row->sub_amount, 2); ?></td>
                                    <td><?php echo number_format($row->discount, 2); ?> TK</td>
                                    <td><?php echo number_format($row->tax, 2); ?> TK</td> <!-- % bad -->
                                    <td><b><?php echo number_format($grand_total, 2); ?> TK</b></td>
                                    <td class="text-right">
                                        
                                        <a href="invoices.php?id=<?php echo $row->id; ?>" class="btn btn-sm btn-info" title="View/Print">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="invoices.php?edit_id=<?php echo $row->id; ?>" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="invoice_view.php?id=<?php echo $row->id; ?>" class="btn btn-sm btn-primary" title="Details">  
                                            <i class="fa fa-file-text"></i>
                                        </a>
                                        <a href="invoice_list.php?delete_id=<?php echo $row->id; ?>" onclick="return confirm('Are you sure to delete this invoice?')" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    } // while sesh
                                } else { 
                                ?>
                                <tr><td colspan="9" class="text-center">No Invoice Found</td></tr>
                                <?php 
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