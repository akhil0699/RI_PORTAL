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
require '../Smtp/index.php';
$checks=array();$recruiter='';
if(isset($_GET['requestid']) || isset($_POST['allcheck'])){

	if(isset( $_POST['allcheck']))
		$checks=$_POST['allcheck'];
	else
	{
		$requestid=$_GET['requestid'];
		array_push($checks,$requestid);
	}
} 
	if(!empty($checks)){
	foreach($checks as $value) {
	$requestid=$value;

	mysqli_query($link,"update request set status='Engaged' WHERE requestid='$requestid' and employerid='$mid'"); 
	
	mysqli_query($link,"update companyprofile inner join members on members.companyid=companyprofile.id inner join request on request.recruiterid = members.memberid and request.requestid='$requestid' set companyprofile.engagedcount=  engagedcount+1");
	
	
	$res = mysqli_query($link,"SELECT a.name as recruitercompany, c.firstname as recruiter,c.email,d.jobtitle from companyprofile a,request b,members c,jobs d where a.id=c.companyid and b.requestid='$requestid' and b.recruiterid=c.memberid and b.jobid=d.jobid and b.employerid='$mid'"); 
	
    while($row = mysqli_fetch_array($res)){
		$recruiter=$row;
		}
	$content="You have approved the engagement with ".$recruiter['recruiter']."-".$recruiter['recruitercompany']." If you want to send a message, you can <a href='new-msg'>Compose message </a>";
		$to = $recruiter['email'];
		$from = "noreply@recruitinghub.com";
		$subject = "Recruiting Hub - Your Engagement Request has been approved by the Client";
		$message = '<html>
		<body bgcolor="#FFFFFF">
			<div>
<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
  <tbody>
  <tr>
    <td style="padding:12px 12px 0px 12px">
    <table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
	  <tr>
      <td colspan="3" style="padding-left:14px">
      <div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/images/color-logo.png" alt="www.recruitinghub.com"></a></div>
      </td>
       </tr>
	  <tr>
        <td style="padding:0px 14px">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
                   <tr>
                    <td style="padding-top:5px;color:#313131"><br>
                     Dear '.$recruiter['recruiter'].',<br>
                      
                      <p>Greetings from <a href="https://www.recruitinghub.com/" target="_blank">www.recruitinghub.com</a></p>
		<p>Your request for an engagement for <strong>'.$recruiter['jobtitle'].'</strong> has been approved by the Employer. Client name as a link will be displayed next to Job Title in the Engaged Jobs folder.</p>
		<p>Please login to your account and upload quality profiles for the engaged job post.</p>
		<p>Login here <a href="https://www.recruitinghub.com/recruiter/login" target="_blank">Agency Login</a></p>
		<p>Do use our messaging system to get in touch with the client for any support</p>
		<p>Best Regards,</p>
		<P><a href="https://www.recruitinghub.com/">www.recruitinghub.com</a></p>
					                </td>                
                </tr>            
                                   
                    </tbody>
                    </table>                    
                   </td>
                  </tr>
                 
                </tbody></table></td>
                <td width="20">&nbsp;</td>
                </td>
              </tr>
            
       
 		<tr>
        <td style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px" bgcolor="#792785"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024<br>
          </p></td>
      </tr>
</tbody></table>
</div>
</body>
		</html>';
		// end of message
		$headers = "From: $from\r\n";
		$headers .= "Content-type: text/html\r\n";
		//mail($to, $subject, $message, $headers);
		
		$params = ['from'=>'noreply@recruitinghub.com','name'=>'Recruiting Hub','to'=>$to,
'subject'=>$subject,'message'=>$message,'send'=>true];
	AsyncMail($params);
	
	}	
	}
	
else
$errormsg="Access denied";	
}
else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login'); 
exit;
}?>
<h4 class="modal-title" id="feedback-modal-label">
              <?php if(!$errormsg){ 
			  			if($content)
						echo $content;
						}
			  else echo $errormsg; ?>
              </h4>
    
