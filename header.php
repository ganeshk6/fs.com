<style>
  .right-menus {
    width: 700px;
  }
</style>
<?php
// Include config.php to get $pdo object
include("./admin/inc/config.php");
include("./admin/inc/functions.php");
include("admin/inc/CSRF_Protect.php");

$csrf = new CSRF_Protect();
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';
// Function to fetch categories HTML
function fetchCategories($pdo)
{
  // Initialize categories HTML variable
  $categories_html = '';

  // Query to fetch top categories
  $statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu = 1");
  $statement->execute();
  $top_categories = $statement->fetchAll(PDO::FETCH_ASSOC);

  // Loop through top categories
  foreach ($top_categories as $top_category) {
    $tcat_id = $top_category['tcat_id'];
    $tcat_name = $top_category['tcat_name'];

    // Start building HTML for top category
    $categories_html .= '<li class="nav-item dropdown">';
    $categories_html .= '<a href="#" class="nav-link">' . $tcat_name . '</a>';

    // Query to fetch mid categories for current top category
    $statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id = ?");
    $statement->execute([$tcat_id]);
    $mid_categories = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are mid categories
    if ($mid_categories) {
      $categories_html .= '<div class="dropdown-content">';
      $categories_html .= '<div class="left-menu">';

      // Loop through mid categories
      foreach ($mid_categories as $mid_category) {
        $mcat_id = $mid_category['mcat_id'];
        $mcat_name = $mid_category['mcat_name'];

        // Start building HTML for mid category in left menu
        $categories_html .= '<a href="#" class="submenu-link" data-target="midcat_' . $mcat_id . '">' . $mcat_name . '</a>';
      }

      $categories_html .= '</div>'; // Close left-menu

      // Now, we add the right content area for end categories
      $categories_html .= '<div class="right-menus">';

      // Loop through mid categories again to add their end categories in the right content area
      foreach ($mid_categories as $mid_category) {
        $mcat_id = $mid_category['mcat_id'];

        // Query to fetch end categories for current mid category
        $statement = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id = ?");
        $statement->execute([$mcat_id]);
        $end_categories = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($end_categories) {
          $categories_html .= '<div id="midcat_' . $mcat_id . '" class="submenu-content">';

          // Loop through end categories
          foreach ($end_categories as $end_category) {
            $ecat_id = $end_category['ecat_id'];
            $ecat_name = $end_category['ecat_name'];
            $ecat_img = $end_category['ecat_img'];

            // Build HTML for end category
            $categories_html .= '<div class="end-category">';
            $categories_html .= '<img src="admin/uploads/end_category/' . $ecat_img . '" alt="' . $ecat_name . '" style="width: 70px; height: auto;">';
            $categories_html .= '<div class="end-category-name">' . $ecat_name . '</div>';
            // Query to fetch last categories for current end category
            $statement = $pdo->prepare("SELECT * FROM tbl_last_category WHERE ecat_id = ?");
            $statement->execute([$ecat_id]);
            $last_categories = $statement->fetchAll(PDO::FETCH_ASSOC);
            // Check if there are last categories
            if ($last_categories) {
              $categories_html .= '<div class="last-categories">';
              // Loop through last categories
              foreach ($last_categories as $last_category) {
                $lecat_name = $last_category['lecat_name'];

                // Build HTML for last category
                $categories_html .= '<p class="last-category-name">' . $lecat_name . '</p>';
              }

              $categories_html .= '</div>'; // Close last-categories
            }
            $categories_html .= '</div>'; // Close end-category
          }

          $categories_html .= '</div>'; // Close submenu-content for current mid category
        }
      }

      $categories_html .= '</div>'; // Close right-menus

      $categories_html .= '</div>'; // Close dropdown-content
    }

    $categories_html .= '</li>'; // Close nav-item dropdown
  }

  return $categories_html;
}

// Fetching categories HTML using above function
try {
  $categories_html = fetchCategories($pdo);
} catch (PDOException $e) {
  echo "Error fetching categories: " . $e->getMessage();
}

$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row)
{
	$logo = $row['logo'];
	$favicon = $row['favicon'];
	$contact_email = $row['contact_email'];
	$contact_phone = $row['contact_phone'];
	$meta_title_home = $row['meta_title_home'];
    $meta_keyword_home = $row['meta_keyword_home'];
    $meta_description_home = $row['meta_description_home'];
    $before_head = $row['before_head'];
    $after_body = $row['after_body'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php

	$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$about_meta_title = $row['about_meta_title'];
		$about_meta_keyword = $row['about_meta_keyword'];
		$about_meta_description = $row['about_meta_description'];
		$faq_meta_title = $row['faq_meta_title'];
		$faq_meta_keyword = $row['faq_meta_keyword'];
		$faq_meta_description = $row['faq_meta_description'];
		$blog_meta_title = $row['blog_meta_title'];
		$blog_meta_keyword = $row['blog_meta_keyword'];
		$blog_meta_description = $row['blog_meta_description'];
		$contact_meta_title = $row['contact_meta_title'];
		$contact_meta_keyword = $row['contact_meta_keyword'];
		$contact_meta_description = $row['contact_meta_description'];
		$pgallery_meta_title = $row['pgallery_meta_title'];
		$pgallery_meta_keyword = $row['pgallery_meta_keyword'];
		$pgallery_meta_description = $row['pgallery_meta_description'];
		$vgallery_meta_title = $row['vgallery_meta_title'];
		$vgallery_meta_keyword = $row['vgallery_meta_keyword'];
		$vgallery_meta_description = $row['vgallery_meta_description'];
	}

	$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	
	if($cur_page == 'index.php' || $cur_page == 'login.php' || $cur_page == 'registration.php' || $cur_page == 'cart.php' || $cur_page == 'checkout.php' || $cur_page == 'forget-password.php' || $cur_page == 'reset-password.php' || $cur_page == 'product-category.php' || $cur_page == 'product.php') {
		?>
		<title><?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
		<?php
	}

	if($cur_page == 'about.php') {
		?>
		<title><?php echo $about_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $about_meta_keyword; ?>">
		<meta name="description" content="<?php echo $about_meta_description; ?>">
		<?php
	}
	if($cur_page == 'faq.php') {
		?>
		<title><?php echo $faq_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $faq_meta_keyword; ?>">
		<meta name="description" content="<?php echo $faq_meta_description; ?>">
		<?php
	}
	if($cur_page == 'contact.php') {
		?>
		<title><?php echo $contact_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $contact_meta_keyword; ?>">
		<meta name="description" content="<?php echo $contact_meta_description; ?>">
		<?php
	}
	if($cur_page == 'product.php')
	{
		$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
		foreach ($result as $row) 
		{
		    $og_photo = $row['p_featured_photo'];
		    $og_title = $row['p_name'];
		    $og_slug = 'product.php?id='.$_REQUEST['id'];
			$og_description = substr(strip_tags($row['p_description']),0,200).'...';
		}
	}

	if($cur_page == 'dashboard.php') {
		?>
		<title>Dashboard - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
		<?php
	}
	if($cur_page == 'customer-profile-update.php') {
		?>
		<title>Update Profile - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
		<?php
	}
	if($cur_page == 'customer-billing-shipping-update.php') {
		?>
		<title>Update Billing and Shipping Info - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
		<?php
	}
	if($cur_page == 'customer-password-update.php') {
		?>
		<title>Update Password - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
		<?php
	}
	if($cur_page == 'customer-order.php') {
		?>
		<title>Orders - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
		<?php
	}
	?>
  <link rel="icon" type="image/png" href="assets/uploads/<?php echo $favicon; ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />   
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
  <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
  <header class="header">
    <div class="header-container">
      <div class="left-side">
        <div class="logo">
        <a href="index.php"><img src="assets/uploads/<?php echo $logo; ?>" alt="logo image"></a>
        </div>
        <nav class="navigation">
          <ul class="nav-list">
            <?php echo $categories_html; ?> <!-- Ensure $categories_html is defined here -->
          </ul>
        </nav>
      </div>
      <div class="nav-icons">
        <a href="#" class="nav-icon-link"><i class="uil uil-search" id="searchIcon"></i></a>
        <a href="#" class="nav-icon-link"><i class="uil uil-shopping-cart" id="cartIcon"></i></a>
        <a href="#" class="nav-icon-link"><i class="uil uil-user" id="userIcon"></i></a>
      </div>
    </div>
  </header>