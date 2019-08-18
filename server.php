<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
// initialising  variables

$name="";
$college="";
$event="";
$contact="";
$people="";

//connect to database

$db = mysqli_connect('localhost','root','','rannneeti') or die("could not connect to database");

//Register User
if(isset($_POST['submit']))
{
  $id=0;
  $rannid="RANN2k19";
  $name=mysqli_real_escape_string($db,$_POST['name']);
  $email=mysqli_real_escape_string($db,$_POST['email']);
  $college=mysqli_real_escape_string($db,$_POST['college']);
  $teamsize=mysqli_real_escape_string($db,$_POST['people']);
  $contact=mysqli_real_escape_string($db,$_POST['contact']);

  $events = array();

  //getting events
  if(isset($_POST['Table-Tennis(Boys)'])){
    array_push($events,$_POST['Table-Tennis(Boys)']);
  }
  if(isset($_POST['Table-Tennis(Girls)'])){
    array_push($events,$_POST['Table-Tennis(Girls)']);
  }
  if(isset($_POST['Badminton(Boys)'])){
    array_push($events,$_POST['Badminton(Boys)']);
  }
  if(isset($_POST['Badminton(Girls)'])){
    array_push($events,$_POST['Badminton(Girls)']);
  }
  if(isset($_POST['Basketball(Boys)'])){
    array_push($events,$_POST['Basketball(Boys)']);
  }
  if(isset($_POST['Basketball(Girls)'])){
    array_push($events,$_POST['Basketball(Girls)']);
  }
  if(isset($_POST['Football(Boys)'])){
    array_push($events,$_POST['Football(Boys)']);
  }
  if(isset($_POST['Cricket(Boys)'])){
    array_push($events,$_POST['Cricket(Boys)']);
  }
  if(isset($_POST['Volleyball(Boys)'])){
    array_push($events,$_POST['Volleyball(Boys)']);
  }
  if(isset($_POST['Volleyball(Girls)'])){
    array_push($events,$_POST['Volleyball(Girls)']);
  }
  if(isset($_POST['Hockey(Boys)'])){
    array_push($events,$_POST['Hockey(Boys)']);
  }
  if(isset($_POST['Athletics(Boys)'])){
    array_push($events,$_POST['Athletics(Boys)']);
  }
  if(isset($_POST['Athletics(Girls)'])){
    array_push($events,$_POST['Athletics(Girls)']);
  }
  if(isset($_POST['Lawn-Tennis'])){
    array_push($events,$_POST['Lawn-Tennis']);
  }
  if(isset($_POST['Chess'])){
    array_push($events,$_POST['Chess']);
  }

  $length=sizeof($events);
  for($i=0;$i<$length;$i++)
  {
      $temp=$events[$i];
      if(strpos($events[$i], "Girls") != "")
      {
        $category = "GIRLS";
        $temp = findfirst($temp);
      }
      else
      {
        if(strpos($events[$i], "Boys") != "")
        {
          $category = "BOYS";
          $temp = findfirst($temp);
        }
        else
        {
          $category = "BOTH";
        }
      }

      //inserting values
      $query = "INSERT INTO ranninfo (rannid,fname , email,mobile , college , teamsize , event , edone,category ) VALUES ('".$rannid."','".$name."','".$email."','".$contact."','".$college."','".$teamsize."','".$temp."','0','".$category."')";
      mysqli_query($db,$query);
      if($i==0)
      {
        $id = mysqli_insert_id($db);
      }
  }

  //Rann Id qutiyapa
  $length = strlen($id);
  for($x = 1 ; $x <= 4-$length ; $x++)
  {
    $rannid = $rannid."0";
  }
  $rannid = $rannid.$id;
  $sql1 = "UPDATE ranninfo SET rannid='".$rannid."' WHERE rannid='RANN2k19'";
  $success=mysqli_query($db,$sql1);

  if($success)
  {
    phpAlert("Form Submitted. Your Rann-NeetiID is : ".$rannid);
  }


  //emailing
  /*
  $cookiname = "id";
	$cookivalue = $rannid;
	setcookie($cookiname, $cookivalue, time() + (86400 * 30), "/"); // 86400 = 1 day
  require 'C:\xamp\phpMyAdmin\vendor\autoload.php';
	$mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'SSL'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = "smtp.students.iitmandi.ac.in";
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = "sports_secretary@students.iitmandi.ac.in";
	$mail->Password = "Rannneeti@2018";
	$mail->SetFrom("sports_secretary@students.iitmandi.ac.in");
	$mail->Subject = "Rann-neeti'19";
	$mail->Body = "you have successfuly registered for the Rann-neeti'18 , your unique team id is "." ".$rannid."\n"."kindly do the payement process for confirming your participation";
	$mail->AddAddress($email);

	if(!$mail->Send())
	{
	    //echo "<script>console.log('Mailer Error: " . $mail->ErrorInfo."')</script>";
	    $sql1 = "UPDATE ranninfo SET edone='0' WHERE rannid=".$rannid."'";
	    mysqli_query($db,$sql1);
	}
	else
	{
	   // echo "<script> console.log('Done')</script>";
	    $sql1 = "UPDATE ranninfo SET edone='1' WHERE rannid='".$rannid."'";
	    mysqli_query($db,$sql1);
	}
	echo "<script>window.location = 'success.php';console.log('ss');</script> ";*/
}

function findfirst($data) {
	return explode(' ',trim($data))[0];
}

function phpAlert($msg) {
    echo '<script type="text/javascript">
          var ask=window.confirm("' . $msg . '")
          if(ask){
            window.location.href="index.html";
          }
          </script>';
}


?>
