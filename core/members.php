<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['user_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "Browse Members";

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
	if(empty($_POST['first_name']) && empty($_POST['last_name']) && empty($_POST['email']))
	{
		$errors[] = 'Enter atleast one search term.';
	}else{
		$first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
		$last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}
	
	//search DATA in DATABASE
	if(empty($errors))
	{
		//APTANA STUDIOS shows ERROR in the line BELOW
		$q = "SELECT user_id, first_name, last_name, email FROM users WHERE first_name = '".mysql_real_escape_string($first_name)."' OR last_name = '".mysql_real_escape_string($last_name)."' OR email = '".mysql_real_escape_string($email)."' LIMIT 0,20";
		$r = mysqli_query($dbc, $q);
		
		//check if MEMBERS exist in DATABASE
		if(mysqli_num_rows($r) >= 1)
		{
			echo "<p>Search Results</p>";
			
			while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
			{
				//print DETAILS on SCREEN
				echo "<div id='content-members'>";
				echo "<b>Name:<b> <a href='profile.php?id={$row['user_id']}'>{$row['first_name']} {$row['last_name']}</a><br>";
				echo "<b>Email: <b>{$row['email']}<br>";
				echo '</div>';
				echo '<br>';
			}
			
			//close CONNECTION with DATABASE
			mysqli_close($dbc);
		}
		//if NO MEMBER exist in DATABASE
		else{
			
			echo '<p>There are no members related to your search query.</p>';
			
			//close CONNECTION with DATABASE
			mysqli_close($dbc);
		}
		
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
	
	echo "<br>";
		
	//SEARCH FORM
	echo "<div id='content-search'>";
	echo '<form action="members.php" method="POST">
		  <b>First Name:</b> <input type="text" name="first_name" size="30" maxlength="100">
		  <b>Last Name:</b> <input type="text" name="last_name" size="30" maxlength="100">
		  <b>E-Mail:</b> <input type="text" name="email" size="45" maxlength="100"><br>';
		  /*<b>Results:</b> <select name="results">
		  					<option>10</option>
    						<option>20</option>
    						<option>50</option>
						  </select><br>*/
	echo '<input type="submit" value="Search">
		  </form>';
	echo "</div>";
	
	//set LIMIT for no. of USERS displayed per PAGE
	$rec_limit = 10;
		
	//count no. of ITEMS
	require ('../connect_db.php');
	$q = "SELECT count(user_id) FROM users";
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
	
	//retrive PROFILE data from DATABASE
	$q = "SELECT user_id, first_name, last_name, email, profile_pic FROM users ORDER BY first_name ASC LIMIT $offset, $rec_limit";
	$r = mysqli_query($dbc, $q);
	
	if(!$r)
	{
		die('Could not get data: ' . mysql_error());
	}
	
	echo "<div id='content-text'>
			<h1>Browsing Members</h1>
		  </div>";
	
	echo "<div id='main1'>";	
	
	while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
	{
			
		//get PROFILE PIC link
		$profile_pic = "{$row['profile_pic']}" . ".jpg";
			
		//print DETAILS on SCREEN
		echo "<div id='left1'>";
		echo "<a href='profile.php?id={$row['user_id']}'><img src='profiles/profile_pics/$profile_pic' height='150px' width='150px'></a><br>";
		echo "<b>Name:<b> <a href='profile.php?id={$row['user_id']}'>{$row['first_name']} {$row['last_name']}</a><br>";
		echo "<b>Email: <b>{$row['email']}<br>";
		echo '</div>';
		echo '<br>';
	}
	
	
	
	echo "<div id='left1'>
			<h1>Browsing Members</h1>
		  </div>";
	
	
	echo "<div id='right1'>
			<h1>Browsing Members</h1>
		  </div>";
	
	echo "</div>";
	
	
	
	if($page == 0)
	{
		if($left_rec < $rec_limit)
		{
			echo "<div id='content-text'>
					Nothing more to display
				  </div>";
		
		}else{
			
			echo "<a href=\"members.php?page=$page\">Next 10 Records</a>";
			
		}
		
	
	}else if($left_rec < $rec_limit)
	{
		$last = $page - 2;
		echo "<a href=\"members.php?page=$last\">Last 10 Records</a>";
	
	}else{
		
		$last = $page - 2;
		echo "<a href=\"members.php?page=$last\">Last 10 Records</a> |";
		echo "<a href=\"members.php?page=$page\">Next 10 Records</a>";
		
	}
	
	echo "</div>";
	
	mysqli_close($dbc);
}

//PAGE FOOTER
include ('includes/footer.html');
?>