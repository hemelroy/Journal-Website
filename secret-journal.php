<?php
	session_start(); 
	
	$error = ""; 
	
	if(array_key_exists("logout", $_GET)){
		unset($_SESSION); 
		setcookie("userid", "", time()-60*60); 
		$_COOKIE["userid"] = "";
	}
	
	else if ((array_key_exists("userid", $_SESSION) AND $_SESSION['userid']) OR (array_key_exists("userid", $_COOKIE) AND $cookie['userid'])){
		header("Location: secret-journal-logged-in.php"); 
	}
	
	include("connection.php"); 
	
	if(array_key_exists("submit", $_POST)){
		
		if(!$_POST['email']){
				$error.="<p>An email address is required</p>"; 
		}
		
		if(!$_POST['password']){
				$error.="<p>A password is required</p>"; 
		}
		
		if($error != ""){
			$error = "<p>There were error(s) in your form</p>".$error; 
		}
		else{
			if ($_POST['signUp'] == '1'){
				$query = "SELECT `userid` FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])." ' LIMIT 1"; 
				
				$result = mysqli_query($link, $query); 
				
				if(mysqli_num_rows($result) > 0){
					$error = "That email address is taken";
				}
				else{
					$query = "INSERT INTO users (email, password) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."','".mysqli_real_escape_string($link, $_POST['password'])."')";
					
					if(!mysqli_query($link, $query)){
						$error = "<p>Could not sign you up - please try again later</p>";
					}
					else{
						
						$query = "UPDATE users SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."'WHERE userid = ".mysqli_insert_id($link)." LIMIT 1"; 
						
						mysqli_query($link, $query);
						
						$_SESSION['userid'] = mysqli_insert_id($link);
						
						if($_POST['stayLoggedIn'] == '1'){
								setcookie("userid", mysqli_insert_id($link), time() + 60*60*24); 
						}
						
						header("Location: secret-journal-logged-in.php"); 
					}
				}
			}
			else{
				
				$query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
				
				$result = mysqli_query($link, $query); 
				
				$row = mysqli_fetch_array($result); 
				
				if(isset($row)){
					
					$hashedPassword = md5(md5($row['userid']).$_POST['password']);
					
					if($hashedPassword == $row['password']){
						
						$_SESSION['userid'] = $row['userid']; 
						
						if($_POST['stayLoggedIn'] == '1'){

							setcookie("userid", $row['userid'], time() + 60*60*24); 
						}
						header("Location: secret-journal-logged-in.php"); 
					}
					else{
						$error = "That email/password combination could not be found";
					}
				}
				else{
					$error = "That email/password combination could not be found"; 
				}
			}
		}
	}
?>

<?php include("header.php"); ?>
  
	<div class ="container" id="homePageContainer"> 
	
		
		<h1>Secret Journal</h1> 
		<p><strong>Store your thoughts, accessible anywhere</strong></p>
		<div id="error"><?php echo $error; ?></div>

		<form method = "post" id="signupForm">
			<p>Interested? Sign up now</p>
			<fieldset class="form-group">
				<input type = "email" class ="form-control" name = "email" placeholder = "Email">
			</fieldset>
			
			<fieldset class="form-group">
				<input type = "password" class ="form-control" name = "password" placeholder = "Password">
			</fieldset>
			
			<div class="checkbox">
				<label>
					<input type="checkbox" name = "stayLoggedIn" value = 1>
					Stay logged in
				</label>
			</div>
			
			<fieldset class="form-group">
				<input type = "hidden" name = "signUp" value = "1">
				<input type="submit" class = "btn btn-success" name = "submit" value="Sign Up">
			</fieldset>
			
			<p> <a class="toggleForms" >Log In</a> </p>
		</form>

		<form method = "post" id="loginForm">
			<p>Log in with your email and password</p>
			<fieldset class="form-group">
				<input type class ="form-control"= "email" name = "email" placeholder = "Email">
			</fieldset>
			
			<fieldset class="form-group">
				<input type class ="form-control" = "password" name = "password" placeholder = "Password">
			</fieldset>
			
			<div class = "checkbox">
				<label>
					<input type="checkbox" name = "stayLoggedIn" value = 1>
					Stay logged in
				</label>
			</div>
			
			<fieldset class="form-group">
				<input type = "hidden" name = "signUp" value = "0">
				<input type="submit" class = "btn btn-success" name = "submit" value="Log In">
			</fieldset>
			
			<p><a class="toggleForms" >Sign In</a></p>
		</form>
	</div> 

    <?php include("footer.php"); ?>