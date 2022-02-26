<?php

	//connecting to database
	include("connection.php");
	//adding menu bar 
	include("menu.php");
	$success = 0;

	if(isset($_SESSION['id']))
	{
		header('Location: index.php');
	}

	//registering the user data into database 
	if(isset($_POST['create_account']))
	{
		if(empty($_POST['username']))
		{
			$message = " PLEASE ENTER USERNAME ! ";
		}
		else if (!ctype_alpha(str_replace(' ', '', $_POST['username'])))
		{
			$message = " USER NAME CAN ONLY CONTAIN ALPHABETS ! ";
		}
		else if(empty($_POST['email']))
		{
			$message = " PLEASE ENTER E-MAIL ! ";
		}
		else if(!preg_match("/^[A-Z a-z 0-9 .-_]+@[A-Z a-z .-_]+\.[A-Z a-z]{2,5}$/",$_POST['email']))
		{
			$message = " PLEASE ENTER VALID E-MAIL ! <br> THE E-MAIL YOU HAVE ENTERED DOES NOT EXSIST !";
		}
		else if(empty($_POST['password']))
		{
			$message = " PLEASE ENTER PASSWORD ! ";
		}
		else if(strlen($_POST['password'])<8)
		{
			$message = " PASSWORD MUST CONTAIN AT LEAST 8 CHARACTERS !";
		}
		else if(!preg_match("@[A-Z]@",$_POST['password']) || !preg_match("@[a-z]@",$_POST['password']) 
			  || !preg_match("@[0-9]@",$_POST['password']) )
		{
			$message = " PASSWORD MUST CONTAIN LOWER CASE AND UPPER CASE LETTERS <br> INCLUDING NUMBERS ! ";
		}
		else if(empty($_POST['confirm_password']))
		{
			$message = " PLEASE ENTER CONFIRM PASSWORD !";
		}
		else if($_POST['password']!=$_POST['confirm_password'])
		{
			$message = " PASSWORD AND CONFRIM PASSWORD DO NOT MATCHED ! ";
		}
		else
		{
			//Now we are ready to send user data into database 

			$username = trim( htmlspecialchars( $_POST['username'] ) );
			$email = base64_encode( trim( htmlspecialchars( $_POST['email'] ) ) );
			$password = base64_encode(htmlspecialchars( $_POST['password']));
			$confirm_password = base64_encode(htmlspecialchars( $_POST['confirm_password']));
			$verification_code = base64_encode( time().$username ) ;

			$insert = "INSERT INTO users (username,email,password,confirm_password,verification_code) 
			 values ('".$username."' , '".$email."' , '".$password."' , '".$confirm_password."' , 
			 '".$verification_code."')";

			$checkuser = "SELECT * FROM users ";
			$newUser = 0;

			if($result=mysqli_query($conn,$checkuser))
			{
				if(mysqli_num_rows($result)>0)
				{
					while($row=mysqli_fetch_assoc($result))
					{
						if($row['email']==$email)
						{
							$newUser = 0;
							break;
						}
						else
						{
							$newUser = 1;
						}
					}
				}
				else
				{
					$newUser = 1;
				}
			}
			else
			{
				$message = " SOME ERROR HAS BEEN OCCURED PLEASE TRY AGAIN AFTER SOMETIME ! ";
			}

			if($newUser)
			{
					$to = base64_decode($email);
					$subject = " Code Stack Email Verification Link ";
					$msg = "<a style='font-size:20px;font-weight:800;' 
					href='".$currentServer."/codestack/verify.php?token=".$verification_code."'>
						Click Here to Verify Your Email ! 
					 </a>";
					 
					$headers = 'From: CodeStackTeam'."\r\n";
					$headers .= 'Content-type: text/html';

					 if(mail($to,$subject,$msg,$headers))
					 {
					 	if(mysqli_query($conn,$insert))
						{
							$success = 1;
							$message = "<img src='project_images/success.jpg' height='40' width='40' />
								<br> <br>
							 REGISTERATION SUCCESSFUL ! <br>
							 AN EMAIL VERIFICATION LINK IS SENT TO YOUR EMAIL ! <br> EMAIL DELIVERY MAY TAKE UP TO 10 MINUTES !";
						}
						else
						{
							$message = " SOME ERROR HAS BEEN OCCURED PLEASE TRY AGAIN AFTER SOMETIME ! ";
						}
					 }
					 else
					 {
					 	$message = " SOME ERROR HAS BEEN OCCURED WHILE SENDING EMAIL VERIFICATION LINK ! <BR> PLEASE CHECK THE EMAIL YOU HAVE ENTERED ! ";
					 }
			}
			else
			{
				$message = " THIS EMAIL IS ALREADY REGISTERED PLEASE TRY ANOTHER EMAIL ! ";
			}


		}
		
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sign Up</title>
	<style type="text/css">

		:root {
  			--animate-delay: 0.5s;
		}

		#head{
			margin-top: -120px;
		}
		#signup-container{
			background-color: rgba(39, 42, 45, .9);
			padding: 10px 10px 30px 10px;
			width: 70%;
			border-radius: 10px;
			margin-top: -100px;
			--animate-duration: .7s;
		}

		#content{
			margin-bottom: 10px;
		}

		#heading
		{
			margin:2%;
			font-weight: 500;
			color: #efefef;
			text-align: left;
			border-bottom: 2px solid #efefef;
			font-size: 50px;
			padding: 0px 0px 20px 20px;
		}

		.form-data
		{
			text-align: left;
			margin-left: 0;
			margin-bottom: 15px;
		}

		#submit_button
		{
			display: block;
			text-align: center;
			margin:0px;
			margin-top: 20px;
			width: 100%;
		}

		img
		{
			height: 40px;
			width: 40px;
			border-radius: 20px;
			border:none;
		}

		input[type="text"],input[type="email"],input[type="password"]
		{
			background: transparent;
    		border: none;
    		height: 38%;
    		width: 85%;
    		color:#cdcdcd;
    		margin-top: 10px;
    		font-size: 15px;
    		padding-left: 15px;
    		font-weight: 600;
    		border:none;
			outline: none;
		}

		fieldset
		{
			width: 30%;
			height: 65px;
			text-align: center;
			display: inline;
			border-radius: 7px;
			border:1px solid #ededed;
			position: relative;
		}

		fieldset:nth-child(odd){
			left: 10%;
		}

		fieldset:nth-child(even){
			left: 30%;
		}

		legend
		{
			text-align: left;
			margin-left: 15px;
			font-size: 18px;
			font-weight: 600;
			height: 17px; 
			line-height: 14px;
			font-family: serif;
			color: #efefef;
		}

		input::placeholder
		{
			color:#fefefe;
			font-size: 18px;
			font-weight: 100;
			font-family: serif;
		}

		#create_account{
			height: 38px;
			width: 13%;
			margin-top: 25px;
			margin-bottom: 15px;
			border: none;
			outline: none;
			color: #ffffff;
			opacity: 1;
			font-size: 15px;
			border-radius: 6px;
			background-color: #1E90FF;
			cursor: pointer;
		}

		#create_account:hover{
			background-color: #0c70bb;
			transition: all .2s;
		}

		#message
		{
			margin:15px 0px;
			font-size: 17px;
			font-weight: 100;
			color: #ededed;
			padding: 4px 0;
			width: 70%;
			border: 1px solid transparent;
			<?php
				if(isset($message))
				{
					echo 'background-color: rgba(52, 52, 52, .8);border-radius: 5px;border: 1px solid #dedede;';
				}
			?>

			<?php

				if(!$success)
				{
					echo "height:40px;";
					echo "display: flex;";
					echo "justify-content: center;";
  					echo "align-items: center;";
				}
				else
				{
					echo "height:160px;";
					echo 'background-color: transparent;border-radius: 5px;border: none;';
				}

			?>
		}

		/* The message box is shown when the user clicks on the password field */
		#pwd_validate
		{
		  display:none;
		  background-color: #ededed;
		  padding: 10px;
		  margin-top: 10px;
		  position: absolute;
		  width: 25%;
		  top:38%;
		  left:19.4%;
		  border:2px solid #565656;
		  border-radius: 10px;
		  text-align: left;
		}

		h3
		{
			color: #212221;
		}

		#pwd_validate p 
		{
		  padding: 1px 35px;
		  font-size: 17px;

		}
		
		/* Add a green text color and a checkmark when the requirements are right */
		.valid 
		{
		  color: green;
		}
		
		.valid:before 
		{
		  position: relative;
		  left: -35px;
		  content: "✔";
		}
		
		/* Add a red text color and an "x" when the requirements are wrong */
		.invalid 
		{
		  color: #ff2121;
		}
		
		.invalid:before 
		{
		  position: relative;
		  left: -35px;
		  content: "✖";
		}

	</style>

	<script type="text/javascript">
		function _focus(legend,input)
		{
			document.getElementById(legend).innerHTML = input;
			document.getElementById(input).setAttribute("placeholder","");
			document.getElementById(legend).style.padding="0px 2px";

		}
		function _blur(legend,input)
		{
			var val = document.getElementById(input).value;
			if(val=="")
			{
				document.getElementById(legend).innerHTML = "";
				document.getElementById(input).setAttribute("placeholder",input);
				document.getElementById(legend).style.padding="0px";

			}
		}
	</script>
</head>
<body>

<div id="content">
	<center>

	<div <?php 
			
			if(!isset($_POST['create_account']))
			{
				echo "class='animate__animated animate__zoomIn'";
			}

		?> id="signup-container">

		<form autocomplete="off" method="post">

		<div id="heading">
			Sign Up
		</div>

		<center>

			<div <?php
					if(isset($message))
					{
						echo 'class="animate__animated animate__delay-1s animate__bounceIn"';
					}
				?> id="message">

				<?php
					if(isset($message))
					{ 
						echo $message;
					}
				?>

			</div>
		</center>

		<?php

			if(!$success)
			{

		?>

		<div class="form-data">
			<fieldset id="container1">
				<legend>
					<label id="L1" for="User name">
						<!-- Username -->
					</label>
				</legend>

				<input type="text" onblur="_blur('L1','User name');" 
				onfocus="_focus('L1','User name');" placeholder="User name"
				id="User name" title="Username should only contain letters. " name="username" value="<?php if(isset($_POST['username'])) { echo trim( htmlspecialchars( $_POST['username'] ) ) ; } ?>" >

			</fieldset>
		
			<fieldset id="container2">
				<legend>
					<label id="L2" for="E-mail">
						<!-- E-mail -->
					</label>
				</legend>

				<input type="text" id="E-mail" onblur=" _blur('L2','E-mail')" 
				onfocus=" _focus('L2','E-mail')" value="<?php 
				if(isset($_POST['email'])) 
				{ 
					echo trim( htmlspecialchars( $_POST['email'] ) ) ; 
				} 
				?>"
				 placeholder="E-mail" name="email" title="E-mail : example@domain.com">

			</fieldset>
		</div>

		<div class="form-data">
			<fieldset id="container3">
				<legend>
					<label id="L3" for="Password">
						<!-- Password -->
					</label>
				</legend>

				<input type="password" id="Password"  onblur=" hide_box(); _blur('L3','Password');" 
				onfocus=" show_box(); _focus('L3','Password');"placeholder="Password" onkeyup="validate_pass();" 
				name="password" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" >

			</fieldset> 
	
			<fieldset id="container4">
				<legend>
					<label id="L4" for="Confirm Password">
						<!-- Confirm Password -->
					</label>
				</legend>
				<input type="password" id="Confirm Password" 
				onblur="_blur('L4','Confirm Password')" 
				onfocus="_focus('L4','Confirm Password')"
				placeholder="Confirm Password" name="confirm_password">

			</fieldset>
		</div>

		<div class="form-data" id="submit_button">
			<input type="submit" id="create_account" name="create_account" value="Sign Up">
		</div>

		<?php

			}

		?>

		<div id="pwd_validate">
  			<h3>Password must contain the following:</h3>
  			<p id="letter" class="invalid">A lowercase letter</p>
  			<p id="capital" class="invalid">A capital (uppercase) letter</p>
  			<p id="number" class="invalid">A number</p>
  			<p id="length" class="invalid">Minimum 8 characters</p>
		</div>

		</form>


	</div>
	</center>

</div>

	<script type="text/javascript">
	var username = document.getElementById('User name').value;
	var myInput = document.getElementById("Password");
	var letter = document.getElementById("letter");
	var capital = document.getElementById("capital");
	var number = document.getElementById("number");
	var length = document.getElementById("length");


	// When the user clicks on the password field, show the message box
		function show_box() 
		{

  			document.getElementById("pwd_validate").style.display = "block";
  			document.getElementById("message").style.opacity = "0";
		}

		// When the user clicks outside of the password field, hide the message box
		function hide_box() 
		{
  			document.getElementById("pwd_validate").style.display = "none";
  			document.getElementById("message").style.opacity = "1";
		}

		// When the user starts to type something inside the password field
		function validate_pass() 
		{
  			// Validate lowercase letters
  			var lowerCaseLetters = /[a-z]/g;
  			if(myInput.value.match(lowerCaseLetters)) 
  			{  
    			letter.classList.remove("invalid");
    			letter.classList.add("valid");
  			} 
  			else 
  			{
    			letter.classList.remove("valid");
    			letter.classList.add("invalid");
  			}
  
  			// Validate capital letters
  			var upperCaseLetters = /[A-Z]/g;
 			if(myInput.value.match(upperCaseLetters)) 
 			{  
   				capital.classList.remove("invalid");
    			capital.classList.add("valid");
  			} 
  			else 
  			{
    			capital.classList.remove("valid");
    			capital.classList.add("invalid");
 			 }

  			// Validate numbers
  			var numbers = /[0-9]/g;
  			if(myInput.value.match(numbers)) 
  			{  
    			number.classList.remove("invalid");
    			number.classList.add("valid");
  			} 
  			else 
  			{
    			number.classList.remove("valid");
    			number.classList.add("invalid");
  			}
  
  			// Validate length
  			if(myInput.value.length >= 8) 
  			{
    			length.classList.remove("invalid");
    			length.classList.add("valid");
  			} 
  			else 
  			{
    			length.classList.remove("valid");
    			length.classList.add("invalid");
  			}
		}


	if(username != '')
	{
		document.getElementById('L1').innerHTML = "User name";
		document.getElementById('User name').setAttribute("placeholder","");
		document.getElementById('L1').style.padding="0px 2px";
	}

	var email = document.getElementById('E-mail').value;

	if(email != '')
	{
		document.getElementById('L2').innerHTML = "E-mail";
		document.getElementById('E-mail').setAttribute("placeholder","");
		document.getElementById('L2').style.padding="0px 2px";
	}

	</script>

</body>
</html>