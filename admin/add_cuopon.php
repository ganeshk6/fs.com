<?php require_once('header.php'); ?>

<style>
  .coupon-form {
    width: 800px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f8f9fa;
  }
  .input-field {
    display: none;
  }
  .dropdown {
    font-family: Arial, sans-serif;
    font-size: 14px;
  }
  .dropdown select {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    appearance: none;
    background-color: #fff;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="6"><path d="M0 0l5 6 5-6" fill="none" stroke="%23333"/></svg>');
    background-repeat: no-repeat;
    background-position: right 5px center;
    padding-right: 20px;
  }
  .input-field {
    font-family: Arial, sans-serif;
    font-size: 14px;
    align-items: center;
    margin-top: 10px;
  }
  .input-field label {
    margin-right: 10px;
  }
  .input-field input {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    width: 150px;
  }
  .input-field span {
    margin-left: 5px;
  }
  .input-group{
      display: flex;
      gap:1rem;
     }
</style>
    <button style="border: none;font-size: 20px; border-radius: 5px; outline: none;padding: 0px 12px; margin-top: 20px; margin-left: 20px;"><a href="cupan.php">
       
<!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
<svg fill="#000000" height="40px" width="40px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
	 viewBox="0 0 330 330" xml:space="preserve">
<path id="XMLID_6_" d="M165,0C74.019,0,0,74.019,0,165s74.019,165,165,165s165-74.019,165-165S255.981,0,165,0z M205.606,234.394
	c5.858,5.857,5.858,15.355,0,21.213C202.678,258.535,198.839,260,195,260s-7.678-1.464-10.606-4.394l-80-79.998
	c-2.813-2.813-4.394-6.628-4.394-10.606c0-3.978,1.58-7.794,4.394-10.607l80-80.002c5.857-5.858,15.355-5.858,21.213,0
	c5.858,5.857,5.858,15.355,0,21.213l-69.393,69.396L205.606,234.394z"/>
</svg></a></button>


<div class="coupon-form">
  <h4 class="mb-2">Add Coupon</h4>
  <form method="post" action="">
    <div class="form-group">
      <label for="couponCode">Coupon Code*</label>
      <div class="input-group">
        <input id="showall" type="text" class="form-control" id="couponCode" name="code" placeholder="Enter coupon code" value="">
        <div class="input-group-append">
          <button id="generate" class="btn btn-outline-secondary" type="button">Generate Coupon</button>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="usageLimit">Usage limit per customer*</label>
      <select class="form-control" id="usageLimit" name="unlimit">
        <option value="Unlimited" selected>Unlimited</option>
             <option value="1" >1</option>
             <option value="1-500" >1-500</option>
      </select>
    </div>
    <div class="form-group">
      <label for="applyOn">Apply coupon on*</label>
      <select class="form-control" id="applyOn" name="applycupan">
        <option value="All Product" selected>All PRODUCTS</option>
                <option value="HairCare" >HairCare</option>
                <option value="GiftSets" >GiftSets</option>
                <option value="Grooming" >Grooming</option>
                <option value="SkinCare" >SkinCare</option>
                <option value="BodyCare" >BodyCare</option>
      </select>
    </div>

<div style="display:flex;align-items: center;justify-content:space-between;">
    <div class="dropdown">
      <select id="discountType" name="discounttype">
        <option value="" disabled selected>Please Choose Discount Type</option>
        <option value="percent">Percent Discount</option>
        <option value="flat">Flat Discount</option>
      </select>
    </div>

    <div class="input-field" id="percentageField">
      <label for="percentage">Percentage Discount*</label>
      <input type="text" name="discount_percent" id="percentage" placeholder="Enter Discount Percentage">
      <span>%</span>
    </div>

    <div class="input-field" id="flatField">
      <label for="flat">Flat Discount*</label>
      <input type="number" name="discount_flat" id="flat" placeholder="Enter Flat Discount">
    </div>
    </div>

    <div class="form-group">
      <label for="applyOn">Apply coupon on*</label>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="applyon" id="orderValue" value="order_value" checked>
        <label style="margin-left:20px;" class="form-check-label" for="orderValue">Order Value</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="applyon" id="orderQuality" value="order_quality">
        <label style="margin-left:20px;" class="form-check-label" for="orderQuality">Order Quality</label>
      </div>
    </div>
    <div class="form-group">
      <label for="minOrderValue">Minimum Order Value*</label>
      <input type="number" name="min" class="form-control" id="minOrderValue">
    </div>
    <div class="form-group">
      <label for="maxDiscount">Maximum discount*</label>
      <input type="number" class="form-control" id="maxDiscount" name="max">
    </div>
    <div class="form-group">
      <label for="function">Coupon Functionality</label>
      <div class="form-check form-switch">
        <input name="function[]" class="form-check-input" type="checkbox" id="showCoupon" value="showCoupon">
        <label style="margin-left:20px;" class="form-check-label" for="showCoupon">Show Coupon to Customers</label>
      </div>
      <div class="form-check form-switch">
        <input name="function[]" class="form-check-input" type="checkbox" id="newCustomers" value="newCustomers" checked>
        <label style="margin-left:20px;" class="form-check-label" for="newCustomers">Valid Only For New Customers</label>
      </div>
      <div class="form-check form-switch">
        <input name="function[]" class="form-check-input" type="checkbox" id="onlinePayments" value="onlinePayments">
        <label style="margin-left:20px;" class="form-check-label" for="onlinePayments">Valid Only For Online Payments</label>
      </div>
      <div class="form-check form-switch">
        <input name="function[]" class="form-check-input" type="checkbox" id="autoApply" value="autoApply">
        <label style="margin-left:20px;" class="form-check-label" for="autoApply">Auto Apply Coupon</label>
      </div>
    </div>
    <div class="form-group">
      <label for="startDate">Coupon Validity</label>
      <div class="input-group">
        <input name="sd" type="date" class="form-control" id="startDate" placeholder="Start Date">
        <input name="time" type="time" class="form-control" id="startTime" placeholder="--:-- --">
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="setEndDate">
        <label style="margin-left:20px;" class="form-check-label" for="setEndDate">Set an End date</label>
      </div>
      <input name="ed" type="datetime-local" class="form-control" id="endDateTime" disabled>
    </div>
    <button  class="form-group col-12 btn btn-primary" type="submit" name="submit">Submit</button>
  </form>
</div>

<script>
  const setEndDateCheckbox = document.getElementById('setEndDate');
  const endDateTimeInput = document.getElementById('endDateTime');

  setEndDateCheckbox.addEventListener('change', function() {
    endDateTimeInput.disabled = !this.checked;
  });

  document.getElementById('discountType').addEventListener('change', function() {
    var discountType = this.value;
    var percentageField = document.getElementById('percentageField');
    var flatField = document.getElementById('flatField');

    if (discountType === 'percent') {
      percentageField.style.display = 'flex';
      flatField.style.display = 'none';
    } else if (discountType === 'flat') {
      percentageField.style.display = 'none';
      flatField.style.display = 'flex';
    } else {
      percentageField.style.display = 'none';
      flatField.style.display = 'none';
    }
  });
  
 document.getElementById('generate').addEventListener('click', () => {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const length = 8; // Length of the random string

    let randomString = '';
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        randomString += characters.charAt(randomIndex);
    }

    document.getElementById('showall').value = randomString;
});


</script>
<?php
if (isset($_POST['submit'])) {
    $code = $_POST['code'];
    $unlimit = $_POST['unlimit'];
    $applycupan = $_POST['applycupan'];
    $discounttype = $_POST['discounttype'];
    $discount = $discounttype === 'percent' ? $_POST['discount_percent'] : $_POST['discount_flat'];
    $applyon = $_POST['applyon'];
    $min = $_POST['min'];
    $max = $_POST['max'];
    $function = isset($_POST['function']) ? implode(",", $_POST['function']) : '';
    $sd = $_POST['sd'];
    $time = $_POST['time'];
    $ed = $_POST['ed'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=u295085671_ecommerce", "u295085671_ecommerce", "Ecom#12345");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $insert = "INSERT INTO tbl_cupan (code, unlimit, applycupan, discount, discounttype, applyon, min, max, function, sd, time, ed) 
                   VALUES (:code, :unlimit, :applycupan, :discount, :discounttype, :applyon, :min, :max, :function, :sd, :time, :ed)";
        $stmt = $pdo->prepare($insert);
        $stmt->execute([
            ':code' => $code,
            ':unlimit' => $unlimit,
            ':applycupan' => $applycupan,
            ':discount' => $discount,
            ':discounttype' => $discounttype,
            ':applyon' => $applyon,
            ':min' => $min,
            ':max' => $max,
            ':function' => $function,
            ':sd' => $sd,
            ':time' => $time,
            ':ed' => $ed
        ]);

        header("Location: cupan.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<?php require_once('footer.php'); ?>