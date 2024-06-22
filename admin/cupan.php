<?php require_once('header.php'); ?>

<style>
    .card {
      background-color: #f2f2f2;
      border-radius: 8px;
      padding: 16px;
      margin-bottom: 16px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      
    }

    .card-content {
      flex-grow: 1;
    }

    .card-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 8px;
    }

    .card-subtitle {
      font-size: 14px;
      color: #666;
      margin-bottom: 8px;
    }

    .card-info {
      font-size: 12px;
      color: #999;
    }


  </style>


<div style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 2rem;">
  
    <div class="container mt-5">
        <div style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 2rem;">
         <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="active"><a href="#allTab" data-toggle="tab">All</a></li>
        <li><a href="#flat" data-toggle="tab">Flat</a></li>
        <li><a href="#percentage" data-toggle="tab">Percentage</a></li>
    </ul>
            <div>
    <button><a style="color:green; font-weight:bold; margin-left:100px; margin:40px;  background-color: transparent;  text-decoration: none;" href="add_cuopon.php">ADD CUOPON</a></button>
</div>
        </div>
        <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade in active" id="allTab">
                                <?php
                $pdo = new PDO("mysql:host=localhost;dbname=u295085671_ecommerce", "u295085671_ecommerce", "Ecom#12345");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $select = "SELECT * FROM tbl_cupan";
                $result = $pdo->query($select);
                while ($ans = $result->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <div class="card">
                        <div class="card-content">
                            <div class="card-title mb-2">Discount Type: <?php echo htmlspecialchars($ans['discounttype']); ?></div>
                            <div class="card-subtitle mb-2"><?php echo htmlspecialchars($ans['discount']); ?> OFF</div>
                            <div class="card-info mb-2">Up to ₹ <?php echo htmlspecialchars($ans['min']); ?></div>
                            <div class="card-info mb-2">On orders above <?php echo htmlspecialchars($ans['max']); ?></div>
                            <div class="card-info mb-2">Total Sales Generated: ₹17,082</div>
                        </div>
                           <div class="cards">
    <div class="card-content">
        <!-- card content -->
    </div>
    <div class="toggle-content" style="display: none;">
        <!-- content to be toggled -->
    </div>
    <div class="toggle-container">
        <button class="toggle" data-target=".toggle-content">Toggle</button>
    </div>
</div>
                    </div>
                <?php } ?>
            </div>
        <div class="tab-pane fade" id="flat">
                                <?php
                $pdo = new PDO("mysql:host=localhost;dbname=u295085671_ecommerce", "u295085671_ecommerce", "Ecom#12345");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $select = "SELECT * FROM tbl_cupan where discounttype='flat'";
                $result = $pdo->query($select);
                while ($ans = $result->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <div class="card">
                        <div class="card-content">
                            <div class="card-title mb-2">Discount Type: <?php echo htmlspecialchars($ans['discounttype']); ?></div>
                            <div class="card-subtitle mb-2"><?php echo htmlspecialchars($ans['discount']); ?> OFF</div>
                            <div class="card-info mb-2">Up to ₹ <?php echo htmlspecialchars($ans['min']); ?></div>
                            <div class="card-info mb-2">On orders above <?php echo htmlspecialchars($ans['max']); ?></div>
                            <div class="card-info mb-2">Total Sales Generated: ₹17,082</div>
                        </div>
                           <div class="cards">
    <div class="card-content">
        <!-- card content -->
    </div>
    <div class="toggle-content" style="display: none;">
        <!-- content to be toggled -->
    </div>
    <div class="toggle-container">
        <button class="toggle" data-target=".toggle-content">Toggle</button>
    </div>
</div>
                    </div>
                <?php } ?>
            </div>
        <div class="tab-pane fade" id="percentage">
                                <?php
                $pdo = new PDO("mysql:host=localhost;dbname=u295085671_ecommerce", "u295085671_ecommerce", "Ecom#12345");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $select = "SELECT * FROM tbl_cupan where discounttype='percent'";
                $result = $pdo->query($select);
                while ($ans = $result->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <div class="card">
                        <div class="card-content">
                            <div class="card-title mb-2">Discount Type: <?php echo htmlspecialchars($ans['discounttype']); ?></div>
                            <div class="card-subtitle mb-2"><?php echo htmlspecialchars($ans['discount']); ?> OFF</div>
                            <div class="card-info mb-2">Up to ₹ <?php echo htmlspecialchars($ans['min']); ?></div>
                            <div class="card-info mb-2">On orders above <?php echo htmlspecialchars($ans['max']); ?></div>
                            <div class="card-info mb-2">Total Sales Generated: ₹17,082</div>
                        </div>
                           <div class="cards">
    <div class="card-content">
        <!-- card content -->
    </div>
    <div class="toggle-content" style="display: none;">
        <!-- content to be toggled -->
    </div>
    <div class="toggle-container">
        <button class="toggle" data-target=".toggle-content">Toggle</button>
    </div>
</div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    
</div>

<script>
    $(document).ready(function() {
        $('.nav-tabs a').click(function() {
            $(this).tab('show');
        });
    });

    document.querySelectorAll('.toggle').forEach(function(toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            var card = this.closest('.card');
            var content = card.querySelector('.toggle-content');
            if (content.style.display === 'none') {
                content.style.display = 'block';
            } else {
                content.style.display = 'none';
            }
        });
    });
</script>


<?php require_once('footer.php'); ?>
