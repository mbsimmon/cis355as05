<?php 

session_start();
if(!isset($_SESSION['id'])){ // if "user" not set,
	session_destroy();
	header('Location: ../login.php');     // go to login page
	exit;
}
	
	require '../../database/database.php';

	$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if ( null==$id ) {
		header("Location: kennel_list.php");
	}
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$typeError = null;
		$sizeError = null;
		
		// keep track post values
		$type = $_POST['type'];
		$size = $_POST['size'];
		
		// validate input
		$valid = true;
		if (empty($type)) {
			$typeError = 'Please enter type of kennel';
			$valid = false;
		}
		
		if (empty($size)) {
			$sizeError = 'Please enter door size';
			$valid = false;
		}
		
		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE Kennels set kennel_type = ?, kennel_door_size = ? WHERE kennel_id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($type,$size,$id));
			Database::disconnect();
			header("Location: kennel_list.php");
		}
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM Kennels where kennel_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$type = $data['kennel_type'];
		$size = $data['kennel_door_size'];
		Database::disconnect();
	}
?>


<!DOCTYPE html>
<html lang="en">
<?php require '../html_head.php'; ?>

<body>
    <div class="container">
    
    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Update a Kennel</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="update.php?id=<?php echo $id?>" method="post">
					  <div class="control-group">
					    <label class="control-label">Kennel Number</label>
					    <div class="controls">
						    <label class="checkbox">
						     	<?php echo $data['kennel_id'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($typeError)?'error':'';?>">
					    <label class="control-label">Type</label>
					    <div class="controls">
					      	<input name="type" type="text"  placeholder="Type" value="<?php echo !empty($type)?$type:'';?>">
					      	<?php if (!empty($typeError)): ?>
					      		<span class="help-inline"><?php echo $typeError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($sizeError)?'error':'';?>">
					    <label class="control-label">Door Size</label>
					    <div class="controls">
					      	<input name="size" type="text" placeholder="Door Size" value="<?php echo !empty($size)?$size:'';?>">
					      	<?php if (!empty($sizeError)): ?>
					      		<span class="help-inline"><?php echo $sizeError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group">
					    <label class="control-label">Side of Building</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['kennel_side'];?>
						    </label>
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Update</button>
						  <a class="btn" href="kennel_list.php">Back</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>