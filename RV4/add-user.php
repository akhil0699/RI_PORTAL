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
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
$content='';

if(isset($_POST['submit'])){
		
	if(isset($_POST['admin'])){
		$admin=0;
		$adminrights=$_POST['admin'];
		}
	else{
		$admin=0;
		$adminrights=0;
		}
	$companyidres=mysqli_fetch_assoc(mysqli_query($link,"select companyid from members where memberid='$mid'"));
	$companyid=$companyidres['companyid'];
	$fetchemail=mysqli_fetch_assoc(mysqli_query($link,"select email from members where memberid='".$mid."'"));
	$check2=mysqli_num_rows(mysqli_query($link,"select * from members where email='".$_POST['email']."'"));
		if($check2)
		$content="This email id already exists in our database. Ask them to login to their account. Click Forgot Password link to reset password if unable to login. Go back and use a different email id to create another user.";
		else{
	mysqli_query($link,"insert into members(email,companyid,iam,firstname,lastname,mobile,emailactivated,designation,linkedin,biography,admin,active,adminrights,accountmanager,password,signupdate) values('".$_POST['email']."','$companyid','$iam','".$_POST['fname']."','".$_POST['lname']."','".$_POST['mobile']."','1','".$_POST['jobtitle']."','".$_POST['linkedin']."','".$_POST['additionalinfo']."','$admin',1,'$adminrights','$email','hub123',now())");
	if(mysqli_affected_rows($link)>0){
	
	$nuid=mysqli_insert_id($link);
	
	if(isset($_FILES['photo'])){
	 $photo=$_FILES['photo']['name'];
			 
			 
		if (!file_exists("photo/".$nuid)) {
			mkdir("photo/".$nuid,0777,true);
			}
		 if (is_uploaded_file($_FILES["photo"]["tmp_name"]) ) {
			move_uploaded_file($_FILES['photo']['tmp_name'], "photo/".$nuid."/".$photo);
			mysqli_query($link,"update members set photo='$photo' where memberid='$nuid'");
				}
				
	}
$content="New user <b>".$_POST['fname']."</b> has been activated. Please ask this user to check their email inbox including spam folder for login details. Your email id would also have received a copy of the login credentials, please check your email. Click here to go to Manager Users section -> <a href=https://www.recruitinghub.com/recruiter/manage-users><b>Manage Users</b></a>";
$from='noreply@recruitinghub.com';
				$to=$_POST['email'];
				$subject="RecruitingHub.com - Your Agency (Sub-User) Account has been Activated";
				
				$message = '<html>
				<body bgcolor="#FFFFFF">
				<div>
		<table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000" align="center" bgcolor="#792785" border="0" cellpadding="0" cellspacing="0" width="620">
		  <tbody><tr>
			<td style="padding:12px 12px 0px 12px"><table style="border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
			  <tbody>
			  <tr><td colspan="3" style="padding-left:14px"><div style="boder:0"><a href="https://www.recruitinghub.com/"><img src="https://www.recruitinghub.com/image/color-logo.png" alt="www.recruitinghub.com"></a></div></td>
					  </tr>
			  <tr>
				<td style="padding:0px 14px"><table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tbody><tr>
					<td><table border="0" cellpadding="0" cellspacing="0" width="100%">
					  <tbody><tr>
						<td valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tbody><tr>
							<td style="padding-top:5px;color:#313131"><br>
							  Dear ' . $_POST['fname']. ',<br>
							  
							  <p>Recruitment Agency Login here -> <a href="https://www.recruitinghub.com/recruiter/login" target="_blank">Recruiter Login</a></p>
							
							<p><b>'.$name.'</b> has added you to use our Online recruitment marketplace to increase revenues for your recruitment agency account by supplying quality candidates. You can login using the following credentials now and change the password.</p> 
							<p>Email: <b>'.$_POST['email'].'</b><br/>Password: <b>hub123</b></p>
							
							<p> You can now login to your agency account and access 100s of Pre-qualified vacancies posted by our clients. Our AI mechanism will trigger jobs based on your specialist sector, so dont forget to add as many sector as possible when you login to your account!</p>

                            <p> Advantages of using Recruiting Hub,<p/>

                            <p> *Access to top companies</p>
                            <p> *No subscription cost | Split fee on success - Visit our FAQ for more info</p>
                            <p> *Supply Candidates & increase billings, invoice us and get paid</p>
                            <p> *Exclusive access to our Jobsite - Post Jobs & Search CVs for free</p>
                            <p> *Prove your service quality and get feedback ratings from Clients</p>
                            <p> *Rec-Rec bench hiring, source bench resources for free</p>
                            <p> *Finding clients and marketing your agency is no longer a challenge when you have an account with RecruitingHub.com</p>

                            <p>Your success is our success! Good luck with your Business</p>
							
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
				
				$headers = "From: $from\r\n";
				$headers .= "Content-type: text/html\r\n";
			//	mail($to, $subject, $message, $headers);
				
						$params = ['from'=>'noreply@recruitinghub.com','name'=>'Recruiting Hub','to'=>$to,'cc'=>$fetchemail['email'],'subject'=>$subject,'message'=>$message,'send'=>true];
                AsyncMail($params);
                
                //header('location:manage-users');
		//	exit;
			}
			else
			{
			$content="Sorry,unable to add user, contact our team using live chat widget available at bottom right corner of page";
			}
}
}

 ?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Add User | Recruiters Hub</title>
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
    <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messaging (<?php echo $new; ?>) </a>    </li>
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
        

  <div class="row">
    <div class="col-sm-12">
         <div class="panel panel-default">
       <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title">Got more Recruiters? Add New User - We will send automated email to below user with login credentials</h3>
  </header>
        <div class="panel-body">
<?php if($content) echo $content; else { ?>
<form action="add-user" method="post" enctype="multipart/form-data">
<div class="form-group col-sm-12">
<label class="col-sm-2">Email (*)</label>
<div class="col-sm-10">
<input class="form-control" required placeholder="type email id of your recruiter and they will receive automated email from Recruiting Hub with login details to sign in" name="email" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Full name (*)</label>
<div class="col-sm-10">
<input class="form-control" required name="fname" type="text">
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-2">Last name (*)</label>
<div class="col-sm-10">
<input class="form-control" required name="lname" type="text">
</div></div>-->

<div class="form-group col-sm-12">
<label class="col-sm-2">Job title (Designation) (*)</label>
<div class="col-sm-10">
<input class="form-control" placeholder="e.g Recruiter" name="jobtitle" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Mobile number (*)</label>
<div class="col-sm-10">
<input class="form-control" required name="mobile" type="text">
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-2">Linkedin url</label>
<div class="col-sm-10">
<input class="form-control" name="linkedin" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Twitter url</label>
<div class="col-sm-10">
<input class="form-control"  name="twitter" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Biography</label>
 <div class="col-sm-10">
<textarea class="form-control" name="additionalinfo" rows="6"></textarea>
</div></div>-->

<div class="form-group col-sm-8">
<label class="col-sm-3">Admin</label>
 <div class="col-sm-7">
<input value="1" name="admin" type="checkbox" />
<p class="help-block"> Tick this box if you want this user to be an admin. They will have the ability to view all engaged jobs, all submitted candidates by all users of your agency account. Also be able to create, edit & delete users.</p>
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-2">Photo</label>
<div class="col-sm-10">
<input name="photo" type="file">

</div></div>-->


<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<input name="submit" value="Create User" class=" btn btn-primary" type="submit">
</div></div>
</form>
<?php } ?>
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

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>


 </body>
</html>
