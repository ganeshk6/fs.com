<?php require_once('header.php'); ?>

<?php
$error_message = '';
$success_message = '';

if(isset($_POST['form1'])) {
    $valid = 1;

    if(empty($_POST['tcat_id'])) {
        $valid = 0;
        $error_message .= "You must select a top level category<br>";
    }

    if(empty($_POST['mcat_id'])) {
        $valid = 0;
        $error_message .= "You must select a mid level category<br>";
    }

    if(empty($_POST['ecat_name'])) {
        $valid = 0;
        $error_message .= "End level category name cannot be empty<br>";
    }

    // Check if a new image file is uploaded
    $path = $_FILES['ecat_img']['name'];
    $path_tmp = $_FILES['ecat_img']['tmp_name'];

    if($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if( $ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif' ) {
            $valid = 0;
            $error_message .= 'You must upload a jpg, jpeg, png, or gif file<br>';
        }
    }

    if($valid == 1) {
        // Fetch existing category data
        $statement = $pdo->prepare("SELECT ecat_img FROM tbl_end_category WHERE ecat_id=?");
        $statement->execute(array($_REQUEST['id']));
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $ecat_img = $result['ecat_img']; // existing image

        // Process new image if uploaded
        if($path != '') {
            // Delete old image
            if($ecat_img != '') {
                unlink('uploads/end_category/' . $ecat_img);
            }

            // Upload new image
            $final_name = 'category-' . $_REQUEST['id'] . '.' . $ext;
            move_uploaded_file($path_tmp, 'uploads/end_category/' . $final_name);

            $ecat_img = $final_name; // new image name
        }

        // Update category details including description
        $statement = $pdo->prepare("UPDATE tbl_end_category SET ecat_name=?, mcat_id=?, ecat_img=?, ecat_desc=? WHERE ecat_id=?");
        $statement->execute(array($_POST['ecat_name'], $_POST['mcat_id'], $ecat_img, $_POST['ecat_desc'], $_REQUEST['id']));

        $success_message = 'End Level Category updated successfully.';
    }
}

// Fetch existing category data for pre-populating the form
$statement = $pdo->prepare("SELECT t1.*, t2.tcat_id, t2.mcat_id, t2.mcat_name, t3.tcat_id, t3.tcat_name 
                            FROM tbl_end_category t1 
                            LEFT JOIN tbl_mid_category t2 ON t1.mcat_id = t2.mcat_id 
                            LEFT JOIN tbl_top_category t3 ON t2.tcat_id = t3.tcat_id 
                            WHERE t1.ecat_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetch(PDO::FETCH_ASSOC);

if(!$result) {
    header('location: logout.php');
    exit;
}

$ecat_id = $result['ecat_id'];
$ecat_name = $result['ecat_name'];
$ecat_desc = $result['ecat_desc'];
$mcat_id = $result['mcat_id'];
$tcat_id = $result['tcat_id'];
$ecat_img = $result['ecat_img'];
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Edit End Level Category</h1>
    </div>
    <div class="content-header-right">
        <a href="end-category.php" class="btn btn-primary btn-sm">View All</a>
    </div>
</section>

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
                                    $top_categories = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($top_categories as $top_category) {
                                        $selected = ($top_category['tcat_id'] == $tcat_id) ? 'selected' : '';
                                        echo '<option value="' . $top_category['tcat_id'] . '" ' . $selected . '>' . $top_category['tcat_name'] . '</option>';
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
                                    $mid_categories = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($mid_categories as $mid_category) {
                                        $selected = ($mid_category['mcat_id'] == $mcat_id) ? 'selected' : '';
                                        echo '<option value="' . $mid_category['mcat_id'] . '" ' . $selected . '>' . $mid_category['mcat_name'] . '</option>';
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
                            <label for="" class="col-sm-3 control-label">End Level Category Description</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="ecat_desc" rows="5"><?php echo $ecat_desc; ?></textarea>
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

<?php require_once('footer.php'); ?>
