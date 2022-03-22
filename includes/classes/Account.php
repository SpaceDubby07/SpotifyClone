<?php	
	class Account {

		private $con;
		private $errorArray;

		public function __construct($con) {
			$this->con = $con;
			$this->errorArray = array();
		}

		//Login function
		public function login($un, $pw) {
			//encrypt the password
			$pw = md5($pw);

			//get username and password from the users table
			$query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");

			//Check them, if one exists, other spit an error
			if(mysqli_num_rows($query) == 1) {
				return true;
			} 
			else {
				array_push($this->errorArray, Constants::$loginFailed);
				return false;
			}
		}

		//This function is called when the register button is clicked, it validates all fields, and if good inserts info into users table in database
		public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
			$this->validateUsername($un);
			$this->validateFirstName($fn);
			$this->validateLastName($ln);
			$this->validateEmails($em, $em2);
			$this->validatePasswords($pw, $pw2);

			if(empty($this->errorArray)) {
				//Insert into db
				return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
			}

			else {
				return false;
			}	
		}

		//function to spit back an error code if fields are wrong, in constants.php file
		public function getError($error) {
			if(!in_array($error, $this->errorArray)) {
				$error = "";
			}
			return "<span class='errorMessage'>$error</span>";
		}

		//insert user details into users table
		private function insertUserDetails($un, $fn, $ln, $em, $pw) {
			$encryptedPW = md5($pw); // encrypt the password using md5
			$profilePic = "assets/images/profile-pics/profile1.png"; //profile picture
			$date = date("Y-m-d");

			//Add data to the users table
			$result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$fn', '$ln', '$em', '$encryptedPW', '$date', '$profilePic')");

			return $result;
		}

		//validate the username field
		private function validateUsername($un) {
			
			if(strlen($un) > 25 || strlen($un) < 5) {
				array_push($this->errorArray, Constants::$usernameCharacters);
				return;
			}

			//TODO: check if username exists
			$checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
			if(mysqli_num_rows($checkUsernameQuery) != 0) {
				array_push($this->errorArray, Constants::$usernameTaken);
				return;
			}

		}


		//validate the first name field
		private function validateFirstName($fn) {
			if(strlen($fn) > 25 || strlen($fn) < 2) {
				array_push($this->errorArray,  Constants::$firstNameCharacter);
				return;
			}

		}

		//validate the last name field
		private function validateLastName($ln) {
			if(strlen($ln) > 25 || strlen($ln) < 2) {
				array_push($this->errorArray,  Constants::$lastNameCharacter);
				return;
			}
		}

		//validate the email fields, perform checks on both
		private	function validateEmails($em, $em2) {
			if($em != $em2) {
				array_push($this->errorArray,  Constants:: $emailDoNotMatch);
				return;
			}

			if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
				array_push($this->errorArray,  Constants::$emailInvalid);				
				return;
			}

			//TODO: Check that email hasn't aready been used
			$checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
			if(mysqli_num_rows($checkEmailQuery) != 0) {
				array_push($this->errorArray, Constants::$emailTaken);
				return;
			}
		}

		//validate the password fields, perform checks on both.
		private function validatePasswords($pw, $pw2) {
			if($pw != $pw2) {
				array_push($this->errorArray,  Constants::$passwordsDoNotMatch);
				return;
			}

			if(preg_match('/[^A-Za-z0-9]/', $pw)) {
				array_push($this->errorArray,  Constants::$passwordNotAlphanumeric);
				return;
			}

			if(strlen($pw) > 30 || strlen($pw) < 5) {
				array_push($this->errorArray,  Constants::$passwordCharacters);
				return;
			}			
		}	

	}
?>