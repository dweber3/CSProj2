README for 433 Project 1 (Advising Assistant)
Spring 2016 UMBC

TABLE OF CONTENTS
1. INTRODUCTION
2. HOW TO USE
3. COMPONENTS
  3a. HTML / CSS
  3b. PHP
  3c. JAVASCRIPT
  3d. MYSQL DATABASE


---- 1.  INTRODUCTION ----

This is the readme document for the CMSC 433 Project 1, which we title Advising Assistant (AA for short).
This document will include all the information needed to have a basic understanding of the web app, for both
users and developers. Section 2 will cover how a user would interact with the software. Section 3 will cover 
what each of the components of the application do and how they interact with each other. 

---- 2. HOW TO USE ----

When loading the webpage, the user will be presented with a split page. On the left are four (4) text entry boxes. 
From top to bottom they are the user's Name (first and last), Campus ID, Email, and Phone Number. Name, ID, and Email are 
required fields. Contact Number is not. 

On the right side will be a list of CMSC classes available at UMBC. Initially, none will be selected. 
The class list uses a color coding system to display the available classes. 

The system is broken down as such:
	Gold: The user is eligible to take the class this semester
	Grey: The user is NOT eligible to take the class this semester
	Black: The user has already taken this class

	A star next to the class name indicates that the class is a required class to graduate

To begin, the user will enter their information into the text boxes on the left side of the page. Once all the information is
entered, they will press submit. Their information will be validated, and then looked up in the database. If they have already
entered class information and it was found in the database, the list on the right side will be updated to show what classes they
previously entered as taken. If they are a new user, the right side will not change.

Once their information has been submitted, the user can then select the classes they have taken on the right side of the page. 
The application will not allow them to select any classes they have not met the requirements for. As they select the classes they
have already taken, the page will update to show any new classes they are eligible to take. Once they have entered all of the 
classes they have previously taken, they can view all the classes they are currently eligible for by scrolling through the page.
Any class that is golden in color is a class they could take next semester.

Once they have finished updating their classes, they can press the submit button on the bottom of the right side of the page to 
store their (possibly) updated information back into the database.


---- 3. Components ----
3.a. HTML / CSS

	HTML was used to represent our web page. There is a main wrapper HTML page called “main.html” which is divided into two separate elements containing “left.html” and “right.php”. Left.html contains purely HTML code and contains a form that accepts a user’s name, campus ID, email address, and phone number. The information in this form is then sent to “right.php” when submitted.

	“Right.php” uses HTML to display a list of classes and checkboxes for each. The checkboxes for the classes is a form that is sent to a separate php page called “after.php” that enters the information into the database then redirects the user back to “right.php”.

3.b. PHP

PHP is used in the initial data validation. When the user clicks the submit button on the left page, it will load the right page which will check each field for valid input. It ensures that both a first name and last name are entered along with a valid campus id, email, and phone number.

	PHP is used primarily for database interactions. The project uses PHP to connect with a mySQL server that was located on Ian’s laptop for development. The database structure can be found in the included DatabaseDoc.pdf. When a user clicks submit with valid information on the left page, a database connection is  made a looks up the user based on their name and campus id. If they are in the database already then their classes taken will be retrieved and checked automatically on the interface. If they are not in the database then nothing superfluous will happen. Regardless if the user is in the database already, PHP will generate a custom welcome message depending on the user’s name and will store session variables with their information supplied in the left page. 
	Another database interaction occurs when the user clicks submit on the right page. The PHP here is used to get the POST variables from the class page and update the user’s database entry with the new information. This page retrieves the session variables created in the right page and uses them to update the database.  

3.c. JAVASCRIPT

	Javascript is used in the manipulation of the classes and handling user interactions with the checkboxes. Each checkbox has unique onclick events that correspond to the class’s hierarchy. The javascript methods disables checkboxes for classes that are unavailable or prerequisites for other selected classes. It also changes the color of the classes in the list to signal to the user which classes are available to them. Available classes’ checkboxes are enabled by the javascript method showMe() and the color is changed to reflect that.

3.d. MYSQL DATABASE

	Information on the database can be found in the file ‘DatabaseDoc.pdf’

