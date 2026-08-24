<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; 

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Edit Stock</h4>
                <h6>Update stock record #<?= (int)$stock->id ?></h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="<?= $base_url ?>stock/update.php" method="POST">
                    <input type="hidden" name="id" value="<?= (int)$stock->id ?>">

                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <select name="product_id" class="form-control" required>
                            <option value="">-- Select Product --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= (int)$p->id ?>"
                                    <?= ((int)$p->id === (int)$stock->product_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p->product_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warehouse</label>
                        <select name="warehouse_id" class="form-control" required>
                            <option value="">-- Select Warehouse --</option>
                            <?php foreach ($warehouses as $w): ?>
                                <option value="<?= (int)$w->id ?>"
                                    <?= ((int)$w->id === (int)$stock->warehouse_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($w->warehouse_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="0"
                               value="<?= (int)$stock->quantity ?>" required>
                    </div>

                    <button type="submit" class="btn btn-submit me-2">Update</button>
                    <a href="<?= $base_url ?>stock/list.php" class="btn btn-cancel">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

</div> <!-- /.main-wrapper -->

<?php require_once "../component/footer.php" ?> 
