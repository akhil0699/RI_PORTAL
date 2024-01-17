<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email=$iam='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$ecountry=$_SESSION['country'];
$cid=$_SESSION['cid'];
}
else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login'); 
exit;
}
require_once '../config.php';
include_once '../Smtp/index.php';
if(isset($_GET['a'])&&($_GET['a']!='')){
$a=$_GET['a']; 
}
if(isset($_GET['eid'])&&($_GET['eid']!='')){
$eid=$_GET['eid'];
}
if(isset($_GET['resrcid'])&&($_GET['resrcid']!='')){
$resrcid=$_GET['resrcid'];	
}	
if($a=='at'){	

$sql=mysqli_query($link,"update resourceengagedetails set finalstatus=1 where resourceid=$resrcid and engagedby=$eid");	

$joinT = "SELECT name FROM companyprofile WHERE id=".$cid;
              $resultT = mysqli_query($link,$joinT);
              $company = mysqli_fetch_array($resultT);
              
$emp=mysqli_fetch_assoc(mysqli_query($link,"select a.*,b.* from resource a, members b where a.id='$resrcid' and a.emid=b.memberid"));

$message_temlt = '<html>
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
                                        <td valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                                            <tr>
                                              <td><p><br>
                                                 Dear '.$_GET['name'].',<br>
                                                
                        <p>Your Engagement request for a Bench Resource "<strong>'.$emp['consultant'].'</strong>" with Title "<strong>'.$emp['jobtitle'].'</strong>" has been approved by <strong>'.$name.' from ('.$company['name'].')</strong>.</p>
                        
                        <p> Login here <a href="https://www.recruitinghub.com/employer/login" target="_blank">Login Here</a></p>
                        
                        <p>Please login to your account, click <b>BENCH HIRING</b> -> <b>SEARCH BENCH RESOURCES FROM OTHERS</b> and click <b>"View Resource Detail"</b> to contact the Company that uploaded this bench resource directly.</p>
                                    
                
                                                 
                                                
                                                </td>
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
                                <tr>
                <td>&nbsp;</td>
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
         
          $params = ['from'=>'noreply@recruitinghub.com','name'=>'Recruiting Hub','to'=>$_GET['email'],'subject'=>'RecruitingHub.com - Bench Resource Request for '.$emp['consultant'].' has been Approved - Get in touch','message'=>$message_temlt,'send'=>true];
        
        
          AsyncMail($params);
          





}elseif($a=='rt'){ 

        $sql=mysqli_query($link,"update resourceengagedetails set finalstatus=0 where resourceid=$resrcid and engagedby=$eid");	



    //Rejected Mail 

                  









}
if(mysqli_affected_rows($link)){
header('location: view-all-resource-engagement');
}else{
header('location: view-all-resource-engagement');

}
	?>