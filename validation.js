function validate(){
  // Name validation
  var re = /^[A-Za-z\s\']+$/;
  var text = document.getElementById('name').value;

  if (text.length<1 || text.length>120){
    window.alert('Please enter a valid Name');
    return false;
  }
  else if (re.test(text)==false){
    window.alert('Please enter a valid Name');
    return false;
  }

  // Email validation
  re = /^[\w]+\.[\w]+-?[\w]+@(?:students.)?mq.edu.au$/;
  text = document.getElementById('email').value;

  if (re.test(text)==false){
    window.alert('Please enter a valid MQ email address');
    return false;
  }

  // Password validation
  re = /^[A-Za-z]+[0-9]+[A-Za-z]*[0-9]*$/;
  text = document.getElementById('password').value;

  if (text.length<6 || text.length>10){
    window.alert('Please enter a valid password');
    return false;
  }
  else if (re.test(text)==false){
    window.alert('Please enter valid a passowrd');
    return false;
  }

  // Street No validation
  re = /^\d+\/?\d+?$/;
  text = document.getElementById('streetno').value;

  if (text.length<1 || text.length>10){
    window.alert('Please enter a valid Street No');
    return false;
  }
  else if (re.test(text)==false){
    window.alert('Please enter a valid Street No');
    return false;
  }

  // Street Name validation
  re = /^[A-Za-z\s\']+$/;
  text = document.getElementById('streetname').value;

  if (text.length<1 || text.length>120){
    window.alert('Please enter a valid Street Name');
    return false;
  }
  else if (re.test(text)==false){
    window.alert('Please enter a valid Street Name');
    return false;
  }

  // Suburb Name validation
  re = /^[A-Za-z\s\']+$/;
  text = document.getElementById('suburb').value;

  if (text.length<1 || text.length>120){
    window.alert('Please enter a valid Suburb/City');
    return false;
  }
  else if (re.test(text)==false){
    window.alert('Please enter a valid Suburb/City');
    return false;
  }

  // State validation
  text = document.getElementById('state').value;

  if (text.length<1 || text.length>30){
    window.alert('Please enter a valid State');
    return false;
  }

  var states = ["New South Wales", "NSW", "Queensland", "QLD", "South Australia", "SA", "Tasmania", "TAS", "Victoria", "VIC", "Western Australia", "WA", "Australian Capital Territory", "ACT"];
  var i=0;
  var trig = true;
  for (i; i<states.length; i++){
    if(states[i].toLowerCase()==text.toLowerCase()){
      trig = false;
      break;
    }
  }
  if (trig){
    window.alert('Please enter a valid State');
    return false;
  }

  // Post Code validation
  re = /^\d+$/;
  text = document.getElementById('postcode').value;

  if (text.length != 4){
    window.alert('Please enter a valid Post Code');
    return false;
  }
  else if (re.test(text)==false){
    window.alert('Please enter a valid Post Code');
    return false;
  }

  // Credit Card Name validation
  re = /^[A-Za-z\s\']+$/;
  text = document.getElementById('ccname').value;

  if (text.length<1 || text.length>120){
    window.alert('Please enter a valid Name');
    return false;
  }
  else if (re.test(text)==false){
    window.alert('Please enter a valid Name');
    return false;
  }

  // Card No validation
  re = /^\d+$/;
  text = document.getElementById('cardno').value;

  if (text.length != 8){
    window.alert('Please enter a valid Credit Card No');
    return false;
  }
  else if (re.test(text)==false){
    window.alert('Please enter a valid Credit Card No');
    return false;
  }

  // Card expiry validation
  re = /^\d\d\/\d\d$/;
  text = document.getElementById('card_ex').value;

  if (text.length != 5){
    window.alert('Please enter a valid expiry date');
    return false;
  }
  else if (re.test(text)==false){
    window.alert('Please enter a valid expiry date');
    return false;
  }

  var m = text.slice(0,2);
  var y = text.slice(3,5);
  var dt = new Date();
  //year
  var x = dt.getFullYear().toString().slice(2,4);
  var y_now = parseInt(x);
  var y_inp = parseInt(y);
  //month
  var m_now = dt.getMonth()+1;
  var m_inp = parseInt(m);

  if (m_inp>12){
    window.alert('Please enter a valid expiry date');
    return false;
  }

  if (y_inp<y_now){
    window.alert('Sorry, your Credit Card is expired');
    return false;
  }
  if (y_inp==y_now){
    if(m_inp<m_now){
      window.alert('Sorry, your Credit Card is expired');
      return false;
    }
  }

  // Card CVV validation
  re = /^\d+$/;
  text = document.getElementById('card_cvv').value;

  if (text.length != 3){
    window.alert('Please enter a valid CVV');
    return false;
  }
  else if (re.test(text)==false){
    window.alert('Please enter a valid CVV');
    return false;
  }

}

function logValidate(){
  // Email validation
  var xre = /^[\w]+\.[\w]+-?[\w]+@(?:students.)?mq.edu.au$/;
  var xtext = document.getElementById('l_email').value;

  if (xre.test(xtext)==false){
    window.alert('Please enter a valid MQ email address');
    return false;
  }

  // Password validation
  xre = /^[A-Za-z]+[0-9]+[A-Za-z]*[0-9]*$/;
  xtext = document.getElementById('l_pass').value;

  if (xtext.length<6 || xtext.length>10){
    window.alert('Please enter a valid password');
    return false;
  }
  else if (xre.test(xtext)==false){
    window.alert('Please enter valid a passowrd');
    return false;
  }
}
