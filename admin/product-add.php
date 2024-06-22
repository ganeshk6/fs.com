<?php require_once('header.php'); ?>
<?php
if(isset($_POST['form1'])) {
    $valid = 1;
    $error_message = '';

    // Validation
    $required_fields = ['tcat_id', 'mcat_id', 'ecat_id', 'p_name', 'p_current_price', 'p_qty'];
    foreach ($required_fields as $field) {
        if(empty($_POST[$field])) {
            $valid = 0;
            $error_message .= ucfirst(str_replace('_', ' ', $field)) . " can not be empty<br>";
        }
    }

    $path = $_FILES['p_featured_photo']['name'];
    $path_tmp = $_FILES['p_featured_photo']['tmp_name'];

    if($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if(!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $valid = 0;
            $error_message .= 'You must upload a file with jpg, jpeg, gif or png extension<br>';
        }
    } else {
        $valid = 0;
        $error_message .= 'You must select a featured photo<br>';
    }

    if($valid == 1) {
        $pdo->beginTransaction();

        try {
            $statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product'");
            $statement->execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            $ai_id = $row['Auto_increment'];

            if(isset($_FILES['photo']['name'])) {
                $photos = array_filter($_FILES['photo']['name']);
                $photos_temp = array_filter($_FILES['photo']['tmp_name']);

                $statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product_photo'");
                $statement->execute();
                $row = $statement->fetch(PDO::FETCH_ASSOC);
                $next_id1 = $row['Auto_increment'];
                $z = $next_id1;

                for($i = 0; $i < count($photos); $i++) {
                    $my_ext1 = pathinfo($photos[$i], PATHINFO_EXTENSION);
                    if(in_array($my_ext1, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $final_name1 = $z . '.' . $my_ext1;
                        move_uploaded_file($photos_temp[$i], "../assets/uploads/product_photos/" . $final_name1);
                        $z++;

                        $statement = $pdo->prepare("INSERT INTO tbl_product_photo (photo, p_id) VALUES (?, ?)");
                        $statement->execute([$final_name1, $ai_id]);
                    }
                }
            }

            $final_name = 'product-featured-' . $ai_id . '.' . $ext;
            move_uploaded_file($path_tmp, '../assets/uploads/' . $final_name);

            $statement = $pdo->prepare("INSERT INTO tbl_product (
                p_name, p_old_price, p_current_price, p_qty, p_featured_photo, p_description, p_short_description,
                p_feature, p_condition, p_return_policy, p_total_view, p_is_featured, p_is_active, ecat_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute([
                $_POST['p_name'], $_POST['p_old_price'], $_POST['p_current_price'], $_POST['p_qty'], $final_name,
                $_POST['p_description'], $_POST['p_short_description'], $_POST['p_feature'], $_POST['p_condition'],
                $_POST['p_return_policy'], 0, $_POST['p_is_featured'], $_POST['p_is_active'], $_POST['ecat_id']
            ]);

            if(isset($_POST['size'])) {
                $size_statement = $pdo->prepare("INSERT INTO tbl_product_size (size_id, p_id) VALUES (?, ?)");
                foreach($_POST['size'] as $value) {
                    $size_statement->execute([$value, $ai_id]);
                }
            }

            if(isset($_POST['color'])) {
                $color_statement = $pdo->prepare("INSERT INTO tbl_product_color (color_id, p_id) VALUES (?, ?)");
                foreach($_POST['color'] as $value) {
                    $color_statement->execute([$value, $ai_id]);
                }
            }

            $pdo->commit();
            $success_message = 'Product is added successfully.';
        } catch (Exception $e) {
            $pdo->rollBack();
            $error_message = 'Something went wrong: ' . $e->getMessage();
        }
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Product</h1>
	</div>
	<div class="content-header-right">
		<a href="product.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<section class="content">

	<div class="row">
		<div class="col-md-12">

			 <?php if(!empty($error_message)): ?>
            <div class="callout callout-danger">
                <p><?php echo $error_message; ?></p>
            </div>
            <?php endif; ?>

            <?php if(!empty($success_message)): ?>
            <div class="callout callout-success">
                <p><?php echo $success_message; ?></p>
            </div>
            <?php endif; ?>

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Top Level Category Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="tcat_id" class="form-control select2 top-cat">
									<option value="">Select Top Level Category</option>
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_name ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['tcat_id']; ?>"><?php echo $row['tcat_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Mid Level Category Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="mcat_id" class="form-control select2 mid-cat">
									<option value="">Select Mid Level Category</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">End Level Category Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="ecat_id" class="form-control select2 end-cat">
									<option value="">Select End Level Category</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Product Name <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_name" class="form-control">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Old Price <br><span style="font-size:10px;font-weight:normal;">(In USD)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_old_price" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Current Price <span>*</span><br><span style="font-size:10px;font-weight:normal;">(In USD)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_current_price" class="form-control">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Quantity <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_qty" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Select Size</label>
							<div class="col-sm-4">
								<select name="size[]" class="form-control select2" multiple="multiple">
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_size ORDER BY size_id ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['size_id']; ?>"><?php echo $row['size_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Select Color</label>
							<div class="col-sm-4">
								<select name="color[]" class="form-control select2" multiple="multiple">
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_color ORDER BY color_id ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['color_id']; ?>"><?php echo $row['color_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Featured Photo <span>*</span></label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Other Photos</label>
							<div class="col-sm-4" style="padding-top:4px;">
								<table id="ProductTable" style="width:100%;">
			                        <tbody>
			                            <tr>
			                                <td>
			                                    <div class="upload-btn">
			                                        <input type="file" name="photo[]" style="margin-bottom:5px;">
			                                    </div>
			                                </td>
			                                <td style="width:28px;"><a href="javascript:void()" class="Delete btn btn-danger btn-xs">X</a></td>
			                            </tr>
			                        </tbody>
			                    </table>
							</div>
							<div class="col-sm-2">
			                    <input type="button" id="btnAddNew" value="Add Item" style="margin-top: 5px;margin-bottom:10px;border:0;color: #fff;font-size: 14px;border-radius:3px;" class="btn btn-warning btn-xs">
			                </div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Description</label>
							<div class="col-sm-8">
								<textarea name="p_description" class="form-control" cols="30" rows="10" id="editor1"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Short Description</label>
							<div class="col-sm-8">
								<textarea name="p_short_description" class="form-control" cols="30" rows="10" id="editor2"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Features</label>
							<div class="col-sm-8">
								<textarea name="p_feature" class="form-control" cols="30" rows="10" id="editor3"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Conditions</label>
							<div class="col-sm-8">
								<textarea name="p_condition" class="form-control" cols="30" rows="10" id="editor4"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Return Policy</label>
							<div class="col-sm-8">
								<textarea name="p_return_policy" class="form-control" cols="30" rows="10" id="editor5"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Is Featured?</label>
							<div class="col-sm-8">
								<select name="p_is_featured" class="form-control" style="width:auto;">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Is Active?</label>
							<div class="col-sm-8">
								<select name="p_is_active" class="form-control" style="width:auto;">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Add Product</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>