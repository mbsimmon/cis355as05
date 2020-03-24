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
    			<h3>Kennels</h3>
    		</div>
			<div class="row">
			    <a class="btn" href="../Reservations/reservation_list.php">View Reservations</a>
	            <a class="btn" href="../Placements/placement_list.php">View Placements</a>
				<table class="table table-striped table-bordered">
		              <thead>
		                <tr>
		                  <th>Number</th>
                          <th>Type</th>
                          <th>Door Size</th>
                          <th>Side</th>
    		              <th>Action</th>
		                </tr>
		              </thead>
		              <tbody>
						<?php 
							require '../../database/database.php';
							$pdo = Database::connect();
							$sql = 'SELECT * FROM Kennels ORDER BY kennel_id ASC';
						    foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['kennel_id'] . '</td>';
								echo '<td>'. $row['kennel_type'] . '</td>';
								echo '<td>'. $row['kennel_door_size'] . '</td>';
								echo '<td>'. $row['kennel_side'] . '</td>';
								echo '<td width=250>';
								echo '<a class="btn" href="read.php?id='.$row['kennel_id'].'">Read</a>';
								echo '&nbsp;';
								echo '<a class="btn btn-success" href="update.php?id='.$row['kennel_id'].'">Update</a>';
								echo '&nbsp;';
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