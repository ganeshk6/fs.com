<?php
require_once('header.php');

if(!isset($_REQUEST['id'])) {
    header('location: logout.php');
    exit;
} else {
    $statement = $pdo->prepare("SELECT * FROM tbl_case_studies WHERE id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    if($total == 0) {
        header('location: logout.php');
        exit;
    }
}

$statement = $pdo->prepare("SELECT * FROM tbl_case_studies WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $study_img = $row['study_img'];
    $country_flag = $row['country_flag'];
    unlink('./'.$study_img);
    unlink('./'.$country_flag);
}

$statement = $pdo->prepare("DELETE FROM tbl_case_studies WHERE id=?");
$statement->execute(array($_REQUEST['id']));

header('location: case_studies.php');
?>
