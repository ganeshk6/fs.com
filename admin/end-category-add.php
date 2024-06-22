<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
    $valid = 1;
    $error_message = '';

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

    if(isset($_FILES['ecat_img']['name']) && $_FILES['ecat_img']['name'] != '') {
        $path = $_FILES['ecat_img']['name'];
        $path_tmp = $_FILES['ecat_img']['tmp_name'];

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, png or gif file<br>';
        }
    } else {
        $valid = 0;
        $error_message .= "You must have to select a photo<br>";
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

        // Create the new file name
        $ecat_img = $category_name . '/' . $next_number . '.' . $ext;
        move_uploaded_file($path_tmp, $target_dir . '/' . $next_number . '.' . $ext);

        // Saving data into the main table tbl_end_category
        $statement = $pdo->prepare("INSERT INTO tbl_end_category (ecat_name,mcat_id,ecat_img) VALUES (?,?,?)");
        $statement->execute(array($_POST['ecat_name'], $_POST['mcat_id'], $ecat_img));

        $success_message = 'End Level Category is added successfully.';
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
                                <input type="text" class="form-control" name="ecat_name">
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
