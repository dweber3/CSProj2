<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Old School Script Relay</title>
    


    
    <link rel="stylesheet" href="normalize.css">

    
  </head>

  <body>




<?php 
  session_start();

  /* DATABASE INFO 
    Change these values to use your own server (requires at least PHP 5)
  */
  // $dbHost = "10.200.8.60";
  // $dbName = "scripting";
  // $dbUser = "ikirk";
  // $dbPassword = "umbc";
  // $dbTable = "project1";
    $dbHost = "45.55.136.207";
    $dbName = "CS433";
    $dbUser = "cs_student";
    $dbPassword = "cs_student_proj2";
    $dbTable = "cs_student";


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
      echo "<center><h3 style='color: grey;'></h3></center>";
  } else {
      echo "Error updating record: " . $conn->error;
  }

  // Resets session variables
  //session_unset();
  //session_destroy();

  // Will reload right.php after 4 seconds
 // header( "refresh:4; url=right.php" ); 
 // exit();
 ?>

    <body>
<canvas id ='oldSkool'></canvas>
<script>
  var db = document.body;
  var c = document.getElementById('oldSkool');
  var $ = c.getContext('2d');
  c.width = window.innerWidth;
  c.height = window.innerHeight;
  var resume;
  function relay(){
  window.requestAnimationFrame(relay);
  resume();
}
</script>
  <!--Script to Relay!-->
<script>

  //Record updated successfully 


/* Documation
  Project 2
    Team member:  Adane Gebresenbet, Daniel Weber, Teng Luo
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation here
    We could copy past our documentation hereWe could copy past our documentation here



*/

relay();
</script>

<script>
 
_s = db.querySelectorAll("script")[1].innerHTML.split("\n");
  x = 0; 
  y = 1; 
  c.width = w = window.innerWidth;
  c.height = h = _s.length*20;
  db.style.margin = 0;
  db.style.background = "hsla(0,0%,0%,1)";
  db.style.overflow = "hidden";
  $.textBaseline = "top";
  $.font = "1.1em monospace";

resume = function(){
    $.globalCompositeOperation = "source-over";
    $.shadowBlur = 0; $.fillStyle = "hsla(0,0%,0%,0.4)";
    $.fillRect(0,0,w,h);
    $.shadowColor = "hsla(120,100%,50%,0.5)";
    $.shadowBlur = 9; $.fillStyle = "hsla(120,100%,20%,1)";
    $.globalCompositeOperation = "lighter";
  _s.forEach(function(t, i) {
      if (i <= y) {
        if (i == y) { t = t.substr(0, x); }
        $.fillText(t, 100, 100+i*16);		}
	});
                 
	$.fillStyle = "hsla(120,100%,50%,1)";
	$.fillRect(100+$.measureText(_s[y].substr(0, x)).width, 102+y*16, 10, 14);
	x++;
		if (x >= _s[y].length) { y++; x = 0;}
			if (y*16 > innerHeight-200) { $.translate(0, -0.5);}
				if (y >= _s.length-1) { window.clearInterval();}}
  

  
//END SCRIPT RELAY _
</script>




</body>


    
    
    
    
  </body>

</html>




