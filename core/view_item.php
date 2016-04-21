<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['user_id']))
{
	require ('login_tools.php');
	load();
}


//get ID of ITEM
if(isset($_GET['id'])){
	$id = $_GET['id'];
}else{
	$id = 1;
}

//retrive ITEM details from DATABASE
require ('../connect_db.php');
$q = "SELECT * FROM items WHERE item_id = $id";
$r = mysqli_query($dbc, $q);
	
//fetch DETAILS in ARRAY
$row = mysqli_fetch_array($r, MYSQLI_ASSOC);


//SET page title
//different from other pages
$page_title = "{$row['item_name']}";

//PAGE HEADER
include('includes/header.html');

echo "<div class='content'>";

echo "<h1>Item</h1>";

//check if ITEM exists in DATABASE
if(mysqli_num_rows($r) == 1)
{
	if($row['user_id'] == $_SESSION['user_id'])
	{
		
		echo '<a href="edit_item.php?id='.$id.'">Edit Item</a>';
		
	}else{
		
		echo "<p>Viewing Item: {$row['item_name']}</p>";
		
	}	

	$u = "SELECT first_name, last_name FROM users WHERE user_id = {$row['user_id']}";
	$v = mysqli_query($dbc, $u);
	$user_row = mysqli_fetch_array($v, MYSQLI_ASSOC);
	
	//print DETAILS on SCREEN
	echo '<dl>';
	echo '<dt><strong>ID:</strong><dt>'.'<dd>'.$row['item_id'].'</dd>';
	echo '<dt><strong>Type:</strong><dt>'.'<dd>'.$row['item_type'].'</dd>';
	echo '<dt><strong>Name:</strong><dt>'.'<dd>'.$row['item_name'].'</dd>';
	echo "<dt><strong>Added By:</strong><dt><dd><a href='profile.php?id={$row['user_id']}'>{$user_row['first_name']} {$user_row['last_name']}</a></dd>";
	echo '<dt><strong>Added On:</strong><dt>'.'<dd>'.$row['entry_date'].'</dd>';
	echo '<dt><strong>Description:</strong><dt>'.'<dd>'.$row['item_desc'].'</dd>';
	echo '</dl>';
	
	//close CONNECTION with DATABASE
	mysqli_close($dbc);

}
//if ITEM does NOT EXIST
else{
	echo "<p>Sorry, the item you are looking for does not exist.</p>";
	
	//close CONNECTION with DATABASE
	mysqli_close($dbc);
}

echo "</div>";

//PAGE FOOTER
include('includes/footer.html');

?>