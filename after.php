<?php 
	session_start();

	/* DATABASE INFO 
		Change these values to use your own server (requires at least PHP 5)
	*/
	$dbHost = "10.200.8.60";
	$dbName = "scripting";
	$dbUser = "ikirk";
	$dbPassword = "umbc";
	$dbTable = "project1";

	// Grabs session information from previous page, right.php, for database storage
	$name = $_SESSION["name"];
	$campusID = $_SESSION["campusID"];
	$email = $_SESSION["email"];
	$contactNum = $_SESSION["contactNum"];
	$classesTaken = $_SESSION["classes"];

	// Create connection
	$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$classes = $classesTaken;
	
	$classes = "";

	// Loops through the posted information from previous page, right.php
	// Constructs $classes with checked classes separated by spaces in this way:
	// CMSC201 CMSC202 CMSC203
	foreach($_POST as $class) {
		if($class != "SUBMIT") {
			$classes .= $class . " ";
		}
	}

	// Checks if user was determined to be in the database already from previous page
	// If they were then it updates their record
	// Otherwise it creates a new record for them
	if($_SESSION["inDB"]) {
		$sql = "UPDATE $dbTable SET classes=\"$classes\" WHERE name=\"$name\"";
	} else {
		$sql = "INSERT INTO $dbTable (name, campusid, email, contactnum, classes)
				VALUES (\"$name\", \"$campusID\", \"$email\", \"$contactNum\", \"$classes\")";
	}

	// Prints a message on successful database entry
	if ($conn->query($sql) === TRUE) {
	    echo "<center><h3 style='color: grey;'>Record updated successfully</h3></center>";
	} else {
	    echo "Error updating record: " . $conn->error;
	}

	// Resets session variables
	session_unset();
	session_destroy();

	// Will reload right.php after 4 seconds
	header( "refresh:4; url=right.php" ); 
	exit();
 ?>