<?php session_start(); ?>
<?php if(!isset($_SESSION['email'])){
    header("Location: /login.php");
    exit;
}
?>

<?php include("db.php");
include("functions.php"); ?>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="style.css">
  <title><?php getTitle($_GET['item']); ?> - Macquarie University Online Laptop Store</title>
</head>
<body>

  <div class="nav">
    <div class="nav_area">

      <a href="/index.php"><div class="name">Macquarie University<br/> Online Laptop Store</div></a>
      <div class="cart">
        <button class="nav_button"onclick="window.location = '/cart.php'">Cart</button>
      </div>

      <div class="logout">
        <form method="post">
          <input id="nav_but" type="submit" name="logout" value="Logout" />
        </form>
      </div>
    </div>
    <div class="navLine"></div>

  </div>
  <div class="content_area">

    <?php
    $itemID = $_GET['item'];
    detailsProduct($itemID);
    ?>
    <div class="detailsPrice">
      <?php totalPrice_details(); ?>
    </div>
    <form method="post">
      <!-- Options for CPU -->
      <span class="custom-dropdown">
      <select name="cpu">
        <?php
        $itemID = $_GET['item'];
        options($itemID, 'cpu', $cpu);
        ?>
      </select>
    </span>
      <!-- Options for RAM -->
      <span class="custom-dropdown">
      <select name="ram">
        <?php
        $itemID = $_GET['item'];
        options($itemID, 'ram', $ram);
        ?>
      </select>
    </span>
      <!-- Options for HDD -->
      <span class="custom-dropdown">
      <select name="hdd">
        <?php
        $itemID = $_GET['item'];
        options($itemID, 'hdd', $hdd);
        ?>
      </select>
    </span>
    <div class="slct">
      <input type="submit" name="select" value="Select" />
    </div>
      <div class="detailsQty">
      <div class="qtyTitle">Quantity: </div>
      <input type='text' class='qty' value='1' name='qty_val' />
    </div>
    <div class="detailsAddToCart">
      <input type="submit" name="addToCart" value="Add to cart" />
    </div>
    </form>
    <div class="disclaimer">*Base price for this model, excluding CPU, RAM and hard disk.</div>
  </div>
</body>

<?php

if (isset($_POST['addToCart'])){
  $cpu = $_POST['cpu'];
  $ram = $_POST['ram'];
  $hdd = $_POST['hdd'];
  $itemID = $_GET['item'];
  $qty = $_POST['qty_val'];

  $groupID = "" . $itemID . $cpu . $ram . $hdd . "";

  addToCart($itemID, 'prod', $_SESSION['email'], $groupID, $qty);
  addToCart($cpu, 'opt', $_SESSION['email'], $groupID, $qty);
  addToCart($ram, 'opt', $_SESSION['email'], $groupID, $qty);
  addToCart($hdd, 'opt', $_SESSION['email'], $groupID, $qty);
}

if (isset($_POST['select'])){
  $cpu = $_POST['cpu'];
  $ram = $_POST['ram'];
  $hdd = $_POST['hdd'];
  $itemID = $_GET['item'];

}

if (isset($_POST['logout'])){
  session_destroy();
  echo "<script>window.location = '/login.php'</script>";
}

?>

</html>
