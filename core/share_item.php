<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['user_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "Share Item";

//PAGE HEADER
include('includes/header.html');

echo "<div class='content'>";

if($_SERVER['REQUEST_METHOD']=='POST')
{
	require ('../connect_db.php');
	$errors = array();
	
	//check whether ITEM NAME is EMPTY
	if(empty($_POST['item_name']))
	{
		$errors[] = 'Enter item name.';
	}else{
		$item_name = mysqli_real_escape_string($dbc, trim($_POST['item_name']));
	}
	
	//check whether ITEM TYPE is EMPTY
	if(empty($_POST['item_type']))
	{
		$errors[] = 'Select item type.';
	}else{
		$item_type = mysqli_real_escape_string($dbc, trim($_POST['item_type']));
	}
	
	//check whether ITEM DESCRIPTION is EMPTY
	if(empty($_POST['item_desc']))
	{
		$errors[] = 'Enter item description.';
	}else{
		$item_desc = mysqli_real_escape_string($dbc, trim($_POST['item_desc']));
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
	
	//insert DATA into DATABASE
	if(empty($errors))
	{
		$q = "INSERT INTO items (item_name, item_type, item_desc, user_id, entry_date) VALUES ('$item_name', '$item_type', '$item_desc', {$_SESSION['user_id']}, NOW())";
		$r = mysqli_query($dbc, $q);
		
		//confirm SHARING of ITEM
		if($r)
		{
			echo '<h1>Item shared successfully</h1>
				  <p>Thank you for sharing.</p>
				  <p><a href="share_item.php">Share another item</a></p>';
				  
		}
		//if SHARING of item FAILS
		else{
			
			echo '<p>There was a problem sharing the last item.</p>
				  <p>Please try again.</p>
				  <p><a href="share_item.php">Try again</a>
				  </p>';
		}
		
		mysqli_close($dbc);
		
		//BOTTOM MENU
		echo '<p>
				<a href="index.php">Home</a> | 
	  			<a href="share_item.php">Share</a> | 
	  			<a href="profile.php?id='.$_SESSION['user_id'].'">Profile</a> | 
	  			<a href="members.php">Members</a> | 
	  			<a href="items.php">Items</a> | 
	  			<a href="logout.php">Logout</a>
	  		  </p>';
		
		include ('includes/footer.html');
		exit();
	}
	//display ERROR(S) if sharing FAILS
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

<!-- SHARING FORM -->
<h1>Share an item</h1>
<form action="share_item.php" method="POST">
	<p>
		Item Name: <input type="text" name="item_name" value="<?php if(isset($_POST['item_name'])) echo $_POST['item_name'];?>"/>
	</p>
	<p>
		Item Type: <select name="item_type">
					<option value="TV Shows">TV Shows</option>
					<option value="Movies">Movies</option>
					<option value="Notes">Notes</option>
					<option value="Others" selected>Others</option>
				   </select>
	</p>
	<p>
		Description:<br /><textarea rows="10" cols="80" name="item_desc"></textarea>
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
		<input type="submit" value="Share IT">
	</p>
</form>


<?php

echo "</div>";

//PAGE FOOTER
include ('includes/footer.html');
?>