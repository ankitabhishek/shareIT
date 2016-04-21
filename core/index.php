<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['user_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "share IT | Home";

//PAGE HEADER
include('includes/header.html');


//fixing the "No name change on front page after profile update" BUG
//retrive PROFILE data from DATABASE

$id = $_SESSION['user_id'];

require ('../connect_db.php');
$q = "SELECT * FROM users WHERE user_id = '$id'";
$r = mysqli_query($dbc, $q);
	
//fetch DETAILS in ARRAY
$row = mysqli_fetch_array($r, MYSQLI_ASSOC);


echo "<div class='content'>
	  <h1>HOME</h1>
	  <p>Hello, {$row['first_name']} {$row['last_name']}</p>
	  </div>";

//close CONNECTION with DATABASE
mysqli_close($dbc);

//PAGE FOOTER
include('includes/footer.html');

?>