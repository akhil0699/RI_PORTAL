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
$cid=$_SESSION['cid'];
$ecountry=$_SESSION['country'];
require_once '../config.php';
require '../Smtp/index.php';
require '../helpers/general.php';
$checks=array();
$pstatus=array();
//if(isset(($_POST['EID'])&&($_POST['jobids']))|| isset($_POST['allcheck']))){
if((isset($_POST['EID'])&&($_POST['jobids']))|| isset($_POST['allcheck'])){
$j=$_POST['jobids'];

	if(isset( $_POST['allcheck'])){
		$checks=$_POST['allcheck'];
		}
	else
	{
		$candidateid=$_POST['EID'];
		array_push($checks,$candidateid);
	}

	if(!empty($checks)){
	foreach($checks as $value) {
	$candidateid=$value;
	$namesql=mysqli_query($link,"select fname from candidates where id='$candidateid'");
	$namerow=mysqli_fetch_assoc($namesql);




	$jobmemsql=mysqli_query($link,"select memberid from jobs where jobid=$j");
	$jobrow=mysqli_fetch_assoc($jobmemsql);

	$jmid =  $jobrow['memberid'];
	$_SESSION['jmid'] = $jmid;

	$nameres[]=$namerow;
	
	$res=mysqli_query($link,"select a.candidateid,a.status,a.recruiterid,b.jobtitle,a.jobid from submitted_candidates a,jobs b WHERE a.candidateid='$candidateid' and a.jobid=b.jobid and b.memberid='$jmid' and a.jobid=$j");
	
	$row=mysqli_fetch_assoc($res);
	$pstatus[]=$row;

	// var_dump($pstatus);
	// exit;
		}
				
				
				}
			
}		
elseif(isset($_POST['submit'])){

    $allcheck=$_POST['allchecks'];
	$comment=htmlspecialchars($_POST['reason'],ENT_QUOTES);
	$prestatus=$_POST['status'];
	$fetchid=$_POST['recrid'];
	$jtitle=$_POST['jtitle'];
	$jid=$_POST['jid'];
	$i=0;	
	if(!empty($allcheck)){
	foreach($allcheck as $value) {
	$candidateid=$value;
	foreach($fetchid as $valuefetch){
	foreach($jid as $valuejid){
		$fetchjid=$valuejid;
	
	if($prestatus[$i]=='Awaiting Feedback'){
	$statusres='CV Rejected';
	}elseif($prestatus[$i]=='Shortlisted'){
	$statusres='Interview Rejected';
	}
	elseif($prestatus[$i]=='Offered'){
	$statusres='Offer Rejected';
	}
	elseif($prestatus[$i]=='Filled'){
	$statusres='Offer Rejected';
	}
	 $jmid = $_SESSION['jmid'] ;

	mysqli_query($link,"Update jobs INNER JOIN submitted_candidates ON submitted_candidates.jobid = jobs.jobid set submitted_candidates.status='Rejected',submitted_candidates.feedbackdate=now(), submitted_candidates.comment='$comment',submitted_candidates.prestatus='$prestatus[$i]' WHERE submitted_candidates.candidateid='$candidateid' and jobs.memberid='$jmid' and submitted_candidates.jobid='$fetchjid'");
	
	
			if(mysqli_affected_rows($link)>0){
			$namesql1=mysqli_query($link,"select fname,email from candidates where id='$candidateid'");


	$namerow1=mysqli_fetch_assoc($namesql1);
			$fetchemail=mysqli_fetch_assoc(mysqli_query($link,"select email from members where memberid='".$valuefetch."'"));
			$fetchname=mysqli_fetch_assoc(mysqli_query($link,"select firstname from members where memberid='".$valuefetch."'"));
			$fetchempname=mysqli_fetch_assoc(mysqli_query($link,"select a.name from companyprofile a,members b where b.memberid='".$jmid."' and a.id=b.companyid"));
			$fetchrecname=mysqli_fetch_assoc(mysqli_query($link,"select a.name from companyprofile a,members b where b.memberid='".$valuefetch."' and a.id=b.companyid"));
			
	$namerow2=mysqli_fetch_assoc($namesql1);
			$fetchemail2=mysqli_fetch_assoc(mysqli_query($link,"select email from candidates where id='$candidateid'"));
			$fetchname2=mysqli_fetch_assoc(mysqli_query($link,"select fname from candidates where id='$candidateid'"));
			
		
			
			foreach($jtitle as $valuejtitle){
			$fetchjtitle=$valuejtitle;
			}
		$r=	getAccountManagerDetail($link,$mid);
		$c=	getCandidatesDetail($link,$mid);
		$to =$fetchemail['email'] ;
		$from = "noreply@recruitinghub.com";
		$subject = "Recruiting Hub - ".$namerow1['fname']." has been ".$statusres." for ".$fetchjtitle." by ".$fetchempname['name']."";
		$message = '<html>
		<body bgcolor="#FFFFFF">
			<div>
<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
  <tbody>
  <tr>
    <td colspan="2" style="padding:12px 12px 0px 12px">
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
                   Dear '.$fetchname['firstname'].',
                      <p>Greetings from <a href="https://www.recruitinghub.com/">www.recruitinghub.com</a></p>
                      
                      <p style = "font-size: 14px;
            color: #a02121;">  PLEASE DO NOT REPLY TO THIS EMAIL AS EMAILS ARE NOT MONITORED FOR THIS EMAIL ID. LOGIN TO YOUR ACCOUNT TO RESPOND AS APPROPRIATE </p>   
                      
					  <b>Details</b>: <br><br>
				  Employer Name: <strong>'.$fetchempname['name'].'</strong><br>
					  Candidate Name: <strong>'.$namerow1['fname'].'</strong>  <br>
					  Job Title: <strong>'.$fetchjtitle.'</strong>  <br>
					  Rejection: <strong>'.$statusres.'</strong>  <br>
					 
					  Reason: <strong><p style = "font-size: 14px;
            color: #a02121;">'.$comment.'</strong><p> 
					  <p>In case you find the reason for rejection not suitable please open the profile and click <b>ADD NOTES</b> from your Recruiter Login to update further progress of this candidature. 
					  <br><br>
					  Login here <a href="https://www.recruitinghub.com/recruiter/login" target="_blank">Recruiter Login</a></p>
		
		
		<p>Best Regards,</p>
		<p><a href="https://www.recruitinghub.com/">www.recruitinghub.com</a></p>
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
       <td width="272" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>
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
		//mail($to, $subject, $message, $headers);
		
		$params = ['from'=>'noreply@recruitinghub.com','name'=>'Recruiting Hub','to'=>$to,
'subject'=>$subject,'message'=>$message,'cc'=>$r['email'],'send'=>true];
	AsyncMail($params);
	
	//mail to candidate
	
	$to =$fetchemail2['email'] ;
		$from = "noreply@recruitinghub.com";
		$subject = "Recruiting Hub - ".$namerow1['fname']." has been ".$statusres." for ".$fetchjtitle." by ".$fetchempname['name']."";
		$message = '<html>
		<body bgcolor="#FFFFFF">
			<div>
<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
  <tbody>
  <tr>
    <td colspan="2" style="padding:12px 12px 0px 12px">
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
                   Dear '.$fetchname2['fname'].',
                      <p>Greetings from <a href="https://www.recruitinghub.com/">www.recruitinghub.com</a></p>
					  <p>Your profile <strong>'.$namerow1['fname'].'</strong> submitted by <strong>'.$fetchrecname['name'].'</strong> for <strong>'.$fetchjtitle.'</strong> has been <strong>'.$statusres.'</strong> by Employer <strong>'.$fetchempname['name'].'</strong> for reason <br>
					  <strong><p style = "font-size: 14px;
            color: #a02121;">'.$comment.'</strong></p>
					  <p>This is an automated email sent by Recruiting Hub to let you know the progress of your candidature in the interview process. The recruiter that submitted your profile is copied in this email who will liase with you for further process.</p>
					  
					  <p>We are sorry that your application was not selected at this time and the employer has moved on with other candidates in their hiring process but we wish you good luck in your job search.</p>
					  
					  <p>If you are an active job seeker and would like to maximise your job search you can as well upload your CV on our recruiter marketplace. Click here to upload your CV -> <a href="https://www.recruitinghub.com/candidate-registration" target="_blank">Register here</a></p>
		
		
		<p>Best Regards,</p>
		<p><a href="https://www.recruitinghub.com/">www.recruitinghub.com</a></p>
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
       <td width="272" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>
          </p></td>
        <td width="346" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px"><br>
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
'subject'=>$subject,'message'=>$message,'cc'=>$fetchemail['email'],'send'=>true];
	AsyncMail($params);
	
	echo "<script>
alert('Candidate has been Rejected. An email notification has now been sent to the Recruiter');
</script>";
		
			/** header('location: awaiting-feedback');**/
			 if($prestatus[$i]=='Awaiting Feedback')
			  header('location: awaiting-feedback.php');
			  elseif($prestatus[$i]=='Shortlisted')
			  header('location: shortlist.php');
			  elseif($prestatus[$i]=='Offered')
			  header('location:offer.php');
			  elseif($prestatus[$i]=='Filled')
			  header('location:all.php');
			$i++;}
			else
			$content="Unable to reject. Please use the live chat below to talk to us.";
		} 
		}
		}
		}
}
else
$errormsg="Access denied";	
}
?>
<?php  foreach($nameres as $namevalue) { echo '<h4 class="modal-title" id="feedback-modal-label">'.$namevalue['fname'].'</h4>'; } ?>
<div id="feedback_name"><strong>Reason for Rejection</strong> </div>
                   <?php if(!$errormsg){ 
			  			if($content)
							echo $content;
						else{ ?>
                            <form action="reject-candidate" method="post">
                                <textarea rows="5" cols="80" class="form-control"  name="reason" required></textarea> 
                                  <?php  foreach($pstatus as $value)
                                    {
                                      echo '<input type="hidden" name="allchecks[]" value="'. $value['candidateid']. '">
									        <input type="hidden" name="status[]" value="'. $value['status']. '">
											<input type="hidden" name="recrid[]" value="'. $value['recruiterid']. '">
											<input type="hidden" name="jtitle[]" value="'. $value['jobtitle']. '">
											<input type="hidden" name="jid[]" value="'. $value['jobid']. '">';
                                    }?>
                                <input type="submit"  name="submit" value="Submit">                             
                            </form>
                        <?php }
						}
			  else echo $errormsg; ?>
                  
          