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
  <title>Macquarie University Online Laptop Store</title>
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

    <?php getProduct(); ?>

  </div>
</body>

<?php
if (isset($_POST['logout'])){
  session_destroy();
  echo "<script>window.location = '/login.php'</script>";
}
?>

</html>
