<?php require_once('header.php'); ?>

<?php
// Preventing direct access of this page.
if (!isset($_REQUEST['id'])) {
    header('location: logout.php');
    exit;
} else {
    // Check if the id is valid
    $statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE mcat_id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    if ($total == 0) {
        header('location: logout.php');
        exit;
    } else {
        // Get the image file name
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $mcat_img = $result['mcat_img'];
        if ($mcat_img != '') {
            unlink('uploads/mid_category/' . $mcat_img);
        }
    }
}
?>

<?php
// Initialize the ecat_ids array
$ecat_ids = array();

// Getting all ecat ids
$statement = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id=?");
$statement->execute(array($_REQUEST['id']));
$total = $statement->rowCount();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $ecat_ids[] = $row['ecat_id'];
}

if (!empty($ecat_ids)) {
    // Initialize the p_ids array
    $p_ids = array();

    for ($i = 0; $i < count($ecat_ids); $i++) {
        $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE lecat_id=?");
        $statement->execute(array($ecat_ids[$i]));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $p_ids[] = $row['p_id'];
        }
    }

    if (!empty($p_ids)) {
        for ($i = 0; $i < count($p_ids); $i++) {

            // Getting photo ID to unlink from folder
            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
            $statement->execute(array($p_ids[$i]));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $p_featured_photo = $row['p_featured_photo'];
                unlink('../assets/uploads/' . $p_featured_photo);
            }

            // Getting other photo ID to unlink from folder
            $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
            $statement->execute(array($p_ids[$i]));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $photo = $row['photo'];
                unlink('../assets/uploads/product_photos/' . $photo);
            }

            // Delete from tbl_product
            $statement = $pdo->prepare("DELETE FROM tbl_product WHERE p_id=?");
            $statement->execute(array($p_ids[$i]));

            // Delete from tbl_product_photo
            $statement = $pdo->prepare("DELETE FROM tbl_product_photo WHERE p_id=?");
            $statement->execute(array($p_ids[$i]));

            // Delete from tbl_product_size
            $statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
            $statement->execute(array($p_ids[$i]));

            // Delete from tbl_product_color
            $statement = $pdo->prepare("DELETE FROM tbl_product_color WHERE p_id=?");
            $statement->execute(array($p_ids[$i]));

            // Delete from tbl_rating
            $statement = $pdo->prepare("DELETE FROM tbl_rating WHERE p_id=?");
            $statement->execute(array($p_ids[$i]));

            // Delete from tbl_payment
            $statement = $pdo->prepare("SELECT * FROM tbl_order WHERE product_id=?");
            $statement->execute(array($p_ids[$i]));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $statement1 = $pdo->prepare("DELETE FROM tbl_payment WHERE payment_id=?");
                $statement1->execute(array($row['payment_id']));
            }

            // Delete from tbl_order
            $statement = $pdo->prepare("DELETE FROM tbl_order WHERE product_id=?");
            $statement->execute(array($p_ids[$i]));
        }
    }

    // Delete from tbl_end_category
    for ($i = 0; $i < count($ecat_ids); $i++) {
        // First, delete any dependent records from tbl_last_category
        $statement = $pdo->prepare("DELETE FROM tbl_last_category WHERE ecat_id=?");
        $statement->execute(array($ecat_ids[$i]));

        // Now delete the end category
        $statement = $pdo->prepare("DELETE FROM tbl_end_category WHERE ecat_id=?");
        $statement->execute(array($ecat_ids[$i]));
    }
}

// Delete from tbl_mid_category
$statement = $pdo->prepare("DELETE FROM tbl_mid_category WHERE mcat_id=?");
$statement->execute(array($_REQUEST['id']));

header('location: mid-category.php');
?>
