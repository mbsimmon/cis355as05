<?php
session_start();
if(!isset($_SESSION['id'])){ // if "user" not set,
	session_destroy();
	header('Location: ../login.php');     // go to login page
	exit;
}
require '../../database/database.php';

$id = $_GET['id'];

if ( !empty($_POST)) {

	// initialize user input validation variables
	$reservationError = null;
	$kennelError = null;
	
	// initialize $_POST variables
	$reservation = $_POST['reservation'];
	$kennel = $_POST['kennel'];
	
	// validate user input
	$valid = true;
	if (empty($reservation)) {
		$reservationError = 'Please choose a reservation';
		$valid = false;
	} 
	if (empty($kennel)) {
		$kennelError = 'Please choose a kennel';
		$valid = false;
	}
			
	if ($valid) { // if valid user input update the database
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "UPDATE Placements set place_res_id = ?, place_kennel_id = ? WHERE place_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($reservation,$kennel,$id));
		Database::disconnect();
		header("Location: placement_list.php");
	}
} else { // if $_POST NOT filled then pre-populate the form
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
	
	$reservation = $data['place_res_id'];
	$kennel = $data['place_kennel_id'];
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
				<h3>Update Assignment</h3>
			</div>
	
			<form class="form-horizontal" action="update.php?id=<?php echo $id?>" method="post">
		
				<div class="control-group">
					<label class="control-label">Customer</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM Reservations ORDER BY res_checkin_date ASC';
							echo "<select class='form-control' name='reservation' id='res_id'>";
							foreach ($pdo->query($sql) as $row) {
								if($row['res_id']==$reservation)
									echo "<option selected value='" . $row['res_id'] . " '> " . $row['res_cust_id'] . "</option>";
								else
									echo "<option value='" . $row['res_id'] . " '> " . $row['res_cust_id'] . "</option>";
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
			  
				<div class="control-group">
					<label class="control-label">Kennels</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM Kennels ORDER BY kennel_id ASC';
							echo "<select class='form-control' name='kennel' id='kennel_id'>";
							foreach ($pdo->query($sql) as $row) {
								if($row['kennel_id'==$kennel])
									echo "<option selected value='" . $row['kennel_id'] . " '> " . $row['kennel_type'] . " Kennel, " . $row['kennel_door_size'] . " Door - " . $row['kennel_side']. "</option>";
								else
									echo "<option value='" . $row['kennel_id'] . " '> " . $row['kennel_type'] . " Kennel, " . $row['kennel_door_size'] . " Door - " . $row['kennel_side']. "</option>";
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Update</button>
					<a class="btn" href="placement_list.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->

  </body>
</html>