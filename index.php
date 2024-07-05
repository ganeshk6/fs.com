<?php include "./header.php" ?>

<div id="myCarousel" class="carousel slide" data-ride="carousel" style="padding: 0;">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php
        $i = 0;
        $statement = $pdo->prepare("SELECT * FROM tbl_slider");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
        ?>
            <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" <?php if ($i == 0) echo 'class="active"'; ?>></li>&nbsp;
        <?php
            $i++;
        }
        ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <?php
        $i = 0;
        foreach ($result as $row) {
        ?>
            <div class="item <?php if ($i == 0) echo 'active'; ?>">
                <img src="./admin/uploads/<?php echo $row['photo']; ?>" width="100%" alt="Slide <?php echo $i + 1; ?>" />
                <div class="slider_img">
                    <div class="carousel-caption d-none d-md-block">
                        <h1><?php echo $row['heading']; ?></h1>
                        <p><?php echo nl2br($row['content']); ?></p>
                        <a href="<?php echo $row['button_url']; ?>" target="_blank" class="btn btn-primary"><?php echo $row['button_text']; ?> <i class="fa-solid fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
        <?php
            $i++;
        }
        ?>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
</div>
<?php
// Fetch mid categories
$statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id = 9");
$statement->execute();
$mid_categories = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch end categories
$statement = $pdo->prepare("SELECT * FROM tbl_end_category");
$statement->execute();
$end_categories = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch last categories
$statement = $pdo->prepare("SELECT * FROM tbl_last_category");
$statement->execute();
$last_categories = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="software_section">
<div class="tabs">
        <?php foreach ($mid_categories as $mid_category): ?>
            <div class="tab" data-tab="<?php echo $mid_category['mcat_id']; ?>">
                <img src="./admin/uploads/mid_category/<?php echo $mid_category['mcat_img']; ?>" alt="" srcset="">
                <p class="contentTab">
                    <?php echo $mid_category['mcat_name']; ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

    <?php foreach ($mid_categories as $mid_category) : ?>
    <div id="tab-<?php echo $mid_category['mcat_id']; ?>" class="slider-container <?php echo $mid_category['mcat_id'] == 7 ? 'active' : ''; ?>">
        <div class="slick-slider">
            <?php
            // Filter end categories for current mid category
            $filtered_end_categories = array_filter($end_categories, function($end_category) use ($mid_category) {
                return $end_category['mcat_id'] == $mid_category['mcat_id'];
            });

            foreach ($filtered_end_categories as $end_category) :
                ?>
                <div class="card">
                    <a href="end_category.php?ecat_id=<?php echo $end_category['ecat_id']; ?>">
                        <img src="./admin/uploads/end_category/<?php echo $end_category['ecat_img']; ?>" alt="<?php echo $end_category['ecat_name']; ?>" style="height: 150px; width: 100%;">
                        <div class="card-content">
                            <h3><?php echo $end_category['ecat_name']; ?></h3>
                            <?php
                            // Filter last categories for current end category
                            $filtered_last_categories = array_filter($last_categories, function($last_category) use ($end_category) {
                                return $last_category['ecat_id'] == $end_category['ecat_id'];
                            });

                            foreach ($filtered_last_categories as $last_category) :
                                ?>
                                <a href="last_category.php?lecat_id=<?php echo $last_category['lecat_id']; ?>">
                                    <p style="margin-top: 10px;"><?php echo $last_category['lecat_name']; ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

</div>

<div class="software_section">
    <h3 style="text-align: center;">PicOS® Software Platform</h3>
    <div class="slick-slider">
        <?php
        $statement = $pdo->prepare("SELECT * FROM tbl_software_section");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
        ?>
            <a href="<?php echo $row['url']; ?>" class="card">
                <div class="image-container">
                    <img src="./admin/uploads/<?php echo $row['image']; ?>" alt="Server Room">
                    <div class="overlay">
                        <h2><?php echo $row['img_heading']; ?></h2>
                    </div>
                </div>
                <div class="card-content">
                    <h3><?php echo $row['soft_type']; ?></h3>
                    <p><?php echo $row['soft_content']; ?></p>
                </div>
            </a>
        <?php
        }
        ?>
    </div>
</div>
<div class="software_section">
    <h3 style="text-align: center;">Feature Solutions</h3>
    <div class="slick-slider">
        <?php
        $statement = $pdo->prepare("SELECT * FROM tbl_feature_solutions");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
        ?>
            <a href="<?php echo $row['url']; ?>" class="card">
                <div class="image-container">
                    <img src="./admin/uploads/<?php echo $row['image']; ?>" alt="Feature Solution">
                </div>
                <div class="card-content">
                    <h3><?php echo $row['feat_type']; ?></h3>
                    <p><?php echo $row['feat_content']; ?></p>

                </div>
            </a>
        <?php
        }
        ?>
    </div>
    <!-- FS Certified section   -->
</div>
<div class="fullwidth">
    <h3 style="text-align: center;">FS Certified</h3>
    <div class="certificate_section">
        <?php
        $statement = $pdo->prepare("SELECT * FROM tbl_fs_certified");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
        ?>
            <a href="<?php echo $row['url']; ?>" class="card cardCerificate">
                <div class="icon">
                    <?php echo $row['cert_icon']; ?>
                </div>
                <h2><?php echo $row['cert_name']; ?></h2>
                <p><?php echo $row['cert_content']; ?></p>
            </a>
        <?php
        }
        ?>
    </div>
</div>


<div class="software_section">
    <h3 style="text-align: center;">Case Studies</h3>
    <div class="slick-slider3">
        <?php
        $statement = $pdo->prepare("SELECT * FROM tbl_case_studies");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
        ?>
            <a href="<?php echo $row['url']; ?>" class="card">
                <img src="./admin/<?php echo $row['study_img']; ?>" alt="<?php echo $row['study_heading']; ?>" class="card-image">
                <div class="card-content">
                    <div class="country">
                        <img src="./admin/<?php echo $row['country_flag']; ?>" alt="<?php echo $row['country_name']; ?> flag" class="flag-icon">
                        <span><?php echo $row['country_name']; ?></span>
                    </div>
                    <h2><?php echo $row['study_heading']; ?></h2>
                    <div class="tags">
                        <?php
                        $tags = explode(',', $row['study_content']);
                        foreach ($tags as $tag) {
                        ?>
                            <span class="tag"><?php echo trim($tag); ?> |</span>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </a>
        <?php
        }
        ?>
    </div>

</div>

</div>

<div class="fullwidth">
    <div class="certificate_section">
        <div class="card lastsection">
            <div class="card-content">
                <div class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 3L3 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M3 3L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="text">
                    <h3>Project Inquiry <span class="arrow">></span></h3>
                    <p>Customized technical support to meet different requests.</p>
                </div>
            </div>
        </div>
        <div class="card lastsection">
            <div class="card-content">
                <div class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 3L3 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M3 3L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="text">
                    <h3>Project Inquiry <span class="arrow">></span></h3>
                    <p>Customized technical support to meet different requests.</p>
                </div>
            </div>
        </div>
        <div class="card lastsection">
            <div class="card-content">
                <div class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 3L3 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M3 3L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="text">
                    <h3>Project Inquiry <span class="arrow">></span></h3>
                    <p>Customized technical support to meet different requests.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "./footer.php" ?>