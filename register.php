<?php session_start(); ?>
<?php if(isset($_SESSION['email'])){
    header("Location: /index.php");
    exit;
}
?>

<?php include("db.php");
include("functions.php");?>

<html>
<head>
  <script type="text/javascript" src="validation.js" ></script>
  <link rel="stylesheet" type="text/css" href="style.css">

  <title>Macquarie University Online Laptop Store - Register</title>
</head>
<body>

  <div class="nav">
    <div class="nav_area">

      <a href="/login.php"><div class="nameFr">Macquarie University Online Laptop Store</div></a>

    </div>
    <div class="navLine"></div>

  </div>

  <div class="registration">
    <div class="heading_regi">Register</div>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <input id ="name" type="text" name="fullName" placeholder="Full Name" required><br/>
    <input id ="email" type="email" name="email" placeholder="MQ Email" required><br/>
    <input id ="password" type="password" name="password" placeholder="Password" required><br/>
    <input id ="streetno" type="text" name="streetNo" placeholder="Street No" required><br/>
    <input id ="streetname" type="text" name="streetName" placeholder="Street Name" required><br/>
    <input id ="suburb" type="text" name="suburb_city" placeholder="Suburb/City" required><br/>
    <input id ="state" type="text" name="state" placeholder="State" required><br/>
    <input id ="postcode" type="text" name="postcode" placeholder="Post Code" required><br/>
    <div class="cardHeading">Credit Card Details</div>
    <input id ="ccname" type="text" name="nameOnCard" placeholder="Name on Credit Card" required><br/>
    <input id ="cardno" type="text" name="cardNo" placeholder="8-Digit Number" required><br/>
    <input type="text" id="card_ex" name="expiry" placeholder="Expiry Date" required>
    <input type="text" id="card_cvv" name="cvv" placeholder="CVV" required><br/>

    <input type="submit" onclick="return validate();" name="reg_button" value="Register" />
  </form>
  <div id="rerr" class="regi_error"></div>
  </div>
</body>
</html>

<?php
  if (isset($_POST['reg_button'])){

    $_fullName = $_POST['fullName'];
    $_email = $_POST['email'];
    $_password = $_POST['password'];
    $_streetNo = $_POST['streetNo'];
    $_streetName = $_POST['streetName'];
    $_suburb_city = $_POST['suburb_city'];
    $_state = $_POST['state'];
    $_postcode = $_POST['postcode'];
    $_nameOnCard = $_POST['nameOnCard'];
    $_cardNo = $_POST['cardNo'];
    $_expiry = $_POST['expiry'];
    $_cvv = $_POST['cvv'];

    if (name_validation($_fullName)==false){
      return;
    }
    elseif (email_validation($_email)==false) {
      return;
    }
    elseif (password_validation($_password)==false) {
      return;
    }
    elseif (streetno_validation($_streetNo)==false) {
      return;
    }
    elseif (streetname_validation($_streetName)==false) {
      return;
    }
    elseif (suburb_validation($_suburb_city)==false) {
      return;
    }
    elseif (state_validation($_state)==false) {
      return;
    }
    elseif (postcode_validation($_postcode)==false) {
      return;
    }
    elseif (name_validation($_nameOnCard)==false) {
      return;
    }
    elseif (cardno_validation($_cardNo)==false) {
      return;
    }
    elseif (cardexpiry_validation($_expiry)==false) {
      return;
    }
    elseif (cardcvv_validation($_cvv)==false) {
      return;
    }

    $hashed_password = password_hash($_password, PASSWORD_DEFAULT);

    $query_chk = "SELECT email FROM users WHERE email='$_email'";
    $result_chk = mysqli_query($conn, $query_chk);

    if (mysqli_num_rows($result_chk) > 0) {
      // output data of each row
      while($xrow = mysqli_fetch_assoc($result_chk)) {
        $sql_email = $xrow["email"];
      }
      if ($sql_email==$_email){
        echo "
        <div class='regi_error'>
        Email address is already registered.
        </div>
        ";
        return ;
      }
    }


    $query = "INSERT INTO users (email, fullName, password, streetNo, streetName, suburb_city, state, postcode)
    VALUES ('$_email', '$_fullName', '$hashed_password', '$_streetNo', '$_streetName', '$_suburb_city', '$_state', '$_postcode')";

    $query_cc = "INSERT INTO creditCard VALUES ('$_email', '$_cardNo', '$_nameOnCard', '$_expiry', '$_cvv')";

    if (mysqli_query($conn, $query) && mysqli_query($conn, $query_cc)) {
      $_SESSION['email'] = $_POST['email'];
      $msg = "Thank you for your registration. Your user name is: " . $_email . ". From: no-reply@sender.com";

      echo "<script>window.location = '/index.php'</script>";
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
    }
?>
