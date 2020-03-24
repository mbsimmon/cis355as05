<?php 

session_start();
if(!isset($_SESSION['id'])){ // if "user" not set,
	session_destroy();
	header('Location: ../login.php');     // go to login page
	exit;
}

require '../../database/database.php';

if ( !empty($_POST)) {

	// initialize user input validation variables
	$reservationError = null;
	$kennelError = null;
	
	// initialize $_POST variables
	$reservation = $_POST['reservation'];
	$kennel = $_POST['kennel'];
	
	// validate user input
	$valid = true;
	if (empty($kennel)) {
		$kennelError = 'Please choose a kennel';
		$valid = false;
	}
	if (empty($reservation)) {
		$reservationError = 'Please choose a reservation';
		$valid = false;
	} 
		
	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO Placements (place_kennel_id,place_res_id) values(?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($kennel,$reservation));
		Database::disconnect();
		header("Location: placement_list.php");
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
				<h3>Assign a Reservation to a Kennel</h3>
			</div>
	
			<form class="form-horizontal" action="create.php" method="post">
		
				<div class="control-group">
					<label class="control-label">Reservation</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM Reservations ORDER BY res_checkin_date ASC';
							echo "<select class='form-control' name='reservation' id='res_id'>";
							foreach ($pdo->query($sql) as $row) {
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
								echo "<option value='" . $row['kennel_id'] . " '> " . $row['kennel_type'] . " Kennel, " . $row['kennel_door_size'] . " Door - " . $row['kennel_side']. "</option>";

							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
						<a class="btn" href="placement_list.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
    </div> <!-- end div: class="container" -->

  </body>
</html>