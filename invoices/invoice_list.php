<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-size-26 text-color-2">Patient Invoices</h3>
                </div>
                <div class="mt-3 mt-lg-0">
                    <a href="<?= $base_url; ?>invoices/create.php" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-2"></i> Add Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">SL</th>
                                <th>Patient Name</th>
                                <th>Sub Amount</th>
                                <th>Discount</th>    
                                <th>Tax</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>  
                                <th>Due Amount</th>
                                <th>Invoice Date</th>
                                <th>Payment Status</th>
                                <th class="text-center" width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // deleted_at bad diye disi
                                $sql = "SELECT i.*, IFNULL(p.name, CONCAT('Unknown Patient ID: ', i.patient_id)) as patient_name 
                                        FROM invoices i
                                        LEFT JOIN patients p ON i.patient_id = p.id 
                                        ORDER BY i.id DESC";

                                $result = $crud->common_query($sql);
                                $sl = 1;

                            if ($result['status'] && !empty($result['data'])) {
                                foreach ($result['data'] as $invoice) {
                                    $total = $invoice->sub_amount - $invoice->discount + $invoice->tax;
                                    $due = $total - $invoice->paid_amount;
                                    
                                    // Payment status badge
                                    if ($due <= 0 && $invoice->paid_amount > 0) { 
                                        $badge = '<span class="badge bg-success">Paid</span>';
                                    } elseif ($invoice->paid_amount > 0) { 
                                        $badge = '<span class="badge bg-info">Partial</span>';
                                    } else { 
                                        $badge = '<span class="badge bg-warning text-dark">Due</span>';
                                    }
                            ?>
                            <tr>
                                <td><?= $sl++ ?></td>
                                <td><b><?= htmlspecialchars($invoice->patient_name) ?></b></td>
                                <td><?= number_format($invoice->sub_amount, 2) ?> BDT</td>
                                <td><?= number_format($invoice->discount, 2) ?> BDT</td>
                                <td><?= number_format($invoice->tax, 2) ?> BDT</td>
                                <td><b><?= number_format($total, 2) ?> BDT</b></td>
                                <td><?= number_format($invoice->paid_amount, 2) ?> BDT</td>  
                                <td><b class="text-danger"><?= number_format($due, 2) ?> BDT</b></td>  
                                <td><?= date('d-m-Y', strtotime($invoice->invoice_date)) ?></td>
                                <td><?= $badge ?></td>
                                <td class="text-center">
                                    <a href="<?= $base_url; ?>invoices/view.php?id=<?= $invoice->id ?>" class="btn btn-sm btn-info" title="View">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="<?= $base_url; ?>invoices/delete.php?id=<?= $invoice->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this invoice?')" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='11' class='text-center py-4'>No invoices found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../component/footer.php"; ?>