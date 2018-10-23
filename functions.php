<?php
include("db.php");

// Fetch prducts in the homepage
function getProduct(){
  global $conn;
  $query = "SELECT * FROM products";
  $result = mysqli_query($conn, $query);

  while($row = mysqli_fetch_assoc($result)) {
    $pro_id = $row["productID"];
    $pro_name = $row["productName"];
    $pro_des = $row["productDes"];
    $pro_image = $row["productImage"];
    $pro_price = $row["price"];
    $pro_price=number_format($pro_price);

    echo "
      <div class='single_product'>
      <div class='title'><a href='details.php?item=$pro_id'>$pro_name</a></div>
      <img src='product_images/$pro_image' width='220' height='200' />
      <div class='price'>Base Price: $$pro_price</div>
      </div>
    ";
  }

}

// Fetch details of the product in the details page
function detailsProduct($itemID){
  global $conn;
  $query = "SELECT * FROM products WHERE productID='$itemID'";
  $result = mysqli_query($conn, $query);

  while($row = mysqli_fetch_assoc($result)) {
    $pro_id = $row["productID"];
    $pro_name = $row["productName"];
    $pro_des = $row["productDes"];
    $pro_image = $row["productImage"];
    $pro_price = $row["price"];
    $price=number_format($pro_price);
    echo "
      <div class='product_box'>
      <h2>$pro_name</h2>
      <img src='product_images/$pro_image' width='400' height='350' />
      <br/>$pro_des
      <h5>Base Price: $$price*</h5>
      </div>
    ";
  }

}

function getTitle($itemID){
  global $conn;
  $query = "SELECT * FROM products WHERE productID='$itemID'";
  $result = mysqli_query($conn, $query);

  while($row = mysqli_fetch_assoc($result)) {
    $pro_name = $row["productName"];
  }
  echo "$pro_name";

}

// Fetch option items
function options($itemID, $type){
  global $conn;
  $query = "SELECT * FROM options WHERE type='$type' AND compID='$itemID'";
  $result = mysqli_query($conn, $query);

  $firstLoad =true;
  while($row = mysqli_fetch_assoc($result)) {
    $opt_id = $row["optionID"];
    $opt_name = $row["optionName"];
    $opt_price = $row["price"];


    if (isset($_POST['select'])){
      $x = $_POST[$type];
      if ($opt_id==$x){
        $selected = "selected";
      }
      else{
        $selected = "";
      }

      // echo "<option> $x </option>";
    }
    else {
      if ($firstLoad){
        $selected = "selected";
        $firstLoad = false;
      }
      else {
        $selected = "";
      }

    }

    echo "
      <option $selected value='$opt_id'>$opt_name - $$opt_price</option>
    ";
  }

}

// Add to cart button
function addToCart($itemID, $type, $_email, $groupID, $qty){
  if ($qty == 0){
    return;
  }
  global $conn;

  $query_chk = "SELECT qty FROM shoppingCart
  WHERE ((email = '$_email' AND groupID = '$groupID') AND type='$type') AND productID = '$itemID'";
  $result_chk = mysqli_query($conn, $query_chk);
  $ret = 0;

  while($row_chk = mysqli_fetch_assoc($result_chk)) {
    $ret = $row_chk["qty"];
  }


  if ($ret==0){
    $query = "INSERT INTO shoppingCart VALUES ('$_email', '$itemID', '$type', '$groupID', '$qty')";
  }
  else {
    $ret += $qty;
    $query = "UPDATE shoppingCart SET qty = '$ret'
    WHERE email = '$_email' AND productID = '$itemID' AND type = '$type' AND groupID = '$groupID'";
  }



  if (!mysqli_query($conn, $query)){
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }


}

//Fetch items in the cart table in cart page
function cart_options($_email, $compID, $group_id){
  global $conn;
  $query = "SELECT o.optionName, o.price, o.type
  FROM options AS o, shoppingCart AS s
  WHERE (((s.email = '$_email' AND o.optionID = s.productID) AND s.type = 'opt')
  AND o.compID='$compID') AND s.groupID='$group_id'";
  $result = mysqli_query($conn, $query);

  $total_price = 0;

  while($row = mysqli_fetch_assoc($result)) {
    $opt_name = $row["optionName"];
    $opt_type = strtoupper($row["type"]);
    $opt_price = $row["price"];

    $total_price += $opt_price;

    echo "
      <tr>
        <td>$opt_type</td>
        <td>$opt_name</td>
        <td>$$opt_price</td>
      </tr>
    ";
  }
  return $total_price;
}

function emptyCart($_email){
  global $conn;
  $query = "SELECT p.productID, p.productName, p.productImage, p.price, s.groupID, s.qty
  FROM products AS p, shoppingCart AS s
  WHERE (s.email = '$_email' AND p.productID = s.productID) AND s.type = 'prod'";
  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result)==0){
    return true;
  }
  else {
    return false;
  }
}

//Fetch items in the cart table in cart page
function cart($_email){
  global $conn;
  $query = "SELECT p.productID, p.productName, p.productImage, p.price, s.groupID, s.qty
  FROM products AS p, shoppingCart AS s
  WHERE (s.email = '$_email' AND p.productID = s.productID) AND s.type = 'prod'";
  $result = mysqli_query($conn, $query);
  $finalTotalPrice = 0;
  while($row = mysqli_fetch_assoc($result)) {
    $group_id = $row["groupID"];
    $pro_id = $row["productID"];
    $pro_name = $row["productName"];
    $pro_image = $row["productImage"];
    $pro_price = $row["price"];
    $pro_qty = $row["qty"];
    $price = number_format($pro_price);
    echo "
      <tr>
        <td><img src='product_images/$pro_image' width='70' height='50' /></td>
        <td>$pro_name</td>
        <td>$price</td>
      </tr>
    ";

    // Calling function to echo all the options
    $total_price = cart_options($_email, $pro_id, $group_id);
    $total_price = ($total_price+$pro_price)*$pro_qty;
    $finalTotalPrice += $total_price;
    $total_price_nf = number_format($total_price);
    $finalTotalPrice_nf = number_format($finalTotalPrice);
    echo "
    <tr class='qty'>
    <td>Qty</td>
    <td></td>
    <td>
      <form action='cart.php' method='post' >
        <input type='text' class='qty' value='$pro_qty' name='qty_val' />
        <input type='hidden' name='productID' value='$pro_id'>
        <input type='hidden' name='groupID' value='$group_id'>
        <input type='submit' name='update' value='Update' />
      </form>
    </td>
    </tr>
    <!--Total price of each laptop -->
    <tr>
      <td><b>Price: </td>
      <td></td>
      <td>$$total_price_nf</td>
    </tr>
    ";
  }

  // Echoing total price of the cart
  if(mysqli_num_rows($result)>0){
    echo "
    <tr>
      <td><b>Total Price: </td>
      <td></td>
      <td>$$finalTotalPrice_nf</td>
    </tr>
    ";
  }


  if (isset($_POST['update'])){
    $_proID = $_POST['productID'];
    $_groupID = $_POST['groupID'];
    $_qty = $_POST['qty_val'];

    updateQty($_email, $_groupID, $_qty);
  }

}

// Update qty from the checkout page
function updateQty($email, $groupID, $qty){
  global $conn;

  if ($qty>0){
    $query = "UPDATE shoppingCart SET qty='$qty' WHERE email = '$email' AND groupID = '$groupID'";
  }
  else {
    $query = "DELETE FROM shoppingCart WHERE email = '$email' AND groupID = '$groupID'";
  }

  if (mysqli_query($conn, $query)) {
    echo "<script>location.reload();</script>";

  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }

}


function price_pr($productID){
  global $conn;

  $query = "SELECT price FROM products WHERE productID = '$productID'";
  $result = mysqli_query($conn, $query);

  while($row = mysqli_fetch_assoc($result)){
    $price = $row['price'];
  }

  return $price;

}

function price_op($optionID){
  global $conn;

  $query = "SELECT price FROM options WHERE optionID = '$optionID'";
  $result = mysqli_query($conn, $query);

  while($row = mysqli_fetch_assoc($result)){
    $price = $row['price'];
  }

  return $price;

}


function totalPrice_details(){
  if (!isset($_POST['select'])){
    global $conn;
    $itemID = $_GET['item'];
    $query = "SELECT optionID FROM options WHERE compID = '$itemID' AND type='cpu' LIMIT 1";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($result)){
      $cpu = $row['optionID'];
    }

    $query = "SELECT optionID FROM options WHERE compID = '$itemID' AND type='ram' LIMIT 1";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($result)){
      $ram = $row['optionID'];
    }

    $query = "SELECT optionID FROM options WHERE compID = '$itemID' AND type='hdd' LIMIT 1";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($result)){
      $hdd = $row['optionID'];
    }
  }
  else {
    $cpu = $_POST['cpu'];
    $ram = $_POST['ram'];
    $hdd = $_POST['hdd'];
    $itemID = $_GET['item'];
  }


  $totalPrice = price_pr($itemID) + price_op($cpu) + price_op($ram) + price_op($hdd);
  $totalPrice = number_format($totalPrice);
  echo "Price: $$totalPrice";
}

// Regular Expression for form validation
function name_validation($text){
  $re = "/^[A-Za-z\s\']+$/";

  if (strlen($text)<1 || strlen($text)>120){
    echo "
    <div class='regi_error'>
    Please enter a valid name
    </div>
    ";
    return false;
  }

  else if (preg_match($re, $text)==false){
    echo "
    <div class='regi_error'>
    Please enter a valid name
    </div>
    ";
    return false;
  }
  else {
    return true;
  }
}

function email_validation($text){
  $re = "/^[\w]+\.[\w]+-?[\w]+@(?:students.)?mq.edu.au$/";

  if (preg_match($re, $text)==false){
    echo "
    <div class='regi_error'>
    Please enter a valid MQ email address
    </div>
    ";
    return false;
  }
  else {
    return true;
  }

}

function password_validation($text){
  $re = "/^[A-Za-z]+[0-9]+[A-Za-z]*[0-9]*$/";

  if (strlen($text)<6 || strlen($text)>10){
    echo "
    <div class='regi_error'>
    Please enter a valid password
    </div>
    ";
    return false;
  }

  if (preg_match($re, $text)==false){
    echo "
    <div class='regi_error'>
    Please enter a valid password
    </div>
    ";
    return false;
  }
  else {
    return true;
  }

}

function streetno_validation($text){
  $re = "/^\d+\/?\d+?$/";

  if (strlen($text)<1 || strlen($text)>10){
    echo "
    <div class='regi_error'>
    Please enter a valid Street No
    </div>
    ";
    return false;
  }

  if (preg_match($re, $text)==false){
    echo "
    <div class='regi_error'>
    Please enter a valid Street No
    </div>
    ";
    return false;
  }
  else {
    return true;
  }

}

function streetname_validation($text){
  $re = "/^[A-Za-z\s\']+$/";

  if (strlen($text)<1 || strlen($text)>120){
    echo "
    <div class='regi_error'>
    Please enter a valid Street Name
    </div>
    ";
    return false;
  }

  if (preg_match($re, $text)==false){
    echo "
    <div class='regi_error'>
    Please enter a valid Street Name
    </div>
    ";
    return false;
  }
  else {
    return true;
  }

}

function suburb_validation($text){
  $re = "/^[A-Za-z\s\']+$/";

  if (strlen($text)<1 || strlen($text)>120){
    echo "
    <div class='regi_error'>
    Please enter a valid Suburb/City
    </div>
    ";
    return false;
  }

  if (preg_match($re, $text)==false){
    echo "
    <div class='regi_error'>
    Please enter a valid Suburb/City
    </div>
    ";
    return false;
  }
  else {
    return true;
  }

}

function state_validation($text){

  if (strlen($text)<1 || strlen($text)>30){
    echo "
    <div class='regi_error'>
    Please enter a valid State aa
    </div>
    ";
    return false;
  }

  $states_lst = array("New South Wales", "NSW", "Queensland", "QLD", "South Australia", "SA", "Tasmania", "TAS", "Victoria", "VIC", "Western Australia", "WA", "Australian Capital Territory", "ACT");
  $trig = true;
  for ($i = 0; $i < count($states_lst); $i++) {
    if(strtolower($states_lst[$i])==strtolower($text)){
      $trig = false;
      break;
    }
  }
  if ($trig){
    echo "
    <div class='regi_error'>
    Please enter a valid State
    </div>
    ";
    return false;
  }
  else {
    return true;
  }

}


function postcode_validation($text){
  $re = "/^\d+$/";

  if (strlen($text) != 4){
    echo "
    <div class='regi_error'>
    Please enter a valid Post Code
    </div>
    ";
    return false;
  }

  if (preg_match($re, $text)==false){
    echo "
    <div class='regi_error'>
    Please enter a valid Post Code
    </div>
    ";
    return false;
  }
  else {
    return true;
  }
}


function cardno_validation($text){
  $re = "/^\d+$/";

  if (strlen($text) != 8){
    echo "
    <div class='regi_error'>
    Please enter a valid Credit Card No
    </div>
    ";
    return false;
  }

  if (preg_match($re, $text)==false){
    echo "
    <div class='regi_error'>
    Please enter a valid Credit Card No
    </div>
    ";
    return false;
  }
  else {
    return true;
  }
}


function cardcvv_validation($text){
  $re = "/^\d+$/";

  if (strlen($text) != 3){
    echo "
    <div class='regi_error'>
    Please enter a valid CVV
    </div>
    ";
    return false;
  }

  if (preg_match($re, $text)==false){
    echo "
    <div class='regi_error'>
    Please enter a valid CVV
    </div>
    ";
    return false;
  }
  else {
    return true;
  }
}

function cardexpiry_validation($text){
  $re = "/^\d\d\/\d\d$/";

  if (strlen($text) != 5){
    echo "
    <div class='regi_error'>
    Please enter a valid expiry date
    </div>
    ";
    return false;
  }

  if (preg_match($re, $text)==false){
    echo "
    <div class='regi_error'>
    Please enter a valid CVV
    </div>
    ";
    return false;
  }
  $m = substr($text,0,2);
  $y = substr($text,3,5);
  //year
  $x = substr((string)date("Y"),2,4);
  $y_now = (int)$x;
  $y_inp = (int)$y;
  //month
  $m_now = date("m");
  $m_inp = (int)$m;

  if ($m_inp>12){
    echo "
    <div class='regi_error'>
    Please enter a valid expiry date
    </div>
    ";
    return false;
  }

  if ($y_inp<$y_now){
    echo "
    <div class='regi_error'>
    Sorry, your Credit Card is expired
    </div>
    ";
    return false;
  }
  if ($y_inp==$y_now){
    if($m_inp<$m_now){
      echo "
      <div class='regi_error'>
      Sorry, your Credit Card is expired
      </div>
      ";
      return false;
    }
  }

  return true;

}

?>
