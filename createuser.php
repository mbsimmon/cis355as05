<?php
session_start();
	
require '../database/database.php';

if ( !empty($_POST)) {

	// initialize user input validation variables
	$usernameError = null;
	$emailError = null;
	$passwordError = null;
	$titleError = null;
	
	// initialize $_POST variables
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$passwordHash = MD5($password);
	$title =  $_POST['title'];
	
	// initialize $_FILES variables
	$fileName = $_FILES['image']['name'];
	$tmpName  = $_FILES['image']['tmp_name'];
	$fileSize = $_FILES['image']['size'];
	$fileType = $_FILES['image']['type'];
	$content = file_get_contents($tmpName);

	// validate user input
	$valid = true;
	if (empty($username)) {
		$usernameError = 'Please enter username';
		$valid = false;
	}
	// do not allow 2 records with same email address!
	if (empty($email)) {
		$emailError = 'Please enter valid email address';
		$valid = false;
	} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$emailError = 'Please enter a valid email address';
		$valid = false;
	}
	if (empty($password)) {
		$passwordError = 'Please enter password';
		$valid = false;
	}
	if (empty($title)) {
		$titleError = 'Please enter title';
		$valid = false;
	}

	$pdo = Database::connect();
	$sql = "SELECT * FROM Users";
	foreach($pdo->query($sql) as $row) {
		if($email == $row['user_email']) {
			$emailError = 'Email has already been registered!';
			$valid = false;
		}
		if($username == $row['user_username']) {
			$usernameError = 'Username has already been registered!';
			$valid = false;
		}
	}
	Database::disconnect();
	
	// email must contain only lower case letters
	if (strcmp(strtolower($email),$email)!=0) {
		$emailError = 'email address can contain only lower case letters';
		$valid = false;
	}

	// restrict file types for upload
	$types = array('image/jpeg','image/gif','image/png');
	if($fileSize > 0) {
		if(in_array($_FILES['image']['type'], $types)) {
		}
		else {
			$filename = null;
			$filetype = null;
			$filesize = null;
			$filecontent = null;
			$imageError = 'improper file type';
			$valid=false;
			
		}
	}
	// insert data
	if ($valid) 
	{
		$fp = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);
		$code = rand(10000,99999);
		
		$pdo = Database::connect();
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO Users (user_username,user_email,user_password,user_image,user_title,user_validated,user_code) values(?, ?, ?, ?, ?, 0, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($username,$email,$passwordHash,$content,$title,$code));
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM Users WHERE user_email = ? AND user_password = ? LIMIT 1";
		$q = $pdo->prepare($sql);
		$q->execute(array($email,$passwordHash));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		$_SESSION['id'] = $data['user_id'];
		$_SESSION['title'] = $data['user_title'];
		
		Database::disconnect();
		header("Location: index.php");
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
				<h3>Create a User</h3>
			</div>
			<form class="form-horizontal" action="createuser.php" method="post" enctype="multipart/form-data">

				<div class="control-group <?php echo !empty($usernameError)?'error':'';?>">
					<label class="control-label">Username</label>
					<div class="controls">
						<input name="username" type="text"  placeholder="Username" value="<?php echo !empty($username)?$username:'';?>">
						<?php if (!empty($usernameError)): ?>
							<span class="help-inline"><?php echo $usernameError;?></span>
						<?php endif; ?>
					</div>
				</div>				
				<div class="control-group <?php echo !empty($emailError)?'error':'';?>">
					<label class="control-label">Email</label>
					<div class="controls">
						<input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
						<?php if (!empty($emailError)): ?>
							<span class="help-inline"><?php echo $emailError;?></span>
						<?php endif;?>
					</div>
				</div>
				<div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
					<label class="control-label">Password</label>
					<div class="controls">
						<input id="password" name="password" type="password"  placeholder="password" value="<?php echo !empty($password)?$password:'';?>">
						<?php if (!empty($passwordError)): ?>
							<span class="help-inline"><?php echo $passwordError;?></span>
						<?php endif;?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Title</label>
					<div class="controls">
						<select class="form-control" name="title">
							<!--<option value="Client" selected>Client</option> -->
							<option selected value="Admin" >Administrator</option>
						</select>
					</div>
				</div>
				<div class="control-group <?php echo !empty($imageError)?'error':'';?>">
					<label class="control-label">Picture</label>
					<div class="controls">
						<input type="hidden" name="MAX_FILE_SIZE" value="16000000">
						<input name="image" type="file" id="image">
						
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
					<a class="btn" href="login.php">Back</a>
				</div>
			</form>
		</div>	
    </div>
  </body>
</html>