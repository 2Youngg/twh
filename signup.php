<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>注册</title>
  <link rel="shortcut icon" href="favicon.ico" >
	<link rel="stylesheet" href="styles/signup.css">
</head>
<body>
<?php
  require_once('appvars.php');
  require_once('connectvars.php');

  // Connect to the database
  $dbc = mysqli_connect('localhost', 'root', '', 'mismatch');

  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
   $error_msgs = "";

    if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
      // Make sure someone isn't already registered using this username
      $query = "SELECT * FROM mismatch_user WHERE username = '$username'";
      $data = mysqli_query($dbc, $query);
      if (mysqli_num_rows($data) == 0) {
        // The username is unique, so insert the data into the database
        $query = "INSERT INTO mismatch_user (username, password, join_date) VALUES ('$username', SHA('$password1'), NOW())";
        mysqli_query($dbc, $query);

        // Confirm success with the user
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
          header('Location: ' . $home_url);
        mysqli_close($dbc);
        exit();
      }
      else {
        // An account already exists for this username, so display an error message
        $error_msgs = 'An account already exists for this username. Please use a different address.';

        $username = "";
      }
    }
    else {
    	$error_msgs = 'You must enter all of the sign-up data, including the desired password twice.';
  
    }
  }
   else {
    	$error_msgs = "";
  
    }
  mysqli_close($dbc);
?>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<div class="main">
		<img src="images/logo.png" alt="" class="logo">
		<div class="sheet">

			<div class="login mail">
				<span>用户名</span>
				<input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username; ?>" class="blank"  />
			</div>
			<div class="login">
				<span>设置密码</span>
				<input type="password" id="password1" name="password1" class="blank"/>
			</div>	
			<div class="login">
				<span>确认密码</span>
				<input type="password" id="password2" name="password2" class="blank"/>
			</div>	
			<?php	  
    echo '<p class="error">' . $error_msgs . '</p>';
?>	
			<input type="submit" value="注   册" name="submit" class="denglu" />



		</div>	
	</div>
  </form>

</body>
</html>


