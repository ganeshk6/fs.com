<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
    $valid = 1;

    if(empty($_POST['cert_name'])) {
        $valid = 0;
        $error_message .= 'Name cannot be empty<br>';
    }

    if(empty($_POST['cert_content'])) {
        $valid = 0;
        $error_message .= 'Content cannot be empty<br>';
    }

    if(empty($_POST['url'])) {
        $valid = 0;
        $error_message .= 'URL cannot be empty<br>';
    }

    if($valid == 1) {
        $statement = $pdo->prepare("INSERT INTO tbl_fs_certified (cert_icon, cert_name, cert_content, url) VALUES (?,?,?,?)");
        $statement->execute(array($_POST['cert_icon'], $_POST['cert_name'], $_POST['cert_content'], $_POST['url']));

        $success_message = 'FS Certified is added successfully.';
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Add FS Certified</h1>
    </div>
    <div class="content-header-right">
        <a href="fs_certified.php" class="btn btn-primary btn-sm">View All</a>
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
                    <form class="form-horizontal" action="" method="post">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Icon <span>*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="cert_icon" style="height:100px;"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Name <span>*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="cert_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Content <span>*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="cert_content" style="height:100px;"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">URL <span>*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="url">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-success pull-left" name="form1">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

<?php require_once('footer.php'); ?>
