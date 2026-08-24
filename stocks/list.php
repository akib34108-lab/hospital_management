<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php";?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4>Stock Management</h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="<?= $base_url ?>stock/create.php" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus-circle me-2"></i>Add Stock</a>
                    </div>
                </div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-border table-striped custom-table datatable mb-0">
								<thead>
									<tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Warehouse</th>
                                        <th>Quantity</th>
                                        <th>Last Updated</th>
									</tr>
								</thead>
								<tbody>
                            <?php if (!empty($stocks)): ?>
                                <?php foreach ($stocks as $row): ?>
                                    <tr>
                                        <td><?= (int)$row->id ?></td>
                                        <td><?= htmlspecialchars($row->product_name) ?></td>
                                        <td><?= htmlspecialchars($row->warehouse_name) ?></td>
                                        <td><?= (int)$row->quantity ?></td>
                                        <td><?= htmlspecialchars($row->updated_at) ?></td>
                                        <!-- <td class="text-end">
                                            <a href="<?= $base_url ?>stock/edit.php?id=<?= (int)$row->id ?>" class="me-2">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a href="<?= $base_url ?>stock/delete.php?id=<?= (int)$row->id ?>"
                                               onclick="return confirm('Are you sure you want to delete this stock record?');">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                        </td> -->
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No stock records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
							</table>
						</div>
					</div>
                </div>
            </div>
        </div>
   <?php require_once "../component/footer.php" ?>
