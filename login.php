<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false

	if(isset($_POST["submit"])){
		$target_dir= "gallery/";
		$uploadFile = $_FILES["picToUpload"];
		if($uploadFile["type"] == "image/jpeg" && $uploadFile["size"] < 1048576){
			if(file_exists("gallery/" . $uploadFile["name"])){
				move_uploaded_file($uploadFile["tmp_name"],$target_dir.$uploadFile["name"]);
			}
			else{
				move_uploaded_file($uploadFile["tmp_name"],$target_dir.$uploadFile["name"]);
				$userID = mysqli_query($mysqli,"SELECT `user_id` FROM `tbusers` WHERE `password` LIKE '".$pass."' AND `email` LIKE '".$email."'");
				mysqli_query($mysqli,"INSERT INTO tbgallery (user_id,filename) VALUES ('".($userID->fetch_assoc())['user_id']."','".$uploadFile['name']."')");

				$userID = mysqli_query($mysqli,"SELECT `user_id` FROM `tbusers` WHERE `password` LIKE '".$pass."' AND `email` LIKE '".$email."'");
				$userImages = mysqli_query($mysqli,"SELECT filename FROM tbgallery WHERE user_id LIKE ".($userID->fetch_assoc())['user_id']);
			}	
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Zeeshaan Alekar">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
					echo 	"<form action='' method='post' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
									<input type='hidden' name='loginEmail' value=".$email."/>
									<input type='hidden' name='loginPass' value=".$pass."/>
								</div>
						  	</form>";

					echo	"<p class='h2'>Image Gallery</p>
							<div class='row imageGallery'>";
							if(isset($userImages)){
							 	while($row = $userImages->fetch_assoc()) {
									echo "<div class='col-3' style='background-image: url(gallery/".$row['filename']."'></div>";
								}
							 	
							}
					echo    "</div>";

				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>