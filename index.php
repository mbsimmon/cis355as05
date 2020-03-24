<?php
session_start();
if(!isset($_SESSION['id'])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
?>
<h2>Welcome to the Happy Pups Reservation System</h2><br>
<h3>What would you like to do?</h3><br>

<!-- <a href="Customers/customer_list.php">View/Edit Customers</a><br> -->
<a href="Reservations/reservation_list.php">View/Edit Reservations</a><br>
<a href="Placements/placement_list.php">View/Edit Kennel Placements</a><br>
<a href="Kennels/kennel_list.php">View/Edit Kennels</a><br>
