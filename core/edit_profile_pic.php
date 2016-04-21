<?php

session_start();

//if not LOGGED-IN redirect to LOGIN
if(!isset($_SESSION['user_id']))
{
	require ('login_tools.php');
	load();
}

$page_title = "Edit Profile Pic";

//PAGE HEADER
include('includes/header.html');

?>

	<link rel="stylesheet" type="text/css" href="profiles/css/imgareaselect-animated.css" />
	<!-- scripts -->
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="profiles/js/jquery.imgareaselect.pack.js"></script>
	<script type="text/javascript" src="profiles/js/script.js"></script>
	<style>
	img#uploadPreview{
		border: 0;
		border-radius: 3px;
		-webkit-box-shadow: 0px 2px 7px 0px rgba(0, 0, 0, .27);
		box-shadow: 0px 2px 7px 0px rgba(0, 0, 0, .27);
		margin-bottom: 30px;
		overflow: hidden;
	}
	</style>

<div class="content">
	<h1>Edit Profile Pic</h1>
	<!-- image preview area-->
	<img id="uploadPreview" style="display:none;"/>
	
	<!-- image uploading form -->
	<form action="profiles/upload.php" method="post" enctype="multipart/form-data">
		
		<?php
		//provide USER-ID of USER to update script
		echo '<input type="hidden" name="form_user_id" value="'.$_SESSION['user_id'].'" />';
		?>
		
		<input id="uploadImage" type="file" accept="image/jpeg" name="image" /><br>
		<input type="submit" value="Upload">

		<!-- hidden inputs -->
		<input type="hidden" id="x" name="x" />
		<input type="hidden" id="y" name="y" />
		<input type="hidden" id="w" name="w" />
		<input type="hidden" id="h" name="h" />
	</form>
</div>

<?php

	//PAGE FOOTER
	include('includes/footer.html');
?>