<?php
include "./header.php";

$lecat_id = isset($_GET['lecat_id']) ? (int)$_GET['lecat_id'] : 0;

if ($lecat_id == 0) {
    echo "Invalid category.";
    include "./footer.php";
    exit;
}

// Fetch last category details
$statement = $pdo->prepare("SELECT * FROM tbl_last_category WHERE lecat_id = ?");
$statement->execute([$lecat_id]);
$last_category = $statement->fetch(PDO::FETCH_ASSOC);

if (!$last_category) {
    echo "Category not found.";
    include "./footer.php";
    exit;
}

// Fetch end category details
$ecat_id = $last_category['ecat_id'];
$statement = $pdo->prepare("SELECT * FROM tbl_end_category WHERE ecat_id = ?");
$statement->execute([$ecat_id]);
$end_category = $statement->fetch(PDO::FETCH_ASSOC);

if (!$end_category) {
    echo "End-category not found.";
    include "./footer.php";
    exit;
}

// Fetch mid category details
$mcat_id = $end_category['mcat_id'];
$statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE mcat_id = ?");
$statement->execute([$mcat_id]);
$mid_category = $statement->fetch(PDO::FETCH_ASSOC);

if (!$mid_category) {
    echo "Mid-category not found.";
    include "./footer.php";
    exit;
}

// Initial load of products for the last category
$statement = $pdo->prepare("
    SELECT p.*, 
           IF(LENGTH(p.p_name) > 50, CONCAT(SUBSTRING(p.p_name, 1, 50), '...'), p.p_name) AS p_name, 
           (SELECT COUNT(*) FROM tbl_rating WHERE p_id = p.p_id) AS total_ratings, 
           (SELECT COUNT(*) FROM tbl_order WHERE p_id = p.p_id) AS total_sold
    FROM tbl_product p
    WHERE p.lecat_id = ?
");
$statement->execute([$lecat_id]);
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

// Count total products
$total_products = count($products);
?>

<div class="headerCategory">
    <nav class="breadcrumb">
        <a href="index.php">Home</a> /
        <a href="mid_category.php?mcat_id=<?php echo $mid_category['mcat_id']; ?>"><?php echo $mid_category['mcat_name']; ?></a> /
        <a href="end_category.php?ecat_id=<?php echo $end_category['ecat_id']; ?>"><?php echo $end_category['ecat_name']; ?></a> /
        <span><?php echo $last_category['lecat_name']; ?></span>
    </nav>

    <h1 class="midcategoryname"><?php echo $last_category['lecat_name']; ?></h1>

    <div class="filters">
        <select class="filter-dropdown" id="stock-status-filter">
            <option value="">Stock Status</option>
            <option value="in_stock">In Stock</option>
            <option value="out_of_stock">Out of Stock</option>
        </select>
        <select class="filter-dropdown" id="port-count-filter">
            <option value="">Port Count</option>
            <!-- Add port count options here -->
        </select>
        <button class="more-filters">More Filters</button>

        <div class="results-info">
            <span id="total-products"><?php echo $total_products; ?> Results</span>
            <select class="sort-dropdown" id="sort-dropdown" onchange="filterProducts()">
                <option value="newest">Sort by: Newest First</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="rating_desc">Rating: High to Low</option>
                <option value="popularity">Popularity</option>
            </select>
        </div>

        <div class="view-toggle">
            <button class="grid-view" onclick="toggleView('grid')">&#9783;</button>
            <button class="list-view" onclick="toggleView('list')">&#9776;</button>
        </div>
    </div>
</div>

<section class="software_section" id="product-list">
    <div class="row" id="filtered-products">
        <?php foreach ($products as $product) : ?>
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
        <?php endforeach; ?>
    </div>
</section>

<?php include "./footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function filterProducts() {
    var lecat_id = <?php echo $lecat_id; ?>;
    var stock_status = $('#stock-status-filter').val();
    var sort_order = $('#sort-dropdown').val();

    $.ajax({
        url: 'filter_products.php',
        type: 'POST',
        data: {
            lecat_id: lecat_id,
            stock_status: stock_status,
            sort_order: sort_order
        },
        success: function(response) {
            $('#filtered-products').html(response);
            $('#total-products').text(response.length + ' Results');
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status + ' - ' + error);
        }
    });
}

$(document).ready(function() {
    filterProducts(); // Initial load of products
    $('#stock-status-filter').on('change', filterProducts);
    $('#port-count-filter').on('change', filterProducts);
});

function sortProducts(sort_order) {
    $('#sort-dropdown').val(sort_order); // Update dropdown value
    filterProducts(); // Reload products with new sorting
}
</script>
