<?php
$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
$max_file_size = 200 * 1024; #200kb
$nw = $nh = 200; # image with # height

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	require ('../../connect_db.php');
	$form_user_id = mysqli_real_escape_string($dbc, trim($_POST['form_user_id']));
	
	if ( isset($_FILES['image']) ) {
		if (! $_FILES['image']['error'] && $_FILES['image']['size'] < $max_file_size) {
			$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
			if (in_array($ext, $valid_exts)) {
				
					$file_name = uniqid();
					$path = 'profile_pics/' . $file_name . '.' . $ext;
					$size = getimagesize($_FILES['image']['tmp_name']);

					$x = (int) $_POST['x'];
					$y = (int) $_POST['y'];
					$w = (int) $_POST['w'] ? $_POST['w'] : $size[0];
					$h = (int) $_POST['h'] ? $_POST['h'] : $size[1];

					$data = file_get_contents($_FILES['image']['tmp_name']);
					$vImg = imagecreatefromstring($data);
					$dstImg = imagecreatetruecolor($nw, $nh);
					imagecopyresampled($dstImg, $vImg, 0, 0, $x, $y, $nw, $nh, $w, $h);
					imagejpeg($dstImg, $path);
					imagedestroy($dstImg);
					
					//extract DETAILS from DATABASE
					$q = "SELECT * FROM users WHERE user_id = '$form_user_id'";
					$r = mysqli_query($dbc, $q);
						
					//fetch DETAILS in ARRAY
					$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
					
					//fetch OLD IMAGE NAME
					$old_profile_pic = "profile_pics/" . "{$row['profile_pic']}" . ".jpg";
					
					//delete OLD IMAGE
					unlink($old_profile_pic);
					
					//UPDATE image name in database
					$q = "UPDATE users SET profile_pic = '$file_name' WHERE user_id = '$form_user_id'";
					$r = mysqli_query($dbc, $q);
					
					//redirect to PROFILE after UPDATION
					if($r)
					{
						header('Location: ../profile.php');
					}					

				} else {
					echo 'unknown problem!';
				} 
		} else {
			echo 'file is too small or large';
		}
	} else {
		echo 'file not set';
	}
} 

//if attempt is made to access this page directly
else {
	
	$page = '../profile_pic.php';
	$url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	$url = rtrim($url, '/\\');
	$url .= '/'.$page;
	
	header("Location: $url");
	exit();
}

?>