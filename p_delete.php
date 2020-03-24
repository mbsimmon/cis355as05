<?php

session_start();
if(!isset($_SESSION['id'])){ // if "user" not set,
	session_destroy();
	header('Location: ../login.php');     // go to login page
	exit;
}
require '../../database/database.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if user clicks "yes" (sure to delete), delete record

	$id = $_POST['id'];
	
	// delete data
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM Placements  WHERE place_id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: placement_list.php");
} 
else { // otherwise, pre-populate fields to show data to be deleted

	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "SELECT * FROM Placements where place_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		$sql = "SELECT * FROM Reservations where res_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($data['place_res_id']));
		$reservationdata = $q->fetch(PDO::FETCH_ASSOC);

		$sql = "SELECT * FROM Customers where cust_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($reservationdata['res_cust_id']));
		$customerdata = $q->fetch(PDO::FETCH_ASSOC);

		$sql = "SELECT * FROM Kennels where kennel_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($data['place_kennel_id']));
		$kenneldata = $q->fetch(PDO::FETCH_ASSOC);
	
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
				<h3>Delete Placement</h3>
			</div>
			<form class="form-horizontal" action="delete.php?id=<?php echo $id?>" method="post">
				<input type="hidden" name="id" value="<?php echo $id;?>"/>
				<p class="alert alert-error">Are you sure you want to delete this placement?</p>
				<div class="form-actions">
					<button type="submit" class="btn btn-danger">Yes</button>
					<a class="btn" href="placement_list.php">No</a>
				</div>
			</form>
			
			<!-- Display same information as in file: read.php -->
			
			<div class="form-horizontal" >
				<div class="control-group">
					<label class="control-label"><b>Customer Name</b></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $customerdata['cust_fname'] . " " . $customerdata['cust_lname'];?>
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><b>Reservation Date</b></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $reservationdata['res_checkin_date'] . " - " . $reservationdata['res_checkout_date'];?>
						</label>
					</div>
				</div>
				<br>
				<div class="control-group">
					<label class="control-label"><b>Kennel Reserved</b></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $kenneldata['kennel_id'] ;?>
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><b>Kennel Details</b></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo "Type: " .  $kenneldata['kennel_type'] . "<br>Door Size: " .  $kenneldata['kennel_door_size'] . "<br>Side: " .  $kenneldata['kennel_side'];?>
						</label>
					</div>
				</div>
				
				<div class="form-actions">
					<a class="btn" href="placement_list.php">Back</a>
				</div>
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
		
    </div> <!-- end div: class="container" -->
	
</body>
</html>