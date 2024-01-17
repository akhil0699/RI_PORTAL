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
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
$content='';
$res=mysqli_query($link,"select sector,id from jobsectors");
if(mysqli_num_rows($res)){
	while($row=mysqli_fetch_assoc($res)){
		$sector[]=$row;
	}
}
$profileres = mysqli_query($link,"SELECT a.*,b.* FROM companyprofile a, members b WHERE b.memberid='$mid' and a.id=b.companyid"); 

$ispresent = mysqli_num_rows($profileres);

if($ispresent > 0){ 

    while($row = mysqli_fetch_array($profileres)){ 

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

	$ssector=$_POST['ssector'];
if (isset($_POST['ssector'])){
$ssector=implode(',',$_POST['ssector']);}
		
	mysqli_query($link,"update companyprofile a inner join members b on a.id=b.companyid set a.name='".htmlspecialchars($_POST['name'],ENT_QUOTES)."',a.sectors='$ssector',a.address1='".htmlspecialchars($_POST['address1'],ENT_QUOTES)."',a.address2='".htmlspecialchars($_POST['address2'],ENT_QUOTES)."',a.address3='".htmlspecialchars($_POST['address3'],ENT_QUOTES)."',a.city='".$_POST['city']."',a.postcode='".$_POST['postcode']."',a.phone='".$_POST['phone']."',a.benname='".$_POST['benname']."',a.benbank='".$_POST['benbank']."',a.bensort='".$_POST['bensort']."',a.benacc='".$_POST['benacc']."',a.benswift='".$_POST['benswift']."',a.taxinfo='".$_POST['taxinfo']."',a.website='".$_POST['website']."',a.profile='".htmlspecialchars($_POST['profile'],ENT_QUOTES)."',a.updateby='$mid',a.updatedon=now() where b.memberid='$mid'");

	$content="Your agency profile has been successfully updated. Click here to go to Agency Profile -> <a href=https://www.recruitinghub.com/recruiter/company-profile><b>Agency Profile</b></a>";
}
$comprofile= htmlspecialchars_decode($profile['profile'],ENT_QUOTES);
$compname= htmlspecialchars_decode($profile['name'],ENT_QUOTES);
$address1=htmlspecialchars_decode($profile['address1'],ENT_QUOTES);
$address2=htmlspecialchars_decode($profile['address2'],ENT_QUOTES);
$address3=htmlspecialchars_decode($profile['address3'],ENT_QUOTES);
$city=htmlspecialchars_decode($profile['city'],ENT_QUOTES);
 ?>

<!doctype html>

<html>

<head>

     <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

     <title>Edit Agency | Recruiters Hub</title>

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

    <ul class="dropdown-menu" role="menu"><li><a href="company-profile"><i class="fa fa-user"></i>Agency Profile</a>   </li>

      <li><a href="edit-profile"><i class="fa fa-gears"></i>Your Account</a></li>

       <?php if($admin){
       echo'<li><a href="manage-users"><i class="fa fa-users"></i>Manage Users</a></li>';
	 /**  if($rcountry=='India'){echo'
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

    <h3 class="panel-title">Update Agency Profile  </h3>

  </header>

        <div class="panel-body">

<?php if($content) echo $content; else { ?>

<form action="edit-company" method="post" enctype="multipart/form-data">

<div class="form-group col-sm-12">

<label class="col-sm-2">Agency Name </label>

<div class="col-sm-10">

<input class="form-control" required name="name" value="<?php echo $compname; ?>"  type="text" readonly> <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="You cannot edit the Agency Name. If you like to change your Agency Name please use the live chat to talk to us"><i class="fa fa-question-circle"></i> </span>

</div></div>

<div class="col-sm-12 form-group">
<div class="location-fliter">
<label class="col-sm-2">Specialist Sector (Choose as many sectors as possible so our AI mechanism can trigger relevant jobs to you)</label>
<div class="city-checkbox" id="location-filter">
<ul>
<?php 
$exp=explode(',',$profile['sectors']);
foreach($sector as $s){
if((in_array($s['sector'], $exp)||in_array($s['id'], $exp)))  {
echo'
<li style="list-style:none">
<div class="checkbox"><label><input name="ssector[]" value="'.$s['id'].'" type="checkbox" checked>'.$s['sector'].'</label></div>
</li>';
}else{
echo'
<li style="list-style:none">
<div class="checkbox"><label><input name="ssector[]" value="'.$s['id'].'" type="checkbox">'.$s['sector'].'</label></div>
</li>';
 }}?>
</ul>
</div>
</div>
</div>

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

<!-- <div class="form-group col-sm-12">

<label class="col-sm-2">Address line 3</label>

<div class="col-sm-10">

<input class="form-control" name="address3" value="<?php echo $address3; ?>"  type="text">

</div></div> -->


<div class="form-group col-sm-12">

<label class="col-sm-2">City</label>

<div class="col-sm-10">

<input class="form-control" required name="city" value="<?php echo $profile['city']; ?>"  type="text">

</div></div>



<!--<div class="form-group col-sm-12">

<label class="col-sm-2">Postcode | Zip Code | Pin Code</label>

<div class="col-sm-10">

<input class="form-control" required name="postcode" value="<?php echo $profile['postcode']; ?>" type="text">

</div></div>-->



<div class="form-group col-sm-12">

<label class="col-sm-2">Phone number</label>

<div class="col-sm-10">

<input class="form-control" required name="phone" value="<?php echo $profile['phone']; ?>" type="text">

</div></div>



<div class="form-group col-sm-12">

<label class="col-sm-2">Website</label>

<div class="col-sm-10">

<input class="form-control" required name="website" value="<?php echo $profile['website']; ?>" type="text">

</div></div>



<div class="form-group col-sm-12">

<label class="col-sm-2">Agency Profile</label>

 <div class="col-sm-10">

<textarea class="form-control" name="profile" rows="6"><?php echo $comprofile; ?></textarea>

</div></div>



<div class="form-group col-sm-12">

<label class="col-sm-2">Agency Logo</label>

<div class="col-sm-10">

<input name="logo" type="file"><?php echo '<a href="logo/'.$cid.'/'.$profile['logo'].'" target="_blank"><p>'.$profile['logo'].'</p> </a>'; ?>

</div></div>

<div class="form-group col-sm-12">

<label class="col-sm-2">Beneficiary Name</label>

<div class="col-sm-10">

<input class="form-control" name="benname" placeholder="e.g ABC Consultants Ltd" value="<?php echo $profile['benname']; ?>" type="text">

</div></div>

<div class="form-group col-sm-12">

<label class="col-sm-2">Bank Name</label>

<div class="col-sm-10">

<input class="form-control" name="benbank" placeholder="e.g Barclays Bank" value="<?php echo $profile['benbank']; ?>" type="text">

</div></div>

<div class="form-group col-sm-12">

<label class="col-sm-2">A/C No</label>

<div class="col-sm-10">

<input class="form-control" name="benacc" placeholder="e.g 123456789" value="<?php echo $profile['benacc']; ?>" type="text">

</div></div>

<div class="form-group col-sm-12">

<label class="col-sm-2">Sort Code / IFSC / ABA</label>

<div class="col-sm-10">

<input class="form-control" name="bensort" placeholder="e.g 20-34-75 or ICIC000123 or 129131673" value="<?php echo $profile['bensort']; ?>" type="text">

</div></div>

<div class="form-group col-sm-12">

<label class="col-sm-2">SWIFT Code / BIC</label>

<div class="col-sm-10">

<input class="form-control" name="benswift" placeholder="e.g BUKBGB22" value="<?php echo $profile['benswift']; ?>" type="text">

</div></div>

<div class="form-group col-sm-12">

<label class="col-sm-2">Tax Info</label>

<div class="col-sm-10">

<input class="form-control" name="taxinfo" placeholder="e.g VAT no or GST no or Sales Tax no or type NA if not registered for VAT or GST or Sales Tax" value="<?php echo $profile['taxinfo']; ?>" type="text">

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

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>

</html>

