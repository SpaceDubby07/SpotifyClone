<?php
	//This file creates the rules for the registration form to follow

	//This function cleans up the username text field 
	function sanitizeFormUsername($inputText) {
		$inputText = strip_tags($inputText);
		$inputText = str_replace(" ", "", $inputText); //no blank space allowed
		return $inputText;
	}

	//this function cleans up all other text fields.
	function sanitizeFormString($inputText) {
		$inputText = strip_tags($inputText);
		$inputText = str_replace(" ", "", $inputText); //no spaces allowed
		$inputText = ucfirst(strtolower($inputText)); //first letter caps, rest lowercase
		return $inputText;
	}

	//this function cleans up the password text field
	function sanitizeFormPassword($inputText) {
		$inputText = strip_tags($inputText);
		return $inputText;
	}	

	//When the register button is clicked, make sure all fields are valid.
	if(isset($_POST['registerButton'])) {
		//Register button was pressed, perform proper checks on all fields
		$username = sanitizeFormUsername($_POST['username']);
		$firstName = sanitizeFormString($_POST['firstName']);
		$lastName = sanitizeFormString($_POST['lastName']);
		$email = sanitizeFormString($_POST['email']);
		$email2 = sanitizeFormString($_POST['email2']);
		$password = sanitizeFormPassword($_POST['password']);
		$password2 = sanitizeFormPassword($_POST['password2']);

		//check if all fields are successful
		$wasSuccessfull = $account->register($username, $firstName, $lastName, $email, $email2, $password, $password2);

		/*
		//if successful, proceed to index.php, the data is now in the database.
		if($wasSuccessfull == true) {
			//Create a session to keep the user logged in
			$_SESSION['userLoggedIn'] = $username;
			header("Location: index.php");
		}
		*/
	}

?>