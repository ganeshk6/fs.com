<?php require_once('header.php'); ?>

<?php
if (isset($_POST['form1'])) {
    $valid = 1;
    $error_message = '';

    if (empty($_POST['tcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a top-level category<br>";
    }

    if (empty($_POST['mcat_name'])) {
        $valid = 0;
        $error_message .= "Mid-Level Category Name cannot be empty<br>";
    }

    $path = $_FILES['mcat_img']['name'];
    $path_tmp = $_FILES['mcat_img']['tmp_name'];

    $bg_path = $_FILES['mcat_bg_img']['name'];
    $bg_path_tmp = $_FILES['mcat_bg_img']['tmp_name'];

    if ($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if ($bg_path != '') {
        $bg_ext = pathinfo($bg_path, PATHINFO_EXTENSION);
        $bg_file_name = basename($bg_path, '.' . $bg_ext);
        if ($bg_ext != 'jpg' && $bg_ext != 'png' && $bg_ext != 'jpeg' && $bg_ext != 'gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for background image<br>';
        }
    }

    if ($valid == 1) {
        $mcat_url = generateSlug($_POST['mcat_name']);

        if ($path != '') {
            $final_name = 'mid_category_' . time() . '.' . $ext;
            move_uploaded_file($path_tmp, 'uploads/mid_category/' . $final_name);
        } else {
            $final_name = '';
        }

        if ($bg_path != '') {
            $final_bg_name = 'mid_category_bg_' . time() . '.' . $bg_ext;
            move_uploaded_file($bg_path_tmp, 'uploads/mid_category/' . $final_bg_name);
        } else {
            $final_bg_name = '';
        }

        $statement = $pdo->prepare("INSERT INTO tbl_mid_category (mcat_name, mcat_img, mcat_url, tcat_id, mcat_bg_img) VALUES (?, ?, ?, ?, ?)");
        $statement->execute(array($_POST['mcat_name'], $final_name, $mcat_url, $_POST['tcat_id'], $final_bg_name));

        $success_message = 'Mid-Level Category is added successfully.';
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Add Mid-Level Category</h1>
    </div>
    <div class="content-header-right">
        <a href="mid-category.php" class="btn btn-primary btn-sm">View All</a>
    </div>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-12">

            <?php if (isset($error_message) && $error_message != ''): ?>
                <div class="callout callout-danger">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <?php if (isset($success_message) && $success_message != ''): ?>
                <div class="callout callout-success">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>

            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

                <div class="box box-info">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Top-Level Category Name <span>*</span></label>
                            <div class="col-sm-4">
                                <select name="tcat_id" class="form-control select2">
                                    <option value="">Select Top-Level Category</option>
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
                            <label for="" class="col-sm-3 control-label">Mid-Level Category Name <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="mcat_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Image <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="file" name="mcat_img">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Background Image</label>
                            <div class="col-sm-4">
                                <input type="file" name="mcat_bg_img">
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
