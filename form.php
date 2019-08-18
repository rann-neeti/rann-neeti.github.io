<?php
if($_SERVER["REQUEST_METHOD"] == "GET")
{
		die( "<script> window.location = '/' </script>");
}
$servername = "localhost";
$username = "rannwebmaster";
$password = "controller";
$DBname = "ranniitm";

// Create connection
$conn = new mysqli($servername, $username, $password , $DBname);

// Check connection
if ($conn->connect_error) {
    echo $conn->connect_error;
	exit;
   // die("<script> window.location = 'error404.html';</script>");
}

$name = $contact = $email = $college = $teamsize = $category = "";
$nameerr = $contacterr = $emailerr = $collegeerr = $teamnameerr = $teamsizeerr = $eventerr = $categoryerr ="";
$games = array("Table-Tennis (Boys)" , "Table-Tennis (Girls)" ,"Badminton (Boys)","Badminton (Girls)","Basketball (Boys)","Basketball (Girls)","Football (Boys)", "Cricket (Boys)" ,"Volleyball (Boys)","Volleyball (Girls)","Hockey (Boys)","Athletics (Boys)","Athletics (Girls)" , "Lawn-Tennis", "Chess");
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id;
	$name = test_input($_POST["name"]);
	// echo $name;
	$email = test_input($_POST["email"]);
	$contact = test_input($_POST["mobile"]);
	$college = test_input($_POST["college"]);
	$teamsize = test_input($_POST["teamsize"]);
	$char = "a";
	for($i = 0; $i < 15 ; $i++)
	{
		$evento[$i] = test_input($_POST[$char]);
		$char++;
	}
	// rannid qutiyapa
	//
	$rannid = "RANN";
	$counter = 0;
	for($i = 0 ; $i < 15 ; $i++)
	{
		if($evento[$i] == "on")
		{
			$temp = $games[$i];
			if(strpos($games[$i], "Girls") != "")
			{
				$category = "GIRLS";
				$temp = findfirst($temp);
			}
			else
			{
				if(strpos($games[$i], "Boys") != "")
				{
					$category = "BOYS";
					$temp = findfirst($temp);
				}
				else
				{
					$category = "BOTH";
				}
			}

			$sql = "INSERT INTO ranninfo (rannid,fname , email,mobile , college , teamsize , event , edone,category ) VALUES ('".$rannid."','".$name."','".$email."','".$contact."','".$college."','".$teamsize."','".$temp."','0','".$category."')";

			if($conn->query($sql) === FALSE)
			{
				die("<script>window.location = 'error404.html';</script>");
				//die($conn->error);
			}
			else
			{
				if($counter == 0)
				{
					$counter = 1;
					$id = $conn->insert_id;
					$length = strlen($id);
					for($x = 1 ; $x <= 4-$length ; $x++)
					{
						$rannid = $rannid."0";
					}
					$rannid = $rannid.$id;
					$sql1 = "UPDATE ranninfo SET rannid='".$rannid."' WHERE id='".$id."'";
	    			$conn->query($sql1);
				}
			}
		}
	}


	$cookiname = "id";
	$cookivalue = $rannid;
	setcookie($cookiname, $cookivalue, time() + (86400 * 30), "/"); // 86400 = 1 day
	require './phpmailer/PHPMailerAutoload.php';
	$mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = "sports.iitmandi@gmail.com";
	$mail->Password = "HostelGC17";
	$mail->SetFrom("sports.iitmandi@gmail.com");
	$mail->Subject = "Rann-neeti'18";
	$mail->Body = "yo have successfuly registered for the Rann-neeti'18 , your unique team id is "." ".$rannid."\n"."kindly do the payement process for confirming your participation";
	$mail->AddAddress($email);

	if(!$mail->Send())
	{
	    //echo "<script>console.log('Mailer Error: " . $mail->ErrorInfo."')</script>";
	    $sql1 = "UPDATE ranninfo SET edone='0' WHERE rannid=".$rannid."'";
	    $conn->query($sql1);


	}
	else
	{
	   // echo "<script> console.log('Done')</script>";
	    $sql1 = "UPDATE ranninfo SET edone='1' WHERE rannid='".$rannid."'";
	    $conn->query($sql1);
	}
	echo "<script>window.location = 'success.php';console.log('ss');</script> ";

}
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
function findfirst($data) {
	return explode(' ',trim($data))[0];
}

?>
