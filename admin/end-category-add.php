<?php
require_once('header.php');

if(isset($_POST['form1'])) {
    $valid = 1;
    $error_message = '';
    $success_message = '';

    if(empty($_POST['tcat_id'])) {
        $valid = 0;
        $error_message .= "You must select a top level category.<br>";
    }

    if(empty($_POST['mcat_id'])) {
        $valid = 0;
        $error_message .= "You must select a mid level category.<br>";
    }

    if(empty($_POST['ecat_name'])) {
        $valid = 0;
        $error_message .= "End level category name cannot be empty.<br>";
    }

    // Validate image upload
    if(isset($_FILES['ecat_img']['name']) && $_FILES['ecat_img']['name'] != '') {
        $path = $_FILES['ecat_img']['name'];
        $path_tmp = $_FILES['ecat_img']['tmp_name'];

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        if(!in_array($ext, $allowed_extensions)) {
            $valid = 0;
            $error_message .= 'You must upload a file with jpg, jpeg, png or gif extension.<br>';
        }
    } else {
        $valid = 0;
        $error_message .= "You must select an image.<br>";
    }

    // Validate description
    if(empty($_POST['ecat_desc'])) {
        $valid = 0;
        $error_message .= "End level category description cannot be empty.<br>";
    }

    if($valid == 1) {
        // Create folder if it doesn't exist
        $category_name = strtolower(str_replace(' ', '_', $_POST['ecat_name']));
        $target_dir = 'uploads/end_category/' . $category_name;
        if(!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Get the next sequential number
        $files = glob($target_dir . '/*');
        $file_count = count($files);
        $next_number = $file_count + 1;

        // Upload image
        $ecat_img = $category_name . '/' . $next_number . '.' . $ext;
        move_uploaded_file($path_tmp, $target_dir . '/' . $next_number . '.' . $ext);

        // Insert data into tbl_end_category
        $statement = $pdo->prepare("INSERT INTO tbl_end_category (ecat_name, ecat_desc, ecat_img, mcat_id) VALUES (?, ?, ?, ?)");
        $statement->execute(array($_POST['ecat_name'], $_POST['ecat_desc'], $ecat_img, $_POST['mcat_id']));

        $success_message = 'End Level Category added successfully.';
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Add End Level Category</h1>
    </div>
    <div class="content-header-right">
        <a href="end-category.php" class="btn btn-primary btn-sm">View All</a>
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
                                    <?php
                                    if(isset($_POST['tcat_id']) && !empty($_POST['tcat_id'])) {
                                        $statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id = ? ORDER BY mcat_name ASC");
                                        $statement->execute([$_POST['tcat_id']]);
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            ?>
                                            <option value="<?php echo $row['mcat_id']; ?>"><?php echo $row['mcat_name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">End Level Category Name <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ecat_name" value="<?php if(isset($_POST['ecat_name'])) { echo $_POST['ecat_name']; } ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">End Level Category Description <span>*</span></label>
                            <div class="col-sm-4">
                                <textarea class="form-control" name="ecat_desc"><?php if(isset($_POST['ecat_desc'])) { echo $_POST['ecat_desc']; } ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">End Level Category Image <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="file" class="form-control" name="ecat_img">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success pull-left" name="form1">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once('footer.php'); ?>
