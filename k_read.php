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
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM Kennels where kennel_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
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
		    			<h3>View Kennels</h3>
		    		</div>
		    		
	    			<div class="form-horizontal" >
					  <div class="control-group">
					    <label class="control-label">Kennel Number</label>
					    <div class="controls">
						    <label class="checkbox">
						     	<?php echo $data['kennel_id'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <label class="control-label">Type</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['kennel_type'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <label class="control-label">Door Size</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['kennel_door_size'];?>
						    </label>
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
						  <a class="btn" href="kennel_list.php">Back</a>
					   </div>
					</div>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>