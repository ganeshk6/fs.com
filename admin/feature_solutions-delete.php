<?php
require_once('header.php');

if(!isset($_REQUEST['id'])) {
    header('location: logout.php');
    exit;
} else {
    $statement = $pdo->prepare("SELECT * FROM tbl_feature_solutions WHERE id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    if($total == 0) {
        header('location: logout.php');
        exit;
    }
}

$statement = $pdo->prepare("SELECT * FROM tbl_feature_solutions WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $image = $row['image'];
}

if($image!='') {
    unlink('./uploads/'.$image);
}

$statement = $pdo->prepare("DELETE FROM tbl_feature_solutions WHERE id=?");
$statement->execute(array($_REQUEST['id']));

header('location: feature_solutions.php');
?>
