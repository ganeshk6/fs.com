<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
    header('location: logout.php');
    exit;
} else {
    $statement = $pdo->prepare("SELECT * FROM tbl_software_section WHERE id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    if($total == 0) {
        header('location: logout.php');
        exit;
    }
}

if(isset($_POST['form1'])) {
    $valid = 1;

    if(empty($_POST['img_heading'])) {
        $valid = 0;
        $error_message .= 'Heading cannot be empty<br>';
    }

    if(empty($_POST['soft_type'])) {
        $valid = 0;
        $error_message .= 'Type cannot be empty<br>';
    }

    if(empty($_POST['soft_content'])) {
        $valid = 0;
        $error_message .= 'Content cannot be empty<br>';
    }

    if(empty($_POST['url'])) {
        $valid = 0;
        $error_message .= 'URL cannot be empty<br>';
    }

    $path = $_FILES['image']['name'];
    $path_tmp = $_FILES['image']['tmp_name'];

    if($path!='') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if($ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if($valid == 1) {
        if($path == '') {
            $statement = $pdo->prepare("UPDATE tbl_software_section SET img_heading=?, soft_type=?, soft_content=?, url=? WHERE id=?");
            $statement->execute(array($_POST['img_heading'],$_POST['soft_type'],$_POST['soft_content'],$_POST['url'],$_REQUEST['id']));
        } else {
            unlink('./uploads/'.$_POST['current_image']);
            $final_name = 'software_section-'.time().'.'.$ext;
            move_uploaded_file($path_tmp, './uploads/'.$final_name);

            $statement = $pdo->prepare("UPDATE tbl_software_section SET img_heading=?, soft_type=?, soft_content=?, image=?, url=? WHERE id=?");
            $statement->execute(array($_POST['img_heading'],$_POST['soft_type'],$_POST['soft_content'],$final_name,$_POST['url'],$_REQUEST['id']));
        }
        
        $success_message = 'Software Section is updated successfully.';
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Edit Software Section</h1>
    </div>
    <div class="content-header-right">
        <a href="software-plateform.php" class="btn btn-primary btn-sm">View All</a>
    </div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_software_section WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $img_heading = $row['img_heading'];
    $soft_type = $row['soft_type'];
    $soft_content = $row['soft_content'];
    $image = $row['image'];
    $url = $row['url'];
}
?>

<section class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    <?php
                    if($error_message) {
                        echo '<div class="alert alert-danger">'.$error_message.'</div>';
                    }
                    if($success_message) {
                        echo '<div class="alert alert-success">'.$success_message.'</div>';
                    }
                    ?>
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Heading <span>*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="img_heading" value="<?php echo $img_heading; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Type <span>*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="soft_type" value="<?php echo $soft_type; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Content <span>*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="soft_content" style="height:100px;"><?php echo $soft_content; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Existing Image</label>
                            <div class="col-sm-10">
                                <img src="./uploads/<?php echo $image; ?>" alt="" style="width:200px;">
                                <input type="hidden" name="current_image" value="<?php echo $image; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Image </label>
                            <div class="col-sm-10">
                                <input type="file" name="image">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">URL <span>*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="url" value="<?php echo $url; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-success pull-left" name="form1">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

<?php require_once('footer.php'); ?>
