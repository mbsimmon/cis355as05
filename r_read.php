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
		header("Location: reservation_list.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM Reservations where res_id = ?";
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
		    			<h3>View Reservation</h3>
		    		</div>
		    		
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
						  <a class="btn" href="reservation_list.php">Back</a>
					   </div>
					</div>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>