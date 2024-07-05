<?php include "./header.php"; ?>

<?php
// Fetch all mid categories
$statement = $pdo->prepare("SELECT * FROM tbl_mid_category");
$statement->execute();
$mid_categories = $statement->fetchAll(PDO::FETCH_ASSOC);

$selected_mid_category = null;

if (isset($_GET['mcat_id'])) {
    $mcat_id = $_GET['mcat_id'];

    // Fetch the selected mid category details
    $statement = $pdo->prepare("SELECT mcat_name, mcat_bg_img FROM tbl_mid_category WHERE mcat_id = ?");
    $statement->execute([$mcat_id]);
    $selected_mid_category = $statement->fetch(PDO::FETCH_ASSOC);

    // Fetch end categories based on the mid category ID
    $statement = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id = ?");
    $statement->execute([$mcat_id]);
    $end_categories = $statement->fetchAll(PDO::FETCH_ASSOC);

    $products = [];

    if (!empty($end_categories)) {
        $end_category_ids = array_column($end_categories, 'ecat_id');
        $placeholders = rtrim(str_repeat('?,', count($end_category_ids)), ',');

        // Fetch last categories based on the end category IDs
        $statement = $pdo->prepare("SELECT lecat_id FROM tbl_last_category WHERE ecat_id IN ($placeholders)");
        $statement->execute($end_category_ids);
        $last_categories = $statement->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($last_categories)) {
            // Fetch products based on the last category IDs
            $placeholders = rtrim(str_repeat('?,', count($last_categories)), ',');
            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE lecat_id IN ($placeholders)");
            $statement->execute($last_categories);
            $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    } else {
        $products = [];
    }
} else {
    echo "No mid-category selected.";
    exit;
}
?>
<div class="banner">
    <img src="./admin/uploads/mid_category/<?php echo htmlspecialchars($selected_mid_category['mcat_bg_img']); ?>" alt="Background Image">
    <div class="overlaycontent">
        <h1><?php echo htmlspecialchars($selected_mid_category['mcat_name']); ?></h1>
    </div>
</div>

<div class="midcategory">
    <h2 style="text-align: center; color: #333;">Why FS Switching</h2>
    <p style="text-align: center;">We are committed to providing high-performing switching products through professional and reliable capabilities.</p>
    <div class="certificate_section">
        <div class="card cardMid">
            <div class="icon">
                <svg height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="#000000" stroke-width="1"></path>
                        <path d="M9 16C9.85038 16.6303 10.8846 17 12 17C13.1154 17 14.1496 16.6303 15 16" stroke="#000000" stroke-width="1" stroke-linecap="round"></path>
                    </g>
                </svg>
            </div>
            <h4>FS's Labs</h4>
            <p>Explore the most extensive and stringent testing and solution designing processes.</p>
            <div class="bottomlink">
                <a href="#">Explore Test Lab </a>
                <span><svg height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg></span>
            </div>
        </div>
        <div class="card cardMid">
            <div class="icon">
                <svg height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="#000000" stroke-width="1"></path>
                        <path d="M9 16C9.85038 16.6303 10.8846 17 12 17C13.1154 17 14.1496 16.6303 15 16" stroke="#000000" stroke-width="1" stroke-linecap="round"></path>
                    </g>
                </svg>
            </div>
            <h4>FS's Labs</h4>
            <p>Explore the most extensive and stringent testing and solution designing processes.</p>
            <div class="bottomlink">
                <a href="#">Explore Test Lab </a>
                <span><svg height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg></span>
            </div>
        </div>
        <div class="card cardMid">
            <div class="icon">
                <svg height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z" stroke="#000000" stroke-width="1"></path>
                        <path d="M9 16C9.85038 16.6303 10.8846 17 12 17C13.1154 17 14.1496 16.6303 15 16" stroke="#000000" stroke-width="1" stroke-linecap="round"></path>
                    </g>
                </svg>
            </div>
            <h4>FS's Labs</h4>
            <p>Explore the most extensive and stringent testing and solution designing processes.</p>
            <div class="bottomlink">
                <a href="#">Explore Test Lab </a>
                <span><svg height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg></span>
            </div>
        </div>
    </div>

    <h2 style="text-align: center; color: #333;">Inside FS's Labs</h2>
    <p class="subtitle">FS's labs combine one of the most extensive and stringent testing processes to ensure quality.</p>
    <div class="content">
        <div class="video-container">
            <img src="./image/a2.jpg" alt="Lab Technician working on network equipment">
            <div style="text-align: center;">▶ 01:52</div>
        </div>
        <div class="features">
            <div class="feature">
                <img src="./image/videpsideicon.svg" alt="Network Icon" class="feature-icon">
                <span class="feature-text">Multiple Testing Scenarios</span>
            </div>
            <div class="feature" style="border-right: 0;">
                <img src="./image/videpsideicon2.svg" alt="Process Icon" class="feature-icon">
                <span class="feature-text">Standard Processes</span>
            </div>
            <div class="feature" style="border-bottom: 0;">
                <img src="./image/videpsideicon3.svg" alt="Test Icon" class="feature-icon">
                <span class="feature-text">Comprehensive Test Items</span>
            </div>
            <div class="feature" style="border-right: 0; border-bottom: 0; ">
                <img src="./image/videpsideicon4.svg" alt="Equipment Icon" class="feature-icon">
                <span class="feature-text">Top Test Equipment</span>
            </div>
        </div>
    </div>

    <div class="solutionsectionMid">
        <h2 style="text-align: center; color: #333;">Solution Design Capabilities</h2>
        <p class="subtitle">FS can provide a wide range of solutions with a focus on customer satisfaction, quality, and cost management.</p>
        <div class="process-flow">
            <div class="process-item">
                <div class="process-icon">
                    <img src="./image/solution1.svg" alt="" srcset="">
                </div>
                <span>Demand Reception</span>
            </div>
            <span class="arrow">→</span>
            <div class="process-item">
                <div class="process-icon">
                    <img src="./image/solution2.svg" alt="" srcset="">
                </div>
                <span>Solution Design</span>
            </div>
            <span class="arrow">→</span>
            <div class="process-item">
                <div class="process-icon">
                    <img src="./image/solution3.svg" alt="" srcset="">
                </div>
                <span>Solution Testing</span>
            </div>
            <span class="arrow">→</span>
            <div class="process-item">
                <div class="process-icon">
                    <img src="./image/solution4.svg" alt="" srcset="">
                </div>
                <span>Customer Feedback</span>
            </div>
        </div>
        <button class="button">Start Solution Design</button>
    </div>

    <div class="testingsectionmid">
        <h2 style="text-align: center; color: #333;">Testing Capabilities</h2>
        <p class="subtitle">Professional test equipment, standard test processes and experienced test engineers cover all testing needs of our products.</p>
        <div class="slick-slider3">
            <div class="card">
                <img src="./image/a1.jpg" alt="fds" class="card-image">
                <div class="card-content">
                    <h2> Performance Test</h2>
                    <p>Ensure product parameters and performance meet the corresponding requirements with professional testing equipment and platforms.</p>
                    <div class="textinglink" style="text-align: start;">
                        <a href="#">Explore Test Lab </a>
                        <span><svg height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </svg></span>
                    </div>
                </div>
            </div>
            <div class="card">
                <img src="./image/a1.jpg" alt="fds" class="card-image">
                <div class="card-content">
                    <h2> Performance Test</h2>
                    <p>Ensure product parameters and performance meet the corresponding requirements with professional testing equipment and platforms.</p>
                    <div class="textinglink" style="text-align: start;">
                        <a href="#">Explore Test Lab </a>
                        <span><svg height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </svg></span>
                    </div>
                </div>
            </div>
            <div class="card">
                <img src="./image/a1.jpg" alt="fds" class="card-image">
                <div class="card-content">
                    <h2> Performance Test</h2>
                    <p>Ensure product parameters and performance meet the corresponding requirements with professional testing equipment and platforms.</p>
                    <div class="textinglink" style="text-align: start;">
                        <a href="#">Explore Test Lab </a>
                        <span><svg height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </svg></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="midproductsection">
        <h2 style="text-align: center; color: #333;">Products</h2>
        <p class="subtitle">You can explore a wide range of products and find the best one for you.</p>

        <section class="contents" style="padding: 20px;">
            <div class="row">
                <div class="col-md-12">
                    <?php if (!empty($end_categories)) : ?>
                        <div class="row">
                            <?php foreach ($end_categories as $end_category) : ?>
                                <div class="col-md-3">
                                    <div class="card">
                                        <img src="admin/uploads/end_category/<?php echo htmlspecialchars($end_category['ecat_img']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($end_category['ecat_name']); ?>" style="width: 100%; height: 250px;">
                                        <div class="card-body" style="padding: 15px;">
                                            <h5 class="card-title"><?php echo htmlspecialchars($end_category['ecat_name']); ?></h5>
                                            <p><?php echo htmlspecialchars($end_category['ecat_desc']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p>No end categories found for this mid-category.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>


    <div class="customerSucces">
        <h2 style="text-align: center; color: #333;">Customer Success</h2>
        <div class="slick-slider3">
            <div class="card">
                <img src="./image/a1.jpg" alt="fds" class="card-image">
                <div class="card-content">
                    <div class="country">
                        <img src="./image/usa.svg" alt="usa flag" class="flag-icon" style="height: 20px; width: 20px;">
                        <span>Internet Service</span>
                        <span>United States</span>
                    </div>
                    <h2>10G Network Upgrade Between Two Buildings</h2>
                    <div class="textinglink" style="text-align: start;">
                        <a href="#">Read more </a>
                        <span><svg height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </svg></span>
                    </div>
                </div>
            </div>
            <div class="card">
                <img src="./image/a1.jpg" alt="fds" class="card-image">
                <div class="card-content">
                    <div class="country">
                        <img src="./image/usa.svg" alt="usa flag" class="flag-icon" style="height: 20px; width: 20px;">
                        <span>Internet Service</span>
                        <span>United States</span>
                    </div>
                    <h2>NSK GmbH & Co. KG Successfully Built Enterprise Network with FS Switches</h2>
                    <div class="textinglink" style="text-align: start;">
                        <a href="#">Read more </a>
                        <span><svg height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </svg></span>
                    </div>
                </div>
            </div>
            <div class="card">
                <img src="./image/a1.jpg" alt="fds" class="card-image">
                <div class="card-content">
                    <div class="country">
                        <img src="./image/usa.svg" alt="usa flag" class="flag-icon" style="height: 20px; width: 20px;">
                        <span>Internet Service</span>
                        <span>United States</span>
                    </div>
                    <h2>Italian Information Technology Company Successfully Realizes Data Center</h2>
                    <div class="textinglink" style="text-align: start;">
                        <a href="#">Read more </a>
                        <span><svg height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </svg></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="testimonial-section">
        <blockquote class="testimonial">
            <div class="testimonialText">
                <p>
                    <span class="quote-icon"><img src="./image/testimonial1.svg" alt="" srcset=""></span>
                    I have to say all of the technicians I order material for love the POE switches I buy from you all. They never have any problems with the POE switches I purchase from you all. They say they are fantastic and that the price for them is not too bad either. Also the wait time for them is pretty good as well. We normally receive them within a few days of us placing the order for them. Just all around wonderful service! I like ordering things from your website, it is very user friendly, I have to say.
                </p>
                <cite>— Chantay, Purchasing Manager</cite>
            </div>
            <img src="./image/testimonial.svg" alt="" srcset="">
        </blockquote>
    </div>
</div>
<div class="info-section">
    <div class="infosectionstart">
        <h2>Questions? <br> We're here to help.</h2>
        <div class="support-section">
            <div class="support-item">
                <div class="icon">🛠️</div>
                <div class="text">
                    <h3>Free Tech Support</h3>
                    <p>Get expert advice</p>
                </div>
            </div>
            <div class="support-item">
                <div class="icon">📄</div>
                <div class="text">
                    <h3>Free Solution Design</h3>
                    <p>Explore how ideas realized</p>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include "./footer.php"; ?>