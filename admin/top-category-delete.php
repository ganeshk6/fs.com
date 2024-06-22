<?php require_once('header.php'); ?>

<?php
// Preventing the direct access of this page.
if (!isset($_REQUEST['id'])) {
    header('location: logout.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE tcat_id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    if ($total == 0) {
        header('location: logout.php');
        exit;
    }
}
?>

<?php
// Initialize arrays
$ecat_ids = array();
$p_ids = array();

// Getting all end category ids
$statement = $pdo->prepare("SELECT t3.ecat_id 
                            FROM tbl_top_category t1
                            JOIN tbl_mid_category t2 ON t1.tcat_id = t2.tcat_id
                            JOIN tbl_end_category t3 ON t2.mcat_id = t3.mcat_id
                            WHERE t1.tcat_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $ecat_ids[] = $row['ecat_id'];
}

if (!empty($ecat_ids)) {
    // Getting all product ids for each end category id
    foreach ($ecat_ids as $ecat_id) {
        $statement = $pdo->prepare("SELECT p_id FROM tbl_product WHERE lecat_id=?");
        $statement->execute(array($ecat_id));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $p_ids[] = $row['p_id'];
        }
    }

    if (!empty($p_ids)) {
        foreach ($p_ids as $p_id) {
            // Unlink photos associated with the product
            $statement = $pdo->prepare("SELECT p_featured_photo FROM tbl_product WHERE p_id=?");
            $statement->execute(array($p_id));
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $p_featured_photo = $result['p_featured_photo'];
                if (file_exists('../assets/uploads/' . $p_featured_photo)) {
                    unlink('../assets/uploads/' . $p_featured_photo);
                }
            }

            $statement = $pdo->prepare("SELECT photo FROM tbl_product_photo WHERE p_id=?");
            $statement->execute(array($p_id));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $photo = $row['photo'];
                if (file_exists('../assets/uploads/product_photos/' . $photo)) {
                    unlink('../assets/uploads/product_photos/' . $photo);
                }
            }

            // Delete associated records
            $statement = $pdo->prepare("DELETE FROM tbl_product WHERE p_id=?");
            $statement->execute(array($p_id));

            $statement = $pdo->prepare("DELETE FROM tbl_product_photo WHERE p_id=?");
            $statement->execute(array($p_id));

            $statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
            $statement->execute(array($p_id));

            $statement = $pdo->prepare("DELETE FROM tbl_product_color WHERE p_id=?");
            $statement->execute(array($p_id));

            $statement = $pdo->prepare("DELETE FROM tbl_rating WHERE p_id=?");
            $statement->execute(array($p_id));

            // Delete from tbl_payment via tbl_order
            $statement = $pdo->prepare("SELECT payment_id FROM tbl_order WHERE product_id=?");
            $statement->execute(array($p_id));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $statement1 = $pdo->prepare("DELETE FROM tbl_payment WHERE payment_id=?");
                $statement1->execute(array($row['payment_id']));
            }

            $statement = $pdo->prepare("DELETE FROM tbl_order WHERE product_id=?");
            $statement->execute(array($p_id));
        }
    }

    // Delete from tbl_end_category
    foreach ($ecat_ids as $ecat_id) {
        // First, delete any dependent records from tbl_last_category
        $statement = $pdo->prepare("DELETE FROM tbl_last_category WHERE ecat_id=?");
        $statement->execute(array($ecat_id));

        // Now delete the end category
        $statement = $pdo->prepare("DELETE FROM tbl_end_category WHERE ecat_id=?");
        $statement->execute(array($ecat_id));
    }
}

// Get all mid category ids associated with the top category
$statement = $pdo->prepare("SELECT mcat_id FROM tbl_mid_category WHERE tcat_id=?");
$statement->execute(array($_REQUEST['id']));
$mid_categories = $statement->fetchAll(PDO::FETCH_ASSOC);

// Delete from tbl_mid_category
foreach ($mid_categories as $mid_category) {
    $statement = $pdo->prepare("DELETE FROM tbl_mid_category WHERE mcat_id=?");
    $statement->execute(array($mid_category['mcat_id']));
}

// Delete from tbl_top_category
$statement = $pdo->prepare("DELETE FROM tbl_top_category WHERE tcat_id=?");
$statement->execute(array($_REQUEST['id']));

header('location: top-category.php');
?>
