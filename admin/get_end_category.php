<?php
require_once('inc/config.php');

$mcat_id = $_POST['mcat_id'];
$statement = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id = ?");
$statement->execute([$mcat_id]);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
?>
