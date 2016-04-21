<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['user_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "Browse Items";

//PAGE HEADER
include('includes/header.html');

echo "<div class='content'>";

//get USER profile ID
if(isset($_GET['id'])){
	$id = $_GET['id'];
}else{
	$id = $_SESSION['user_id'];
}

if($_SERVER['REQUEST_METHOD']=='POST')
{
	require ('../connect_db.php');
	$errors = array();
	
	//check whether ATLEAST ONE search term is PRESENT
	if(empty($_POST['item_name']) && empty($_POST['item_type']))
	{
		$errors[] = 'Enter atleast one search term.';
	}else{
		$item_name = mysqli_real_escape_string($dbc, trim($_POST['item_name']));
		$item_type = mysqli_real_escape_string($dbc, trim($_POST['item_type']));
	}
	
	//search DATA in DATABASE
	if(empty($errors))
	{
		//APTANA STUDIOS shows ERROR in the line BELOW
		$q = "SELECT * FROM items WHERE item_name = '".mysql_real_escape_string($item_name)."' OR item_type = '".mysql_real_escape_string($item_type)."' LIMIT 0,20";
		$r = mysqli_query($dbc, $q);
		
		//check if ITEMS exist in DATABASE
		if(mysqli_num_rows($r) >= 1)
		{
			echo "<p>Search Results</p>";
			
			while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
			{				
				$u = "SELECT first_name, last_name FROM users WHERE user_id = {$row['user_id']}";
				$v = mysqli_query($dbc, $u);
				$user_row = mysqli_fetch_array($v, MYSQLI_ASSOC);
				
				//print DETAILS on SCREEN
				echo '<table><tr>';
				
				echo '<tr><td><strong>ID:</strong></td><td>'.$row['item_id'].'</td></tr><br>';
				echo "<tr><td><strong>Name:</strong></td><td><a href='view_item.php?id={$row['item_id']}'>{$row['item_name']}</td></tr><br>";
				echo '<tr><td><strong>Type:</strong></td><td>'.$row['item_type'].'</td></tr><br>';
				echo "<tr><td><strong>Added By:</strong></td><td><a href='profile.php?id={$row['user_id']}'>{$user_row['first_name']} {$user_row['last_name']}</td></tr><br>";
				echo '<tr><td><strong>Added On:</strong></td><td>'.$row['entry_date'].'</td></tr><br>';
				
				echo '</tr></table>';
			}
			
			//close CONNECTION with DATABASE
			mysqli_close($dbc);
		}
		//if NO ITEM exist in DATABASE
		else{
			
			echo '<p>There are no items related to your search query.</p>';
			
			//close CONNECTION with DATABASE
			mysqli_close($dbc);
		}
		
		//BOTTOM MENU
		echo '<p>
				<a href="index.php">Home</a> | 
	  			<a href="add.php">Share</a> | 
	  			<a href="profile.php?id='.$_SESSION['user_id'].'">Profile</a> | 
	  			<a href="members.php">Members</a> | 
	  			<a href="items.php">Items</a> | 
	  			<a href="logout.php">Logout</a>
	  		  </p>';
		
		include ('includes/footer.html');
		exit();
	}
	//display ERROR(S) if searching FAILS
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
}else{
	
	echo "Search Items";
	//SEARCH FORM
	echo '<form action="items.php" method="POST">
		  <b>Item Name:</b> <input type="text" name="item_name" size="50">
		  <b>Item Type:</b> <input type="text" name="item_type" size="50">';
		  /*<b>Results:</b> <select name="results">
		  					<option>10</option>
    						<option>20</option>
    						<option>50</option>
						  </select><br>*/
	echo '<input type="submit" value="Search">
		  </form>';
	
	//set LIMIT for no. of ITEMS displayed per PAGE
	$rec_limit = 10;
	
	//count no. of ITEMS
	require ('../connect_db.php');
	$q = "SELECT count(item_id) FROM items";
	$r = mysqli_query($dbc, $q);
	
	if(!$r)
	{
		die('Could not get data: ' . mysql_error());
	}
	
	$row = mysqli_fetch_array($r, MYSQLI_NUM);
	$rec_count = $row[0];
	
	if(isset($_GET{'page'}))
	{
		$page = $_GET{'page'} + 1;
		$offset = $rec_limit * $page ;
	
	}else{
		$page = 0;
		$offset = 0;
	}
	
	$left_rec = $rec_count - ($page * $rec_limit) - 1;
	
	$q = "SELECT * FROM items ORDER BY entry_date DESC LIMIT $offset, $rec_limit";
	$r = mysqli_query($dbc, $q);
	
	if(!$r)
	{
		die('Could not get data: ' . mysql_error());
	}

	echo "<h1>Browsing Items</h1>";
	
	while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
	{
		$u = "SELECT first_name, last_name FROM users WHERE user_id = {$row['user_id']}";
		$v = mysqli_query($dbc, $u);
		$user_row = mysqli_fetch_array($v, MYSQLI_ASSOC);
		
		//print DETAILS on SCREEN
		echo "<table border='1'>";
		
		echo '<tr><td><strong>ID:</strong></td><td>'.$row['item_id'].'</td></tr><br>';
		echo "<tr><td><strong>Name:</strong></td><td><a href='view_item.php?id={$row['item_id']}'>{$row['item_name']}</td></tr><br>";
		echo '<tr><td><strong>Type:</strong></td><td>'.$row['item_type'].'</td></tr><br>';
		echo "<tr><td><strong>Added By:</strong></td><td><a href='profile.php?id={$row['user_id']}'>{$user_row['first_name']} {$user_row['last_name']}</td></tr><br>";
		echo '<tr><td><strong>Added On:</strong></td><td>'.$row['entry_date'].'</td></tr><br>';
		
		echo '</table>';
	}
	
	
	if($page == 0)
	{
		if($left_rec < $rec_limit)
		{
			echo "<br>Nothing more to display";
		
		}else{
			
			echo "<a href=\"items.php?page=$page\">Next 10 Records</a>";
			
		}
		
	
	}else if($left_rec < $rec_limit)
	{
		$last = $page - 2;
		echo "<a href=\"items.php?page=$last\">Last 10 Records</a>";
	
	}else{
		
		$last = $page - 2;
		echo "<a href=\"items.php?page=$last\">Last 10 Records</a> |";
		echo "<a href=\"items.php?page=$page\">Next 10 Records</a>";
		
	}
	
	echo "</div>";
	
	mysqli_close($dbc);

}

//PAGE FOOTER
include('includes/footer.html');

?>