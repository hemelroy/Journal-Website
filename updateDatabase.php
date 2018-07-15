<?php
	session_start(); 
	
	if(array_key_exists("content", $_POST)){
		
		include("connection.php"); 
		
		$query = "UPDATE `users` SET `journal` = '".mysqli_real_escape_string($link, $_POST['content'])."' WHERE `userid` = ".mysqli_real_escape_string($link, $_SESSION['userid'])." LIMIT 1";
		
		mysqli_query($link, $query);
			
	}
?>