
<?php session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//Dbr XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/Dbr/xhtml1-transitional.dbr">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> </title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<link rel = "stylesheet" href = "aki_style.css" />
</head>
<body class="right">

<script type="text/javascript">
	
	// Purpose: Remove disabled attribute from all checked boxes
	// 			When data is posted, disabled attributes are not sent
	//          regardless of it being checked.
	//          This function ensures all checked boxes are sent to database
	function beforeSubmit() {
        var boxes = document.getElementsByTagName('input');
        //Debug to check accuracy console.log("Called");
        for(var i = 0; i < boxes.length; i++) {
        	if(boxes[i].checked) {
        		boxes[i].disabled = false;
        	}
        }
    }


    /****************************************************************************
	* Function Name: showMe
	* Param: box - the checkbox you want to toggle on/off
	*
	* Used for when the user selects a class checkbox. First checks to see if the
	* checkbox is disabled. If it is disabled, it enables it and changes the color
	* to indicate that the user can select it.
	*
	* This is used as an onclick event for classes that are requirements for other
	* classes. 
	*
	* Example: 341 requires 203 - in the onclick for 203, adding showMe('cmsc341');
	* will activate the 341 checkbox and indicate to the user that it is available
	* for them to take.
	*****************************************************************************/
	function showMe (box) {

	    var show = document.getElementById(box);
	    var cbox = document.getElementById(box.replace(/\D/g,''));
		if (cbox.disabled == true){
			cbox.disabled = false;
			show.style.color = 'GoldenRod';
		}	
		else {
			//cbox.click();
			cbox.checked = false;
			cbox.disabled = true;
			show.style.color = 'lightgrey';
		}   
	}


	/************************************************************************************************
	* Function Name: selected
	* Params: box - the class being selected (Use the full class name, ie cmsc201)
	*
	* Toggles the class as selected or available. GoldenRod means the class is available,
	* black means the class is selected.
	*
	* Example: If the user selects cmsc201, in the onclick for cmsc201 include selected('cmsc201');
	*
	************************************************************************************************/
	function selected(box) {

		var show = document.getElementById(box);
		var cbox = document.getElementById(box.replace(/\D/g,''));

		if (cbox.disabled == false){
			if (show.style.color == 'black'){
				
				show.style.color = 'GoldenRod';
			}	
			else {
				show.style.color = 'black';
			}   
		}
	}


	/************************************************************************************************
	* Function Name: lockprev
	* Params: box - the class being selected (Use the full class name, ie cmsc201)
	*		  prev - the class being locked (because it is a requirement for the class selected)
	*
	* Toggles a requirement class as locked or unlocked depending on whether or not a class that 
	* requires it is selected. Prevents the user from unselecting a class that is a requirement for
	* another selected class. Uses a string of zeroes stored as 'dependency' in the checkbox elements
	* to keep track of dependent classes. 
	*
	* Example: The user selects 203 then selects 341. Since 341 requires 203, the onlick for 341 will include
	* lockprev('cmsc203', 'cmsc341'). This will then lock 203 so the user cannot uncheck it without first unchecking
	* 341 as well. 
	*
	* This works even when multiple dependent classes are selected. For instance, selecting 203, then 331 and 341 will
	* lock 203 until both are unselected. Simply include lockprev('cmsc203', 'cmsc331') in the 331 onclick and 
	* lockprev('cmsc203', 'cmsc341') in the 341 onclick.
	*
	************************************************************************************************/
	function lockprev(prev, box){
		var this_show = document.getElementById(box);
		var this_cbox = document.getElementById(box.replace(/\D/g,''));

		var prev_show = document.getElementById(prev);
		var prev_cbox = document.getElementById(prev.replace(/\D/g,''));

		var dep = prev_cbox.getAttribute('dependency');
		if(this_cbox.checked == true){
			prev_cbox.setAttribute('dependency', dep + 0);
		}
		else{
			dep = dep.substring(0, dep.length - 1);
			prev_cbox.setAttribute('dependency', dep);
		}

		
		if (prev_cbox.getAttribute('dependency').length != 1){
			//prev_show.style.color = 'blue';
			prev_cbox.disabled = true;
			prev_show.style.fontWeight = 'bold';
			prev_show.style.background = 'url("http://userpages.umbc.edu/~kayoung2/CMSC433/Project/images/lock_2-24.png") no-repeat';
			prev_show.style.backgroundPosition = "4px 4px";
		}
		else {
			prev_show.style.color = 'black';
			prev_show.style.fontWeight = 'normal';
			prev_show.style.background = 'url("") no-repeat';
			prev_cbox.disabled = false;
		}
	}


	/************************************************************************************************
	* Function Name: tworeq
	* Params: box - the class being selected (Use the full class name, ie cmsc201)
	*         require - the class that requires this class to activate.
	*
	* Used for classes that have two prerequisite classes. Classes with two prerequisite classes have an additional
	* attribute in their checkbox element called 'req' initialized with a string that has the number of prereq. classes
	* + 1 (So a class with 2 prereqs will have a string size of 3, 3 prereqs = 4, etc.). The class will only be marked
	* as available if all the prerequisite classes for it are also selected. 
	*
	* Example: 487 requires both 421 and 481 to take. In the checkbox element for 487, include the attribute req = '000'
	* since there are two required classes. Then in both the 421 and 481 checkboxes onclick, include tworeq('cmsc487', 'cmsc421');
	* and tworeq('cmsc487', 'cmsc421') respectively. Now both 421 and 481 need to be selected in order for 487 to become available.
	* Additionally, unselecting either will make it unavailable.
	*
	************************************************************************************************/
	function tworeq(require, box){
		var this_show = document.getElementById(box);
		var this_cbox = document.getElementById(box.replace(/\D/g,''));

		var prev_show = document.getElementById(require);
		var prev_cbox = document.getElementById(require.replace(/\D/g,''));

		var req = prev_cbox.getAttribute('req');

		if(this_cbox.checked != true){
			prev_cbox.setAttribute('req', req + 0);
		}
		else{
			req = req.substring(0, req.length - 1);
			prev_cbox.setAttribute('req', req);
		}

		if (prev_cbox.getAttribute('req').length != 1){
			prev_show.style.color = 'lightgrey';
			prev_cbox.checked = false;
			prev_cbox.disabled = true;
		}
		else {
			prev_show.style.color = 'GoldenRod';
			prev_cbox.disabled = false;
		}

	}



</script>






<?php

if (!empty($_POST))
{
	$valid = TRUE;
	$name = $_POST["studentName"];
	$CID = $_POST["campusID"];
	$email = $_POST["email"];
	$number = $_POST["contactNum"];

	if(preg_match('/[A-Z][a-z]* [A-Z][a-z]*/', $name) == 1){
		$name = test_input($name);
	}
	else{
		echo "Please enter your first and last name<br>";
		$valid = FALSE;
	}

	if(preg_match('/[A-Z][A-Z][0-9][0-9][0-9][0-9][0-9]/', $CID) == 1){
		$CID = test_input($CID);
	}
	else{
		echo "Please enter a valid campus ID<br>";
		$valid = FALSE;
	}

	if(preg_match('/.*@.*\..*/', $email) == 1 ){
		$email=trim($email);
		$email=stripslashes($email);
	}
	else{
		echo "Please enter a valid email<br>";
		$valid = FALSE;
	}
	$number = str_replace('-', '', $number);
	if(preg_match('/[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]/', $number) == 1){
		$number = test_input($number);
	}
	else{
		echo "Please enter a valid phone number <br>";
		$valid = FALSE;
	}

	
	if($valid) {
		
		/* DATABASE INFO 
			Change these values to use your own server (requires at least PHP 5)
		*/
		// $dbHost = "162.243.228.9";
		// $dbName = "CS433";
		// $dbUser = "cs_student";
		// $dbPassword = "swordfish";
		// $dbTable = "temp";

		$dbHost = "45.55.136.207";
		$dbName = "CS433";
		$dbUser = "cs_student";
		$dbPassword = "cs_student_proj2";
		$dbTable = "cs_student";

		// Create connection
		$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 

		$name=$_POST["studentName"];
		$campusID=$_POST["campusID"];
		$email=$_POST["email"];
		$contactNum=$_POST["contactNum"];
		$classes = "";

		// SQL Statement to grab current users in database
		$checkIfInDB = "SELECT name, campusid FROM $dbTable";
		$inDB = FALSE;
		$result = $conn->query($checkIfInDB);

		// Iterates over returned users from database
		if ($result->num_rows > 0) {
	    	while($row = $result->fetch_assoc()) {

	    		// Checks if the current user's name or campus id matches any in the databse
		        if ($name == $row["name"] || $campusID == $row["campusid"]) {
		        	$inDB = TRUE;
		    	}
		    }
		}

		$classesTaken = array();

		// If the user is in the database then we want to load what classes they have taken
		if($inDB) {
			$checkClassesTaken = "SELECT name, classes FROM $dbTable";
			$result = $conn->query($checkClassesTaken);

			if($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {

					// Once we found the current user again in the database it takes 
					// the classes field and creates an array out of it
					if ($name == $row["name"]) {
						$classesTaken = explode(" ", $row["classes"]);
					}
				}
			}

			// Creates a more usable string from the classesTaken array 
			foreach($classesTaken as $class) {
				$classes .= $class . " ";
			}
		}

		// Prints out a customized welcome message at the top of the page
		$nameUpper = strtoupper($name);
		echo "<br><div><center> WELCOME <span class='textstyleRed'>$nameUpper</span><br>Please select classes you have already taken</center><br>";
		//echo "Please select classes you have already taken<br>"
		// Saves session variables for the next page, after.php
		
		$_SESSION["name"] = $name;
		$_SESSION["campusID"] = $campusID;
		$_SESSION["email"] = $email;
		$_SESSION["contactNum"] = $contactNum;
		$_SESSION["classes"] = $classes;
		$_SESSION["inDB"] = $inDB;
		
	}

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


?>




	<form method="post" action="after.php" class="formStyle1" onSubmit="beforeSubmit();">
 			
<div class="title1"><span class="textstyleRed">2XX</span> Lv. CLASSES</div>


 			<div id='cmsc201' class='content1' class='content1' style="color: GoldenRod;" >
			<input type="checkbox" dependency='0' name="CMSC201" value="CMSC201" id="201" onclick="selected('cmsc201');showMe('cmsc202');"><label for="201"></label>  CMSC 201 
			<span class="textstyleDescription">: (4.00) Computer Science I for Majors </span>  <br></div>
			
			<div id='cmsc202' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="CMSC202" value="CMSC202" id="202" onclick="selected('cmsc202');lockprev('cmsc201', 'cmsc202');showMe('cmsc203');showMe('cmsc304');showMe('cmsc486');"><label for="202"></label>  CMSC 202  
		    <span class="textstyleDescription">: (4.00) Computer Science II for Majors </span> <br> </div>
			


			<div id='cmsc203' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="CMSC203" value="CMSC203" id="203" onclick="selected('cmsc203');lockprev('cmsc202', 'cmsc203');showMe('cmsc313');showMe('cmsc331');showMe('cmsc457');showMe('cmsc452');showMe('cmsc451');showMe('cmsc341');"><label for="203"></label> 	CMSC 203 <span class="textstyleDescription">: (3.00) Discrete Structures </span> <br>
			</div>

			<div id='cmsc232' class='content1' style="color: GoldenRod;">
			<input type="checkbox" dependency='0' name="cmsc232" value="CMSC232" id="232" onclick="selected('cmsc232');"><label for="232"></label>  
			CMSC 232 <span class="textstyleDescription">: (2.00) Advanced Java Techniques </span> <br></div>

			<div id='cmsc291' class='content1' style="color: GoldenRod;">
			<input type="checkbox" dependency='0' name="cmsc291" value="CMSC291" id="291" onclick="selected('cmsc291');"><label for="291"></label>  CMSC 291 <span class="textstyleDescription">: (1.00 - 4.00) Special Topics in Computer Science </span> <br>	</div>

			<div id='cmsc299' class='content1' style="color: GoldenRod;">
			<input type="checkbox" dependency='0' name="cmsc299" value="CMSC299" id="299" onclick="selected('cmsc299');"><label for="299"></label> CMSC 299 <span class="textstyleDescription">: (1.00 - 4.00) Independent Study in Computer Science </span> <br> </div>

	<div class="title1"><span class="textstyleRed">3XX</span> Lv. CLASSES</div>
 		

 			<div id='cmsc304' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="CMSC304" value="CMSC304" id="304" onclick="selected('cmsc304');lockprev('cmsc202', 'cmsc304');"><label for="304"></label> CMSC 304 <span class="textstyleDescription">: (3.00) Social and Ethical Issues in Information Technology </span> <br> </div>
			

 			<div id='cmsc313' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="CMSC313" value="CMSC313" id="313" onclick="selected('cmsc313');lockprev('cmsc203', 'cmsc313');tworeq('cmsc435', 'cmsc313');tworeq('cmsc421', 'cmsc313');showMe('cmsc411');"><label for="313"></label>  CMSC 313 &#9733 <span class="textstyleDescription">: (3.00) Computer Organization & Assembly Language Program. </span> <br> </div>
			

 			<div id='cmsc331' class='content1' style="color: lightgrey;">


				<input type="checkbox" dependency='0' disabled name="CMSC331" value="CMSC331" id="331" onclick="selected('cmsc331');lockprev('cmsc203', 'cmsc331');tworeq('cmsc431', 'cmsc331');showMe('cmsc433');showMe('cmsc432');showMe('cmsc473');"><label for="331"></label>  CMSC 331 &#9733 <span class="textstyleDescription">: (3.00) Principles of Programming Language </span> <br>
			</div>

			<div id='cmsc341' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="CMSC341" value="CMSC341" id="341" onclick="selected('cmsc341');lockprev('cmsc203', 'cmsc341');tworeq('cmsc421', 'cmsc341');tworeq('cmsc435', 'cmsc341'); tworeq('cmsc431', 'cmsc341');showMe('cmsc427'); showMe('cmsc436');showMe('cmsc471'); showMe('cmsc437'); showMe('cmsc447'); showMe('cmsc441'); showMe('cmsc443'); showMe('cmsc453'); showMe('cmsc455'); showMe('cmsc456');showMe('cmsc476');showMe('cmsc475');showMe('cmsc461');showMe('cmsc481');"><label for="341"></label>  CMSC 341 <span class="textstyleDescription">: (3.00) Data Structures </span> <br> </div>

			
			<div id='cmsc352' class='content1' style="color: GoldenRod;">
			<input type="checkbox" dependency='0' name="CMSC352" value="CMSC352" id="352" onclick="selected('cmsc352');"><label for="352"></label>  CMSC 352 <span class="textstyleDescription">: (3.00) Women, Gender, and Information Technology </span> <br></div>
			<div id='cmsc391' class='content1' style="color: GoldenRod;">
			<input type="checkbox" dependency='0' name="CMSC391" value="CMSC391" id="391" onclick="selected('cmsc391');"><label for="391"></label>  CMSC 391 <span class="textstyleDescription">: (1.00 - 4.00) Special Topics in Computer Science </span> <br></div> 
	
		<div class="title1"><span class="textstyleRed">4XX</span> Lv. CLASSES</div>

				<div id='cmsc411' class='content1' style="color: lightgrey;">

				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC411" value="CMSC411" id="411" onclick="selected('cmsc411');lockprev('cmsc313', 'cmsc411');"><label for="411"></label>  CMSC 411 &#9733 <span class="textstyleDescription">: (3.00) Computer Architecture </span> <br> </div> 

				<div id='cmsc421' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' req='000' disabled name="CMSC421" value="CMSC421" id="421" onclick="selected('cmsc421');lockprev('cmsc313', 'cmsc421');lockprev('cmsc341', 'cmsc421');tworeq('cmsc487', 'cmsc421');showMe('cmsc483');showMe('cmsc426');"><label for="421"></label> CMSC 421 &#9733<span class="textstyleDescription">: (3.00) Principles of Operating Systems </span> <br></div> 

				<div id='cmsc426' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC426" value="CMSC426" id="426" onclick="selected('cmsc426');lockprev('cmsc421', 'cmsc426');"><label for="426"></label> CMSC 426 <span class="textstyleDescription">: (3.00) Principles of Computer Security </span> <br> </div> 
				
				<div id='cmsc427' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC427" value="CMSC427" id="427" onclick="selected('cmsc427');lockprev('cmsc341', 'cmsc427');"><label for="427"></label> CMSC 427 <span class="textstyleDescription">: (3.00) Wearable Computing </span> <br></div> 
				
				<div id='cmsc431' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' req='000' disabled name="CMSC431" value="CMSC431" id="431" onclick="selected('cmsc431');lockprev('cmsc331', 'cmsc431');lockprev('cmsc341', 'cmsc431');"><label for="431"></label>  CMSC 431 <span class="textstyleDescription">: (3.00) Compiler Design Principles </span> <br></div> 
				
				<div id='cmsc432' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC432" value="CMSC432" id="432" onclick="selected('cmsc432');lockprev('cmsc331', 'cmsc432');"><label for="432"></label>  CMSC 432 <span class="textstyleDescription">: (3.00) Object-Oriented Programming Languages and Systems </span> <br></div> 
				
				<div id='cmsc433' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC433" value="CMSC433" id="433" onclick="selected('cmsc433');lockprev('cmsc331', 'cmsc433');"><label for="433"></label> CMSC 433 <span class="textstyleDescription">: (3.00) Scripting Languages </span> <br> </div> 
				
				<div id='cmsc435' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' req='000' disabled name="CMSC435" value="CMSC435" id="435" onclick="selected('cmsc435');lockprev('cmsc313', 'cmsc435');lockprev('cmsc341', 'cmsc435');tworeq('cmsc493', 'cmsc435');"><label for="435"></label>  CMSC 435 <span class="textstyleDescription">: (3.00) Computer Graphics </span> <br> </div> 
				
				<div id='cmsc436' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC436" value="CMSC436" id="436" onclick="selected('cmsc436');lockprev('cmsc341', 'cmsc436');"><label for="436"></label>  CMSC 436 <span class="textstyleDescription">: (3.00) Data Visualization </span> <br> </div> 
				
				<div id='cmsc437' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC437" value="CMSC437" id="437" onclick="selected('cmsc437');lockprev('cmsc341', 'cmsc437');"><label for="437"></label> CMSC 437 <span class="textstyleDescription">: (3.00) Graphical User Interface Programming </span> <br> </div> 
				
				<div id='cmsc441' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC441" value="CMSC441" id="441" onclick="selected('cmsc441');lockprev('cmsc341', 'cmsc441');"><label for="441"></label> CMSC 441 &#9733 <span class="textstyleDescription">: (3.00) Design and Analysis of Algorithms. </span> <br> </div> 
				
				<div id='cmsc442' class='content1' style="color: GoldenRod;">
				<input class="400-level-box" type="checkbox" dependency='0' name="CMSC442" value="CMSC442" id="442" onclick="selected('cmsc442');"><label for="442"></label> CMSC 442 <span class="textstyleDescription">: (3.00) Information and Coding Theory </span> <br> </div>
				
				<div id='cmsc443' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC443" value="CMSC443" id="443" onclick="selected('cmsc443');lockprev('cmsc341', 'cmsc443');"><label for="443"></label>  CMSC 443 <span class="textstyleDescription">: (3.00) Cryptology </span> <br> </div> 
				
				<div id='cmsc444' class='content1' style="color: GoldenRod;">
				<input class="400-level-box" type="checkbox" dependency='0' name="CMSC444" value="CMSC444" id="444" onclick="selected('cmsc444');"><label for="444"></label>  CMSC 444 <span class="textstyleDescription">: (3.00) Information Assurance </span> <br></div> 
				
				<div id='cmsc446' class='content1' style="color: GoldenRod;">
				<input class="400-level-box" type="checkbox" dependency='0' name="CMSC446" value="CMSC446" id="446" onclick="selected('cmsc446');"><label for="446"></label>  CMSC 446 <span class="textstyleDescription">: (3.00) Introduction to Design Patterns </span> <br></div> 
				
				<div id='cmsc447' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC447" value="CMSC447" id="447" onclick="selected('cmsc447');lockprev('cmsc341', 'cmsc447');showMe('cmsc448');"><label for="447"></label>  CMSC 447 &#9733 <span class="textstyleDescription">: (3.00) Software Design and Development </span> <br> </div> 
				
				<div id='cmsc448' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC448" value="CMSC448" id="448" onclick="selected('cmsc448');lockprev('cmsc447', 'cmsc448');"><label for="448"></label>  CMSC 448 <span class="textstyleDescription">: (3.00) Software Engineering II </span> <br></div> 
				
				<div id='cmsc451' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC451" value="CMSC451" id="451" onclick="selected('cmsc451');lockprev('cmsc203', 'cmsc451');"><label for="451"></label>  CMSC 451 <span class="textstyleDescription">: (3.00) Automata Theory and Formal Languages </span> <br></div> 

				<div id='cmsc452' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC452" value="CMSC452" id="452" onclick="selected('cmsc452');lockprev('cmsc203', 'cmsc452');"><label for="452"></label>  CMSC 452 <span class="textstyleDescription">: (3.00) Logic for Computer Science </span> <br> </div> 
				
				<div id='cmsc453' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC453" value="CMSC453" id="453" onclick="selected('cmsc453');lockprev('cmsc341', 'cmsc453');"><label for="453"></label>  CMSC 453 <span class="textstyleDescription">: (3.00) Applied Combinatorics and Graph Theory </span> <br> </div> 
				
				<div id='cmsc455' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC455" value="CMSC455" id="455" onclick="selected('cmsc455');lockprev('cmsc341', 'cmsc455');"><label for="455"></label>  CMSC 455 <span class="textstyleDescription">: (3.00) Numerical Computations </span> <br> </div> 
				
				<div id='cmsc456' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC456" value="CMSC456" id="456" onclick="selected('cmsc456');lockprev('cmsc341', 'cmsc456');"><label for="456"></label>  CMSC 456 <span class="textstyleDescription">: (3.00) Symbolic Computation </span> <br></div> 
				
				<div id='cmsc457' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC457" value="CMSC457" id="457" onclick="selected('cmsc457');lockprev('cmsc203', 'cmsc457');"><label for="457"></label> CMSC 457 <span class="textstyleDescription">: (3.00) Quantum Computation </span> <br> </div> 
				
				<div id='cmsc461' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC461" value="CMSC461" id="461" onclick="selected('cmsc461');lockprev('cmsc341', 'cmsc461');tworeq('cmsc465', 'cmsc461');tworeq('cmsc466', 'cmsc461');"><label for="461"></label>  CMSC 461 <span class="textstyleDescription">: (3.00) Database Management Systems </span> <br> </div> 
							
				<div id='cmsc465' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' req='000' disabled name="CMSC465" value="CMSC465" id="465" onclick="selected('cmsc465');lockprev('cmsc481', 'cmsc465');lockprev('cmsc461', 'cmsc465');"><label for="465"></label>  CMSC 465 <span class="textstyleDescription">: (3.00) Introduction to Electronic Commerce </span> <br> </div> 
				
				<div id='cmsc466' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' req='000' disabled name="CMSC466" value="CMSC466" id="466" onclick="selected('cmsc466');lockprev('cmsc481', 'cmsc466');lockprev('cmsc461', 'cmsc466');"><label for="466"></label>  CMSC 466 <span class="textstyleDescription">: (3.00) Electronic Commerce Technology </span> <br></div>

				<div id='cmsc471' class='content1' style="color: lightgrey;"> 
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC471" value="CMSC471" id="471" onclick="selected('cmsc471');lockprev('cmsc341', 'cmsc471');tworeq('cmsc493', 'cmsc471');showMe('cmsc479');showMe('cmsc478');showMe('cmsc477');"><label for="471"></label> CMSC 471 <span class="textstyleDescription">: (3.00) Introduction to Artificial Intelligence </span> <br> </div> 
				
				<div id='cmsc473' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC473" value="CMSC473" id="473" onclick="selected('cmsc473');lockprev('cmsc331', 'cmsc473');"><label for="473"></label>  CMSC 473 <span class="textstyleDescription">: (3.00) Introduction to Natural Language Processing </span> <br> </div> 
				
				<div id='cmsc475' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC475" value="CMSC475" id="475" onclick="selected('cmsc475');lockprev('cmsc341', 'cmsc475');"><label for="475"></label>  CMSC 475 <span class="textstyleDescription">: (3.00) Introduction to Neural Networks </span> <br> </div> 
				
				<div id='cmsc476' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC476" value="CMSC476" id="476" onclick="selected('cmsc476');lockprev('cmsc341', 'cmsc476');"><label for="476"></label>  CMSC 476 <span class="textstyleDescription">: (3.00) Information Retrieval </span> <br> </div> 
				
				<div id='cmsc477' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC477" value="CMSC477" id="477" onclick="selected('cmsc477');lockprev('cmsc471', 'cmsc477');"><label for="477"></label>  CMSC 477 <span class="textstyleDescription">: (3.00) Agent Architectures and Multi-Agent Systems </span> <br> </div> 
				
				<div id='cmsc478' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC478" value="CMSC478" id="478" onclick="selected('cmsc478');lockprev('cmsc471', 'cmsc478');"><label for="478"></label>  CMSC 478 <span class="textstyleDescription">: (3.00) Introduction to Machine Learning </span> <br></div> 
					
				<div id='cmsc479' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC479" value="CMSC479" id="479" onclick="selected('cmsc479');lockprev('cmsc471', 'cmsc479');"><label for="479"></label> CMSC 479 <span class="textstyleDescription">: (3.00) Introduction to Robotics </span> <br> </div> 

				<div id='cmsc481' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC481" value="CMSC481" id="481" onclick="selected('cmsc481');lockprev('cmsc341', 'cmsc481');tworeq('cmsc487', 'cmsc481');tworeq('cmsc466', 'cmsc481');tworeq('cmsc465', 'cmsc481');"><label for="481"></label> CMSC 481 <span class="textstyleDescription">: (3.00) Computer Networks </span> <br> </div> 
				<div id='cmsc483' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC483" value="CMSC483" id="483" onclick="selected('cmsc483');lockprev('cmsc421', 'cmsc483');"><label for="483"></label>  CMSC 483 <span class="textstyleDescription">: (3.00) Parallel and Distributed Processing </span> <br> </div> 
			
				<div id='cmsc484' class='content1' style="color: GoldenRod;">
				<input class="400-level-box" type="checkbox" dependency='0' name="CMSC484" value="CMSC484" id="484" onclick="selected('cmsc484');"><label for="484"></label> CMSC 484 <span class="textstyleDescription">: (3.00) Java Server Technologies </span> <br> </div> 

				<div id='cmsc486' class='content1' style="color: lightgrey;">
					<input class="400-level-box" type="checkbox" dependency='0' disabled name="CMSC486" value="CMSC486" id="486" onclick="selected('cmsc486');lockprev('cmsc202', 'cmsc486');"><label for="486"></label>  CMSC 486 <span class="textstyleDescription">: (3.00) Mobile Telephony Communications </span> <br> </div> 
				</div>
				
				<div id='cmsc487' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' req='000' disabled name="CMSC487" value="CMSC487" id="487" onclick="selected('cmsc487');lockprev('cmsc421', 'cmsc487');lockprev('cmsc481', 'cmsc487');"><label for="487"></label>  CMSC 487 <span class="textstyleDescription">: (3.00) Introduction To Network Security </span> <br></div> 
			
				<div id='cmsc491' class='content1' style="color: GoldenRod;">
				<input class="400-level-box" type="checkbox" dependency='0' name="CMSC491" value="CMSC491" id="491" onclick="selected('cmsc491');"><label for="491"></label>  CMSC 491 <span class="textstyleDescription">: (3.00) Special Topics in Computer Science </span> <br> </div> 
				<div id='cmsc493' class='content1' style="color: lightgrey;">
				<input class="400-level-box" type="checkbox" dependency='0' req='000' disabled name="CMSC493" value="CMSC493" id="493" onclick="selected('cmsc493');lockprev('cmsc471', 'cmsc491');lockprev('cmsc435', 'cmsc493');"><label for="493"></label>  CMSC 493 <span class="textstyleDescription">: (3.00) Capstone Games Group Project </span> <br></div> 
			
				<div id='cmsc495' class='content1' style="color: GoldenRod;">
				<input class="400-level-box" type="checkbox" dependency='0' name="CMSC495" value="CMSC495" id="495" onclick="selected('cmsc495');"><label for="495"></label>  CMSC 495 <span class="textstyleDescription">: (3.00) Honors Thesis </span> <br></div> 
			
				<div id='cmsc498' class='content1' style="color: GoldenRod;">
				<input class="400-level-box" type="checkbox" dependency='0' name="CMSC498" value="CMSC498" id="498" onclick="selected('cmsc498');"><label for="498"></label>  CMSC 498 <span class="textstyleDescription">: (3.00) Independent Study in Computer Science for CMSC Interns and Coop Students </span> <br> </div> 
		
				<div id='cmsc499' class='content1' style="color: GoldenRod;">
				<input class="400-level-box" type="checkbox" dependency='0' name="CMSC499" value="CMSC499" id="499" onclick="selected('cmsc499');"><label for="499"></label>  CMSC 499 <span class="textstyleDescription">: (1.00 - 4.00) Independent Study in Computer Science </span> <br> </div> 
		
	<div class="title1"><span class="textstyleRed">Math</span> CLASSES</div>

 			<div id='math150' class='content1' class='content1' style="color: GoldenRod;" >
			<input type="checkbox" dependency='0' name="MATH150" value="MATH150" id="150" onclick="selected('math150');showMe('biol141');showMe('chem101');showMe('math151');"><label for="150"></label>  MATH 150 
			<span class="textstyleDescription">: (4.00) Precalculus Mathematics. Please check the checkbox if you got scored 5 on the LRC MATH placement exam</span>  <br></div>

			<div id='math151' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="MATH151" value="MATH151" id="151" onclick="selected('math151');lockprev('math150', 'math151');showMe('math152');showMe('math221');showMe('phys121')"><label for="151"></label>  MATH 151
		    <span class="textstyleDescription">: (4.00) Calculus and Analytic Geometry I </span> <br> </div>

		    <div id='math152' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="MATH152" value="MATH152" id="152" onclick="selected('math152');lockprev('math151', 'math152');"><label for="152"></label>  MATH 152
		    <span class="textstyleDescription">: (4.00) Calculus and Analytic Geometry II </span> <br> </div>

		    <div id='math221' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="MATH221" value="MATH221" id="221" onclick="selected('math221');lockprev('math151', 'math221');"><label for="221"></label>  MATH 221
		    <span class="textstyleDescription">: (3.00)  Introduction to Linear Algebra</span> <br> </div>
		
	<div class="title1"><span class="textstyleRed">Science</span> CLASSES</div>

			
			<div id='phys121' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="PHYS121" value="PHYS121" id="121" onclick="selected('phys121');lockprev('math151', 'phys121');showMe('phys122')"><label for="121"></label>  PHYS 121
		    <span class="textstyleDescription">: (4.00) PHYS 121 - Introductory Physics I </span> <br> </div>

		    <div id='phys122' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="PHYS122" value="PHYS122" id="122" onclick="selected('phys122');lockprev('phys121', 'phys122');showMe('phys1221');"><label for="122"></label>  PHYS 122
		    <span class="textstyleDescription">: (4.00) PHYS 121 - Introductory Physics II </span> <br> </div>

		     <div id='phys1221' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="PHYS122L" value="PHYS122L" id="1221" onclick="selected('phys1221');lockprev('phys122', 'phys1221');"><label for="1221"></label>  PHYS 122L
		    <span class="textstyleDescription">: (3.00) Introductory Chemistry Lab IIntroductory Physics Laboratory </span> <br> </div>



		    <div id='biol141' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="BIOL141" value="BIOL141" id="141" onclick="selected('biol141');lockprev('math150', 'biol141');showMe('biol142')"><label for="141"></label>  BIOL 141
		    <span class="textstyleDescription">: (4.00) Foundations of Biology: Cells, Energy and Organisms </span> <br> </div>

 			<div id='biol142' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="BIOL142" value="BIOL142" id="142" onclick="selected('biol142');lockprev('biol141', 'biol142');showMe('biol1001')"><label for="142"></label>  BIOL 142  
			 <span class="textstyleDescription">: (4.00)  Foundations of Biology: Ecology and Evolution </span> <br> </div>

			 <div id='biol1001' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="BIOL100L" value="BIOL100L" id="1001" onclick="selected('biol1001');lockprev('biol142', 'biol1001');"><label for="1001"></label>  BIOL 100L
		    <span class="textstyleDescription">: (2.00) Concepts of Experimental Biology </span> <br> </div>
			


			<div id='chem101' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="CHEM101" value="CHEM101" id="101" onclick="selected('chem101');lockprev('math150', 'chem101');showMe('chem102')"><label for="101"></label>  CHEM 101
		    <span class="textstyleDescription">: (4.00) Principles of Chemistry I </span> <br> </div>
	
			<div id='chem102' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="CHEM102" value="CHEM102" id="102" onclick="selected('chem102');lockprev('chem101', 'chem102');showMe('chem1021')"><label for="102"></label>  CHEM 102  
			 <span class="textstyleDescription">: (4.00)  Principles of Chemistry II </span> <br> </div>

			 <div id='chem1021' class='content1' style="color: lightgrey;">
			<input type="checkbox" dependency='0' disabled name="CHEM102L" value="CHEM102L" id="1021" onclick="selected('chem1021');lockprev('chem102', 'chem1021');"><label for="1021"></label>  CHEM 102L
		    <span class="textstyleDescription">: (2.00) Introductory Chemistry Lab I </span> <br> </div>
		

		    <div id='ges110' class='content1' class='content1' style="color: GoldenRod;" >
			<input type="checkbox" dependency='0' name="GES110" value="GES110" id="110" onclick="selected('ges110');"><label for="110"></label>  GES 110 
			<span class="textstyleDescription">: (3.00) Physical Geography </span>  <br></div>

			<div id='ges120' class='content1' class='content1' style="color: GoldenRod;" >
			<input type="checkbox" dependency='0' name="GES120" value="GES120" id="120" onclick="selected('ges120');"><label for="120"></label>  GES 120 
			<span class="textstyleDescription">: (3.00)  Environmental Science and Conservation</span>  <br></div>


			<div id='ges286' class='content1' class='content1' style="color: GoldenRod;" >
			<input type="checkbox" dependency='0' name="GES286" value="GES286" id="286" onclick="selected('ges286');"><label for="286"></label> GES 286 
			<span class="textstyleDescription">: (4.00)  – Introduction to the Environment: A Geo-Spatial Perspective</span>  <br></div>



			<div id='sci1011' class='content1' class='content1' style="color: GoldenRod;" >
			<input type="checkbox" dependency='0' name="SCI101L" value="SCI101L" id="1011" onclick="selected('sci1011');"><label for="1011"></label> SCI 101L
			<span class="textstyleDescription">: (2.00)  Quantitative Reasoning: Measurement and Skills Lab</span>  <br></div>



	</div> <br/>
	<div class="title1"><span class="textstyleRed">
		
			<input type="submit" name="" value="SUBMIT" class="btn-style2">
		
	</div>
	
	</form>

	<script>
	// Neat way to grab a php variable for javascript
    var classes = "";
    // Debug to check accuracy console.log(classes);
    var classArr = classes.split(" ");

    // Loops through newly split class array to "click" each class in the interface
    for(var i = 0; i < classArr.length; i++) {
    	// classArr contains the full class name CMSC201 for example and the ids are 
    	// named with only the class number 201. 
    	// The regex here is used to remove the letters so that the element can be
    	// retrieved easily. 
    	var cbox = document.getElementById(classArr[i].replace(/\D+/g,''));

    	// After element is retrieved simply click the element
        if(cbox) {
        	cbox.click();
       	}
    }
	</script>


</body>

</html>
