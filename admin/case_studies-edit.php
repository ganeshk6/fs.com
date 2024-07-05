<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
    header('location: logout.php');
    exit;
}

$statement = $pdo->prepare("SELECT * FROM tbl_case_studies WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
if(!$result) {
    header('location: logout.php');
    exit;
}

foreach ($result as $row) {
    $study_img = $row['study_img'];
    $country_flag = $row['country_flag'];
    $country_name = $row['country_name'];
    $study_heading = $row['study_heading'];
    $study_content = $row['study_content'];
    $url = $row['url'];
}

if(isset($_POST['form1'])) {
    $valid = 1;

    if(empty($_POST['study_heading'])) {
        $valid = 0;
        $error_message .= 'Heading cannot be empty<br>';
    }

    if(empty($_POST['study_content'])) {
        $valid = 0;
        $error_message .= 'Content cannot be empty<br>';
    }

    if(empty($_POST['url'])) {
        $valid = 0;
        $error_message .= 'URL cannot be empty<br>';
    }

    $path = $_FILES['study_img']['name'];
    $path_tmp = $_FILES['study_img']['tmp_name'];

    if($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, png, or gif file<br>';
        }
    }

    $path2 = $_FILES['country_flag']['name'];
    $path_tmp2 = $_FILES['country_flag']['tmp_name'];

    if($path2 != '') {
        $ext2 = pathinfo($path2, PATHINFO_EXTENSION);
        if($ext2 != 'jpg' && $ext2 != 'jpeg' && $ext2 != 'png' && $ext2 != 'gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, png, or gif file<br>';
        }
    }

    if($valid == 1) {
        if($path != '') {
            unlink(''.$study_img);
            $final_name = 'study-img-'.time().'.'.$ext;
            move_uploaded_file($path_tmp, 'uploads/'.$final_name);
            $study_img = 'uploads/'.$final_name;
        }

        if($path2 != '') {
            unlink(''.$country_flag);
            $final_name2 = 'flag-img-'.time().'.'.$ext2;
            move_uploaded_file($path_tmp2, 'uploads/'.$final_name2);
            $country_flag = 'uploads/'.$final_name2;
        }

        $statement = $pdo->prepare("UPDATE tbl_case_studies SET study_img=?, country_flag=?, country_name=?, study_heading=?, study_content=?, url=? WHERE id=?");
        $statement->execute(array($study_img, $country_flag, $_POST['country_name'], $_POST['study_heading'], $_POST['study_content'], $_POST['url'], $_REQUEST['id']));

        $success_message = 'Case Study is updated successfully.';
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Edit Case Study</h1>
    </div>
    <div class="content-header-right">
        <a href="case_studies.php" class="btn btn-primary btn-sm">View All</a>
    </div>
</section>

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
                            <label for="" class="col-sm-2 control-label">Existing Image</label>
                            <div class="col-sm-10">
                                <img src="<?php echo $study_img; ?>" style="width:200px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="study_img">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Existing Country Flag</label>
                            <div class="col-sm-10">
                                <img src="<?php echo $country_flag; ?>" style="width:50px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Country Flag</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="country_flag">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Country Name <span>*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="country_name" value="<?php echo $country_name; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Heading <span>*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="study_heading" value="<?php echo $study_heading; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Content <span>*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="study_content" style="height:100px;"><?php echo $study_content; ?></textarea>
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
