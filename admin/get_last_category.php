<?php
require_once('inc/config.php');

$ecat_id = $_POST['ecat_id'];
$statement = $pdo->prepare("SELECT * FROM tbl_last_category WHERE ecat_id = ?");
$statement->execute([$ecat_id]);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
?>
