<?php
	//connecting to database
	include("connection.php");
	//adding menu bar 
	include("menu.php");

	//sending user to forgot password page 
	if(isset($_POST['forgot_password']))
	{
		header("Location: forgot_password.php");
	}

	if(isset($_SESSION['id']))
	{
		header('Location: index.php');
	}

	$validUser = 0;

	if(isset($_POST['login_account']))
	{
		if(empty($_POST['email']))
		{
			$message = " PLEASE ENTER E-MAIL ! ";
		}
		else if(empty($_POST['password']))
		{
			$message = " PLEASE ENTER PASSWORD ! ";
		}
		else
		{
			$extractUserdata = "SELECT * FROM users WHERE email = '".base64_encode(htmlspecialchars($_POST['email']))."' AND password = '".base64_encode(htmlspecialchars($_POST['password']))."' ";

			if($result=mysqli_query($conn,$extractUserdata))
			{
				if(mysqli_num_rows($result)==1)
				{
					$validUser = 1;
				}
				else
				{
					$validUser = 0;
				}

			}
			else
			{
				$message = " SOME ERROR HAS BEEN OCCURED PLEASE TRY AGAIN AFTER SOMETIME ! ";
			}

			if($validUser)
			{
				$row = mysqli_fetch_assoc($result);
				
				if($row['verified']=='1')
				{
					$_SESSION['id'] = $row['user_id'];
					$_SESSION['utype'] = 'user';
					header('Location: index.php');
				}
				else
				{
					$message = " EMAIL NOT VERIFIED ! <BR> AN EMAIL VERIFICATION LINK HAS BEEN SENT TO YOUR REGISTERED EMAIL ! ";
				}
			}
			else
			{
				$message = " INVALID EMAIL OR PASSWORD ! ";
			}

		}
	}

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login</title>

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

	<style type="text/css">

		:root {
  			--animate-delay: 0.5s;
		}

		#head{
			margin-top: -105px;
		}
		#login-container{
			background-color: rgba(39, 42, 45, .9);
			padding: 10px 10px 5px 10px;
			width: 70%;
			border-radius: 10px;
			margin-top: -100px;
			--animate-duration: .7s;
		}

		#content{
			margin-bottom: 0px;
			margin-top: 215px;
		}

		#heading
		{
			margin:2%;
			margin-bottom: 0;
			font-weight: 500;
			color: #efefef;
			text-align: left;
			border-bottom: 2px solid #efefef;
			font-size: 50px;
			padding: 0px 0px 20px 20px;
		}

		.form-data
		{
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

		#login_account{
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

		#login_account:hover{
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
			height:40px;
			<?php
				if(isset($message))
				{
					echo 'background-color: rgba(52, 52, 52, .8);border-radius: 5px;border: 1px solid #dedede;';
				}

				if( ($validUser && $row['verified']=='0') || $conn == null )
				{
					echo 'line-height: 20px';
				}
				else
				{
					echo 'line-height: 40px';
				}
			?>
		}

		#forgot_password
		{
			background:transparent;
			border:none;
			outline: none;
			font-size: 15px;
			color: #ededed;
			margin-top: 10px;			
		}

		#forgot_password:hover
		{
			text-decoration: underline;
			text-shadow: 0px 0px 10px blue;
			cursor: pointer;
		}

	</style>

</head>
<body>

<div id="content">
	<center>
	<div <?php 
			
			if(!isset($_POST['login_account']))
			{
				echo "class='animate__animated animate__zoomIn'";
			}

		?> id="login-container">

		<form autocomplete="off" method="post">

		<div id="heading">
			User Login
		</div>

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


		<div class="form-data">
			<fieldset>
				<legend>
					<label id="L1" for="E-mail">
						<!-- E-mail -->
					</label>
				</legend>

				<input type="text" id="E-mail" onblur="_blur('L1','E-mail')" 
				onfocus="_focus('L1','E-mail')" placeholder="E-mail" 
				name="email">

			</fieldset>
		</div>

		<div class="form-data">
			<fieldset>
				<legend>
					<label id="L3" for="Password">
						<!-- Password -->
					</label>
				</legend>

				<input type="password" id="Password" onblur="_blur('L3','Password')" 
				onfocus="_focus('L3','Password')" placeholder="Password" 
				name="password">

			</fieldset> 
		</div>
		
		<div class="form-data" id="submit_button">
			<input type="submit" id="login_account" name="login_account" value="Login">
		</div>

		<div class="form-data">
			<input type="submit" id="forgot_password" name="forgot_password" value="Forgot Password ?">
		</div>

		</form>

	</div>
	</center>
</div>

</body>
</html>