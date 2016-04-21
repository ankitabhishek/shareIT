
<?php
$dbc = 
mysqli_connect('localhost', 'root', '', 'share_it')
OR die(mysqli_connect_error());
mysqli_set_charset($dbc, 'UTF-8');
