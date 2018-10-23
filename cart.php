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
  <title>Macquarie University Online Laptop Store - Shopping Cart</title>
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
    <div class="cartText">Shopping Cart</div>
    <table class="container">
      <?php
      cart($_SESSION['email']);
      ?>
    </table>
    <div id="check_empty" class="checkout">
    <form method="post">
      <input type="submit" name="checkout" value="Checkout" />
    </form>
  </div>
  <?php
  if(emptyCart($_SESSION['email'])){
    echo "
    <script>document.getElementById('check_empty').innerHTML='Your shopping cart is empty.'</script>
    ";
  }
  ?>
  </div>
</body>

<?php

if (isset($_POST['checkout'])){
  // Send Email
  $_email = $_SESSION['email'];
  $query = "DELETE FROM shoppingCart WHERE email='$_email'";
  mysqli_query($conn, $query);
  echo "<script>window.alert('The order has been placed. Thank you');</script>";
  echo "<script>window.location = '/index.php'</script>";
}

if (isset($_POST['logout'])){
  session_destroy();
  echo "<script>window.location = '/login.php'</script>";
}

?>

</html>
