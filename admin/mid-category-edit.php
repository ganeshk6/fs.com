<?php require_once('header.php'); ?>

<?php
$error_message = '';
$success_message = '';

if (isset($_POST['form1'])) {
    $valid = 1;

    if (empty($_POST['tcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a top-level category<br>";
    }

    if (empty($_POST['mcat_name'])) {
        $valid = 0;
        $error_message .= "Mid Level Category Name cannot be empty<br>";
    }

    if (empty($_POST['mcat_url'])) {
        $valid = 0;
        $error_message .= "URL cannot be empty<br>";
    }

    // Handle mid-level category image upload
    $path = $_FILES['mcat_img']['name'];
    $path_tmp = $_FILES['mcat_img']['tmp_name'];

    if ($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $valid = 0;
            $error_message .= 'You must upload a valid image file (jpg, jpeg, png, gif)<br>';
        }
    }

    // Handle mid-level category background image upload
    $bg_path = $_FILES['mcat_bg_img']['name'];
    $bg_path_tmp = $_FILES['mcat_bg_img']['tmp_name'];

    if ($bg_path != '') {
        $bg_ext = pathinfo($bg_path, PATHINFO_EXTENSION);
        if (!in_array($bg_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $valid = 0;
            $error_message .= 'You must upload a valid background image file (jpg, jpeg, png, gif)<br>';
        }
    }

    if ($valid == 1) {
        // Prepare data for updating
        $mcat_url = generateSlug($_POST['mcat_name']);

        // Update mid-level category details in the database
        $statement = $pdo->prepare("UPDATE tbl_mid_category SET mcat_name=?, mcat_url=?, tcat_id=? WHERE mcat_id=?");
        $statement->execute(array($_POST['mcat_name'], $mcat_url, $_POST['tcat_id'], $_REQUEST['id']));

        // Handle image uploads if new files are selected
        if ($path != '') {
            // Remove existing image file
            $statement = $pdo->prepare("SELECT mcat_img FROM tbl_mid_category WHERE mcat_id=?");
            $statement->execute(array($_REQUEST['id']));
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            $existing_image = $row['mcat_img'];
            unlink('uploads/mid_category/' . $existing_image);

            // Upload new image file
            $final_name = 'mid_category_' . time() . '.' . $ext;
            move_uploaded_file($path_tmp, 'uploads/mid_category/' . $final_name);

            // Update database with new image file name
            $statement = $pdo->prepare("UPDATE tbl_mid_category SET mcat_img=? WHERE mcat_id=?");
            $statement->execute(array($final_name, $_REQUEST['id']));
        }

        // Handle background image uploads if new files are selected
        if ($bg_path != '') {
            // Remove existing background image file
            $statement = $pdo->prepare("SELECT mcat_bg_img FROM tbl_mid_category WHERE mcat_id=?");
            $statement->execute(array($_REQUEST['id']));
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            $existing_bg_image = $row['mcat_bg_img'];
            unlink('uploads/mid_category/' . $existing_bg_image);

            // Upload new background image file
            $final_bg_name = 'mid_category_bg_' . time() . '.' . $bg_ext;
            move_uploaded_file($bg_path_tmp, 'uploads/mid_category/' . $final_bg_name);

            // Update database with new background image file name
            $statement = $pdo->prepare("UPDATE tbl_mid_category SET mcat_bg_img=? WHERE mcat_id=?");
            $statement->execute(array($final_bg_name, $_REQUEST['id']));
        }

        $success_message = 'Mid Level Category is updated successfully.';
    }
}

// Fetch mid-level category details to pre-fill the form
$statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE mcat_id=?");
$statement->execute(array($_REQUEST['id']));
$total = $statement->rowCount();
$result = $statement->fetch(PDO::FETCH_ASSOC);

if ($total == 0) {
    header('location: logout.php');
    exit;
}

$mcat_name = $result['mcat_name'];
$tcat_id = $result['tcat_id'];
$mcat_img = $result['mcat_img'];
$mcat_bg_img = $result['mcat_bg_img'];
$mcat_url = $result['mcat_url'];
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Edit Mid Level Category</h1>
    </div>
    <div class="content-header-right">
        <a href="mid-category.php" class="btn btn-primary btn-sm">View All</a>
    </div>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-12">

            <?php if ($error_message): ?>
                <div class="callout callout-danger">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
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
                                <select name="tcat_id" class="form-control select2">
                                    <option value="">Select Top Level Category</option>
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_name ASC");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) {
                                        ?>
                                        <option value="<?php echo $row['tcat_id']; ?>" <?php if ($row['tcat_id'] == $tcat_id) {
                                            echo 'selected';
                                        } ?>><?php echo $row['tcat_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Mid Level Category Name <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="mcat_name"
                                       value="<?php echo $mcat_name; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">URL <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="mcat_url" value="<?php echo $mcat_url; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Existing Photo</label>
                            <div class="col-sm-4">
                                <img src="uploads/mid_category/<?php echo $mcat_img; ?>" alt="Existing Photo" style="width:200px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">New Photo</label>
                            <div class="col-sm-4">
                                <input type="file" name="mcat_img">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Existing Background Image</label>
                            <div class="col-sm-4">
                                <img src="uploads/mid_category/<?php echo $mcat_bg_img; ?>" alt="Existing Background Image" style="width:200px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">New Background Image</label>
                            <div class="col-sm-4">
                                <input type="file" name="mcat_bg_img">
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
