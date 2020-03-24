<?php 
session_start();
if(!isset($_SESSION['id'])){ // if "user" not set,
	session_destroy();
	header('Location: ../login.php');     // go to login page
	exit;
}
	
	require '../../database/database.php';
	if ( !empty($_POST)) {
		// keep track validation errors
		$customerError = null;
		$checkinError = null;
		$checkoutError = null;
		
		// keep track post values
		$customer_id = $_POST['customer_id'];
		$checkin_date = $_POST['checkin_date'];
		$checkin_time = $_POST['checkin_time'];
		$checkout_date = $_POST['checkout_date'];
		$checkout_time = $_POST['checkout_time'];
		
		// validate input
		$valid = true;
		if (empty($customer_id)) {
			$customerError = 'Please enter customer number';
			$valid = false;
		}
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
			$sql = "INSERT INTO Reservations (res_cust_id, res_checkin_date, res_checkin_time, res_checkout_date, res_checkout_time) values (?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($customer_id,$checkin_date,$checkin_time,$checkout_date,$checkout_time));
			Database::disconnect();
			header("Location: reservation_list.php");
		}
	}
?>


<!DOCTYPE html>
<html lang="en">
<?php require '../html_head.php'; ?>

<body>
    <div class="container">
    
    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Make a Reservation</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="create.php" method="post">
					  <div class="control-group <?php echo !empty($customerError)?'error':'';?>">
					    <label class="control-label">Customer ID</label>
					    <div class="controls">
					      	<input name="customer_id" type="text"  placeholder="Customer ID" value="<?php echo !empty($customer_id)?$customer_id:'';?>">
					      	<?php if (!empty($customerError)): ?>
					      		<span class="help-inline"><?php echo $customerError;?></span>
					      	<?php endif; ?>
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
						  <button type="submit" class="btn btn-success">Reserve</button>
						  <a class="btn" href="reservation_list.php">Back</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>