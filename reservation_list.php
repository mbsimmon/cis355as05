<?php 
session_start();
if(!isset($_SESSION['id'])){ // if "user" not set,
	session_destroy();
	header('Location: ../login.php');     // go to login page
	exit;
}

require '../html_head.php'; ?>

<body>
    <div class="container">
    		<div class="row">
    			<h3>Reservations</h3>
    		</div>
			<div class="row">
				<p>
					<a href="create.php" class="btn btn-success">Create</a>
					<a class="btn" href="../Kennels/kennel_list.php">View Kennels</a>
	                <a class="btn" href="../Placements/placement_list.php">View Placements</a>
				</p>
				
				<table class="table table-striped table-bordered">
		              <thead>
		                <tr>
		                  <th>Customer ID</th>
                          <th>Check-In Date</th>
                          <th>Check-In Time</th>
                          <th>Check-Out Date</th>
                          <th>Check-Out Time</th>
    		              <th>Action</th>
		                </tr>
		              </thead>
		              <tbody>
						<?php 
							require '../../database/database.php';
							$pdo = Database::connect();
							$sql = 'SELECT * FROM Reservations ORDER BY res_checkin_date ASC';
						    foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['res_cust_id'] . '</td>';
								echo '<td>'. $row['res_checkin_date'] . '</td>';
								echo '<td>'. $row['res_checkin_time'] . '</td>';
								echo '<td>'. $row['res_checkout_date'] . '</td>';
								echo '<td>'. $row['res_checkout_time'] . '</td>';
								echo '<td width=250>';
								echo '<a class="btn" href="read.php?id='.$row['res_id'].'">Read</a>';
								echo '&nbsp;';
								echo '<a class="btn btn-success" href="update.php?id='.$row['res_id'].'">Update</a>';
								echo '&nbsp;';
								echo '<a class="btn btn-danger" href="delete.php?id='.$row['res_id'].'">Delete</a>';
								echo '</td>';
								echo '</tr>';
							}
							Database::disconnect();
						?>
				      </tbody>
	            </table>
    	</div>
    </div> <!-- /container -->
  </body>
</html>