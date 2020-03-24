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
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM Reservations where res_id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	Database::disconnect();
	
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$id = $_POST['id'];
		
		// delete data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM Reservations  WHERE res_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		Database::disconnect();
		header("Location: reservation_list.php");
		
	} 
?>

<!DOCTYPE html>
<html lang="en">
<?php require '../html_head.php'; ?>

<body>
    <div class="container">
    
    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Cancel A Reservation</h3>
		    		</div>
		    		
	    			<form class="form-horizontal" action="delete.php" method="post">
	    			  <input type="hidden" name="id" value="<?php echo $id;?>"/>
	    			  <p class="alert alert-error">Are you sure you want to cancel your reservation?</p>
					  <div class="form-horizontal" >
					  <div class="control-group">
					    <label class="control-label">Customer ID</label>
					    <div class="controls">
						    <label class="checkbox">
						     	<?php echo $data['res_cust_id'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <label class="control-label">Check-In Date</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['res_checkin_date'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <label class="control-label">Check-In Time</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['res_checkin_time'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <label class="control-label">Check-Out Date</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['res_checkout_date'];?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group">
					    <label class="control-label">Check-Out Time</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['res_checkout_time'];?>
						    </label>
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-danger">Yes</button>
						  <a class="btn" href="reservation_list.php">No</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>