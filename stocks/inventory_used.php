<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">Rest Inventory</h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="add-patient.php" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i>go rest</a>
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
										<th>Category</th>
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
                                        $rests = $crud->common_select("rest_inventory",'*',[],'AND','id','ASC',10,($page-1)*10);
                                        
                                        if($rests['status']){
                                        foreach ($rests['data'] as $rest) { ?>
                                        <td><?= $rest->id ?></td>
                                        <td><?= $rest->name ?></td>
                                        <td><?= $rest->category?></td>
                                        
                                        <td class="text-center">
                                            <a href="<?= $base_url ?>stocks/rest_edit.php?id=<?= $rest->id ?>" class="btn btn-sm btn-primary mb-2 mb-lg-0 me-0 me-lg-2">Edit</a>
                                            <a href="<?= $base_url ?>stocks/rest_delete.php?id=<?= $rest->id ?>" class="btn btn-sm btn-danger">Delete</a>
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