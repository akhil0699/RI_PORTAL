<?php 
require_once '../config.php';
require '../Smtp/index.php';
require '../helpers/general.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email=$iam='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$adminrights=$_SESSION['adminrights'];
$cid=$_SESSION['cid'];
$admin=$_SESSION['admin'];
$rcountry=$_SESSION['country'];

$notification='';
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
  $data = str_replace("`","",$data);
  return $data;
}
$id=$_REQUEST['id'];
$fname=$_POST['fname'];
$email=$_POST['email'];
$contactnumber=$_POST['contactnumber'];
$currentemployer=test_input($_POST['currentemployer']);
$currentjobtitle=test_input($_POST['currentjobtitle']);
$minex=test_input($_POST['minex']);
$maxex=test_input($_POST['maxex']);
$jobtype=$_POST['jobtype'];
$currentsalary=$_POST['currentsalary'];
$currentcurrency=$_POST['currentcurrency'];
$typesalary=$_POST['typesalary'];
$desiredsalary=$_POST['desiredsalary'];
$expectedcurrency=$_POST['expectedcurrency'];
$nationality=$_POST['nationality'];
$notice=$_POST['notice'];
$location=$_POST['location'];
if(isset($_POST['relocate']))
$relocate=1;
else 
$relocate=0;


$additionalinfo=test_input($_POST['additionalinfo']);
 if (is_uploaded_file($_FILES["cv"]["tmp_name"]) ) {
  				$cv=str_replace("'","-",$_FILES['cv']['name']);
	 $cv=str_replace("`","",$cv);
  				if(file_exists("candidatecvs/".$mid."/".$id."/".$cv))
  					unlink("candidatecvs/".$mid."/".$id."/".$cv);
					
					move_uploaded_file($_FILES['cv']['tmp_name'], "candidatecvs/".$mid."/".$id."/".$cv);
					
					$cv=mysqli_query($link,"update candidates set cv='$cv' where id='$id'");
			
				}
 if (is_uploaded_file($_FILES["video"]["tmp_name"]) ) {
  				
  				$video=str_replace("'","-",$_FILES['video']['name']);
	 $video=str_replace("`","",$video);
  				if(file_exists("video/".$mid."/".$id."/".$video))
  					unlink("video/".$mid."/".$id."/".$video);
					
					move_uploaded_file($_FILES['video']['tmp_name'], "video/".$mid."/".$id."/".$video);
					
					$video=mysqli_query($link,"update candidates set video='$video' where id='$id'");
			
				}
				
if (is_uploaded_file($_FILES["invoice"]["tmp_name"]) ) {
  				$invoice=$_FILES['invoice']['name'];
  				if(file_exists("invoice/".$mid."/".$id."/".$invoice))
  					unlink("invoice/".$mid."/".$id."/".$cv);
					
					move_uploaded_file($_FILES['invoice']['tmp_name'], "invoice/".$mid."/".$id."/".$cv);
					
					$cv=mysqli_query($link,"update candidates set invoice='$invoice' where id='$id'");
			
				}


	//$q=mysqli_query($link,"insert into candidates(fname,email,contactnumber,currentemployer,currentjobtitle,minex,maxex,currentsalary,desiredsalary,typesalary,typesalary1,notice,location,relocate,additionalinfo,cv,video,recruiterid,addeddate) values('$fname','$email','$contactnumber','$currentemployer','$currentjobtitle','$minex','$maxex','$currentsalary','$desiredsalary','$typesalary','$notice','$location','$relocate','$additionalinfo','$cv','$video','$invoice','$mid',now())")or die("Error in query");
	$sql="Update candidates set id='$id',fname='$fname',email='$email',contactnumber='$contactnumber',currentemployer='$currentemployer',currentjobtitle='$currentjobtitle',minex='$minex',maxex='$maxex',jobtype='$jobtype',currentsalary='$currentsalary',currentcurrency='$currentcurrency',typesalary='$typesalary',desiredsalary='$desiredsalary',expectedcurrency='$expectedcurrency',notice='$notice',location='$location',nationality='$nationality',relocate='$relocate',additionalinfo='$additionalinfo',updateddate=now() where id='$id'"
	;
	$q=mysqli_query($link,$sql);
	
	$namerow1=mysqli_fetch_assoc($namesql1);
			$fetchemail=mysqli_fetch_assoc(mysqli_query($link,"select email from members where memberid='".$jmid."'"));
			$fetchname=mysqli_fetch_assoc(mysqli_query($link,"select firstname from members where memberid='".$jmid."'"));
			$fetchempname=mysqli_fetch_assoc(mysqli_query($link,"select a.name from companyprofile a,members b where b.memberid='".$mid."' and a.id=b.companyid"));
			$fetchempnameemail=mysqli_fetch_assoc(mysqli_query($link,"select email from members where memberid='".$mid."'"));
			$fetchempnamename=mysqli_fetch_assoc(mysqli_query($link,"select a.name from companyprofile a,members b where b.memberid='".$jmid."' and a.id=b.companyid"));
			
			$namerow2=mysqli_fetch_assoc($namesql1);
			$fetchemail2=mysqli_fetch_assoc(mysqli_query($link,"select email from candidates where id='$candidateid'"));
			$fetchname2=mysqli_fetch_assoc(mysqli_query($link,"select fname from candidates where id='$candidateid'"));
			
			$namerow3=mysqli_fetch_assoc($namesql1);
			$fetchjobid=mysqli_fetch_assoc(mysqli_query($link,"select jobid from jobs where jobid='$jobid'"));
			$fetchjobtitle=mysqli_fetch_assoc(mysqli_query($link,"select jobtitle from jobs where jobtitle='$jobtitle'"));
			$fetchjobdescription=mysqli_fetch_assoc(mysqli_query($link,"select description1 from jobs where description1='$description1'"));
	
		//mail to candidate
	

		$to =$email ;
		$from = "noreply@recruitinghub.com";
		$subject = "Recruiting Hub - Your CV submitted to ".$fetchempnamename['name']." by ".$fetchempname['name']." has been UPDATED";
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
                   Dear '.$fname.',
                      <p>Greetings from <a href="https://www.recruitinghub.com/">www.recruitinghub.com</a></p>
                     <p> Your CV that was submitted by <strong>'.$fetchempname['name'].'</strong> to the Company <strong>'.$fetchempnamename['name'].'</strong> for the position <strong>'.$fetchjobtitle['jobtitle'].'</strong> has been UPDATED <br><br>
                     
                            Your Candidate Id is - <strong>'.$fetchemail2['id'].'</strong> which you may use as reference to communicate with us or the agency that submitted your profile. <br>
                            <br>
                            If you have not given permission to the agency to upload your details on our system please email your details to gdpr@recruitinghub.com so we can remove your information.<br>
                            <br>
                            You can also register with us for free if you are an active jobseeker and want to maximise your job search on our recruiters marketplace. 
                            <br>
                            Click here to upload your CV -> <a href="https://www.recruitinghub.com/candidate-registration" target="_blank"><b>Register here</b></a> </p>
					  

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
       <td width="272" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2023 <br>
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
'subject'=>$subject,'message'=>$message,'cc'=>$fetchempnameemail['email'],'send'=>true];
	AsyncMail($params);
	
	//mail to employer
		$r=	getAccountManagerDetail($link,$jmid);
		$to =$fetchemail['email'] ;
		$from = "noreply@recruitinghub.com";
		$subject = "Recruiting Hub - ".$fname." submitted by ".$fetchempname['name']." has been UPDATED";
		$message2 = '<html>
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
                     <p> <strong>'.$fname.'</strong> CV that was submitted earlier for the position <strong>'.$fetchjobtitle['jobtitle'].'</strong> has been UPDATED <br><br>
                    
                           </p>
	
		
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
       <td width="272" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2023 <br>
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
'subject'=>$subject,'message'=>$message2,'cc'=>$r['email'],'send'=>true];
	AsyncMail($params);
	
$content="Your profile has been updated";

echo "<script>
alert('Candidate Changes Saved Successfully and an email has been sent to the Candidate Copying you');
window.location.href='all.php';
</script>";


?>