<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['user_id']))
{
	require ('login_tools.php');
	load();
}

//PAGE TITLE
$page_title = 'Edit Profile';

//PAGE HEADER
include ('includes/header.html');

echo "<div class='content'>";

if($_SERVER['REQUEST_METHOD']=='POST')
{
	require ('../connect_db.php');
	$errors = array();
	
	//check whether FIRST NAME is EMPTY
	if(empty($_POST['first_name']))
	{
		$errors[] = 'Enter your first name.';
	}else{
		$first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}
	
	//check whether LAST NAME is EMPTY
	if(empty($_POST['last_name']))
	{
		$errors[] = 'Enter your last name.';
	}else{
		$last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}
	
	//verify from reCaptcha whether CAPTCHA is CORRECT
	require_once('includes/recaptcha/recaptchalib.php');
	$privatekey = "6Le5RvoSAAAAAKlvLOdQzdp7gjBTpeVxb73ygjxr";
	$resp = recaptcha_check_answer ($privatekey,
	                                $_SERVER["REMOTE_ADDR"],
	                                $_POST["recaptcha_challenge_field"],
	                                $_POST["recaptcha_response_field"]);
	if (!$resp->is_valid)
	{
		//if CAPTCHA was entered INCORRECTLY
		$errors[] = 'Captcha was entered incorrectly!';
	}
	
	$temp_id = $_SESSION['user_id'];
	
	//update DATA in DATABASE
	if(empty($errors))
	{
		$q = "UPDATE users SET first_name = '$first_name', last_name = '$last_name' WHERE user_id = '$temp_id'";
		$r = mysqli_query($dbc, $q);
		
		//redirect to PROFILE after UPDATION
		if($r)
		{
			header('Location: profile.php?id='.$_SESSION['user_id']);
		}
		
		mysqli_close($dbc);
		include ('includes/footer.html');
		exit();
	}
	//display ERROR(S) if updation FAILS
	else{
		echo '<h1>Error!</h1>
			  <p id="err_msg">The following error(s) occured:<br>';
			  
			  foreach ($errors as $msg)
			  {
				echo " - $msg<br>";
			  }
			  
			  echo 'Please try again</p>';
			  
		mysqli_close($dbc);
	}
}

?>

<!-- EDIT PROFILE FORM -->
<h1>Edit Profile</h1>
<form action="edit_profile.php" method="POST">
	<p>
		First Name: <input type="text" name="first_name" value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name'];?>"/>
		Last Name: <input type="text" name="last_name" value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name'];?>"/>
	</p>
	<p>
		<?php
		  //for reCaptcha
          require_once('includes/recaptcha/recaptchalib.php');
          $publickey = "6Le5RvoSAAAAAP95vvzVb1_b34iohCad4f7hnItG"; //got this from the signup page of reCaptcha
          echo recaptcha_get_html($publickey);
        ?>
	</p>
	<p>
		<input type="submit" value="Update Info">
	</p>
</form>

<?php

	echo "</div>";
	
	//PAGE FOOTER
	include ('includes/footer.html');
?>