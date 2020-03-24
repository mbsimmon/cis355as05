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
	}
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$checkinError = null;
		$checkoutError = null;
		
		// keep track post values
		$checkin_date = $_POST['checkin_date'];
		$checkin_time = $_POST['checkin_time'];
		$checkout_date = $_POST['checkout_date'];
		$checkout_time = $_POST['checkout_time'];
		
		// validate input
		$valid = true;
		if (empty($checkin_date)) {
			$checkinError = 'Please enter check-in date';
			$valid = false;
		}
		
		if (empty($checkout_date)) {
			$checkoutError = 'Please enter check-out date';
			$valid = false;
		}
		if (empty($checkin_time)) {
			$checkin_time = NULL;
		}
		
		if (empty($checkout_time)) {
			$checkout_time = NULL;
		}
		
		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE Reservations set res_checkin_date = ?, res_checkin_time = ?, res_checkout_date = ?, res_checkout_time = ? WHERE res_id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($checkin_date,$checkin_time,$checkout_date,$checkout_time,$id));
			Database::disconnect();
			header("Location: reservation_list.php");
		}
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM Reservations where res_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$customer_id = $data['res_cust_id'];
		$checkin_date = $data['res_checkin_date'];
		$checkin_time = $data['res_checkin_time'];
		$checkout_date = $data['res_checkout_date'];
		$checkout_time = $data['res_checkout_time'];
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
		    			<h3>Update a Reservation</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="update.php?id=<?php echo $id?>" method="post">
					  <div class="control-group">
					    <label class="control-label">Customer ID</label>
					    <div class="controls">
						    <label class="checkbox">
						     	<?php echo $customer_id;?>
						    </label>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($checkinError)?'error':'';?>">
					    <label class="control-label">Check-In Date</label>
					    <div class="controls">
					      	<input name="checkin_date" type="date"  placeholder="" value="<?php echo !empty($checkin_date)?$checkin_date:'';?>">
					      	<?php if (!empty($checkinError)): ?>
					      		<span class="help-inline"><?php echo $checkinError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group">
					    <label class="control-label">Check-In Time</label>
					    <div class="controls">
					      	<input name="checkin_time" type="time"  placeholder="" value="<?php echo !empty($checkin_time)?$checkin_time:'';?>">
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($checkoutError)?'error':'';?>">
					    <label class="control-label">Check-Out Date</label>
					    <div class="controls">
					      	<input name="checkout_date" type="date"  placeholder="" value="<?php echo !empty($checkout_date)?$checkout_date:'';?>">
					      	<?php if (!empty($checkoutError)): ?>
					      		<span class="help-inline"><?php echo $checkoutError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group">
					    <label class="control-label">Check-Out Time</label>
					    <div class="controls">
					      	<input name="checkout_time" type="time"  placeholder="" value="<?php echo !empty($checkout_time)?$checkout_time:'';?>">
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Update</button>
						  <a class="btn" href="reservation_list.php">Back</a>
						</div>
					</form>
				</div>
    </div> <!-- /container -->
  </body>
</html>