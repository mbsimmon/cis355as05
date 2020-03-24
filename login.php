<?php
session_start(); 

require '../database/database.php';

if ( !empty($_POST)) { //$_POST filled

	$username = $_POST['username'];
	$password = $_POST['password'];
	$passwordhash = MD5($password);
		
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM Users WHERE (user_username = ? OR user_email = ?) AND user_password = ? LIMIT 1";
	$q = $pdo->prepare($sql);
	$q->execute(array($username,$username,$passwordhash));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	
	if($data) {//Successful login
		$_SESSION['id'] = $data['user_id'];
		$sessionid = $data['user_id'];
		$_SESSION['title'] = $data['user_title'];
		Database::disconnect();
		header("Location: index.php?id=$sessionid");
		exit();
	}
	else {//Unsuccessful login
		Database::disconnect();
		header("Location: loginerror.html");
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
		<div class="span10 offset1">
			<div class="row">
				<h3>Login</h3>
			</div>
			<form class="form-horizontal" action="login.php" method="post">
								  
				<div class="control-group">
					<label class="control-label">Username or Email)</label>
					<div class="controls">
						<input name="username" type="text"  placeholder="name@email.com" required> 
					</div>	
				</div> 
				<div class="control-group">
					<label class="control-label">Password</label>
					<div class="controls">
						<input name="password" type="password" placeholder="Password" required> 
					</div>	
				</div> 
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Sign in</button>
					&nbsp; &nbsp;
					<a class="btn btn-primary" href="createuser.php">Create an Account</a>
				</div>
			</form>
		</div>	
    </div>
  </body>
</html>