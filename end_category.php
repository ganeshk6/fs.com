<?php
include "./header.php";

$ecat_id = isset($_GET['ecat_id']) ? (int)$_GET['ecat_id'] : 0;

if ($ecat_id == 0) {
    echo "Invalid category.";
    include "./footer.php";
    exit;
}

// Fetch end category details
$statement = $pdo->prepare("SELECT * FROM tbl_end_category WHERE ecat_id = ?");
$statement->execute([$ecat_id]);
$end_category = $statement->fetch(PDO::FETCH_ASSOC);

if (!$end_category) {
    echo "Category not found.";
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

// Fetch last categories for the end category
$statement = $pdo->prepare("SELECT * FROM tbl_last_category WHERE ecat_id = ?");
$statement->execute([$ecat_id]);
$last_categories = $statement->fetchAll(PDO::FETCH_ASSOC);

if (!$last_categories) {
    echo "No subcategories found.";
    include "./footer.php";
    exit;
}

// Collect all last category IDs
$last_category_ids = array_map(function ($last_category) {
    return $last_category['lecat_id'];
}, $last_categories);

if (empty($last_category_ids)) {
    echo "No products found.";
    include "./footer.php";
    exit;
}

// Fetch products for the collected last category IDs (initial load)
$placeholders = str_repeat('?,', count($last_category_ids) - 1) . '?';
$statement = $pdo->prepare("
    SELECT p.*, 
           IF(LENGTH(p.p_name) > 50, CONCAT(SUBSTRING(p.p_name, 1, 50), '...'), p.p_name) AS p_name, 
           (SELECT COUNT(*) FROM tbl_rating WHERE p_id = p.p_id) AS total_ratings, 
           (SELECT COUNT(*) FROM tbl_order WHERE p_id = p.p_id) AS total_sold
    FROM tbl_product p
    WHERE p.lecat_id IN ($placeholders)
");
$statement->execute($last_category_ids);
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

// Count total products
$total_products = count($products);
?>

<div class="headerCategory">
    <nav class="breadcrumb">
        <a href="index.php">Home</a> /
        <a href="mid_category.php?mcat_id=<?php echo $mid_category['mcat_id']; ?>"><?php echo $mid_category['mcat_name']; ?></a> /
        <span><?php echo $end_category['ecat_name']; ?></span>
    </nav>

    <h1 class="midcategoryname"><?php echo $end_category['ecat_name']; ?></h1>

    <div class="slider">
        <div class="slide">
            <img class="slideImg" src="./image/a1.jpg" alt="Cloud-based Networks">
            <div class="slide-content">
                <h2>Cloud-based Networks Grow with Your Business</h2>
                <a href="#" class="learn-more">Learn more &gt;</a>
            </div>
        </div>
    </div>

    <div class="filters">
        <select class="filter-dropdown" id="category-filter">
            <option value="0">All Categories</option>
            <?php foreach ($last_categories as $last_category) : ?>
                <option value="<?php echo $last_category['lecat_id']; ?>"><?php echo $last_category['lecat_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <select class="filter-dropdown" id="stock-status-filter">
            <option value="">Stock Status</option>
            <option value="in_stock">In Stock</option>
            <option value="out_of_stock">Out of Stock</option>
        </select>
        <!-- Add more filters as needed -->

        <button class="more-filters">More Filters</button>

        <div class="results-info">
            <span id="total-products"><?php echo $total_products; ?> Results</span>
            <select class="sort-dropdown" id="sort-dropdown">
                <option value="newest">Sort by: Newest First</option>
                <option value="price_asc">Price: High to Low</option>
                <option value="price_desc">Price: Low to High</option>
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
    <div class="row">
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

<script>
    // Function to filter and sort products
    function filterAndSortProducts(categoryId, stockStatus, sortOrder) {
        $.ajax({
            url: 'filter_products.php',
            type: 'POST',
            data: {
                lecat_id: categoryId,
                stock_status: stockStatus,
                sort_order: sortOrder
            },
            success: function(response) {
                $('#product-list').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching filtered products:', error);
            }
        });
    }

    // Function to handle category filter change
    $('#category-filter').change(function() {
        var categoryId = $(this).val();
        var stockStatus = $('#stock-status-filter').val();
        var sortOrder = $('#sort-dropdown').val();
        filterAndSortProducts(categoryId, stockStatus, sortOrder);
    });

    // Function to handle stock status filter change
    $('#stock-status-filter').change(function() {
        var categoryId = $('#category-filter').val();
        var stockStatus = $(this).val();
        var sortOrder = $('#sort-dropdown').val();
        filterAndSortProducts(categoryId, stockStatus, sortOrder);
    });

    // Function to handle sort dropdown change
    $('#sort-dropdown').change(function() {
        var categoryId = $('#category-filter').val();
        var stockStatus = $('#stock-status-filter').val();
        var sortOrder = $(this).val();
        filterAndSortProducts(categoryId, stockStatus, sortOrder);
    });

    // Function to toggle between grid and list view (if needed)
    function toggleView(viewType) {
        // Implement toggle view functionality if required
    }
</script>
