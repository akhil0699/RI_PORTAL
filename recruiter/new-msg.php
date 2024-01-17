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
$adminrights=$_SESSION['adminrights'];
$cid=$_SESSION['cid'];
$admin=$_SESSION['admin'];
$rcountry=$_SESSION['country'];
$notification='';
}
else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login'); 
exit;
}
require_once '../config.php';
require '../Smtp/index.php';
require '../helpers/general.php';
$content='';
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
$employers=array();
// change
$empres=mysqli_query($link,"SELECT d.firstname as empname,d.email as empemail,  b.name as companyname,d.memberid as employer,a.jobtitle as jobtitle,a.jobid FROM jobs a,companyprofile b ,request c, members d WHERE c.recruiterid='$mid' and c.jobid=a.jobid and a.memberid=d.memberid and b.id=d.companyid");
if(mysqli_num_rows($empres)){
while($row=mysqli_fetch_assoc($empres)){
$employers[]=$row;
}
}

if(isset($_POST['send'])){
		function test_input($data) {
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
		  return $data;
		}
		
		$subject = test_input($_POST['subject']);
		$recipid = $_POST['recipid'];
		$message = test_input($_POST['message']);
		
		  $replacewith = "hiddentext";
    $message = preg_replace("/[\w-]+@([\w-]+\.)+[\w-]+/", $replacewith, $message);
    $message = preg_replace("/^(\(?\d{3}\)?)?[- .]?(\d{3})[- .]?(\d{4})$/", $replacewith, $message);
    $message = preg_replace("/^(\(?\d{4}\)?)?[- .]?(\d{3})[- .]?(\d{4})$/", $replacewith, $message);
    
    $joi = "SELECT messagenotification FROM members WHERE memberid=".$mid;
    $results = mysqli_query($link,$joi);
    $cnsd_rec = mysqli_fetch_array($results);
    $empsemailnotify = $cnsd_rec["messagenotification"];

// change
$join = "SELECT CONCAT(firstname,' ',lastname) as empname,email FROM members WHERE memberid=".$recipid;
$result = mysqli_query($link,$join);
$cnsd = mysqli_fetch_array($result);
$empsemail = $cnsd["email"];


		if(isset($_FILES['attach'])){
	  $attach=$_FILES['attach']['name'];
			 }
		else{
		$attach='';
		}
	  $corder=mysqli_fetch_assoc(mysqli_query($link,"select max(id) as maxid from personalmsg"));
	  $id=$corder['maxid'] + 1;
    // Added created_at column to get the msg created time
		$res=mysqli_query($link,'insert into personalmsg(id,conversationid, subject, fromuser, touser, message, mtimestamp, fromread, toread, attachment, created_at) values("'.$id.'", "1", "'.$subject.'", "'.$mid.'", "'.$recipid.'", "'.$message.'",now(), "1", "0","'.$attach.'", now())');


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
                                                     Dear '.$cnsd["empname"].',<br>
                                                     
                                            <p style = "font-size: 14px;
            color: #a02121;">  PLEASE DO NOT REPLY TO THIS EMAIL AS EMAILS ARE NOT MONITORED FOR THIS EMAIL ID. LOGIN TO YOUR ACCOUNT TO RESPOND AS APPROPRIATE </p>   
            
            Please login to your Account and click MESSAGING to reply back to this message. Login here <a href="https://www.recruitinghub.com/employer/login" target="_blank"><b>Employer Login</b></a></p>
                                                    
                            <p>You have received a message from the Recruiter <strong>'.$dn2["usedid"].'</strong> as below</p>
                                        
                                        <p> "<strong>'.$message.'</strong>"</p>
                                                     
                                                   
                                                    
                                                    </td>
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
        <td width="272" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px"> All Rights Reserved. &copy; Copyright 2024 <br>
          </p></td>
        <td width="346" bgcolor="#792785" style="font-size:11px;color:#ffffff;padding:9px 0 10px 18px;line-height:16px"><p style="margin:0px;padding:0px">Worlds No.1 Online Recruitment Marketplace<br>
          </p></td>
      </tr>
</tbody>
</table>
</div>
</body>
    </html>';


    if($res){
    $attachid=mysqli_insert_id($link);
    mkdir("../pm/".$attachid,0777,true);
     if(is_uploaded_file($_FILES["attach"]["tmp_name"]) ) {
    		move_uploaded_file($_FILES['attach']['tmp_name'], "../pm/".$attachid."/".$attach);
     }

if($empsemailnotify == 1){
  
   $r = getAccountManagerDetail($link,$_POST['recipid']);
 $params = ['from'=>'noreply@recruitinghub.com','name'=>'Recruiting Hub','to'=>$empsemail,'subject'=>$subject,'message'=> $message_temlt,'cc'=>$r['email'],'send'=>true];
  AsyncMail($params);
}
    $content="<p>Your message has been successfully sent and our software has triggered an automated email to the registered email id of the client.</p>";

    }
	
}	
?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>New Message | Recruiters Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
    <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
         
</head>
<body>

<div class="header-inner">
<div class="header-top">
<a class="navbar-brand" href="#"><img src="images/color-logo.png"></a>
<ul class="navbar-right">
  <li class="dropdown">
    <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">
  <img src="images/user.png" class="img-circle" alt="">     <span class="text"><?php echo $name; ?></span><span class="caret"></span> </a>
    <ul class="dropdown-menu" role="menu"><li><a href="company-profile"><i class="fa fa-user"></i>Company Profile</a>   </li>
      <li><a href="edit-profile"><i class="fa fa-gears"></i>Your Account</a></li>
       <?php if($admin){
       echo'<li><a href="manage-users"><i class="fa fa-users"></i>Manage Users</a></li>';
	  /** if($rcountry=='India'){echo'
	   <li><a href="subscription_plans"><i class="fa fa-money"></i> Manage Subscription</a></li>';}
	   else{echo'
	    <li><a href="subscription-plans"><i class="fa fa-money"></i> Manage Subscription</a></li>';
		}echo'
     <li><a href="subscription-status"><i class="fa fa-info-circle"></i> Subscription Status</a></li>
	   ';**/
	 }
	   ?>
      <li><a href="rmanage-notification"><i class="fa fa-bell"></i>Manage Notification</a></li>
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
<h4>Vendor Section</h4>
    <li><a href="dashboard" role="tab" ><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
 <li><a href="becomefranchise" role="tab" > <i class="fa fa-database"></i>Become our Franchise <span class="label label-primary">New</span></a> </li>
    <li><a href="search" role="tab" ><i class="fa fa-fw fa-search"></i>Search Roles</a>    </li>
    <li><a href="job" role="tab"><i class="fa fa-fw fa-files-o"></i>Engaged Roles</a>    </li>
    <li><a href="disengaged" role="tab"><i class="fa fa-scissors"></i>CLOSED / DISENGAGED</a>  
    <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Supply Candidates (STS)</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="add_new" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add New Candidate</a>  </li>
    <li><a href="all" role="tab" ><i class="fa fa-list"></i>All Candidates</a> </li>
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
    <li><a href="interview" role="tab" > <i class="fa fa-users"></i> Shortlisted</a>  </li>
     <li><a href="offer" role="tab" > <i class="fa fa-envelope"></i>Offered</a>  </li>
      <li><a href="filled" role="tab" > <i class="fa fa-users"></i>Filled</a>  </li>
     
    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i> Rejected</a> </li>
    </ul>
   </div>
   </li>
<li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messaging (<span class="label-primary"><?php echo $new; ?></span>)</a>    </li>
    <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Feedback Rating</a> </li>
<li><a href="psl-list" role="tab" > <i class="fa fa-list"></i>PSL</a> </li>
<li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu1" data-toggle="collapse" aria-controls="candidatesDropdownMenu1" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Agency Settings</a>

   <div class="collapse" id="candidatesDropdownMenu1">
   <ul class="nav-sub" style="display: block;">
    <li><a href="company-profile" role="tab" > <i class="fa fa-briefcase"></i>Agency Profile</a> </li>
    <li><a href="add-user" role="tab" > <i class="fa fa-plus"></i>Add Users</a> </li>
    <li><a href="edit-profile" role="tab" > <i class="fa fa-plus"></i>Your Profile</a> </li>
    
    </ul>
   </div>
   </li>
 <li><a href="hire-resource" role="tab" > <i class="fa fa-database"></i>Bench Hiring <span class="label label-primary">Free</span></a> </li>
    <h4>Jobseeker Section</h4>

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
                 <div class="col-md-12">
                  <a class="btn btn-primary" href="msg">
    <i class="fa fa-step-backward"></i>  Go back to Messages
</a> 

  <div class="row">
 <div class="col-xs-12">
      <section class="panel panel-default">
        <header class="panel-heading panel-heading--buttons wht-bg">
          <h4 class="gen-case">Send New message (Please be as polite as possible while writing to clients. Please be as informative as possible about your message instead of writing a single line.</h4> 
        </header>
        <div class="panel-body minimal">			
          <div class="table-responsive ">
			<?php if($content) echo $content;
			else{
			?>
            <form enctype="multipart/form-data" action="new-msg.php" method="post">
              <div class="form-group col-sm-12">
              <div class="col-sm-3 ">
                  <label>Message Employer (Search from list)</label>
                  </div>
              <div class="col-sm-9">
                 <?php if(isset($_GET['eid'])){ ?>
                  <select  class="form-control" name="recipid" readonly id="engage">
                <?php }else{ ?>
                 <select  class="form-control" name="recipid" id="recipid">
               <?php } ?>
                <!-- change -->
              <?php 
			  if(isset($_GET['eid']))
			  $eid=$_GET['eid'];
			  else
			  $eid=0;
         $re = 0;
          foreach($employers as $row){
          if($row['jobid'] == $_GET['jobid'])
          echo ' <option data-member="'.$row['empname'].'" value="'.$row['employer'].'" selected>'.$row['jobtitle'].' - '.$row['companyname'].'</option>';
          else
          echo ' <option value="'.$row['employer'].'">'.$row['jobtitle'].' - '.$row['companyname'].'</option>';
          }?>
          <!--  change -->
            </select>
            <p class="help-block">Search Recipient by either typing Employer Name or Job Title.</p>
            </div>
            </div>
            
  <div class="form-group col-sm-12">
  <div class="col-sm-3 "><label> Subject (Edit Subject if you like)</label>
    </div>
      <div class="col-sm-9">
        <?php
            if(isset($_GET['eid'])){ 
               $cand= getCandDetail($link,$_GET['cid']);
              
                 ?>
                  <input id="candi" required type="hidden" name="candi" value="<?php echo $cand['fname']; ?>"  class="form-control" />
                <input id="membersub" required type="text" name="subject" value="<?php echo $cand['fname'].' - '. $row['companyname'] .' - '.$row['empname'] .' - '. $row['jobtitle'];  ?>"  class="form-control" />
                <?php 
                 }else{
                 ?>
                <input required type="text" name="subject" class="form-control" />
                <?php  }
                ?>
                </div>
                </div>
              
                <div class="form-group col-sm-12">
                <div class="col-sm-3">
                <label > Message</label></div>
                <div class="col-sm-9">
                <textarea rows="5" required name="message"  class="form-control" ></textarea>
                </div></div>
                
                <!--<div class="form-group col-sm-12">
                <div class="col-sm-3 ">
                <label>Add Attachment</label>
                </div>
                
                <div class="col-sm-9">
                <input type="file" name="attach"  class="form-control" />
                <p class="help-block">Please click choose file to add an attachment to your message. For example: an offer letter, or map to your office<br />(Max File Size: 1 MB)</p>
                </div></div>-->
                
  <input type="submit" name="send" value="Send Message" class="btn-lg btn-default" /> <br><br>
  <span class="label label-primary">Please don't attempt to share your email id or phone number asking clients to contact you directly. Our AI mechasim triggers such messages to us and your account may be restricted.</span>
</form>
<?php } ?>
            
		 </div>
        </div>
      </section>
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

 <script type="text/javascript">
   var member = $( "#engage option:selected" ).text();
   var membername = $( "#engage option:selected" ).data('member');
   var streetaddress= member.split('-'); 
   $('#engage').select2();
   var cname= $( "#candi" ).val();
   $(function () {
      $('input#membersub').val(cname+' - '+member+' - '+membername);
    });
    $('#recipid').select2();
 </script>
</html>