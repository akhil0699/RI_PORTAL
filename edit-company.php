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

$cid=$_SESSION['cid'];

$ecountry=$_SESSION['country'];
$notification='';
}
else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login'); 
exit;
}

require_once '../config.php';
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
$content='';
$profileres =mysqli_query($link,"SELECT a.* FROM companyprofile a, members WHERE members.memberid='$mid' and a.id=members.companyid"); 
$ispresent =mysqli_num_rows($profileres);
if($ispresent > 0){ 
    while($row =mysqli_fetch_array($profileres)){ 
		$profile=$row;
				}
}
else
$content="No data.";

if(isset($_POST['submit'])){
	if(isset($_FILES['logo'])){
	 $logo=$_FILES['logo']['name'];
			 
			 
		if (!file_exists("logo/".$cid)) {
			mkdir("logo/".$cid,0777,true);
			
			}
		 if (is_uploaded_file($_FILES["logo"]["tmp_name"]) ) {
			move_uploaded_file($_FILES['logo']['tmp_name'], "logo/".$cid."/".$logo);
			mysqli_query($link,"update companyprofile a inner join members b on a.id=b.companyid set a.logo='$logo' where b.memberid='$mid'");
				}
				
	}
	
	mysqli_query($link,"update companyprofile a inner join members b on a.id=b.companyid set a.name='".htmlspecialchars($_POST['name'],ENT_QUOTES)."',a.address1='".htmlspecialchars($_POST['address1'],ENT_QUOTES)."',a.address2='".htmlspecialchars($_POST['address2'],ENT_QUOTES)."',a.address3='".htmlspecialchars($_POST['address3'],ENT_QUOTES)."',a.city='".$_POST['city']."',a.postcode='".$_POST['postcode']."',a.phone='".$_POST['phone']."',a.website='".$_POST['website']."',a.profile='".htmlspecialchars($_POST['profile'],ENT_QUOTES)."',a.updateby='$mid',a.updatedon=now() where b.memberid='$mid'");
	
	$content="Your profile is updated";
}
$comprofile= htmlspecialchars_decode($profile['profile'],ENT_QUOTES);
$compname= htmlspecialchars_decode($profile['name'],ENT_QUOTES);
$address1=htmlspecialchars_decode($profile['address1'],ENT_QUOTES);
$address2=htmlspecialchars_decode($profile['address2'],ENT_QUOTES);
$address3=htmlspecialchars_decode($profile['address3'],ENT_QUOTES);
 ?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Edit Company Profile | Employers Hub</title>
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
<a class="btn btn-primary" href="postdashboard">
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
  <li><a href="postdashboard" role="tab"><i class="fa  fa-fw fa-plus"></i>Post New Job</a></li>
 <!-- <li><a href="requests" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Request from Agencies</a></li>-->

     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Jobs</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="postdashboard" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Post New Job</a>  </li>
   <li><a href="jobs" role="tab" ><i class="fa fa-list"></i>Active Jobs</a> </li>
    <li><a href="inactive" role="tab" ><i class="fa fa-list"></i>Inactive Jobs</a> </li>
    <li><a href="closed" role="tab" ><i class="fa fa-clock-o"></i>Closed Jobs</a>  </li>
    <li><a href="filled" role="tab" > <i class="fa fa-users"></i>Filled Jobs</a>  </li>
  
    </ul>
   </div>
   </li>
     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu1" data-toggle="collapse" aria-controls="candidatesDropdownMenu1" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Candidates (ATS)</a>

   <div class="collapse" id="candidatesDropdownMenu1">
   <ul class="nav-sub" style="display: block;">
   
    <li><a href="all" role="tab" ><i class="fa fa-list"></i>All Candidates</a> </li>
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
    <li><a href="shortlist" role="tab" > <i class="fa fa-users"></i>Shortlisted</a>  </li>
        <li><a href="offer" role="tab" > <i class="fa fa-envelope"></i>Offered</a> </li>
    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i>Rejected</a> </li>
    <li><a href="filledcandidates" role="tab" > <i class="fa fa-users"></i>Filled</a> </li>
    </ul>
   </div>
   </li>
   
    <li><?php 
	   if($ecountry=='India'){echo'
	   <li><a href="rec-rec-india"><i class="fa fa-money"></i> Recruiter-On-Demand <span class="label label-primary">New</span></a></li>';}
	   elseif($ecountry=='United Kingdom (UK)'){echo'
	    <li><a href="rec-rec"><i class="fa fa-money"></i> Recruiter-On-Demand <span class="label label-primary">New</span></a></li>';}
	    elseif($ecountry=='United States Of America (US)'){echo'
	    <li><a href="rec-rec-us"><i class="fa fa-money"></i> Recruiter-On-Demand <span class="label label-primary">New</span></a></li>';}
	    else{echo'
	    <li><a href="rec-rec"><i class="fa fa-money"></i> Recruiter-On-Demand <span class="label label-primary">New</span></a></li>';}
	    
	    echo'
	   ';
	   ?></li>
   
     <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messaging (<?php echo $new; ?>)</a>    </li>
    <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu2" data-toggle="collapse" aria-controls="candidatesDropdownMenu2" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i>Rating/PSL/Block</a>

   <div class="collapse" id="candidatesDropdownMenu2">
   <ul class="nav-sub" style="display: block;">
   
   <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Rating/PSL/Block</a> </li>
   <li><a href="psl-list" role="tab" > <i class="fa fa-list"></i>PSL List</a> </li>
    <li><a href="block-list" role="tab" > <i class="fa fa-list"></i>Block List</a> </li>
    </ul>
   </div>
   </li>
   
   <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu3" data-toggle="collapse" aria-controls="candidatesDropdownMenu3" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i>Company Settings</a>

   <div class="collapse" id="candidatesDropdownMenu3">
   <ul class="nav-sub" style="display: block;">
   
   <li><a href="company-profile" role="tab" > <i class="fa fa-plus"></i>Company Profile</a> </li>
   <li><a href="edit-profile" role="tab" > <i class="fa fa-plus"></i>Your Profile</a> </li>
<li><a href="add-user" role="tab" > <i class="fa fa-plus"></i>Add Users</a> </li>
<li><a href="emanage-notification" role="tab" > <i class="fa fa-plus"></i>Notifications</a> </li>
    </ul>
   </div>
   </li>
   
   <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu4" data-toggle="collapse" aria-controls="candidatesDropdownMenu4" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i>Bench Hiring <span class="label label-primary">Free</span></a>

   <div class="collapse" id="candidatesDropdownMenu4">
   <ul class="nav-sub" style="display: block;">
   
   <li><a href="resources" role="tab" > <i class="fa fa-database"></i>Upload Bench Resources </a> </li>
   <li><a href="view-all-resource-engagement" role="tab" > <i class="fa fa-database"></i>Request for Your Bench Resources </a> </li>  
   <li><a href="view-resources" role="tab" > <i class="fa fa-database"></i>View Your Bench Resources </a> </li>  
   <li><a href="search-resources" role="tab" > <i class="fa fa-database"></i>Search Bench Resources from Others </a> </li>  
     
    </ul>
   </div>
   </li>
    
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
    <h3 class="panel-title">Company Profile</h3>
  </header>
        <div class="panel-body">
<?php if($content) echo $content; else {
 ?>
<form action="edit-company" method="post" enctype="multipart/form-data">
<div class="form-group col-sm-12">
<label class="col-sm-2">Company Name</label>
<div class="col-sm-10">
<input class="form-control" required name="name" value="<?php echo $compname; ?>"  type="text" readonly>
</div></div>

<!-- <div class="form-group col-sm-12">
<label class="col-sm-2">Description</label>
<div class="col-sm-10">
<input class="form-control" name="description" value="<?php echo $profile['description']; ?>" type="text">
</div></div> -->

<!--<div class="form-group col-sm-12">
<label class="col-sm-2">Address line 1</label>
<div class="col-sm-10">
<input class="form-control" required name="address1" value="<?php echo $address1; ?>"  type="text">
</div></div>-->

<!--<div class="form-group col-sm-12">
<label class="col-sm-2">Address line 2</label>
<div class="col-sm-10">
<input class="form-control" name="address2" value="<?php echo $address2; ?>"  type="text">
</div></div>-->

<!--<div class="form-group col-sm-12">
<label class="col-sm-2">Address line 3</label>
<div class="col-sm-10">
<input class="form-control" name="address3" value="<?php echo $address3; ?>"  type="text">
</div></div>-->

<div class="form-group col-sm-12">
<label class="col-sm-2">City</label>
<div class="col-sm-10">
<input class="form-control" required name="city" value="<?php echo $profile['city']; ?>"   type="text">
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-2">PostCode | Zip Code | Pin Code</label>
<div class="col-sm-10">
<input class="form-control" required name="postcode" value="<?php echo $profile['postcode']; ?>" type="text">
</div></div>-->

<div class="form-group col-sm-12">
<label class="col-sm-2">Phone Number</label>
<div class="col-sm-10">
<input class="form-control" required name="phone" value="<?php echo $profile['phone']; ?>" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Website</label>
<div class="col-sm-10">
<input class="form-control" required name="website" value="<?php echo $profile['website']; ?>" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Company Profile</label>
 <div class="col-sm-10">
<textarea class="form-control" name="profile" rows="6"><?php echo $comprofile; ?></textarea>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Company Logo</label>
<div class="col-sm-10">
<input name="logo" type="file"><?php echo '<a href="logo/'.$cid.'/'.$profile['logo'].'" target="_blank"><p>'.$profile['logo'].'</p> </a>'; ?>
</div></div>

<!-- <div class="form-group col-sm-12">
<label class="col-sm-2">Password</label>
<div class="col-sm-10">
<input class="form-control" required="required"  type="password">
<p class="help-block">Leave blank to keep the current password</p>
</div></div> -->


<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<input name="submit" value="Save Changes" class=" btn btn-primary" type="submit">
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



 </body>
</html>
