<?php
	if(isset($_POST['loginButton'])) {
		//Login button was pressed
		$username = $_POST['loginUsername'];
		$password = $_POST['loginPassword'];

		//Call the login function
		$result = $account->login($username, $password);

		if($result == true) {
			//create a session to stay logged in
			$_SESSION['userLoggedIn'] = $username;
			header("Location: index.php");
		}
	}
?>