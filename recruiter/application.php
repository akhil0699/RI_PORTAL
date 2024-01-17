<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../Smtp/index.php';
require '../helpers/general.php';
$mid=$name=$email=$iam='';
$array1=$array2=$array3=array();
$content=$errormsg='';
if(isset($_SESSION['mid'])){
	$mid=$_SESSION['mid'];
	$name=$_SESSION['name'];
	$email=$_SESSION['email'];
	$iam=$_SESSION['iam'];
	$adminrights=$_SESSION['adminrights'];
$cid=$_SESSION['cid'];
$admin=$_SESSION['admin'];
$rcountry=$_SESSION['country'];
	require_once '../config.php';
	if(isset($_GET['candidateid']) && isset($_GET['jobid'])){
		$candidateid=$_GET['candidateid'];
		$jobid=$_GET['jobid'];
		}
	elseif(isset($_GET['candidateid']) && !isset($_GET['jobid'])){
		$candidateid=$_GET['candidateid'];
		header('location: job?candidateid='.$candidateid);
		exit();
		}
	elseif(!isset($_GET['candidateid']) && isset($_GET['jobid'])){
		$jobid=$_GET['jobid'];
		header('location: all?jobid='.$jobid);
		exit();
		}
	elseif(isset($_POST['submit'])){
		$jobid=$_POST['jobid'];
		$candidateid=$_POST['candidateid'];
		$viewnotes=$_POST['viewnotes'];
		$q1=mysqli_query($link,"select a.jobtitle,a.jobtype,a.joblocation,a.description1,a.memberid,d.name from jobs a,companyprofile d,members e where a.jobid='$jobid' and d.id=e.companyid  and e.memberid=a.memberid");
		while($row1 = mysqli_fetch_array($q1)){
			$array1=$row1;
				}
		$q2=mysqli_query($link,"select d.name from companyprofile d,members e where e.memberid='$mid' and d.id=e.companyid ");
		while($row2 = mysqli_fetch_array($q2)){
			$array2=$row2;
				}
		$q3=mysqli_query($link,"select a.fname,a.id,a.email,a.expectedcurrency,a.desiredsalary,a.location,a.nationality,a.notice  from candidates a where a.id='$candidateid'");
		while($row3= mysqli_fetch_array($q3)){
			$array3=$row3;
				}
		$issubmit=mysqli_query($link,"select a.recruiterid,a.id from candidates a, candidates b,submitted_candidates c where a.email=b.email and a.contactnumber=b.contactnumber and b.id=$candidateid and a.recruiterid=b.recruiterid and c.jobid=$jobid and c.candidateid=a.id");
		if(mysqli_num_rows($issubmit)==0){
			$result=mysqli_query($link,"select a.cvlimit,b.cvcount,a.novacancies,a.filledvacancy from jobs a, request b where a.jobid='$jobid' and a.jobid=b.jobid and b.recruiterid in(select memberid from members where companyid='$cid')");
			
			$check=mysqli_fetch_assoc($result);
	
			$cvcount=$check['cvcount'];
			$cvlimit=$check['cvlimit'];
		
			if($check['filledvacancy']<$check['novacancies']){
			
				if($check['cvcount']<$check['cvlimit']){
				
						$duplicate=mysqli_query($link,"select a.recruiterid,a.id from candidates a, candidates b,submitted_candidates c where a.email=b.email and b.id=$candidateid and a.recruiterid <> b.recruiterid and c.jobid=$jobid and c.candidateid=a.id");
						
						if(mysqli_num_rows($duplicate)==0){
								mysqli_query($link,"insert into submitted_candidates(candidateid,jobid,recruiterid,status,viewnotes,submitdate) values ('$candidateid','$jobid','$mid','Awaiting Feedback','$viewnotes',now())");
								mysqli_query($link,"update request set cvcount=cvcount+1 where jobid='$jobid' and recruiterid='$mid'");
								
								//send notification to candidate
		$cc = $email;
		$to = $array3['email'];
		$from = $email;
		$subject = "Recruiting Hub - Your CV has been submitted to ".$array1['name']." by ".$array2['name']."";
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
      <div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/image/color-logo.png" alt="www.recruitinghub.com"></a></div>
      </td>
       </tr>
	  <tr>
        <td style="padding:0px 14px">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
                   <tr>
                    <td style="padding-top:5px;color:#313131"><br>
                    
                      
                      <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                          <td><p>
                            Dear '.$array3['fname'].',<br>
                            <br>
                            Your CV has been submitted to a client and details are as below: <br> <br>
                            
                            Employer Name: <strong>'.$array1['name'].'</strong><br>
                            Role: <strong>'.$array1['jobtitle'].'</strong> <br>
                            Job Type: <strong>'.$array1['jobtype'].'</strong> <br>
                            Location: <strong>'.$array1['joblocation'].'</strong><br>
                            Agency that submitted your profile: <strong>'.$array2['name'].'</strong> 
                            <br>
                            Link to Job Description is here - <strong><a href="https://www.recruitinghub.com/employer/jobdescription/'.$array1['memberid'].'/'.$jobid.'/'.$array1['description1'].'">Click Here to Download JD</a></strong><br>
                            <br>
                            Your Candidate Id is - <strong>'.$array3['id'].'</strong> which you may use as reference to communicate with us or the agency that submitted your profile. Their email id is <strong>'.$email.'</strong><br>
                            <br>
                            If you have not given permission to this recruitment agency to upload your details on our system please email your details to gdpr@recruitinghub.com so we can remove your information.<br>
                            <br>
                            You can also register with us for free if you are an active jobseeker and want to maximise your job search on our recruiters marketplace. Click here to upload your CV -> <a href="https://www.recruitinghub.com/candidate-registration" target="_blank">Register here</a> </p></td>
                        </tr>
                        
                        <p style = "font-size: 14px;
            color: #a02121;">  PLEASE DO NOT REPLY TO THIS EMAIL AS EMAILS ARE NOT MONITORED FOR THIS EMAIL ID. READ BELOW. </p>   
                        
                        <tr>
                          <td><p>Best Regards,<br>
                              <a href="https://www.recruitinghub.com" target="_blank">www.recruitinghub.com </a> </p></td>
                        </tr>
                      </table>
                     
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
      </tr>
</tbody></table>
</div>
</body>
		</html>';
		// end of message
			
		$headers = "From: $from\r\n";
		$headers .= "Content-type: text/html\r\n";
	//	mail($to, $subject, $message, $headers);
	
	
	  $params = ['from'=>'noreply@recruitinghub.com','name'=>'Recruiting Hub - '+$name,'to'=>$to,'subject'=>$subject,'message'=>$message,'cc'=>$cc,'send'=>true];
  AsyncMail($params);
		//mail to employer 
		$q1=mysqli_query($link,"select a.jobtitle,a.jobtype,a.joblocation,e.firstname,e.email,e.memberid,e.awnotification from jobs a,companyprofile d,members e where a.jobid='$jobid' and d.id=e.companyid  and e.memberid=a.memberid");
		while($row1 = mysqli_fetch_array($q1)){
			$arr1=$row1;
				}
		/*$q2=mysqli_query($link,"select e.firstname,e.email from companyprofile d,members e where e.memberid='$mid' and d.id=e.companyid ");
		while($row2 = mysqli_fetch_array($q2)){
			$arr2=$row2;
				}*/
			if($arr1['awnotification']==1)
			{	
		$cc = getAccountManagerDetail($link,$arr1['memberid']);
		$toemp = $arr1['email'];
		$from = "noreply@recruitinghub.com";
		$sub = "Recruiting Hub - ".$array3['fname']." CV for ".$arr1['jobtitle']." from ".$array2['name']."";
		$msg = '<html>
		<body bgcolor="#FFFFFF">
		<div>
<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
  <tbody>
  <tr>
    <td  colspan="2" style="padding:12px 12px 0px 12px">
    	<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
      	<tbody>
	  		<tr>	
            	<td colspan="3" style="padding-left:14px"><div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/image/color-logo.png" alt="www.recruitinghub.com"></a></div></td>
          </tr>
	  <tr>
        <td style="padding:0px 14px"><table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td><table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody><tr>
                <td valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody>
                                    <tr>
                                      <td><table border="0" cellspacing="0" cellpadding="0" width="100%">
                                          <tr>
                                            <td><table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                <tr>
                                                  <td valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                      <tr>
                                                        <td><p><br>
                                                          Dear <strong>'.$arr1['firstname'].'</strong>,<br>
                                                          <br>
                                                          Login here <a href="https://www.recruitinghub.com/employer/login" target="_blank"><b>Employer Login</b></a><br>
                                                          <br>
                                                          You received a candidate and details as below:<br><br>
                                        Name: <strong>'.$array3['fname'].'</strong> <br>
                                        Role: <strong>'.$arr1['jobtitle'].' - </strong> <br>
                                
                                        Location: <strong>'.$array1['joblocation'].'</strong> <br>
                                        Agency: <strong>'.$array2['name'].'</strong><br>
                                                          <br>
                                                          Please login to your Employer account and click <b>CANDIDATES (ATS)</b> on your left panel -> <b>ALL CANDIDATES</b> and open the profile and update your feedback by clicking <b>Action button</b>. Login here <a href="https://www.recruitinghub.com/employer/login" target="_blank"><b>Employer Login</b></a><br>
                                                         
                                                          
                                                          <br>
                                        No longer recruiting? You can login and go to JOBS -> ACTIVE -> Click Action to Close the Job so you dont get this reminders further.  
                                        <br><br>
                                        <p style = "font-size: 14px;
            color: #a02121;">  PLEASE DO NOT REPLY TO THIS EMAIL AS EMAILS ARE NOT MONITORED FOR THIS EMAIL ID. LOGIN TO YOUR ACCOUNT TO RESPOND AS APPROPRIATE </p>   
            
                                                          </p></td>
                                                      </tr>
                                                      
                                                      <tr>
                                                        <td><p>Best Regards,<br>
                                                            <a href="https://www.recruitinghub.com" target="_blank">www.recruitinghub.com </a> </p></td>
                                                      </tr>
                                                  </table></td>
                                                  <td width="20"></td>
                                                </tr>
                                            </table></td>
                                            <td width="20"><p>&nbsp;</p></td>
                                          </tr>
                                      </table></td>
                                    </tr>            
                                   
                                  
                  <tr>
                    <td style="padding:4px 0 0 0;line-height:16px;color:#313131"><br>
                      <br>                  </td>
				  </tr>
                </tbody></table></td>
                <td width="20">&nbsp;</td>
                     </tr>
            </tbody></table></td>
          </tr>
        </tbody>
       </table>
      </td>
      </tr>
    </tbody>
   </table>
 </td>
 </tr>
 <tr>
        <td width="272" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>
          </p></td>
        <td width="346" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px">You can unsubscribe to this alerts by switching off Notification in Manage Notification link in your login<br>
          </p></td>
      </tr>
</tbody>
</table>
</div>
</body>
		</html>';
		// end of message
		$headers = "From: $from\r\n";
		$headers .= "Content-type: text/html\r\n";
		
	//	mail($toemp, $sub, $msg, $headers);
	  $params = ['from'=>'noreply@recruitinghub.com','name'=>'Recruiting Hub','to'=>$toemp,'subject'=>$sub,'message'=>$msg,'cc'=>$cc['email'],'send'=>true];
  AsyncMail($params);		
		
			}	
							
			
			echo "<script>
alert('CV Submission Successful. Our software has now emailed both Client & Candidate a notification about the submission. Please insist your candidate to check their email both inbox/spam folder.');
window.location.href='awaiting-feedback.php';
</script>";
	                    exit();
							}else{
							//$row=mysqli_fetch_assoc($duplicate);
							$content="<p style = 'font-size: 16px;
            color: #a02121;'>Duplicate CV! You could not submit this CV as the same candidate has been submitted for this job by another agency working on this requirement. Call the candidate and check if they have been contacted by another agency for the same job from the same client. Ask them to check their email inbox/spam folder as they must have received an email notification from us (noreply@recruitinghub.com) when another agency have submitted their CV for this job.</p>";
						}
					}
				else
				$content="<p style = 'font-size: 16px;
            color: #a02121;'>You could not submit this candidate as the submission has exceeded the CV limit. Please MESSAGE/ADD NOTES to the Employer to increase CV limit for this Job OR alternatively use the live chat to talk to us by letting us know the Job ID so one of our Account Manager may be able to increase the CV limit on behalf of the client.</p>";
			}	
			else
			$content="<p style = 'font-size: 16px;
            color: #a02121;'>Sorry, you cannot submit further cv's as all the vacancies are filled for this job.</p>";
		}
		else
		$content="<p style = 'font-size: 16px;
            color: #a02121;'>You have already submitted this candidate for this job. OR you may be using SAME EMAIL ID for all candidate submissions specific to this job id. Edit Candidate Email ID and try submitting again. To do this go to -> SUPPLY CANDIDATES (STS) -> Click Action button and Click Edit Profile</p>";	
	}		
	else
	$content="<p style = 'font-size: 16px;
            color: #a02121;'>Submission is not possible. If you are facing technical difficulties use the live chat to talk to us.</p>";
	}	
else{
$errormsg="<p style = 'font-size: 16px;
            color: #a02121;'>Access denied. If you are facing technical difficulties use the live chat to talk to us.</p>";	
}
?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Submit CV | Recruiters Hub</title>
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
<h4>Vendor Section (Business)</h4>
    <li><a href="dashboard" role="tab" ><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
    <li><a href="becomefranchise" role="tab" > <i class="fa fa-database"></i>Become our Franchise <span class="label label-primary">New</span></a> </li>
    <li><a href="search" role="tab" ><i class="fa fa-fw fa-search"></i>Search Roles</a>    </li>
    <li><a href="job" role="tab"><i class="fa fa-fw fa-files-o"></i>Engaged Roles</a>    </li>
    <li><a href="disengaged" role="tab"><i class="fa fa-scissors"></i>CLOSED/DISENGAGED</a>  
    <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Supply Candidates (STS)</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="add_new" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add New Candidates</a>  </li>
    <li><a href="all" role="tab" ><i class="fa fa-list"></i> All Candidates</a> </li>
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
<li><a href="hire-resource" role="tab" > <i class="fa fa-database"></i>Bench Hiring <span class="label label-primary">Free</span></a> </li>
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
    <h4><b>Cover Letter</b> (Final stage of submitting your candidate to the engaged job)</h4> <h4>You are submitting Candidate ID - <b><?php echo $candidateid;?></b> for Job ID <b><?php echo $jobid;?></b></h4>
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
			  		if(!$content){
			   ?>
			  			<form action="application" method="post">
                        <input type="hidden" name="candidateid" value="<?php echo $candidateid;?>" />
                        <input type="hidden" name="jobid" value="<?php echo $jobid; ?>" />
                        <div class="col-md-12">
                        <textarea class="form-control" required placeholder="just type candidate name" name="viewnotes"></textarea>
                       
                        </div>
                        <div class="col-md-12"><br>
                        <input type="submit" value="SUBMIT CANDIDATE" name="submit" />
                        </div>
                        </form>
					<?php	}
					else echo $content;
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
