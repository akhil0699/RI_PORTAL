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
$content='';
require_once '../config.php';
include_once '../Smtp/index.php';
require '../helpers/general.php';

if(isset($_GET['id']))
{

$id = intval($_GET['id']);
$employee_id = intval($_GET['ids']);
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];

//We get the title and the narators of the discussion
$req1 = mysqli_query($link,'select id,subject, fromuser, touser from personalmsg where pmid="'.$id.'"');

$dn1 = mysqli_fetch_array($req1);
//We check if the discussion exists
if(mysqli_num_rows($req1)==1)
{
		$conversation=$dn1['id'];
		//We check if the user have the right to read this discussion
		if($dn1['fromuser']==$mid or $dn1['touser']==$mid)
		{
		//The discussion will be placed in read messages
				if($dn1['fromuser']==$mid)
				{
					mysqli_query($link,'update personalmsg set fromread="1" where pmid="'.$id.'"');
					$user_partic = "from";
					$user_non_partic ="to";
				}
				else
				{
					mysqli_query($link,'update personalmsg set toread="1" where pmid="'.$id.'"');
					$user_partic ="to";
					$user_non_partic ="from";
				}
        
				//We get the list of the messages
        // Added created_at column to get the msg created time
				$req2 = mysqli_query($link,'select pm.pmid,pm.attachment,pm.mtimestamp, pm.message,pm.created_at,members.iam, members.email, members.memberid as userid, CONCAT(members.firstname," ",members.lastname) as username, members.photo from personalmsg pm, members where pm.id="'.$conversation.'" and memberid=pm.fromuser order by pm.conversationid');
				
				//We check if the form has been sent
						if(isset($_POST['message']) and $_POST['message']!='')
						{

              $joi = "SELECT messagenotification FROM members WHERE memberid=".$mid;
              $results = mysqli_query($link,$joi);
              $cnsd_rec = mysqli_fetch_array($results);
              $empsemailnotify = $cnsd_rec["messagenotification"];

              // change
              $join = "SELECT CONCAT(firstname,' ',lastname) as empname,email,companyid FROM members WHERE memberid=".$employee_id;
              $result = mysqli_query($link,$join);
              $cnsd = mysqli_fetch_array($result);
              $empsemail = $cnsd["email"];


   

              $joinT = "SELECT * FROM companyprofile WHERE id=".$cid;
              $resultT = mysqli_query($link,$joinT);
              $company = mysqli_fetch_array($resultT);
          //    var_dump($company);

							$message = $_POST['message'];
							
							 $replacewith = "hiddentext";
    $message = preg_replace("/[\w-]+@([\w-]+\.)+[\w-]+/", $replacewith, $message);
    $message = preg_replace("/^(\(?\d{3}\)?)?[- .]?(\d{3})[- .]?(\d{4})$/", $replacewith, $message);
    $message = preg_replace("/^(\(?\d{4}\)?)?[- .]?(\d{3})[- .]?(\d{4})$/", $replacewith, $message);
    
							//We protect the variables
							$message = mysqli_real_escape_string($link,nl2br(htmlentities($message, ENT_QUOTES, 'UTF-8')));
							//We send the message and we change the status of the discussion to unread for the recipient
              // Added created_at column to show the msg created time
							mysqli_query($link,'insert into personalmsg (id, conversationid, subject, fromuser, touser, message, mtimestamp, fromread, toread, created_at)values("'.$conversation.'", "'.(intval(mysqli_num_rows($req2))+1).'", "", "'.$mid.'", "", "'.$message.'", now(), "", "", now())');
							mysqli_query($link,'update personalmsg set '.$user_partic.'read="1",'.$user_non_partic.'read="0"  where pmid="'.$id.'"');

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
                                                    
                            <p>You have received a message from <strong>'.$name.' ('.$company['name'].')  </strong> <strong>'.$dn2["usedid"].'</strong> as below</p>
                                        
                                        <p> "<strong>'.$message.'</strong>"</p>
                                                     
                                                   
                                                    
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
              if($empsemailnotify == 1){
                   $r = getAccountManagerDetail($link,$employee_id);
              $params = ['from'=>'noreply@recruitinghub.com','name'=>'Recruiting Hub','to'=>$empsemail,'subject'=>$dn1['subject'],'message'=>$message_temlt,'cc'=>$r['email'],'send'=>true];

             AsyncMail($params);
              }

              // change
							$content="<p>Your message has been successfully sent and our software has triggered an automated email to the registered email id of the client.</p>";

							}
				}
			else $content="<p>You dont have the rights to access this page.</p>";	
	}
	else $content="<p>This discussion does not exists.</p>";
}	
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Read Message | Recruiters Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
    <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">  
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
<h4>Vendor Section (Business)</h4>
    <li><a href="dashboard" role="tab" ><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
 <li><a href="becomefranchise" role="tab" > <i class="fa fa-database"></i>Become our Franchise <span class="label label-primary">New</span></a> </li>
    <li><a href="search" role="tab" ><i class="fa fa-fw fa-search"></i>Search Roles</a>    </li>
    <li><a href="job" role="tab"><i class="fa fa-fw fa-files-o"></i>Engaged Roles</a>    </li>
    <li><a href="disengaged" role="tab"><i class="fa fa-scissors"></i>CLOSED/ DISENGAGED</a>  
    <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Supply Candidates (STS)</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="add_new" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add new</a>  </li>
    <li><a href="all" role="tab" ><i class="fa fa-list"></i> All</a> </li>
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
                 <div class="col-md-12">
                     

     <span class="label label-primary">Please don't attempt to share your email id or phone number asking clients to contact you directly. Our AI mechasim triggers such messages to us and your account may be restricted.</span>
     <span class="label label-primary">Want to send attachment? </span> <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Click SUPPLY CANDIDATES (STS) -> ALL CANDIDATES -> Against the Candidate click Action -> Edit Profile -> Scroll to the bottom and you will find Upload Additional file -> Save Changes and let the client know here via MESSAGING so they can download the file from the Candidate profile."><i class="fa fa-question-circle"></i>
    </span>
              </div>
            </div>
          </div>
        </div>
        
        
<h2>Reply</h2>
<div class="center">
    <form action="read_pm.php?id=<?php echo $id;?>&ids=<?php echo $employee_id; ?>" method="post">
    	<label for="message" class="center"></label><br />
        <textarea class="form-control" cols="40" rows="3" required name="message" id="message"></textarea><br />
        <input type="submit" value="Send" class="btn-lg btn-default"/><br><br>
        <a class="btn btn-primary" href="msg"><i class="fa fa-step-backward"></i> Go back to Messages
</a>
    </form>
<br>   

<!-- change -->
 <div class="row">
 <div class="col-xs-12">
      <section class="panel panel-default">
        <header class="panel-heading panel-heading--buttons wht-bg">
          <h4 class="gen-case"><?php echo $dn1['subject']; ?></h4>
        </header>
        <div class="panel-body minimal">
          <div class="table-responsive ">
          <?php if($content) echo $content; else{ ?>
<table class="table table-inbox table-hover" id="table_id2" style="border: 1px solid #e4d9d9;">
<thead>
    <tr>
        <th>ID</th>
        <th>Sender</th>
        <th>Message</th>
        <th>Company Name</th> <!-- change -->
        <th>Attachment</th>
    </tr>
</thead>
<tbody> 
<?php
$df = $_SERVER['REQUEST_SCHEME'];
$url = $_SERVER['REQUEST_URI']; //returns the current URL
$parts = explode('/',$url);
$dir = $_SERVER['SERVER_NAME'];
for ($i = 0; $i < count($parts) - 2; $i++) {
 $dir .= $parts[$i] . "/";
}
while($dn2 = mysqli_fetch_array($req2))
{
$agenydetails1 = "SELECT members.memberid, companyprofile.name as agencyname
FROM members
LEFT JOIN companyprofile ON members.companyid = companyprofile.id WHERE members.memberid=".$dn2['userid'];
$result = mysqli_query($link,$agenydetails1);
$cnsd = mysqli_fetch_array($result);

   /* $agenydetails = mysql_query("SELECT id, name as agencyname FROM companyprofile WHERE id=".$cid);
    $cnsd = mysql_fetch_array($agenydetails);*/
?>
<!-- change -->
	<tr>
    <td><?php echo $dn2['pmid']; ?></td>
    	<td class="author center"><?php
if($dn2['photo']!='')
{
	
  if($dn2['iam'] == 'Recruiter' ){
     echo '<img src="'.$df.'://'.$dir.'recruiter/photo/'.$dn2['userid'].'/'.$dn2['photo'].'" alt="Image Perso" style="max-width:100px;max-height:100px;" />';
  }
  if($dn2['iam'] == 'Employer'){
     echo '<img src="'.$df.'://'.$dir.'employer/photo/'.$dn2['userid'].'/'.$dn2['photo'].'" alt="Image Perso" style="max-width:100px;max-height:100px;" />';
  }
}
?>
<br/>
<span><?php echo $dn2['username']; ?></span>
</td>
     <!--  <td class="left"><div class="date">Sent: <?php echo date('d/m/Y H:i:s' ,strtotime($dn2['mtimestamp'])); ?></div> -->
      <!-- Displayed created date -->
    	<td class="left"><div class="date">Sent: <?php echo date('d/m/Y H:i:s' ,strtotime($dn2['created_at'])); ?></div>

    	<b><?php echo $dn2['message']; ?></b></td>
      <td><?php echo $cnsd['agencyname']; ?></td>
        <td><?php if($dn2['attachment']) echo '<a href="../pm/'.$dn2['pmid'].'/'.$dn2['attachment'].'" target="_blank">'.$dn2['attachment'].'</a>'; ?></td>
      </tr>
<?php
}
//We display the reply form
?>

</tbody>
</table><br />
<h2>Reply</h2>
<div class="center">
    <form action="read_pm.php?id=<?php echo $id;?>&ids=<?php echo $employee_id; ?>" method="post">
    	<label for="message" class="center"></label><br />
        <textarea class="form-control" cols="40" rows="3" required name="message" id="message"></textarea><br />
        <input type="submit" value="Send" class="btn-lg btn-default"/>
    </form>
</div>
<?php } ?>
</div>
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

  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  
  <script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
  $(document).ready( function () {
    // $('#myTable').DataTable();
     var table = $('#table_id2').DataTable({
      "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": true,
                "searchable": false
            }
        ]
    });
  
    table
    .order( [[ 0, 'desc' ]] )
    .draw();
    
} );
</script>

 </body>
</html>
