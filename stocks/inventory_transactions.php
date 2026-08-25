<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title"> Inventory Transactions</h4>
                    </div>
                </div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-border table-striped custom-table datatable mb-0">
								<thead>
									<tr>
                                        <th>ID</th>
										<th>Equipment Name</th>
										<th>Qty</th>
										<th>Allocated To</th>
										<th>Issue Date</th>
										<th>Expected Return Date</th>
										<th>Return Status</th>
										<th class="text-right">Action</th>
									</tr>
								</thead>
								<tbody>
                                    <tr>
                                        <?php
                                        // Fetch department from the database
                                        if(isset($_GET['page']) && is_numeric($_GET['page'])){
                                            $page = (int)$_GET['page'];
                                        } else {
                                            $page = 1;
                                        }
                                        $rests = $crud->common_query("SELECT inventory_transaction.*, doctors.name as doc_name, users.full_name as user_name, inventory_list.name FROM `inventory_transaction` 
                                                                    left JOIN doctors on doctors.id=inventory_transaction.source_id and inventory_transaction.source_type='doctor'
                                                                    left JOIN users on users.id=inventory_transaction.source_id and inventory_transaction.source_type='user'
                                                                    JOIN inventory_list on inventory_list.id=inventory_transaction.inventory_list_id");
                                       
                                        if($rests['status']){
                                        foreach ($rests['data'] as $rest) { ?>
                                        <td><?= $rest->id ?></td>
                                        <td><?= $rest->name ?></td>
                                        <td><?= $rest->qty ?></td>
                                        <td><?= $rest->doc_name ?: $rest->user_name ?></td>
                                        <td><?= $rest->issue_date ?></td>
                                        <td><?= $rest->return_date ?></td>
                                        <td>
                                            <?php if($rest->return_date && !$rest->actual_return_date) { ?>
                                                <span class="badge badge-warning">Pending</span>
                                            <?php } elseif($rest->actual_return_date) { ?>
                                                <span class="badge badge-success">Returned</span>
                                            <?php } else { ?>
                                                --
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= $base_url ?>stocks/inventory_allocation_edit.php?id=<?= $rest->id ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2">Edit</a>
                                            <a href="<?= $base_url ?>stocks/inventory_allocation_delete.php?id=<?= $rest->id ?>" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
							</table>
						</div>
					</div>
                </div>
            </div>
        </div>
   <?php require_once "../component/footer.php" ?>