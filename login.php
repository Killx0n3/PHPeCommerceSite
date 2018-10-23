<?php session_start(); ?>
<?php if(isset($_SESSION['email'])){
  header("Location: /index.php");
  exit;
}
?>


<?php
include("db.php");
include("functions.php");
?>

<html>
<head>
  <script type="text/javascript" src="validation.js" ></script>
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>Macquarie University Online Laptop Store - Login</title>
</head>
<body>

  <div class="nav">
    <div class="nav_area">

      <a href="/login.php"><div class="nameFr">Macquarie University Online Laptop Store</div></a>

    </div>
    <div class="navLine"></div>

  </div>
  <div class="login">
    <div class="heading_login">Login</div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <input id ="l_email" type="email" name="email" placeholder="MQ Email"><br/>
      <input id ="l_pass" type="password" name="password" placeholder="Password"><br/>
      <input type="submit" onclick="return logValidate();" name="login_button" value="Login" />
    </form>
    <div class="reg_text">
      Not a member? <a href="/register.php" >Register Now</a>
    </div>
    <div id="le" class="login_error"></div>
  </div>

</body>
</html>

<?php
if (isset($_POST['login_button'])){

  $_email = $_POST['email'];
  $_password = $_POST['password'];

  $query = "SELECT email, password FROM users WHERE email='$_email'";
  $result = mysqli_query($conn, $query);


  if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      $sql_email = $row["email"];
      $hashed_password = $row["password"];
    }
  }
  else {
    echo "<div class='login_error'>
    No user found with the email address
    </div>";
    return ;
  }

  if(password_verify($_password, $hashed_password)) {
    $_SESSION['email'] = $_POST['email'];
    echo "<script>window.location = '/index.php'</script>";
  }
  else {
    echo "<div class='login_error'>
    Wrong password
    </div>";
  }

  mysqli_close($conn);
}
?>
