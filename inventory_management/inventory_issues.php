<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">issues inventory</h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="add_issues.php" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i>Issues</a>
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
                                        <th>problem</th>
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
                                        $problems = $crud->common_select("issues",'*',[],'AND','id','ASC',10,($page-1)*10);
                                        
                                        if($problems['status']){
                                        foreach ($problems['data'] as $issues) { ?>
                                        <td><?= $issues->id ?></td>
                                        <td><?= $issues->name ?></td>
                                        <td><?= $issues->category ?></td>
                                        <td><?= $issues->quantity ?></td>            
                                        <td><?= $issues->problem ?></td>              
                                        <td class="text-center">
                                            <a href="<?= $base_url ?>inventory_management/inventory_issues.php?id=<?= $issues->id ?>" class="btn btn-sm btn-danger">delete</a>
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