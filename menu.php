<?php
	session_start();
	$userlogin = 0;
	if(isset($_SESSION['id']))
	{
		if($_SESSION['id'] != -1)
		{
			$query = "SELECT * FROM users WHERE user_id = '".$_SESSION['id']."' ";
		}
		else
		{
			$query = "SELECT * FROM admin WHERE admin_id = '".$_SESSION['id']."' ";
		}
		

		$userlogin = 1;
		if($welcomeData=mysqli_query($conn,$query))
		{
			if(mysqli_num_rows($welcomeData)==1)
			{
				$singleData=mysqli_fetch_assoc($welcomeData);
				$loggedUser = $singleData['username'];
			}
			else
			{
				echo "<script> alert(\" Something went wrong ! \\n Please Contact to Admin !\"); </script>";
			}
		}
		else
		{
			echo "<script> alert(\" Something went wrong ! \\n Please Contact to Admin !\"); </script>";
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<style type="text/css">
	
		*{
			padding: 0;
			margin: 0;
		}
		html{
    		overflow: -moz-scrollbars-vertical; 
    		overflow-y: scroll;
		}
		body{
			width: 100%;
			background-color: #09080B;
		}

		#myVideo {
 			position: fixed;
  			right: 0;
 		 	bottom: 0;
  			width: 100%;
 		 	height: auto;
 		 	z-index: -1;
		}
		
		#head{
			height: 90px;
			width: 100%;
			background-color: #272A2D;
			opacity: .9;
			line-height: 80px;
			position: fixed;
			margin-top: -220px;
			overflow: visible;
			z-index: 10;
		}

		.menu{
			width: 4%;
			height: 60%;
			float: left;
			margin: 10px 0;
			cursor: pointer;
			background-color: transparent;
			margin-left: 30px;
		}

		.menu div{
			position: absolute;
			background-color: #ededed;
			height: 6px;
			width: 40px;
			border-radius: 2px;
			margin-top: 8px;
			margin-left: 5px;
			box-shadow: 0 0 5px #ff3434, 0 0 5px #ff3434;
		}
		
		.menu div:nth-child(1){
			margin-top: 10px;
			width: 45px;
			top: 10px;
		}

		.menu div:nth-child(2){
			width: 30px;
			top: 26px;
		}

		.menu div:nth-child(3){
			top: 40px;
		}

		#menu_items{
			background-color: #272A2D;
			width: 600%;
			margin-top: 80px;
			margin-left: -28px;
			list-style: none;
			font-size: 20px;
			border-bottom-right-radius: 20px;
			visibility: hidden;
			--animate-duration: .4s;
		}
		li{
			padding-left: 30px;
		}

		a:link,a:active,a:hover,a:visited{
			color: white;
			text-decoration: none;
			display: block;
			padding-left: 5px;
		}

		a:hover{
			background-color: #565656;
			padding-left: 14px;
			transition: all .4s;
		}

		a:nth-child(5)<?php if($_SESSION['id'] == -1 ) {echo ",a:nth-child(4)";} ?>{
			border-bottom-right-radius: 20px;
		}

		.head-btn{
			float: right;
			font-size: 18px;
			font-family: system-ui;
			font-weight: 600;
			color: #ffffff;
			padding: 0 7px;
			width: 11%;
			height: 100%;
			text-align: center;
			background-color: transparent;
			outline: none;
			border: none;
		}

		.head-btn:hover{
			background-color: #494c4f;
			cursor: pointer;
			transition: all .4s;
		}

		#loggedUser
		{
			float: left;
			text-align: left;
			width: 25%;
			font-size: 24px;
			margin-left: 3%;
			font-family: sans-serif;
			line-height: 32px;
			text-transform: capitalize;
		}
		#loggedUser:hover
		{
			background-color: transparent;
			cursor: default;
		}

		#cs-title:hover{
			transition: all .1s;
			text-shadow: 0 0 8px #ff3434, 0 0 8px #ff3434, 0 0 8px #ff3434;
		}

		#cs-title{
			float: right;
			margin-right: 50px;
			margin-left: 10px;
			font-size: 44px;
			font-family: cursive, sans-serif;
			color: #ededed;
			height: 100%;
			line-height: 200%;
			text-shadow: 0 0 6px #ff3434, 0 0 6px #ff3434;
			cursor: pointer;
			background-color: transparent;
			outline: none;
			border: none;
			padding: 0 30px;
		}

		form{
			display: inline;
		}

		#content{
			width: 98.75vw;
			margin-top: 230px;
			margin-bottom: 140px;
		}

		#login_prompt_container{
			position: fixed;
			left: 0;
			right: 0;
			top: 150px;
			z-index: 1000;
		}

		#login_prompt
		{
			position: relative;
			background-color: #171A1D;
			color: #eeeeee;
			text-align: center;
			font-size: 35px;
			font-variant: small-caps;
			width: 50%;
			height: 50px;
			line-height: 50px;
			border-radius: 20px;
		}

		#main-menu{
			z-index: 10;
		}
	</style>
	<link rel="stylesheet" type="text/css" href="css/animate.min.css">
</head>
<body>
	<!-- The video --> 
	<video autoplay muted loop id="myVideo">
 	 	<source src="project_images/video2.mp4" type="video/mp4">
	</video>

	<div id="head">

		<div onclick="checkLogin(<?php echo $userlogin; ?>)" id="main_menu" class="menu">
			<div class="line"></div>
			<div class="line"></div>
			<div class="line"></div>
		<ul id="menu_items" class="animate__animated">

			<?php 

				if ($_SESSION['id'] != -1)
				{

				?>

					<a href="profile.php">  <li> Profile  </li> </a>
					<a href="ask_question.php">  <li> Ask a Question  </li> </a>
					<a href="pending_question_answers.php">  <li> Pending Question/Answers  </li> </a>
					<a href="approved_question_answers.php"> <li>  Approved Question/Answers  </li> </a>

			<?php

				}
				else
				{
			?>
					<a href="index.php">  <li> Home Page  </li> </a>
					<a href="admin_pending.php">  <li> Pending Question/Answers  </li> </a>
					<a href="admin_approved.php"> <li>  Approved Question/Answers  </li> </a>
			<?php
			
				}

			?>
			
			
			<a href="change_password.php"> <li> Change Password </li> </a>
		</ul>
		</div>
		<form action="index.php" autocomplete="off" method="post">
			<button id="cs-title">
				code <strong> stack </strong>
			</button>
		</form><?php
		if(!isset($_SESSION['id']))
		{
			echo '<form action="signup.php" autocomplete="off" method="post">
				<button type="submit" class="head-btn">
					Sign Up
				</button>
			</form>		
			
			<form action="login.php" autocomplete="off" method="post">
				<button type="submit" class="head-btn">
					User Login
				</button>
			</form>
			
			<form action="admin_login.php" autocomplete="off" method="post">
				<button type="submit" class="head-btn">
					Admin Login
				</button>
			</form>';
		}
		else
		{
			echo '<form action="logout.php" autocomplete="off" method="post">
			<button type="submit" class="head-btn">
				Logout
			</button>
		</form>

		<form action="" autocomplete="off" method="post">
			<button id="loggedUser" type="button" class="head-btn">
				Welcome <br> '.$loggedUser.'
			</button>
		</form>';
	 
		}
	?>

</div>
	<div id="login_prompt_container">
		<center>
		<div id="login_prompt" class="animate__animated animate__delay-1s ">
			First you need to Login / Sign Up
		</div>
		</center>
	</div>
	<!--    ----------- SCRIPT STARTS HERE ------------    -->
	<script type="text/javascript" src="script/check_login.js"></script>
</body>
</html>