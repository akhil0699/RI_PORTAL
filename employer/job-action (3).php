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
require '../helpers/general.php';
if(isset($_GET['jobid']) && isset($_GET['a'])){
	$jobid=$_GET['jobid'];
	$action=$_GET['a'];
	
	$request=mysqli_query($link,"select * from request where jobid='$jobid'");
	/*$check_request=mysqli_num_rows($request);
	if($check_request){
	while($row=mysqli_fetch_assoc($request)){
			$requestid=$row['requestid'];
			if($action=='close')
				mysqli_query($link,"update request set status='Job Closed' WHERE requestid='$requestid' and employerid='$mid'"); 
			elseif($action=='inactive')
				mysqli_query($link,"update request set status='Job on Hold' WHERE requestid='$requestid' and employerid='$mid'"); 
			elseif($action=='filled')
				mysqli_query($link,"update request set status='Job Filled' WHERE requestid='$requestid' and employerid='$mid'"); 
		}
	}
	$candidates=mysqli_query($link,"select * from submitted_candidates where jobid='$jobid'");
	$check_candidates=mysqli_num_rows($candidates);
	if($check_candidates){
	while($row1=mysqli_fetch_assoc($candidates)){
			$submittedid=$row1['id'];
			if($action=='close')
				mysqli_query($link,"update submitted_candidates set status='Job Closed' WHERE id='$submittedid'");
			elseif($action=='inactive')
				mysqli_query($link,"update submitted_candidates set status='Job on Hold' WHERE id='$submittedid'"); 
			elseif($action=='filled')
				mysqli_query($link,"update submitted_candidates set status='Job Filled' WHERE id='$submittedid'");  
		}
	} */
	if($action=='close'){
		mysqli_query($link,"update jobs set status='Closed', priority=0,updatedby='$mid'  WHERE jobid='$jobid' ");
		$content="You have closed the job. Click here to go to Closed Jobs section -> <a href=https://www.recruitinghub.com/employer/closed><b>Closed Jobs</b></a>";
			$request=mysqli_query($link,"select * from request where jobid='$jobid'");

	while($row=mysqli_fetch_assoc($request)){
	    $rid=$row['recruiterid'];
	    $empid=$row['employerid'];

			$q2=mysqli_query($link,"select a.jobtitle,d.name, e.email,e.reconsidernotification,e.firstname from members e, jobs a,companyprofile d where d.id=e.companyid and a.jobid ='$jobid' and e.memberid='$rid' ");
			
	$row2 = mysqli_fetch_array($q2);
	
		$q3=mysqli_query($link,"select c.name, e.email,e.firstname from members e, companyprofile c where c.id=e.companyid and e.memberid='$mid' ");
			
	$row3 = mysqli_fetch_array($q3);
				
		//mail to agency
	$to = $row2['email'];
$companyname=$row3["name"];
$jobtitle=$row2["jobtitle"];
			$from = "noreply@recruitinghub.com";
			
			     $r=	getAccountManagerDetail($link,$empid);

			$subject = "RecruitingHub.com - $companyname has Closed the Job - $jobtitle";

			$message = '<html>

			<body bgcolor="#FFFFFF">

				<div>

	<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">

	  <tbody>
	  <tr>

		<td style="padding:12px 12px 0px 12px"><table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="padding:0px 14px"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="padding-top:5px;color:#313131"><table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
            <tr>
              <td colspan="3" style="padding-left:14px"><div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/images/color-logo.png" alt="www.recruitinghub.com"></a></div></td>
            </tr>
            <tr>
              <td style="padding:0px 14px"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody>
                    <tr>
                      <td style="padding-top:5px;color:#313131"><br>
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                          <tr>
                            <td><table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                  <td valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                                      <tr>
                                        <td><p>
                                         Dear '.$row2['firstname'].',<br>
                                          <br>
                                          Greetings from <a href="https://www.recruitinghub.com" target="_blank">www.recruitinghub.com </a><br>
                                          <br>
                                          <b>'.$companyname.'</b> Closed the Job ID <b>('.$jobid.')</b> - <b>'.$jobtitle.'</b>.<br><br>
                                          
                                          This means none of your candidates submitted (if you have submitted any) has been selected in the interview process. You will receive a separate FILLED notification if any one of your candidate (if you have submitted any) has filled the vacancy.<br><br>
                                          
                                      <p style = "font-size: 14px;
            color: #a02121;">   PLEASE STOP WORKING ON THIS ROLE. This job has now been moved to DISENGAGED/CLOSED folder which can be found in your Recruiter login. We will notify you when the job reopens so you can continue to submit candidates.</p>
                                          <br>
                                          Login here <a href="https://www.recruitinghub.com/recruiter/login" target="_blank">Recruiter Login</a></td>
                                      </tr>
                                      
                                      <tr>
                                        <td><p>Best Regards,<br>
                                            <a href="https://www.recruitinghub.com" target="_blank">www.recruitinghub.com </a> </p></td>
                                      </tr>
                                  </table></td>
                                  <td width="20"><p>&nbsp;</p></td>
                                </tr>
                            </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </tbody>
              </table></td>
            </tr>
          </tbody>
		  </table></td>                

					</tr>
		</tbody>
		</table>                    

					   </td>

					  </tr>
		</tbody>
		</table></td>

					<td width="20">&nbsp;</td>

					</td>

				  </tr>

			<tr>

			<td style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px" bgcolor="#792785"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>

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
'subject'=>$subject,'message'=>$message,'cc'=>$r['email'],'send'=>true];
  AsyncMail($params);
 
	}
		} 
	elseif($action=='inactive'){
		mysqli_query($link,"update jobs set status='Hold', priority=0,updatedby='$mid' WHERE jobid='$jobid' and memberid='$mid'");
		$content="You have put the job on temp hold. Click here to go to Inactive Jobs -> <a href=https://www.recruitinghub.com/employer/inactive><b>Inactive Jobs</b></a>";
		$request=mysqli_query($link,"select * from request where jobid='$jobid'");

	while($row=mysqli_fetch_assoc($request)){
	    $rid=$row['recruiterid'];
	      $empid=$row['employerid'];

						$q2=mysqli_query($link,"select a.jobtitle,d.name, e.email,e.reconsidernotification,e.firstname from members e, jobs a,companyprofile d where d.id=e.companyid and a.jobid ='$jobid' and e.memberid='$rid' ");
			
	$row2 = mysqli_fetch_array($q2);
	
		$q3=mysqli_query($link,"select c.name, e.email,e.firstname from members e, companyprofile c where c.id=e.companyid and e.memberid='$mid' ");
			
	$row3 = mysqli_fetch_array($q3);
				
		//mail to agency
	$to = $row2['email'];
$companyname=$row3["name"];
$jobtitle=$row2["jobtitle"];
			$from = "noreply@recruitinghub.com";
			
			  $r=	getAccountManagerDetail($link,$empid);

			$subject = "RecruitingHub.com - $companyname has put the Job on Temporary Hold - $jobtitle";

			$message = '<html>

			<body bgcolor="#FFFFFF">

				<div>

	<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">

	  <tbody>
	  <tr>

		<td style="padding:12px 12px 0px 12px"><table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="padding:0px 14px"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="padding-top:5px;color:#313131"><table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
            <tr>
              <td colspan="3" style="padding-left:14px"><div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/images/color-logo.png" alt="www.recruitinghub.com"></a></div></td>
            </tr>
            <tr>
              <td style="padding:0px 14px"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody>
                    <tr>
                      <td style="padding-top:5px;color:#313131"><br>
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                          <tr>
                            <td><table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                  <td valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                                      <tr>
                                        <td><p>
                                         Dear '.$row2['firstname'].',<br>
                                          <br>
                                          Greetings from <a href="https://www.recruitinghub.com" target="_blank">www.recruitinghub.com </a><br>
                                          <br>
                                          <b>'.$companyname.'</b> has put the <b>'.$jobtitle.'</b> - Job ID <b>('.$jobid.')</b> that you engaged earlier on TEMPORARY HOLD. 
                                          
                                          <br><br>
                                         <b>PLEASE STOP WORKING ON THIS ROLE UNTIL FURTHER NOTICE.</b> This job has now been moved to DISENGAGED/CLOSED folder which can be found in your Recruiter login. We will notify you when the job reopens so you can continue to submit candidates.<br>
                                          <br>
                                          Login here <a href="https://www.recruitinghub.com/recruiter/login" target="_blank">Recruiter Login</a></td>
                                      </tr>
                                      
                                      <tr>
                                        <td><p>Best Regards,<br>
                                            <a href="https://www.recruitinghub.com" target="_blank">www.recruitinghub.com </a> </p></td>
                                      </tr>
                                  </table></td>
                                  <td width="20"><p>&nbsp;</p></td>
                                </tr>
                            </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </tbody>
              </table></td>
            </tr>
          </tbody>
		  </table></td>                

					</tr>
		</tbody>
		</table>                    

					   </td>

					  </tr>
		</tbody>
		</table></td>

					<td width="20">&nbsp;</td>

					</td>

				  </tr>

			<tr>

			<td style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px" bgcolor="#792785"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>

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
'subject'=>$subject,'message'=>$message,'cc'=>$r['email'],'send'=>true];
  AsyncMail($params);
	}
		} 
	elseif($action=='filled'){
		mysqli_query($link,"update jobs set status='Filled', priority=0,updatedby='$mid' WHERE jobid='$jobid' and memberid='$mid'");  
		$content="You have changed the job status as Filled. Click here to go to FILLED Jobs -> <a href=https://www.recruitinghub.com/employer/filled><b>FILLED Jobs</b></a>";
		}
	elseif($action=='reopen'){
        mysqli_query($link,"update jobs set status='Open',priority=1,updatedby='$mid',updatedate=now() WHERE jobid='$jobid' ");  
        $content="You have changed the job status to Open. Click here to go to FILLED Jobs -> <a href=https://www.recruitinghub.com/employer/jobs><b>OPEN Jobs</b></a>";
        $request=mysqli_query($link,"select * from request where jobid='$jobid'");

	while($row=mysqli_fetch_assoc($request)){
	    $rid=$row['recruiterid'];
	      $empid=$row['employerid'];

					$q2=mysqli_query($link,"select a.jobtitle,d.name, e.email,e.reconsidernotification,e.firstname from members e, jobs a,companyprofile d where d.id=e.companyid and a.jobid ='$jobid' and e.memberid='$rid' ");
			
	$row2 = mysqli_fetch_array($q2);
	
		$q3=mysqli_query($link,"select c.name, e.email,e.firstname from members e, companyprofile c where c.id=e.companyid and e.memberid='$mid' ");
			
	$row3 = mysqli_fetch_array($q3);
				
		//mail to agency
	$to = $row2['email'];
$companyname=$row3["name"];
$jobtitle=$row2["jobtitle"];
			$from = "noreply@recruitinghub.com";
			
			$r=	getAccountManagerDetail($link,$empid);

			$subject = "RecruitingHub.com - $companyname Reopened the Job - $jobtitle";

			$message = '<html>

			<body bgcolor="#FFFFFF">

				<div>

	<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">

	  <tbody>
	  <tr>

		<td style="padding:12px 12px 0px 12px"><table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="padding:0px 14px"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="padding-top:5px;color:#313131"><table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
            <tr>
              <td colspan="3" style="padding-left:14px"><div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/images/color-logo.png" alt="www.recruitinghub.com"></a></div></td>
            </tr>
            <tr>
              <td style="padding:0px 14px"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody>
                    <tr>
                      <td style="padding-top:5px;color:#313131"><br>
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                          <tr>
                            <td><table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                  <td valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                                      <tr>
                                        <td><p>
                                         Dear '.$row2['firstname'].',<br>
                                          <br>
                                          Greetings from <a href="https://www.recruitinghub.com" target="_blank">www.recruitinghub.com </a><br>
                                          <br>
                                          You have earlier worked on JOB ID <b>('.$jobid.')</b> - <b>'.$jobtitle.'</b> role from <b>'.$companyname.'</b> has now been RE-OPENED by the client . Please start reworking on this role and start submitting candidates <br>
                                          <br>
                                          Login here <a href="https://www.recruitinghub.com/recruiter/login" target="_blank">Recruiter Login</a></td>
                                      </tr>
                                      <tr>
                                        <td><p>&nbsp;</p></td>
                                      </tr>
                                      <tr>
                                        <td><p>Best Regards,<br>
                                            <a href="https://www.recruitinghub.com" target="_blank">www.recruitinghub.com </a> </p></td>
                                      </tr>
                                  </table></td>
                                  <td width="20"><p>&nbsp;</p></td>
                                </tr>
                            </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </tbody>
              </table></td>
            </tr>
          </tbody>
		  </table></td>                

					</tr>
		</tbody>
		</table>                    

					   </td>

					  </tr>
		</tbody>
		</table></td>

					<td width="20">&nbsp;</td>

					</td>

				  </tr>

			<tr>

			<td style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px" bgcolor="#792785"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>

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
'subject'=>$subject,'message'=>$message,'cc'=>$r['email'],'send'=>true];
  AsyncMail($params);
	}
        header('location: jobs'); exit;
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
}
?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Employers Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
    <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

         
</head>
<body>

<div class="header-inner">
<div class="header-top">
<div class="logo">
<a class="navbar-brand" href="#"><img src="images/color-logo.png"></a>
<a class="btn btn-primary" href="submit-a-job">
     Helpline number +44 02030267557
</a>
</div>
<ul class="navbar-right">
  <li class="dropdown">
    <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">
  <img src="images/user.png" class="img-circle" alt="">     <span class="text"><?php echo $name; ?></span><span class="caret"></span> </a>
    <ul class="dropdown-menu" role="menu"><li><a href="company-profile"><i class="fa fa-user"></i>Company Profile</a>   </li>
      <li><a href="edit-profile"><i class="fa fa-gears"></i>Your Account</a></li>
       <li><a href="manage-users"><i class="fa fa-users"></i>Manage Users</a></li>
         <li><a href="emanage-notification"><i class="fa fa-bell"></i>Manage Notification</a></li>
      <li class="divider"></li>
      <li> <a href="logout"><i class="fa fa-sign-out"></i>Logout</a></li>
    </ul>
  </li>

</ul>

</div>
</div>
<div class="summary-wrapper">
<div class="col-sm-12 account-summary no-padding">

<div class="col-sm-2 no-padding">
<div class="account-navi">
  <ul class="nav nav-tabs">
  <li><a href="dashboard" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
  <li><a href="submit-a-job" role="tab"><i class="fa  fa-fw fa-plus"></i>Post New Job</a></li>


     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i>Jobs (ATS)</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="submit-a-job" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Post New Job</a>  </li>
   <li><a href="jobs" role="tab" ><i class="fa fa-list"></i>Active Jobs</a> </li>
    <li><a href="inactive" role="tab" ><i class="fa fa-list"></i>Inactive Jobs</a> </li>
    <li><a href="closed" role="tab" ><i class="fa fa-clock-o"></i>Closed Jobs</a>  </li>
    <li><a href="filled" role="tab" > <i class="fa fa-users"></i>Filled Jobs</a>  </li>
  
    </ul>
   </div>
   </li>
     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu1" data-toggle="collapse" aria-controls="candidatesDropdownMenu1" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i>Candidates (ATS)</a>

   <div class="collapse" id="candidatesDropdownMenu1">
   <ul class="nav-sub" style="display: block;">
   
    <li><a href="all" role="tab" ><i class="fa fa-list"></i>All Candidates</a> </li>
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
    <li><a href="shortlist" role="tab" > <i class="fa fa-users"></i>Shortlisted</a>  </li>
        <li><a href="offer" role="tab" > <i class="fa fa-trash"></i>Offered</a> </li>
    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i>Rejected</a> </li>
    </ul>
   </div>
   </li>
   
     <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messages</a>    </li>
    <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Feedback Rating</a> </li>
 <li><a href="psl-list" role="tab" > <i class="fa fa-list"></i> PSL</a> </li>
<li><a href="company-profile" role="tab" > <i class="fa fa-plus"></i>Company Profile</a> </li>
<li><a href="add-user" role="tab" > <i class="fa fa-plus"></i>Add Users</a> </li>
    <li><a href="view-all-resource-engagement" role="tab" > <i class="fa fa-database"></i>Request for Your Bench Resources <span class="label label-primary">Free</span></a> </li>  
    <li><a href="resources" role="tab" > <i class="fa fa-database"></i>Bench Hiring <span class="label label-primary">Free</span></a> </li>   </ul>
   
</div>
</div>

<div class="col-sm-10">
<div class="account-navi-content">
  <div class="tab-content">

    <section class="main-content-wrapper">
      <div class="pageheader pageheader--buttons">
          <div class="row">
            <div class="col-md-6">
              <h4>Action Result</h4>
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
