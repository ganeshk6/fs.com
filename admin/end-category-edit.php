<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['tcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a top level category<br>";
    }

    if(empty($_POST['mcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a mid level category<br>";
    }

    if(empty($_POST['ecat_name'])) {
        $valid = 0;
        $error_message .= "End level category name can not be empty<br>";
    }

    // Check if a new image file is uploaded
    $path = $_FILES['ecat_img']['name'];
    $path_tmp = $_FILES['ecat_img']['tmp_name'];

    if($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if( $ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, png or gif file<br>';
        }
    }

    if($valid == 1) {
        // If a new image is uploaded, delete the old one and upload the new one
        if($path != '') {
            // Getting the current image to delete
            $statement = $pdo->prepare("SELECT ecat_img FROM tbl_end_category WHERE ecat_id=?");
            $statement->execute(array($_REQUEST['id']));
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if($result['ecat_img'] != '') {
                unlink('uploads/end_category/' . $result['ecat_img']);
            }

            // Renaming the new image and uploading
            $final_name = 'category-' . $_REQUEST['id'] . '.' . $ext;
            move_uploaded_file($path_tmp, 'uploads/end_category/' . $final_name);

            // Updating the database
            $statement = $pdo->prepare("UPDATE tbl_end_category SET ecat_name=?,mcat_id=?,ecat_img=? WHERE ecat_id=?");
            $statement->execute(array($_POST['ecat_name'], $_POST['mcat_id'], $final_name, $_REQUEST['id']));
        } else {
            // If no new image is uploaded, just update the other details
            $statement = $pdo->prepare("UPDATE tbl_end_category SET ecat_name=?,mcat_id=? WHERE ecat_id=?");
            $statement->execute(array($_POST['ecat_name'], $_POST['mcat_id'], $_REQUEST['id']));
        }

    	$success_message = 'End Level Category is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check if the id is valid or not
	$statement = $pdo->prepare("SELECT * 
                            FROM tbl_end_category t1
                            JOIN tbl_mid_category t2
                            ON t1.mcat_id = t2.mcat_id
                            JOIN tbl_top_category t3
                            ON t2.tcat_id = t3.tcat_id
                            WHERE t1.ecat_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit End Level Category</h1>
	</div>
	<div class="content-header-right">
		<a href="end-category.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php							
foreach ($result as $row) {
	$ecat_name = $row['ecat_name'];
    $mcat_id = $row['mcat_id'];
    $tcat_id = $row['tcat_id'];
    $ecat_img = $row['ecat_img'];
}
?>

<section class="content">
  <div class="row">
    <div class="col-md-12">

		<?php if($error_message): ?>
		<div class="callout callout-danger">
			<p><?php echo $error_message; ?></p>
		</div>
		<?php endif; ?>

		<?php if($success_message): ?>
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
                                <option value="<?php echo $row['tcat_id']; ?>" <?php if($row['tcat_id'] == $tcat_id){echo 'selected';} ?>><?php echo $row['tcat_name']; ?></option>
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
                            <?php
                            $statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id = ? ORDER BY mcat_name ASC");
                            $statement->execute(array($tcat_id));
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);   
                            foreach ($result as $row) {
                                ?>
                                <option value="<?php echo $row['mcat_id']; ?>" <?php if($row['mcat_id'] == $mcat_id){echo 'selected';} ?>><?php echo $row['mcat_name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">End Level Category Name <span>*</span></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="ecat_name" value="<?php echo $ecat_name; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Existing Image</label>
                    <div class="col-sm-4">
                        <?php if($ecat_img != ''): ?>
                            <img src="uploads/end_category/<?php echo $ecat_img; ?>" alt="End Level Category Image" style="width: 100px;">
                        <?php else: ?>
                            <p>No image found</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">New Image</label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" name="ecat_img">
                    </div>
                </div>
                <div class="form-group">
                	<label for="" class="col-sm-3 control-label"></label>
                    <div class="col-sm-6">
                      <button type="submit" class="btn btn-success pull-left" name="form1">Update</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
  </div>
</section>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
