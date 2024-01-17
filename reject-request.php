<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email=$iam='';
$content=$errormsg='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$ecountry=$_SESSION['country'];
$cid=$_SESSION['cid'];

require_once '../config.php';

$checks=array();$recruiter='';

if(isset($_GET['requestid']) || isset($_POST['allcheck'])){
	if(isset( $_POST['allcheck'])){
		$checks=$_POST['allcheck'];
		}
	else
	{
		$requestid=$_GET['requestid'];
		array_push($checks,$requestid);
	}
}

	if(!empty($checks)){

	foreach($checks as $value) {

	$requestid=$value;



	mysqli_query($link,"Update request set status='Rejected' WHERE requestid='$requestid' and employerid='$mid'"); 

	//mysqli_query($link,"update jobs INNER JOIN request ON request.jobid = jobs.jobid set jobs.status='open', jobs.engagedby = '' WHERE request.requestid='$requestid'");

	

	$content="You have rejected the engagement(s)";

	}

	}

else{

$errormsg="Access denied";	

}

}

else{

$notification='Please login to access the page';

$_SESSION['notification']=$notification;

header('location: login'); 

exit;

}	

?>

<h4 class="modal-title" id="reject-modal-label">



              <?php if(!$errormsg){ 

			  			if($content)

						echo $content;

						}

			  else echo $errormsg; ?>

    </h4>