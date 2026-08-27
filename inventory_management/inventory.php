<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">Inventory overview</h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="inventory_issues.php" class="btn btn btn-warning btn-rounded float-right">Issues</a>
                        <a href="add_inventory.php" class="btn btn btn-primary btn-rounded float-right" style="margin-right: 10px;"><i class="fa fa-plus"></i> Add Inventory</a>
                    </div>
                </div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-border table-striped custom-table datatable mb-0">
								<thead>
									<tr>
                                        <th>ID</th>
										<th>Name</th>
										<th>category</th>
										<th>quantity</th>
										<th>Used Type</th>
										<th>listing date</th>
										<th>Supplier</th>
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
                                        $inventories = $crud->common_select("inventory_list",'*',[],'AND','id','ASC',10,($page-1)*10);
                                        
                                        if($inventories['status']){
                                        foreach ($inventories['data'] as $inventory) { ?>
                                        <td><?= $inventory->id ?></td>
                                        <td><?= $inventory->name ?></td>
                                        <td><?= $inventory->category ?></td>
                                        <td><?= $inventory->quantity ?></td>
                                        <td><?= $inventory->used_type == 1 ? 'Reusable' : 'One-time' ?></td>
                                        <td><?= $inventory->date ?></td>                    
                                        <td><?= $inventory->supplier_info ?></td>                    
                                        <td class="text-center">
                                            <a href="<?= $base_url ?>inventory_management/inventory_edit.php?id=<?= $inventory->id ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2">Edit</a>
                                            <a href="<?= $base_url ?>inventory_management/delete_inventory.php?id=<?= $inventory->id ?>" class="btn btn-sm btn-danger">Delete</a>
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