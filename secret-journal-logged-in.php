<?php

	session_start(); 
	$journalContent = ""; 
	
	if(array_key_exists("userid", $_COOKIE)){
		$SESSION['userid'] = $_COOKIE['userid'];
	}
	
	if(array_key_exists("userid", $_SESSION)){
		echo "<p>Logged In <a href='secret-journal.php?logout=1'>Log Out</a></p>"; 
		
		include("connection.php"); 
		
		$query = "SELECT journal FROM `users` WHERE userid = ".mysqli_real_escape_string($link, $_SESSION['userid'])." LIMIT 1"; 
		
		$row = mysqli_fetch_array(mysqli_query($link, $query));
		
		$journalContent = $row['journal']; 
	}
	else{
		header("Location: secret-journal.php"); 
	} 
	
	include("header.php");
?>

	<nav class="navbar navbar-toggleable-md navbar-light bg-faded navbar-fixed-top">
	  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <a class="navbar-brand" href="#">Secret Journal</a>
		
		<div class="pull-xs-right">
		  <a href='secret-journal.php?logout=1'><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Logout</button></a>
		</div>
	  </div>
	</nav>

	<div class="container-fluid" id="loggedin-container">
		<textarea id="journal" class="form-control"><?php echo $journalContent; ?></textarea>
	</div>
	
<?php
	include("footer.php"); 
?>