<?php

require '../Smtp/index.php';
require '../helpers/general.php';
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
  $data = str_replace("`","",$data);
  return $data;
}
$fee='';  
$jobtitle=test_input($_POST['jobtitle']);
$novacancies=test_input($_POST['novacancies']);
$jobsector=test_input($_POST['jobsector']);
$country=test_input($_POST['country']);
$joblocation=test_input($_POST['joblocation']);
$city=test_input($_POST['city']);
$vacancyreason=test_input($_POST['vacancyreason']);
$jobtype=test_input($_POST['jobtype']);
$working=test_input($_POST['working']);
$minex=test_input($_POST['minex']); 
$maxex=test_input($_POST['maxex']);

/**if(($minex=='0') && ($maxex=='2')){ 
$fee=$_POST['rfee']; 
}
else{
$fee=$_POST['fee'];
}
if(isset($_POST['fee'])){

$fee=$_POST['fee'];

}else{
$fee='';
}**/

if(($_POST['country']=='India')&&($_POST['jobtype']=='Contract')){
$currency=$_POST['indiacurrency']; 
$minlakh=$_POST['from'];
$minthousand='';
$maxlakh=$_POST['to'];
$maxthousand='';
$ratetype='';
}
elseif($_POST['country']=='India'){
$currency=$_POST['indiacurrency']; 
$minlakh=$_POST['minlakh'];
$minthousand=$_POST['minthousand'];
$maxlakh=$_POST['maxlakh'];
$maxthousand=$_POST['maxthousand'];
$ratetype='';
}
else{ 
$currency=$_POST['othercurrency1'];
$minlakh=$_POST['from'];
$minthousand='';
$maxlakh=$_POST['to'];
$maxthousand='';
$ratetype=$_POST['ratetype'];
}


//$salary=test_input($_POST['salary']);
//$fullpackage=test_input($_POST['fullpackage']);
$description=test_input($_POST['description']);
//$benefits=test_input($_POST['benefits']);
/**if (isset($_POST['benefits'])){
$benefits=implode(',',$_POST['benefits']);}
else {$benefits='';}***/
$degree=mysqli_real_escape_string($link,test_input($_POST['degree']));
$ir35=$_POST['ir35'];
$keyskills=test_input($_POST['keyskills']);

if (isset($_POST['considerrelocation']))
$considerrelocation=$_POST['considerrelocation'];
else
$considerrelocation=0;
/*if (isset($_POST['relocationassistanc']))
$relocationassistanc=$_POST['relocationassistanc'];
else
$relocationassistanc=0;*/
$feedback=$_POST['feedback'];
$closingdate=$_POST['closingdate'];
if (isset($_POST['interviewstages']))
$interviewstages=$_POST['interviewstages'];
else
$interviewstages='';
$interviewcomments=test_input($_POST['interviewcomments']);
$cvlimit=$_POST['cvlimit'];

if (isset($_POST['fee']) &&($_POST['fee']!='')){
$fee=$_POST['fee'];
}
elseif(isset($_POST['rfee']) &&($_POST['rfee']!='')){
$fee=$_POST['rfee'];
}
else{
$fee='';
}
if (isset($_POST['alimit'])){
$alimit=$_POST['alimit'];
}else{
$alimit='';
}
$notice=$_POST['notice']; 
if(isset($_FILES['description1'])){
	 $description1=preg_replace('/[^A-Za-z0-9 _ .-]/', '', $_FILES['description1']['name']);
	 }
else
	$description1='';

$q=mysqli_query($link,"insert into  jobs(memberid,jobtitle,novacancies,jobsector,country,city,joblocation,vacancyreason,jobtype,working,minex,maxex,minlakh,minthousand,maxlakh,maxthousand,ratetype,currency,description,description1,keyskills,degree,ir35,considerrelocation,feedback,closingdate,interviewstages,interviewcomments,cvlimit,fee,agencylimit,notice,postdate,updatedate) values('$mid','$jobtitle','$novacancies','$jobsector','$country','$city','$joblocation','$vacancyreason','$jobtype','$working','$minex','$maxex','$minlakh','$minthousand','$maxlakh','$maxthousand','$ratetype','$currency','$description','$description1','$keyskills','$degree','$ir35','$considerrelocation','$feedback','$closingdate','$interviewstages','$interviewcomments','$cvlimit','$fee','$alimit','$notice',now(),now())");

$id = mysqli_insert_id($link);

if (!file_exists("jobdescription/".$mid)) {
			mkdir("jobdescription/".$mid,0777,true);
			}
			mkdir("jobdescription/".$mid."/".$id,0777,true);
			 if (is_uploaded_file($_FILES["description1"]["tmp_name"]) ) {
			move_uploaded_file($_FILES['description1']['tmp_name'], "jobdescription/".$mid."/".$id."/".$description1);
				}
$content="<h4>You have successfully posted your job on our marketplace! Our AI mechanism will trigger your job to our approved network of specialist recruitment agencies from within your sector and location that can find you quality candidates in hours. Post more jobs by clicking POST NEW JOB link on the left panel. </h4>";
		$to = $email;
		$r=	getAccountManagerDetail($link,$mid);
		$from = "noreply@recruitinghub.com";
		$subject = "Recruiting Hub - Thanks for posting your ".$jobtype." role for ".$jobtitle." in ".$joblocation."";
		$message = '<html>
		<body bgcolor="#FFFFFF">
		<div>
<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
  <tbody><tr>
    <td style="padding:12px 12px 0px 12px"><table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
	  <tr><td colspan="3" style="padding-left:14px"><div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/images/color-logo.png" alt="www.recruitinghub.com"></a></div></td>
              </tr>
	  <tr>
        <td style="padding:0px 14px"><table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td><table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody><tr>
                <td valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody>
                  <tr>
                    <td style="padding-top:5px;color:#313131"><br>
                      Dear ' . $name . ',<br>
                      <br>
                      Thanks for posting your '.$jobtype.' role for <b>'.$jobtitle.'</b> with a Placement fee <b>('.$currency.' / %).'.$fee.'</b> on our marketplace. You will pay this fee to us on success (success means after candidate joining your organization). Our AI mechanism will trigger your job to the best recruitment agencies from within our network that specialise in your sector and location who can supply quality candidates. <br><br>
                      Use our chat widget to talk to our team directly or messaging system to communicate directly with our agencies that request to work for you.<br>
					                </td>                
                </tr>            
                                    <tr>
                    <td>&nbsp;</td>
                  </tr>
                                  
                  <tr>
                    <td style="padding:4px 0 0 0;line-height:16px;color:#313131">Thanks and Regards,<br>
                      <a href="https://www.recruitinghub.com" style="color:#313131" target="_blank">www.recruitinghub.com </a><br>
                      <br>
                      
                  </td>
				  </tr>
                </tbody></table></td>
                <td width="20">&nbsp;</td>
                     </tr>
            </tbody></table></td>
          </tr>
        </tbody></table></td>
      </tr>
    </tbody></table></td>
  </tr>
 <tr>
        <td style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px" bgcolor="#792785"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2023 <br>
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

	$params = ['from'=>'noreply@recruitinghub.com','name'=>'Recruiting Hub','to'=>$to,
	'subject'=>$subject,'message'=>$message,'cc'=>$r['email'],'cc2'=>'employers@recruitinghub.com','send'=>true];
	 AsyncMail($params);



   $subQueryforpsl =  "  UNION ALL SELECT email,firstname,jobalertnotification FROM members WHERE memberid IN(SELECT rid as memberid from psl where eid = $mid and psl = 1 )";


  if(($country=='Africa')||($joblocation=='Africa')){
	$sql = "SELECT email,firstname,jobalertnotification FROM members WHERE iam='Recruiter' and ((country in(select countrybycountry from countrybycountry where country='Africa')) or (country in(select countrybycountry from countrybycountry where country='Africa')))  $subQueryforpsl";
  }elseif(($country=='Middle East')||($joblocation=='Middle East')){
 	$sql = "SELECT email,firstname,jobalertnotification FROM members WHERE iam='Recruiter' and ((country in(select countrybycountry from countrybycountry where country='Middle East')) or (country in(select countrybycountry from countrybycountry where country='Middle East'))) $subQueryforpsl";
  }elseif(($country=='Europe')||($joblocation=='Europe')){
		$sql = "SELECT email,firstname,jobalertnotification FROM members WHERE iam='Recruiter' and ((country in(select countrybycountry from countrybycountry where country='Europe')) or (country in(select countrybycountry from countrybycountry where country='Europe'))) $subQueryforpsl";
  }elseif(($country=='UK')||($joblocation=='UK')){
		$sql = "SELECT email,firstname,jobalertnotification FROM members WHERE iam='Recruiter' and ((country like '%$country%') or (country like '%$joblocation%')) $subQueryforpsl";
  }else{
    $sql = "SELECT email,firstname,jobalertnotification FROM members WHERE iam='Recruiter' and ((location like '%$country%') or (location like '%$joblocation%')) $subQueryforpsl";
  }
	//$sql = "SELECT email FROM members a,members b,companyprofile c,companyprofile d WHERE a.iam='Recruiter' and a.companyid=c.id and b.companyid=d.id and b.memberid=$mid and c.sectors like CONCAT('%', d.sectors,'%')";

	$result = mysqli_query($link,$sql);

	while($row = mysqli_fetch_assoc($result)){

		$mail_body = '<html>
		<body bgcolor="#FFFFFF">
		<div>
<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
  <tbody>
  <tr>
    <td  colspan="2" style="padding:12px 12px 0px 12px">
    	<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
      	<tbody>
	  		<tr>	
            	<td colspan="3" style="padding-left:14px"><div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/images/color-logo.png" alt="www.recruitinghub.com"></a></div></td>
          </tr>
	  <tr>
        <td style="padding:0px 14px"><table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td><table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody><tr>
                <td valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody>
                  <tr>
                    <td style="padding-top:5px;color:#313131"><br>
                     	Dear '.$row['firstname'].',<br><br>
                      Login here <a href="https://www.recruitinghub.com/recruiter/login" target="_blank">Agency Login</a>					                          <br><br>
					  <strong>'.$jobtitle.'</strong> a new <strong>'.$jobtype.'</strong> role in <strong>'.$joblocation.'</strong> has been posted by an Employer on our marketplace for a <strong>Placement fee/End Rate ('.$currency.' / %).'.$fee.'</strong>
					  <br><br>
					  Please login to your agency account and request for an engagement to work on this role.
					  <br><br>
					  Do use our messaging system to communicate directly with the client
					                </td>                
                </tr>            
                                    <tr>
                    <td>&nbsp;</td>
                  </tr>
                                  
                  <tr>
                    <td style="padding:4px 0 0 0;line-height:16px;color:#313131">Best Regards,<br>
                      <a href="https://www.recruitinghub.com" style="color:#313131" target="_blank">www.recruitinghub.com </a><br>
                      <br>
                      
                  </td>
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
        <td width="272" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2023 <br>
          </p></td>
        <td width="346" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px">You can unsubscribe to this alerts by switching off Notification in Manage Notification link in your login<br>
          </p></td>
      </tr>
</tbody>
</table>
</div>
</body>
		</html>';
	
	 $to = $row["email"];
    $subject = 'Recruiting Hub - Job Alert - '.$jobtitle.' - '.$jobtype.' role (New Business) in '.$joblocation.'';
	$from = "noreply@recruitinghub.com";
    $headers = "From: $from\r\n";
	$headers .= "Content-type: text/html\r\n";
	if($row['jobalertnotification']==1){
    //$mail_result = mail($to, $subject, $mail_body, $headers);
    
    $params = ['from'=>'alerts@recruitinghub.com','name'=>'Recruiting Hub','to'=>$to,
'subject'=>$subject,'message'=>$mail_body,'server'=>'alert'];
 AsyncMail($params);
  
  
		}
	
}