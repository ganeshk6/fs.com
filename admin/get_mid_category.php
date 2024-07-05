<?php
require_once('inc/config.php');

$tcat_id = $_POST['tcat_id'];
$statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id = ?");
$statement->execute([$tcat_id]);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
?>
