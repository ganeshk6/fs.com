<?php
include("./admin/inc/config.php");
include("./admin/inc/functions.php");
include("admin/inc/CSRF_Protect.php");
// Fetch POST data sent via AJAX
$lecat_id = isset($_POST['lecat_id']) ? (int)$_POST['lecat_id'] : 0;
$stock_status = isset($_POST['stock_status']) ? $_POST['stock_status'] : '';
$sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'newest';

// Construct SQL query based on filters
$sql = "SELECT p.*, 
               IF(LENGTH(p.p_name) > 50, CONCAT(SUBSTRING(p.p_name, 1, 50), '...'), p.p_name) AS p_name, 
               (SELECT COUNT(*) FROM tbl_rating WHERE p_id = p.p_id) AS total_ratings, 
               (SELECT COUNT(*) FROM tbl_order WHERE p_id = p.p_id) AS total_sold
        FROM tbl_product p
        WHERE 1";

$params = [];

// Apply category filter if specified
if ($lecat_id > 0) {
    $sql .= " AND p.lecat_id = ?";
    $params[] = $lecat_id;
}

// Apply stock status filter if specified
if ($stock_status === 'in_stock') {
    $sql .= " AND p.p_stock_quantity > 0";
} elseif ($stock_status === 'out_of_stock') {
    $sql .= " AND p.p_stock_quantity <= 0";
}

// Apply sorting order
switch ($sort_order) {
    case 'price_asc':
        $sql .= " ORDER BY p.p_current_price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY p.p_current_price DESC";
        break;
    case 'rating_desc':
        $sql .= " ORDER BY total_ratings DESC";
        break;
    case 'popularity':
        $sql .= " ORDER BY total_sold DESC";
        break;
    case 'newest':
    default:
        $sql .= " ORDER BY p.p_id DESC";
        break;
}

// Prepare and execute SQL query
$statement = $pdo->prepare($sql);
$statement->execute($params);
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

// Output HTML for filtered products
ob_start();
?>
<!-- <section class="software_section" id="product-list"> -->
<div class="row" id="filtered-products">
    <?php
foreach ($products as $product) {
    ?>
    <div class="col-md-3 cardMianContainer">
        <div class="card cardProducts">
            <div class="card-img-container">
                <img class="card-img-top" src="./assets/uploads/<?php echo $product['p_featured_photo']; ?>" alt="<?php echo $product['p_name']; ?>">
                <a href="#" class="add-to-cart-icon" title="Add to Cart"></a>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $product['p_name']; ?></h5>
                <p class="card-text" style="color: rgb(112, 112, 112); font-size: 16px;"><?php echo $product['p_feature']; ?></p>
                <p class="card-text" style="color: #19191a; font-size: 20px; font-weight: 600; line-height: 24px">US$<?php echo $product['p_current_price']; ?></p>
                <div style="display: flex; align-items: center; gap: 5px;">
                    <p class="card-text"><?php echo $product['total_ratings']; ?> Ratings |</p>
                    <p class="card-text"><?php echo $product['total_sold']; ?> Sold</p>
                </div>
            </div>
        </div>
    </div>
    
    <?php
}
?>
</div>
    <!-- </section> -->
    <?php
$html = ob_get_clean();

echo $html;
?>
