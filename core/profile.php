<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['user_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "{$_SESSION['first_name']} {$_SESSION['last_name']}";

//PAGE HEADER
include('includes/header.html');

echo "<div class='content'>";

//get USER profile ID
if(isset($_GET['id'])){
	$id = $_GET['id'];
}else{
	$id = $_SESSION['user_id'];
}

//retrive PROFILE data from DATABASE
require ('../connect_db.php');
$q = "SELECT * FROM users WHERE user_id = $id";
$r = mysqli_query($dbc, $q);
	
//fetch DETAILS in ARRAY
$row = mysqli_fetch_array($r, MYSQLI_ASSOC);

echo "<h1>Profile</h1>";

//check if PROFILE exists in DATABASE
if(mysqli_num_rows($r) == 1)
{
	if($_SESSION['user_id'] == $id)
	{
		
		echo '<a href="edit_profile.php">Edit Profile</a>';
		echo '<br><br><a href="edit_profile_pic.php">Edit Profile Pic</a>';
		
	}else{
		
		echo "<p>Viewing profile of: {$row['first_name']} {$row['last_name']}</p>";
		
	}	
	
	//print DETAILS on SCREEN
	$profile_pic = "{$row['profile_pic']}" . ".jpg";
	echo "<br><img src='profiles/profile_pics/$profile_pic'><br>";
	echo '<dl>';
	echo '<dt><strong>First Name:</strong><dt>'.'<dd>'.$row['first_name'].'</dd>';
	echo '<dt><strong>Last Name:</strong><dt>'.'<dd>'.$row['last_name'].'</dd>';
	echo '<dt><strong>E-Mail:</strong><dt>'.'<dd>'.$row['email'].'</dd>';
	echo '<dt><strong>Password:</strong><dt>'.'<dd>'.$row['pass'].'</dd>';
	echo '</dl>';

	//close CONNECTION with DATABASE
	mysqli_close($dbc);

}
//if PROFILE does NOT EXIST
else{
	echo "<p>Sorry, the profile you are looking for does not exist.</p>";
	
	//close CONNECTION with DATABASE
	mysqli_close($dbc);
}

echo "</div>";

//PAGE FOOTER
include('includes/footer.html');

?>