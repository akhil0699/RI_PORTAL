<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../config.php';
$mid=$name=$email=$iam='';
$content=$errormsg=$message='';
if(isset($_SESSION['mid'])){
		$mid=$_SESSION['mid'];
		$name=$_SESSION['name'];
		$email=$_SESSION['email'];
		$iam=$_SESSION['iam'];
		$adminrights=$_SESSION['adminrights'];
		$cid=$_SESSION['cid'];
		$admin=$_SESSION['admin'];
		$rcountry=$_SESSION['country'];
$psl=mysqli_fetch_assoc(mysqli_query($link,"select psl from psl where eid=".$_GET['employerid']." and rid='$mid'"));
$free=mysqli_fetch_assoc(mysqli_query($link,"select minex,maxex,jobtype from jobs where jobid=".$_GET['jobid']." and memberid=".$_GET['employerid'].""));
$minex=$free['minex'];
$maxex=$free['maxex'];
$checkjobtype=$free['jobtype'];	
$employerid=$_GET['employerid'];
$jobid=$_GET['jobid'];
if((($minex=='0') &&($maxex=='2')) ||(($minex=='1') &&($maxex=='2')) || (($minex=='0') &&($maxex=='1')))	{
		$echeck=mysqli_fetch_assoc(mysqli_query($link,"select a.name from companyprofile a,members b where a.id=b.companyid and b.memberid='$mid'"));
		$agencyname= $echeck['name'];
		$check=mysqli_num_rows(mysqli_query($link,"select * from request where jobid=".$_GET['jobid']." and recruiterid='$mid'"));
						
						if(!$check)
							{
			$result=mysqli_query($link,"select a.agencylimit,a.agencycount from jobs a where a.jobid='$jobid' and  a.memberid='$employerid'");
			$checks=mysqli_fetch_assoc($result);
			$agencycount=$checks['agencycount'];
			$agencylimit=$checks['agencylimit'];
			
					   if($checks['agencycount']<$checks['agencylimit']){
			

							mysqli_query($link,"insert into request(recruiterid, employerid, jobid, status, startdate) values('$mid', '$employerid', '$jobid', 'Engaged', now())");
							//mysqli_query($link,"update companyprofile inner join members on members.companyid=companyprofile.id set  companyprofile.engagedcount=  engagedcount+1 where members.memberid='$mid'");
							mysqli_query($link,"update jobs set agencycount=agencycount+1 where jobid='$jobid' and memberid='$employerid'");
							
							$emp=mysqli_fetch_assoc(mysqli_query($link,"select a.jobtitle,b.email,b.firstname,b.requestnotification from jobs a, members b where a.jobid='$jobid' and a.memberid=b.memberid"));
							
						if(($emp['requestnotification']==1)&&($psl['psl']!=1)){
							
		$to = $emp['email'];
		$from = "noreply@recruitinghub.com";
		$subject = "RecruitingHub.com - Engagement Alert";
		$message = '<html>
		<body bgcolor="#FFFFFF">
			<div>
<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
  <tbody>
  <tr>
    <td colspan=2 style="padding:12px 12px 0px 12px">
    <table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
	  <tr>
      <td colspan="3" style="padding-left:14px">
      <div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/image/color-logo.png" alt="www.recruitinghub.com"></a></div>
      </td>
       </tr>
	  <tr>
        <td style="padding:0px 14px">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
                   <tr>
                    <td style="padding-top:5px;color:#313131"><br>
                   Dear '.$emp['firstname'].',<br>
                      <br>
                      <p>Greetings from www.recruitinghub.com</p>
		<p>You have been requested for an engagement for the job post "'.$emp['jobtitle'].'" by "'.$agencyname.'" </p>
		<p>Please login to your account and approve the agency so they can start uploading quality profiles.</p>
		<p>Do get in touch with your account manager for any support</p>
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
        <td style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px" bgcolor="#792785"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>
          </p></td>
		   <td width="346" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px">You can unsubscribe to this alerts by switching off Notification in Manage Notification link in your login<br>
          </p></td>
      </tr>
</tbody></table>
</div>
</body>
		</html>';
		// end of message
		$headers = "From: $from\r\n";
		$headers .= "Content-type: text/html\r\n";
		mail($to, $subject, $message, $headers);
				}
				
				
							echo "<script>
alert('Your request for engagement has been sent to the Employer');
window.location.href='search-resources.php';
</script>";
							exit();
						}
						else{
						$content="Exceeded Agency Engagement Limit";
						}
						}
						

} 
elseif($checkjobtype!='permanent'){

		$echeck=mysqli_fetch_assoc(mysqli_query($link,"select a.name from companyprofile a,members b where a.id=b.companyid and b.memberid='$mid'"));
		
		$agencyname= $echeck['name'];
		$check=mysqli_num_rows(mysqli_query($link,"select * from request where jobid=".$_GET['jobid']." and recruiterid='$mid'"));
						if(!$check)
							{
/**$result=mysqli_query($link,"select a.agencylimit,a.agencycount from jobs a where a.jobid='$jobid' and  a.memberid='$employerid'");
							
			$check=mysqli_fetch_assoc($result);
				$agencycount=$check['agencycount'];
			$agencylimit=$check['agencylimit'];
			
			if($check['agencycount']<$check['agencylimit']){***/
			

							mysqli_query($link,"insert into request(recruiterid, employerid, jobid, status, startdate) values('$mid', '$employerid', '$jobid', 'Engaged', now())");
							//mysqli_query($link,"update companyprofile inner join members on members.companyid=companyprofile.id set  companyprofile.engagedcount=  engagedcount+1 where members.memberid='$mid'");
							//mysqli_query($link,"update jobs set agencycount=agencycount+1 where jobid='$jobid' and memberid='$employerid'");
							
							$emp=mysqli_fetch_assoc(mysqli_query($link,"select a.jobtitle,b.email,b.firstname,b.requestnotification from jobs a, members b where a.jobid='$jobid' and a.memberid=b.memberid"));
							
							if(($emp['requestnotification']==1)&&($psl['psl']!=1)){
							
		$to = $emp['email'];
		$from = "noreply@recruitinghub.com";
		$subject = "RecruitingHub.com - Engagement Alert";
		$message = '<html>
		<body bgcolor="#FFFFFF">
			<div>
<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
  <tbody>
  <tr>
    <td colspan=2 style="padding:12px 12px 0px 12px">
    <table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
	  <tr>
      <td colspan="3" style="padding-left:14px">
      <div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/image/color-logo.png" alt="www.recruitinghub.com"></a></div>
      </td>
       </tr>
	  <tr>
        <td style="padding:0px 14px">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
                   <tr>
                    <td style="padding-top:5px;color:#313131"><br>
                   Dear '.$emp['firstname'].',<br>
                      <br>
                      <p>Greetings from www.recruitinghub.com</p>
		<p>You have been requested for an engagement for the job post "'.$emp['jobtitle'].'" by "'.$agencyname.'" </p>
		<p>Please login to your account and approve the agency so they can start uploading quality profiles.</p>
		<p>Do get in touch with your account manager for any support</p>
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
        <td style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px" bgcolor="#792785"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>
          </p></td>
		   <td width="346" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px">You can unsubscribe to this alerts by switching off Notification in Manage Notification link in your login<br>
          </p></td>
      </tr>
</tbody></table>
</div>
</body>
		</html>';
		// end of message
		$headers = "From: $from\r\n";

		$headers .= "Content-type: text/html\r\n";
		mail($to, $subject, $message, $headers);
		
				}
				
				
							echo "<script>
alert('Your request for engagement has been sent to the Employer');
window.location.href='job.php';
</script>";
							exit();
						/*}
						else{
						$content="Exceeded Agency Engagement Limit";
						}**/
						}
				
}
elseif(((($minex!='0') &&($maxex!='2')) ||(($minex!='1') &&($maxex!='2')) || (($minex!='0') &&($maxex!='1'))) && ($psl['psl']==1))	{

		$paidstatus= $engagedcount=$permonthcount =$subsexpirydate='';
		$eligiblecheck=mysqli_fetch_assoc(mysqli_query($link,"select a.name,a.paidstatus,a.engagedcount,a.permonthcount,a.subsexpirydate,a.subsdate,a.substartdate from companyprofile a,members b where a.id=b.companyid and b.memberid='$mid'"));
		
		$agencyname= $eligiblecheck['name'];
		$paidstatus= ucwords($eligiblecheck['paidstatus']);
		
		$engagedcount= $eligiblecheck['engagedcount'];
		$permonthcount= $eligiblecheck['permonthcount'];
		$subsexpirydate= $eligiblecheck['subsexpirydate'];
		$subsdate=$eligiblecheck['subsdate'];
		$substartdate=$eligiblecheck['substartdate'];
		$today = date("Y-m-d");
		
		if(($paidstatus=="Success") && ($today <= $subsexpirydate) && ($substartdate <= $today) ){
			if($engagedcount < $permonthcount){
				if(isset($_GET['employerid']) && isset($_GET['jobid'])){
					$employerid=$_GET['employerid'];
					$jobid=$_GET['jobid'];
						$check=mysqli_num_rows(mysqli_query($link,"select * from request where jobid='$jobid' and recruiterid='$mid'"));
						if(!$check)
							{
							mysqli_query($link,"insert into request(recruiterid, employerid, jobid, status, startdate) values('$mid', '$employerid', '$jobid', 'Engaged', now())");
							//mysqli_query($link,"update companyprofile inner join members on members.companyid=companyprofile.id set  companyprofile.engagedcount=  engagedcount+1 where members.memberid='$mid'");
							$emp=mysqli_fetch_assoc(mysqli_query($link,"select a.jobtitle,b.email,b.firstname,b.requestnotification from jobs a, members b where a.jobid='$jobid' and a.memberid=b.memberid"));
							if(($emp['requestnotification']==1)&&($psl['psl']!=1)){
		$to = $emp['email'];
		$from = "noreply@recruitinghub.com";
		$subject = "Recruitinghub.com - Engagement Alert";
		$message = '<html>
		<body bgcolor="#FFFFFF">
			<div>
<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
  <tbody>
  <tr>
    <td colspan=2 style="padding:12px 12px 0px 12px">
    <table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
	  <tr>
      <td colspan="3" style="padding-left:14px">
      <div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/image/color-logo.png" alt="www.recruitinghub.com"></a></div>
      </td>
       </tr>
	  <tr>
        <td style="padding:0px 14px">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
                   <tr>
                    <td style="padding-top:5px;color:#313131"><br>
                   Dear '.$emp['firstname'].',<br>
                      <br>
                      <p>Greetings from www.recruitinghub.com</p>
		<p>You have been requested for an engagement for the job post "'.$emp['jobtitle'].'" by "'.$agencyname.'" </p>
		<p>Please login to your account and approve the agency so they can start uploading quality profiles.</p>
		<p>Do get in touch with your account manager for any support</p>
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
        <td style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px" bgcolor="#792785"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>
          </p></td>
		   <td width="346" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px">You can unsubscribe to this alerts by switching off Notification in Manage Notification link in your login<br>
          </p></td>
      </tr>
</tbody></table>
</div>
</body>
		</html>';
		// end of message
		$headers = "From: $from\r\n";
		$headers .= "Content-type: text/html\r\n";
		mail($to, $subject, $message, $headers);
		 
	
    
		$to = $email;
		$from = "noreply@recruitinghub.com";
		$subject = "RecruitingHub.com - Your Engagement is approved";
		$message1 = '<html>
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
      <div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/image/color-logo.png" alt="www.recruitinghub.com"></a></div>
      </td>
       </tr>
	  <tr>
        <td style="padding:0px 14px">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
                   <tr>
                    <td style="padding-top:5px;color:#313131"><br>
                    Dear '.$emp['firstname'].',<br>
                      <br>
                      <p>Greetings from www.recruitinghub.com</p>
		<p>Your request for an engagement for '.$emp['jobtitle'].' has been approved by the Employer. </p>
		<p>Please login to your account and upload quality profiles for the engaged job post.</p>
		<p>Do get in touch with your account manager for any support</p>
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
		mail($to, $subject, $message1, $headers);
				}
		
							echo "<script>
alert('Your request for engagement has been sent to the Employer');
window.location.href='job.php';
</script>";
							exit();
						}
						else
						$content="Engaged already."; 
					}
				else
				$content="Application is not possible.";
			}
			else
			$content="You are exceeding the subscription limit. Please upgrade your package.";
 	}	
	else {
	if($rcountry!='India'){
	
	header('location: subscription-plans');
	}else{
	header('location: subscription_plans');
	}
	exit();
							
				}
}	

else{

		$paidstatus= $engagedcount=$permonthcount =$subsexpirydate='';
		$eligiblecheck=mysqli_fetch_assoc(mysqli_query($link,"select a.name,a.paidstatus,a.engagedcount,a.permonthcount,a.subsexpirydate,a.subsdate,a.substartdate from companyprofile a,members b where a.id=b.companyid and b.memberid='$mid'"));
		
		$agencyname= $eligiblecheck['name'];
		$paidstatus= ucwords($eligiblecheck['paidstatus']);
		
		$engagedcount= $eligiblecheck['engagedcount'];
		$permonthcount= $eligiblecheck['permonthcount'];
		$subsexpirydate= $eligiblecheck['subsexpirydate'];
		$subsdate=$eligiblecheck['subsdate'];
		$substartdate=$eligiblecheck['substartdate'];
		 $today = date("Y-m-d");
		
		
		if(($paidstatus=="Success") && ($today <= $subsexpirydate) && ($substartdate <= $today ) ){
			if($engagedcount < $permonthcount){
			
				if(isset($_GET['employerid']) && isset($_GET['jobid'])){
					$employerid=$_GET['employerid'];
					$jobid=$_GET['jobid'];
						$check=mysqli_num_rows(mysqli_query($link,"select * from request where jobid='$jobid' and recruiterid='$mid'"));
						if(!$check)
							{
							mysqli_query($link,"insert into request(recruiterid, employerid, jobid, status, startdate) values('$mid', '$employerid', '$jobid', 'Requested', now())");
							//mysqli_query($link,"update companyprofile inner join members on members.companyid=companyprofile.id set  companyprofile.engagedcount=  engagedcount+1 where members.memberid='$mid'");
							$emp=mysqli_fetch_assoc(mysqli_query($link,"select a.jobtitle,b.email,b.firstname,b.requestnotification from jobs a, members b where a.jobid='$jobid' and a.memberid=b.memberid"));
							if(($emp['requestnotification']==1)&&($psl['psl']!=1)){
							
		$to = $emp['email'];
		$from = "noreply@recruitinghub.com";
		$subject = "RecruitingHub.com - Engagement Alert";
		$message = '<html>
		<body bgcolor="#FFFFFF">
			<div>
<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
  <tbody>
  <tr>
    <td colspan=2 style="padding:12px 12px 0px 12px">
    <table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
	  <tr>
      <td colspan="3" style="padding-left:14px">
      <div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/image/color-logo.png" alt="www.recruitinghub.com"></a></div>
      </td>
       </tr>
	  <tr>
        <td style="padding:0px 14px">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
                   <tr>
                    <td style="padding-top:5px;color:#313131"><br>
                    Dear '.$emp['firstname'].',<br>
                      <br>
                      <p>Greetings from www.recruitinghub.com</p>
		<p>You have been requested for an engagement for the job post "'.$emp['jobtitle'].'" by "'.$agencyname.'" </p>
		<p>Please login to your account and approve the agency so they can start uploading quality profiles.</p>
		<p>Do get in touch with your account manager for any support</p>
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
        <td style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px" bgcolor="#792785"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>
          </p></td>
		   <td width="346" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px">You can unsubscribe to this alerts by switching off Notification in Manage Notification link in your login<br>
          </p></td>
      </tr>
</tbody></table>
</div>
</body>
		</html>';
		// end of message
		$headers = "From: $from\r\n";
		$headers .= "Content-type: text/html\r\n";
		mail($to, $subject, $message, $headers);
				}
						
							echo "<script>
alert('Your request for engagement has been sent to the Employer');
window.location.href='job.php';
</script>";
							exit();
						}
						else
						$content="Engaged already."; 
					}
				else
				$content="Application is not possible.";
			}
			else
			$content="You are exceeding the subscription limit. Please upgrade your package.";
 	}	
	else {
	if($rcountry!='India'){
	
	header('location: subscription-plans');
	}else{
	header('location: subscription_plans');
	}
	exit();
							
				}
}	

}
else{
$errormsg="Access denied";	
}

?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Recruiters Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
     <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="../employer/css/bootstrap.min.css" rel="stylesheet">
    <link href="../employer/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../employer/css/fonts/css/font-awesome.min.css" media="screen, projection">
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../employer/js/bootstrap.min.js"></script>

         
</head>
<body>

<div class="header-inner">
<div class="header-top">
<a class="navbar-brand" href="#"><img src="../employer/images/color-logo.png"></a>
</div>
</div>

<div class="summary-wrapper">
<div class="col-sm-12 account-summary no-padding">

<div class="col-sm-2 no-padding">
<div class="account-navi">
  <ul class="nav nav-tabs">
<h4>Client Section (Business)</h4>
    <li><a href="dashboard" role="tab" ><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
    <li><a href="search" role="tab" ><i class="fa fa-fw fa-search"></i>Search Jobs</a>    </li>
    <li><a href="job" role="tab"><i class="fa fa-fw fa-files-o"></i>Engaged Jobs</a>    </li>
    <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Candidates</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="add_new" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add new</a>  </li>
    <li><a href="all" role="tab" ><i class="fa fa-list"></i> All</a> </li>
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
    <li><a href="interview" role="tab" > <i class="fa fa-users"></i> Interviewing</a>  </li>
     <li><a href="offer" role="tab" > <i class="fa fa-users"></i>Offered</a>  </li>
      <li><a href="filled" role="tab" > <i class="fa fa-users"></i>Filled</a>  </li>
     
    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i> Rejected</a> </li>
    </ul>
   </div>
   </li>
    <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messages </a>    </li>
    <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Feedback Rating</a> </li>
<li><a href="psl-list" role="tab" > <i class="fa fa-list"></i>PSL</a> </li>
 <li><a href="resources" role="tab" > <i class="fa fa-database"></i>Bench Hiring</a> </li>  </ul>
    <h4>Jobseeker Section (Jobsite)</h4>

<li><a href="candidate-search" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> CV Search <span class="label label-primary">Free</span></a>    </li>
    <li><a href="post-job" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Post Jobs <span class="label label-primary">Free</span></a>    </li>
    <li><a href="manage-responses" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Manage responses</a>    </li>
    <li><a href="manage-jobs" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Manage Jobs</a>    </li>
  </ul>
</div>


</div>

<div class="col-sm-10">
<div class="account-navi-content">
  <div class="tab-content">

    <section class="main-content-wrapper">
      <div class="pageheader pageheader--buttons">
          <div class="row">
            <div class="col-md-6">
              <h4>Candidate Application</h4>
            </div>
            <div class="col-md-6">
            <div class="panel-body panel-body--buttons text-right">

              </div>
            </div>
          </div>
        </div>


  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-body">

          <div class="row">
            <div class="col-md-12 table-responsive">
              <?php if(!$errormsg){ 
			  			if($content)
                        echo $content;
						}
			  		else echo $errormsg; ?>
    </div>
   </div>
  </div>
   </div>
    </div>
  </div>
</section>
    
      
     </div>
     </div>
     </div>
<div class="clearfix"></div>
</div>
</div>
 </body>
</html>
