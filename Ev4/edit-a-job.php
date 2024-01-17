<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid='';
$name='';
$email='';
$iam='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$cid=$_SESSION['cid'];
$ecountry=$_SESSION['country'];
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

//fetch sector values to an array
$res=mysqli_query($link,"select sector from jobsectors");
if(mysqli_num_rows($res)){
	while($row=mysqli_fetch_assoc($res)){
		$sector[]=$row;
	}
}

$job=array();
if(isset($_GET['id'])){
	$jobid= $_GET['id'];
	$jobres=mysqli_query($link,"select * from jobs where jobid='$jobid'");
	if(mysqli_num_rows($jobres)){
			$job=mysqli_fetch_assoc($jobres);
		}
}
if(isset($_POST['submit'])){
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
	  $data = str_replace("`","",$data);
	  return $data;
	}
	$jobid=$_POST['jobid'];
	$jobtitle=test_input($_POST['jobtitle']);
	$novacancies=test_input($_POST['novacancies']);
	$jobsector=test_input($_POST['jobsector']);
	$joblocation=test_input($_POST['joblocation']);
	$vacancyreason=test_input($_POST['vacancyreason']);
	$country=test_input($_POST['country']);
	$jobtype=test_input($_POST['jobtype']);
	$ir35=test_input($_POST['ir35']);
	$working=test_input($_POST['working']);
	$priority=test_input($_POST['priority']);
	$minex=$_POST['minex'];
	$maxex=$_POST['maxex'];
/**	if(($minex=='0') && ($maxex=='2')){ 
$fee=$_POST['rfee'];
}
else{
$fee=$_POST['fee'];
}**/
if (isset($_POST['fee'])){
$fee=$_POST['fee'];
}elseif(isset($_POST['rfee'])){
$rfee=$_POST['rfee'];
}
else{
$fee='';
}

if(($_POST['country']=='India')&&($_POST['jobtype']=='Contract')){
$currency=$_POST['indiacurrency']; 
$minlakh=$_POST['from'];
$minthousand='';
$maxlakh=$_POST['to'];
$maxthousand='';
}
elseif($_POST['country']=='India'){
$currency=$_POST['indiacurrency'];
$minlakh=$_POST['minlakh'];
$minthousand=$_POST['minthousand'];
$maxlakh=$_POST['maxlakh'];
$maxthousand=$_POST['maxthousand'];
}

else{
$currency=$_POST['othercurrency'];
$minlakh=$_POST['from'];
$minthousand='';
$maxlakh=$_POST['to'];
$maxthousand='';
}
$jobres=mysqli_query($link,"select * from jobs where jobid='$jobid'");
if(mysqli_num_rows($jobres)){
		$job=mysqli_fetch_assoc($jobres);
	}
	$degree=$_POST['degree'];
	$description=test_input($_POST['description']);
	if(isset($_FILES['description1'])){
	 $description1=preg_replace('/[^A-Za-z0-9 _ .-]/', '', $_FILES['description1']['name']);
	 }
else
	$description1=$job['description1'];
	$keyskills=test_input($_POST['keyskills']);
	
	if (isset($_POST['considerrelocation']))
	$considerrelocation=$_POST['considerrelocation'];
	else
	$considerrelocation=0;
	
	$feedback=$_POST['feedback'];
	$closingdate=$_POST['closingdate'];
	if (isset($_POST['interviewstages']))
	$interviewstages=$_POST['interviewstages'];
	else
	$interviewstages='';
	$interviewcomments=test_input($_POST['interviewcomments']);
	$cvlimit=$_POST['cvlimit'];
	//$fee=$_POST['fee'];
	$notice=$_POST['notice'];
	if (isset($_POST['alimit'])){
$alimit=$_POST['alimit'];
}else{
$alimit='';
}
	
    if ($_FILES["description1"]["tmp_name"] == ''){
	$description1=$job['description1'];
	}

	$q=mysqli_query($link,"Update jobs set        memberid='$mid',jobtitle='$jobtitle',novacancies='$novacancies',country='$country',jobsector='$jobsector',joblocation='$joblocation',vacancyreason='$vacancyreason',jobtype='$jobtype',working='$working',minex='$minex',maxex='$maxex',minlakh='$minlakh',minthousand='$minthousand',maxlakh='$maxlakh',maxthousand='$maxthousand',currency='$currency',description='$description',description1='$description1',keyskills='$keyskills',degree='$degree',ir35='$ir35',considerrelocation='$considerrelocation',feedback='$feedback',closingdate='$closingdate',priority='$priority',interviewstages='$interviewstages',interviewcomments='$interviewcomments',cvlimit='$cvlimit',fee='$fee',rfee='$rfee',updatedby='$mid',agencylimit='$alimit',notice='$notice',updatedate=now() where jobid='$jobid'")or die("Error - Please remove (') character from Education section. E.g Bachelor's - Please remove (')"); 
	
if (is_uploaded_file($_FILES["description1"]["tmp_name"]) ) {
			move_uploaded_file($_FILES['description1']['tmp_name'], "jobdescription/".$mid."/".$jobid."/".$description1);
				}

$content="Updated changes successfully.";	

}
 ?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Edit Job | Employers Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
     <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />s
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
    <link rel="stylesheet" href="css/datepicker.css">
 	<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.4.custom.min.css"/>   
</head>
<body>

<div class="header-inner">
<div class="header-top">
<div class="logo">
<a class="navbar-brand" href="#"><img src="images/color-logo.png"></a>
<a class="btn btn-primary" href="submit-a-job">
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
<!--  <li><a href="requests" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Request from Agencies</a></li>-->

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
	   else{echo'
	    <li><a href="rec-rec"><i class="fa fa-money"></i> Recruiter-On-Demand <span class="label label-primary">New</span></a></li>';
		}echo'
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
    <h3 class="panel-title"> You are Editing <?php echo $job['jobtitle']; ?> Job </h3>
  </header>
        <div class="panel-body">
<?php  if(!$content) { if($job) {?>
<form method="post" action="edit-a-job" enctype="multipart/form-data" id="jobpost">
<input type="hidden" name="jobid" value="<?php echo $job['jobid']; ?>" />
<div class="form-group col-sm-12">
<label class="col-sm-4">Job Title</label>
<div class="col-sm-8">
<input class="form-control" required  name="jobtitle" type="text" value="<?php echo $job['jobtitle']; ?>">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">No of Vacancies</label>
<div class="col-sm-8">
<select class="form-control" required="required"  name="novacancies">
<?php 
for($i=1;$i<=100;$i++){
	if($job['novacancies']== $i)
	echo '<option value="'.$i.'" selected>'.$i.'</option>';
	else
	echo '<option value="'.$i.'">'.$i.'</option>';
}
?>
</select>
</div></div>


<div class="form-group col-sm-12">
<label class="col-sm-4">Job Sector</label>
<div class="col-sm-8">
<select required="required" name="jobsector" class="form-control">
 <?php foreach($sector as $s){ 
 	if(trim($s['sector'])== trim($job['jobsector'])) {
 ?>
 <option value="<?php echo $s['sector']; ?>" selected><?php echo $s['sector']; ?></option>
 <?php } else {?>
 <option value="<?php echo $s['sector']; ?>"><?php echo $s['sector']; ?></option>
 <?php } } ?>
</select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Country</label>
<div class="col-sm-8">
<select class="form-control" name="country" id="country">
<?php  $countryarray=array('India','US','UAE','UK','Europe','Africa','Malaysia','Singapore','Middle East','China','Thailand','Indonesia','Australia','Philippines','Hong Kong','Japan','New Zealand','North Korea','South Korea','Russia','Canada','Mexico','South America','Vietnam');
?>
<?php
foreach($countryarray as $c){
if($job['country']==$c){
echo '<option value="'.$c.'" selected>'.$c.'</option>';
}
else{
echo '<option value="'.$c.'">'.$c.'</option>';
}
}
?>
</select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Location</label>
<div class="col-sm-8">
<input class="form-control" value="<?php echo $job['joblocation']; ?>" name="joblocation" id="joblocation">
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4">Vacancy reason</label>
<div class="col-sm-8">
<select required="required" aria-required="true" name="vacancyreason" class="form-control">

<option <?php if($job['vacancyreason']== "Growth/Expansion") echo "selected";?> value="Growth/Expansion">Growth/Expansion</option>
<option <?php if($job['vacancyreason']== "Replacement") echo "selected";?> value="Replacement">Replacement</option>
<option <?php if($job['vacancyreason']== "Internal Restructure") echo "selected";?> value="Internal Restructure">Internal Restructure</option>
<option <?php if($job['vacancyreason']== "Parental Leave") echo "selected";?> value="Parental Leave">Parental Leave</option></select>
</div></div>-->

<div class="form-group col-sm-12">
<label class="col-sm-4">Job type</label>
<div class="col-sm-8">
<select class="form-control" name="jobtype" id="jtype">
<option <?php if($job['jobtype']== "Permanent") echo "selected";?> value="Permanent">Permanent</option>

<option <?php if($job['jobtype']== "Contract") echo "selected";?>  value="Contract">Contract</option></select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Working</label>
<div class="col-sm-8">
<select class="form-control" name="working" id="working">
<option <?php if($job['working']== "Always Remote") echo "selected";?> value="Always Remote">Always Remote</option>

<option <?php if($job['working']== "Hybrid (Office + Home)") echo "selected";?> value="Hybrid (Office + Home)">Hybrid (Office + Home)</option>

<option <?php if($job['working']== "Remote until Covid") echo "selected";?>  value="Remote until Covid">Remote until Covid</option>

<option <?php if($job['working']== "Office only") echo "selected";?>  value="Office only">Office only</option></select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4"> Notice Period (in days)</label>
<div class="col-sm-8">
<input class="numeric integer required form-control" required value="<?php echo $job['notice']; ?>" name="notice" type="text">
</div>
</div>

<div class="form-group col-sm-12" id="exp">
<label class="col-sm-4"> Total Experience</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<input class="numeric integer required form-control" required value="<?php echo $job['minex']; ?>" name="minex" type="number">
</div>
<div class="col-sm-3">
<input class="numeric integer required form-control" required value="<?php echo $job['maxex']; ?>" name="maxex" type="number">
</div>
<div class="col-sm-2 no-padding">
In Years
</div>

</div></div>

<div class="form-group col-sm-12" id="indiacurrency" >
<label class="col-sm-4">Currency</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<select class="form-control" name="indiacurrency" type="text">
<?php if($job['currency']=='INR')
echo'
<option value="INR" selected>INR (Indian Rupee)</option>';
else
echo'<option value="INR">INR (Indian Rupee)</option>';
?>
</select>
</div>
</div></div>

<div class="form-group col-sm-12" id="othercurrency">
<label class="col-sm-4">Currency</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<select class="form-control" name="othercurrency"  type="text">
<?php 
$currencyarray=array('INR','AED','GBP','EURO','USD','SAR','QAR','OMR','BHD','KWD','CAD','AUD','JPY','MYR','SGD','IDR','CNY','KRW','RUB','NZD','HKD','THB','PHP','MAD','EGP','SEK','CHF','ZAR','ETB','PLN','BGN','VND','MXN','BRL'); 
?>
<?php 
foreach ($currencyarray as $cu){
if($job['currency']==$cu){
echo'<option value="'.$cu.'" selected>'.$cu.'</option>';
}
else{
echo'<option value="'.$cu.'">'.$cu.'</option>';
}
}
?>
</select>
</div>
</div></div>

<div class="form-group col-sm-12" id="othercurrency1">
<label class="col-sm-4">Currency</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<select class="form-control" name="othercurrency1"  type="text" id="oc">
<?php 
$currencyarray=array('INR','AED','GBP','EURO','USD','SAR','QAR','OMR','BHD','KWD','CAD','AUD','JPY','MYR','SGD','IDR','CNY','KRW','RUB','NZD','HKD','THB','PHP','MAD','EGP','SEK','CHF','ZAR','ETB','PLN','BGN','VND','MXN','BRL'); 
?>
<?php 
foreach ($currencyarray as $cu){
if($job['currency']==$cu){
echo'<option value="'.$cu.'" selected>'.$cu.'</option>';
}
else{
echo'<option value="'.$cu.'">'.$cu.'</option>';
}
}
?>
?>


</select>
</div>
</div></div>

<div class="form-group col-sm-12" id="one">
<label class="col-sm-4" id="onelabel"> Annual Salary Range </label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<select  name="minlakh" id="minAmount" class="form-control" >
<option value="">Lakhs</option>
<?php 
for($i=1;$i<51;$i++){
	if($job['minlakh']== $i)
	echo '<option value="'.$i.'" selected>'.$i.'</option>';
	else
	echo '<option value="'.$i.'">'.$i.'</option>';
}
?>
</select>
</div>
<div class="col-sm-3">
<select  name="minthousand"  class="form-control" >
<option value="0">Thousands</option>
<?php 
for($i=10;$i<100;$i+=10){
	if($job['minthousand']== $i)
	echo '<option value="'.$i.'" selected>'.$i.'</option>';
	else
	echo '<option value="'.$i.'">'.$i.'</option>';
}
?>
</select>
</div>
<div class="col-sm-1">
To
</div>
<div class="col-sm-2">
<select  name="maxlakh"  id="maxAmount" class="form-control" >
<option value="">Lakhs</option>
<?php 
for($i=1;$i<51;$i++){
		if($job['maxlakh']== $i)
	echo '<option value="'.$i.'" selected>'.$i.'</option>';
	else
	echo '<option value="'.$i.'">'.$i.'</option>';
}
?>
</select>
</div>
<div class="col-sm-3 no-padding-right">
<select  name="maxthousand"  class="form-control" >
<option value="0">Thousands</option>
<?php 
for($i=10;$i<100;$i+=10){
	if($job['maxthousand']== $i)
	echo '<option value="'.$i.'" selected>'.$i.'</option>';
	else
	echo '<option value="'.$i.'">'.$i.'</option>';
}
?>
</select>
</div>

</div></div>

<div class="form-group col-sm-12" id="two">
<label class="col-sm-4" id="dayrate"> Annual Salary Range</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<input class="numeric integer required form-control"   name="from" value="<?php echo $job['minlakh']; ?>"  type="text">
</div>
<div class="col-sm-3">
<input class="numeric integer required form-control"   name="to" value="<?php echo $job['maxlakh']; ?>"  type="text">
</div>
</div></div>

<div class="form-group col-sm-12" id="limit">
<label class="col-sm-4">No of Agencies Limit</label>
<div class="col-sm-8">
<select class="form-control" required="required" name="alimit">
<?php for($l=5;$l<=50;$l+=5){
if($job['agencylimit']==$l)
	echo '<option value="'.$l.'" selected>'.$l.'</option>';
	else
	echo '<option value="'.$l.'">'.$l.'</option>';
}
?></select>
</div></div>

 <div class="form-group col-sm-12">  
<label class="col-sm-4">Job Description</label>
<div class="col-sm-8">
<textarea class="form-control" required rows="6" name="description"><?php echo $job['description']; ?></textarea>
</div></div>

<div class="form-group col-sm-12">  
<label class="col-sm-4">Job Description (Upload as file)</label>
<div class="col-sm-8">
<input type="file" class="form-control"  name="description1"><?php echo '<a href="jobdescription/'.$mid.'/'.$jobid.'/'.$job['description1'].'" target="_blank"><p>'.$job['description1'].'</p> </a>'; ?> 
</div></div>


<div class="form-group col-sm-12">
<label class="col-sm-4">Key skills</label>
 <div class="col-sm-8">
<textarea class="form-control" aria-required="true" name="keyskills"><?php echo $job['keyskills']; ?></textarea>
<p class="help-block">E.g Java, Autocad, Piping Engineer, Investment banking etc. To aid recruiter's search</p>
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4">Education</label>
 <div class="col-sm-8">
 <textarea class="form-control" aria-required="true" name="degree"><?php echo $job['degree']; ?></textarea>
</div></div>-->

<div class="form-group col-sm-12">
<label class="col-sm-4">IR35</label>
<div class="col-sm-8">
<select class="form-control" name="ir35" id="ir35">
<option <?php if($job['ir35']== "Not Applicable") echo "selected";?> value="Not Applicable">Not Applicable</option>
<option <?php if($job['ir35']== "Inside IR35") echo "selected";?>  value="Inside IR35">Inside IR35</option>
<option <?php if($job['ir35']== "Outside IR35") echo "selected";?>  value="Outside IR35">Outside IR35</option></select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Consider relocation?</label>
 <div class="col-sm-8">
<input value="1" name="considerrelocation" type="checkbox" <?php if($job['considerrelocation']) echo "Checked"; ?> />
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4"> Feedback timescale</label>
 <div class="col-sm-8">
<select required="required" name="feedback" class="form-control">
<option value=""></option>
<option value="24-48 hours" <?php if($job['feedback']=="24-48 hours") echo "Selected"; ?> >24-48 hours</option>
<option value="48-72 hours" <?php if($job['feedback']=="48-72 hours") echo "Selected"; ?> >48-72 hours</option>
<option value="1 week" <?php if($job['feedback']=="1 week") echo "Selected"; ?> >1 week</option></select>
</div></div>-->

<!--<div class="form-group col-sm-12">
<label class="col-sm-4"> CV submission closing?</label>
 <div class="col-sm-8">
        <input class="form-control datepicker" name="closingdate" value="<?php echo $job['closingdate']; ?>" type="text" />
</div></div>-->

<div class="form-group col-sm-12">
<label class="col-sm-4"> Urgent</label>
 <div class="col-sm-8">
<select required="required" name="priority" class="form-control">
<option value="1" <?php if($job['priority']=="1") echo "Selected"; ?> >Yes</option>
<option value="0" <?php if($job['priority']=="0") echo "Selected"; ?> >No</option>
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4">  Interview stages</label>
<div class="col-sm-8">
         <span class="radio-inline radio-inline">
         <input required value="1" name="interviewstages" type="radio" <?php if($job['interviewstages']=="1") echo "Checked"; ?> />
        <label class="collection_radio_buttons" for="job_interview_stages_1">1 stage</label>
        </span>
        <span class="radio-inline radio-inline">
        <input required value="2" name="interviewstages" type="radio" <?php if($job['interviewstages']=="2") echo "Checked"; ?> />
        <label class="collection_radio_buttons" for="job_interview_stages_2">2 stages</label>
        </span>
        <span class="radio-inline radio-inline">
        <input required value="3" name="interviewstages" type="radio" <?php if($job['interviewstages']=="3") echo "Checked"; ?> />
        <label class="collection_radio_buttons" for="job_interview_stages_3">3 stages</label>
        </span>
</div>
</div>-->

<div class="form-group col-sm-12">
<label class="col-sm-4"> Interviewer/Hiring Manager Comments</label>
<div class="col-sm-8">
<textarea class="text form-control" name="interviewcomments"> <?php echo $job['interviewcomments']; ?></textarea>
<p class="help-block">Interviewer Comments - E.g. Technical test required before the interview</p>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">  CV Limit</label>
<div class="col-sm-8">
<select class="select required form-control form-control" required="required" aria-required="true" name="cvlimit">
	
	<?php for($c=1;$c<21;$c++){ 
    if($job['cvlimit']== $c)
	echo '<option value="'.$c.'" selected>'.$c.'</option>';
	else
	echo '<option value="'.$c.'">'.$c.'</option>';
	}
	if($job['cvlimit']== 50)
   echo '<option value="50" selected>50</option>';
   else 
   echo '<option value="50">50</option>';
   if($job['cvlimit']== 100)
   echo '<option value="100" selected>100</option>';
   else 
   echo '<option value="100">100</option>';
   ?>
 </select>
 <p class="help-block">The maximum number of CVs each engaged recruiter can submit</p>
</div></div>

<div class="form-group col-sm-12" id="limit">
<label class="col-sm-4">No of Agencies Limit</label>
<div class="col-sm-8">
<select class="form-control" required="required" name="alimit">
<option value="">Select Limit</option>
<?php for($l=5;$l<=50;$l+=5){
	echo '<option value="'.$l.'">'.$l.'</option>';
}
?></select>
</div></div>


<div class="form-group col-sm-12" id="rfee">
<label class="col-sm-4"> Placement fee % (Excluding applicable local taxes)</label>
<div class="col-sm-8">
<select name="fee" class="form-control" id="rfee">

<?php if($job['jobtype']== "Permanent"){ ?>
<?php if($job['fee']=='7'){
echo'<option value="7" selected>7%</option>
<option value="8">8%</option>
<option value="8.33">8.33%</option>
<option value="10">10%</option>
<option value="12.5">12.5%</option>
<option value="15">15%</option>
<option value="20">20%</option>
<option value="25">25%</option>';
}
elseif($job['fee']=='5'){
echo'<option value="5" selected>5%</option>
<option value="6">6%</option>
<option value="7">7%</option>
<option value="8.33">8.33%</option>
<option value="10">10%</option>
<option value="12.5">12.5%</option>
<option value="15">15%</option>
<option value="20">20%</option>
<option value="25">25%</option>';
}
elseif($job['fee']=='6'){
echo'<option value="6" selected>6%</option>
<option value="7">7%</option>
<option value="8">8%</option>
<option value="8.33">8.33%</option>
<option value="10">10%</option>
<option value="12.5">12.5%</option>
<option value="15">15%</option>
<option value="20">20%</option>
<option value="25">25%</option>';
}
elseif($job['fee']=='8'){
echo'<option value="8" selected>8%</option>
<option value="8.33">8.33%</option>
<option value="10">10%</option>
<option value="12.5">12.5%</option>
<option value="15">15%</option>
<option value="20">20%</option>
<option value="25">25%</option>';
}
elseif($job['fee']=='8.33'){
echo'
<option value="8.33" selected>8.33%</option>
<option value="10">10%</option>
<option value="12.5">12.5%</option>
<option value="15">15%</option>
<option value="20">20%</option>
<option value="25">25%</option>';
}
elseif($job['fee']=='10'){
echo'
<option value="10" selected>10%</option>
<option value="12.5">12.5%</option>
<option value="15">15%</option>
<option value="20">20%</option>
<option value="25">25%</option>';
}
elseif($job['fee']=='12.5'){
echo'
<option value="12.5" selected>12.5%</option>
<option value="15">15%</option>
<option value="20">20%</option>
<option value="25">25%</option>';
}
elseif($job['fee']=='15'){
echo'
<option value="15" selected>15%</option>
<option value="20">20%</option>
<option value="25">25%</option>';
}
elseif($job['fee']=='20'){
echo'
<option value="20" selected>20%</option>
<option value="25">25%</option>';
}
elseif($job['fee']=='25'){
echo'
<option value="25" selected>25%</option>';
}
?>
<?php }else{ ?>
<option value="5" <?php if($job['fee']=='5'){ echo 'selected'; }else{echo ''; }?>>5%</option>
<option value="6" <?php if($job['fee']=='6'){ echo 'selected'; }else{echo ''; }?>>6%</option>
<option value="7" <?php if($job['fee']=='7'){ echo 'selected'; }else{echo ''; }?>>7%</option>
<option value="8" <?php if($job['fee']=='8'){ echo 'selected'; }else{echo ''; }?>>8%</option>
<option value="8.33" <?php if($job['fee']=='8.33'){ echo 'selected'; }else{echo ''; }?>>8.33%</option>
<option value="10" <?php if($job['fee']=='10'){ echo 'selected'; }else{echo ''; }?>>10%</option>
<option value="12.5" <?php if($job['fee']=='12.5'){ echo 'selected'; }else{echo ''; }?>>12.5%</option>
<option value="15" <?php if($job['fee']=='15'){ echo 'selected'; }else{echo ''; }?>>15%</option>
<option value="20" <?php if($job['fee']=='20'){ echo 'selected'; }else{echo ''; }?>>20%</option>
<option value="25" <?php if($job['fee']=='25'){ echo 'selected'; }else{echo ''; }?>>25%</option>
<?php } ?>
</select>
<p class="help-block">We recommend you to set a minimum fee between 8.33-10% of the Candidate's annual Salary. Placement fee excluding applicable local taxes.</p>
</div></div>
<div class="form-group col-sm-12" id="radio">
<label class="col-sm-4">Choose Commission type (On top of Candidate Payroll) </label>
<div class="col-sm-8">
 
 <input type="radio" name="feecheck" value="pfee"> Percentage Commission
 
   
  <input type="radio" name="feecheck" value="ffee"> Flat Commission
  <br>
  </div></div>

<div class="form-group col-sm-12" id="rfee1">
<label class="col-sm-4"> Monthly Commission </label>
<div class="col-sm-8">
<select name="rfee" class="form-control" >
<?php for($c=5000;$c<=200000;$c+=5000){ if($job['rfee']==$c) 
    echo'
	<option value="'.$c.'" selected>'.$c.'</option>';
	else echo'<option value="'.$c.'">'.$c.'</option>';
	
	}
	 ?>
</select>
</div></div>

<div class="form-group col-sm-12" id="radio1">
<label class="col-sm-4">Choose Commission type (On top of Candidate Payroll) </label>
<div class="col-sm-8">
 
 <input type="radio" name="feecheck" value="pfee"> Percentage Commission
 
   
  <input type="radio" name="feecheck" value="ffee"> Flat Commission
  <input type="radio" name="feecheck" value="nofee" id="nfee"> No Commission (We add our mark up)
  <br>
  </div></div>

<div class="form-group">
<div class="col-sm-offset-2 col-sm-8">
<input name="submit" value="Save Changes" class=" btn btn-primary" type="submit">
</div></div>
</form>
<?php } else echo "select a job to edit";
} echo $content;
?>
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script> 
    <script src="js/bootstrap-datepicker.js"></script>   
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script> 
    <script src="js/jquery.autocomplete.multiselect.js"></script> 
	<script>
		
		$(function(){
			$('.datepicker').datepicker({
			  dateFormat: 'yy-mm-dd'
			});
			});
		
			/*$('#joblocation').autocomplete({
				source: 'search.php',
				multiselect: true
			}); */
		/**	 $('#jobpost').on('change', 'select[name="maxlakh"]', function(){
			  return minMaxValues();
			});
		
			
			function minMaxValues(){
			var maxamount=document.getElementById("maxAmount");
			var maxAmt = ($('#maxAmount').val());
			var minAmt = ($('#minAmount').val());
		
			if ((minAmt != '') && (maxAmt != '')){
			  try{
				maxAmt = parseInt(maxAmt);
				minAmt = parseInt(minAmt);
				if(maxAmt < minAmt) {
				 maxamount.setCustomValidity('The maximum salary must be larger than the minimum salary.' + maxAmt +'< ' + minAmt );
				  return false;
				}
			  }catch(e){
				return false;
			  }
			} //end maxAmt minAmt comparison
		
			return true;
		
		}//end minMaxValues function**/

   </script>
    <script>
	   $(document).ready(function(){
	   $('#minAmount').on('change',function(){	
	   var from=$('#minAmount').val();
	 /** for(var i = from ; i <= 200; i++) {	
	   $.each(to, function(index ,key) {
	 $('#maxAmount').append($('<option>', { 
                    value: to.key,
                    text : to.value ,
                }));
				});
	 	}**/
		
		for (var i = from ; i <= 200; i++) {
    var added = document.createElement('option');
    var select1 = $('#maxAmount');
    added.value = i;
    added.innerHTML = i;
    select1.append(added);
} 
});
});
   </script>
  <script>
var fee=document.getElementsByName("fee")[0].value;
  	var rfee=document.getElementsByName("rfee")[0].value;
	function demo(){
	$('#limit').hide();
	$('#rfee1').hide();
	$('#radio').hide();
	$('#radio1').hide();
	$("#one").show();
	$("#two").hide();
	$("#othercurrency").hide();
	$("#othercurrency1").hide();
	}
	$(document).ready(function() {
	demo();
	
	$('#exp').on('change',function(){
	
	var jtype=document.getElementsByName("jobtype")[0].value;
	if((((minex==="0") && (maxex==="2")) ||((minex==="0") && (maxex==="1"))||((minex==="1") && (maxex==="2"))) && (scountry==="India")&&(jtype==="Permanent")){
	
	$('#radio').show();
	$("#limit").show();
	//$("#rfee").hide();
	//$('#rfee').removeAttr('selected');
	$("#rfee").hide();
	$("#rfee1").hide();
	 
	}
	else if((((minex==="0") && (maxex==="2")) ||((minex==="0") && (maxex==="1"))||((minex==="1") && (maxex==="2"))) && (scountry==="India")&&(jtype==="Contract")){
	
	$('#radio1').show();
	$("#limit").show();
	//$("#rfee").hide();
	//$('#rfee').removeAttr('selected');
	$("#rfee").hide();
	$("#rfee1").hide();
	 
	}
	else if((((minex==="0") && (maxex==="2")) || ((minex==="0") && (maxex==="1"))||((minex==="1") && (maxex==="2"))) && (scountry!="India")){
	$("#limit").show();
	$("#rfee").show();
	//$('#rfee').removeAttr('selected');
	//$("#rfee1").hide();	
	}
	else{
		$("#limit").hide();
	//$("#rfee").show();
	//$('#rfee').removeAttr('selected');
	$("#rfee1").hide();

	}
	});
	$(document).on("change","input[name='feecheck']",function(){
	var fee=document.getElementsByName("fee")[0].value;
  	var rfee=document.getElementsByName("rfee")[0].value;
   	var feecheck=$('[name="feecheck"]:checked').val();
	
	 if(feecheck==='pfee'){
	 
	 $("#rfee").show();
		 $("#rfee1").hide();
		 document.getElementsByName("fee")[0].value(fee);
	  document.getElementsByName("rfee")[0].value('');
	 }
	 
	 else if(feecheck==='nofee'){
	
	 $("#rfee").hide();
	 $("#rfee1").hide();
	
	  }
	 else {	 
	
	 $("#rfee1").show();
		 $("#rfee").hide();
		  document.getElementsByName("rfee")[0].value(rfee);
	  document.getElementsByName("fee")[0].value('');
		 }
		 
	});
	$('#country').on('change',function(){
		demo();
		if($(this).val()==='US'){
		$('#jtype').append("<option value='Corp-To-Corp'>Corp-To-Corp</option>");
		}
		var minex=document.getElementsByName("minex")[0].value;
	var maxex=document.getElementsByName("maxex")[0].value;
		
        if(( $(this).val()==="India")&&(((minex==="0") && (maxex==="2")) ||((minex==="0") && (maxex==="1"))||((minex==="1") && (maxex==="2")))){ 
		$("#indiacurrency").show();
		$("#othercurrency").hide();
		$("#othercurrency1").hide();
		$("#two").hide();
       	$("#one").show();
			$("#limit").show();
	$("#rfee").show();
	//$('#rfee').removeAttr('selected');
	$("#rfee1").show();

        }
		else if(( $(this).val()==="India")){
		$("#indiacurrency").show();
		$("#othercurrency").hide();
		$("#othercurrency1").hide();
		$("#two").hide();
       	$("#one").show();
		
		}
		else if(( $(this).val()!="India")&&(((minex==="0") && (maxex==="2")) ||((minex==="0") && (maxex==="1"))||((minex==="1") && (maxex==="2")))){ 
		$("#indiacurrency").hide();
		$("#othercurrency").show();
		$("#othercurrency1").hide();
		$("#one").hide(); 
		$("#two").show();
			$("#limit").show();
	$("#rfee").show();
	//$('#rfee').removeAttr('selected');
	$("#rfee1").hide();

        }else if(( $(this).val()!="India")){
		$("#indiacurrency").hide();
		$("#othercurrency").show();
		$("#othercurrency1").hide();
		$("#one").hide(); 
		$("#two").show();
		
		}
        else{ 
		$("#indiacurrency").hide();
		$("#othercurrency").show();
		$("#othercurrency1").hide();
		$("#one").hide(); 
		$("#two").show();
        }
    });
	$('#jtype').on('change',function(){
	
	var scountry=document.getElementsByName("country")[0].value;
	
	 if( ($(this).val()==="Contract") && (scountry=="UK")){
	
	$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').append("<option value='GBP'>GBP (British Pound)</option>");
		}
		else if( ($(this).val()==="Permanent" && (scountry=="UK"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').append("<option value='GBP'>GBP (British Pound)</option>");
		}
	else if( ($(this).val()==="Permanent" && (scountry=="US"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').append("<option value='USD'>USD (American Dollar)</option>");
		
		}
	else if( ($(this).val()==="Contract" && (scountry=="US"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Hourly/Day Rate  (Eg - $80 - $90/hour or $400 - $450/day)';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').append("<option value='USD'>USD (American Dollar)</option>");
		
		}
	else if( ($(this).val()==="Corp-To-Corp" && (scountry=="US"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Hourly/Day Rate  (Eg - $80 - $90/hour or $400 - $450/day)';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').append("<option value='USD'>USD (American Dollar)</option>");
		
		}
	else if( ($(this).val()==="Permanent" && (scountry=="Europe"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='EURO'>EURO</option>");
		$('#oc').append("<option value='SEK'>SEK (Swedish Krona)</option>");
		$('#oc').append("<option value='CHF'>CHF (Swiss Franc)</option>");
		$('#oc').append("<option value='PLN'>PLN (Polish zolty)</option>");
		$('#oc').append("<option value='BGN'>BGN (Bulgarian Lev)</option>");
		}
	else if( ($(this).val()==="Contract" && (scountry=="Europe"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='EURO'>EURO</option>");
		$('#oc').append("<option value='SEK'>SEK (Swedish Krona)</option>");
		$('#oc').append("<option value='CHF'>CHF (Swiss Franc)</option>");
		$('#oc').append("<option value='PLN'>PLN (Polish zolty)</option>");
		$('#oc').append("<option value='BGN'>BGN (Bulgarian Lev)</option>");
		}
	else if( ($(this).val()==="Permanent" && (scountry=="Middle East"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty();
		$('#oc').append("<option value='AED'>AED (UAE Dirham)</option>");
		$('#oc').append("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='SAR'>SAR (Saudi riyal)</option>");
		$('#oc').append("<option value='QAR'>QAR (Qatari riyal)</option>");
		$('#oc').append("<option value='OMR'>OMR (Omani rial)</option>");
		$('#oc').append("<option value='BHD'>BHD (Bahraini dinar)</option>");
		$('#oc').append("<option value='KWD'>KWD (Kuwaiti Dinar)</option>");
		
		}
	else if( ($(this).val()==="Contract" && (scountry=="Middle East"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
	    $("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty();
		$('#oc').append("<option value='AED'>AED (UAE Dirham)</option>");
		$('#oc').append("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='SAR'>SAR (Saudi riyal)</option>");
		$('#oc').append("<option value='QAR'>QAR (Qatari riyal)</option>");
		$('#oc').append("<option value='OMR'>OMR (Omani rial)</option>");
		$('#oc').append("<option value='BHD'>BHD (Bahraini dinar)</option>");
		$('#oc').append("<option value='KWD'>KWD (Kuwaiti Dinar)</option>");
		
		}
		else if( ($(this).val()==="Permanent" && (scountry=="Africa"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='EGP'>EGP (Egyptian Pound)</option>");
		$('#oc').append("<option value='MAD'>MAD (Moroccan Dirham)</option>");
		$('#oc').append("<option value='ZAR'>ZAR (South African Rand)</option>");
		$('#oc').append("<option value='ETB'>ETB (Ethiopian Birr)</option>");
		}
	else if( ($(this).val()==="Contract" && (scountry=="Africa"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='EGP'>EGP (Egyptian Pound)</option>");
		$('#oc').append("<option value='MAD'>MAD (Moroccan Dirham)</option>");
		$('#oc').append("<option value='ZAR'>ZAR (South African Rand)</option>");
		$('#oc').append("<option value='ETB'>ETB (Ethiopian Birr)</option>");
		}
	else if( ($(this).val()==="Permanent" && (scountry=="Australia"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='AUD'>AUD (Australian Dollar)</option>");
		
		}
	else if( ($(this).val()==="Contract" && (scountry=="Australia"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='AUD'>AUD (Australian Dollar)</option>");
		
		}
	else if( ($(this).val()==="Permanent" && (scountry=="Canada"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='CAD'>CAD (Canadian Dollar)</option>");
		$('#oc').append("<option value='USD'>USD (American Dollar)</option>");
		
		}
	else if( ($(this).val()==="Contract" && (scountry=="Canada"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='CAD'>CAD (Canadian Dollar)</option>");
		$('#oc').append("<option value='USD'>USD (American Dollar)</option>");
		
		}
	else if( ($(this).val()==="Permanent" && (scountry=="Singapore"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='SGD'>SGD (Singapore Dollar)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="Singapore"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='SGD'>SGD (Singapore Dollar)</option>");
		
		}
	else if( ($(this).val()==="Permanent" && (scountry=="Malaysia"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='MYR'>MYR (Malaysian Ringgit)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="Malaysia"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='MYR'>MYR (Malaysian Ringgit)</option>");
		
		}
	else if( ($(this).val()==="Permanent" && (scountry=="Indonesia"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='IDR'>IDR (Indonesian Rupiah)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="Indonesia"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='IDR'>IDR (Indonesian Rupiah)</option>");
		
		}
	else if( ($(this).val()==="Permanent" && (scountry=="Russia"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='RUB'>RUB (Russian Ruble)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="Russia"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='RUB'>RUB (Russian Ruble)</option>");
		
		}
	else if( ($(this).val()==="Permanent" && (scountry=="Japan"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='JPY'>JPY (Japanese Yen)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="Japan"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='JPY'>JPY (Japanese Yen)</option>");
		
		}
	else if( ($(this).val()==="Permanent" && (scountry=="China"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='CNY'>CNY (China Yuan Renminbi)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="China"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='CNY'>CNY (China Yuan Renminbi)</option>");
		
		}
		else if( ($(this).val()==="Permanent" && (scountry=="Hong Kong"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='HKD'>HKD (Hong Kong Dollar)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="Hong Kong"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='HKD'>HKD (Hong Kong Dollar)</option>");
		
		}
		else if( ($(this).val()==="Permanent" && (scountry=="Vietnam"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='VND'>VND (Vietnamese Dong)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="Vietnam"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='VND'>VND (Vietnamese Dong)</option>");
		
		}
		else if( ($(this).val()==="Permanent" && (scountry=="Mexico"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='MXN'>MXN (Mexican Peso)</option>");

		}
		else if( ($(this).val()==="Contract" && (scountry=="Mexico"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='MXN'>MXN (Mexican Peso)</option>");
		
		}
		else if( ($(this).val()==="Permanent" && (scountry=="South America"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='BRL'>BRL (Brazilian Real)</option>");

		}
		else if( ($(this).val()==="Contract" && (scountry=="South America"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='BRL'>BRL (Brazilian real)</option>");
		
		}
		else if( ($(this).val()==="Permanent" && (scountry=="Philippines"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='PHP'>PHP (Philippine Peso)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="Philippines"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='PHP'>PHP (Philippine Peso)</option>");
		
		}
		
		else if( ($(this).val()==="Permanent" && (scountry=="North Korea"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='KRW'>KRW (Korean Won)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="North Korea"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='KRW'>KRW (Korean Won)</option>");
		
		}
		else if( ($(this).val()==="Permanent" && (scountry=="South Korea"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='KRW'>KRW (Korean Won)</option>");

		}
	else if( ($(this).val()==="Contract" && (scountry=="South Korea"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = ""
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'Day Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='KRW'>KRW (Korean Won)</option>");
		
		}
	else if( ($(this).val()==="Contract") && (scountry=="India")){
		
		  document.getElementById('dayrate').innerHTML = 'Monthly Salary Range';
		  $("#radio").hide();
		  $("#radio1").show();
		  $("#rfee").hide();
		  $("#one").hide(); 
		  $("#two").show();
		 } 
		else if( ($(this).val()==="Contract") && (scountry=="India")){
		
		  document.getElementById('dayrate').innerHTML = 'Monthly Salary Range';
		    $("#radio").hide();
		  $("#radio1").show();
		  $("#rfee").hide();
		   $("#one").hide(); 
		$("#two").show();
		 }
		else{
		 
		$("#indiacurrency").hide();
		$("#othercurrency").show();
		$("#othercurrency1").hide();
		$("#one").hide(); 
		$("#two").show();
		}
	});
	 });
 </script>
<script>
$(document).ready(function() {
var e = document.getElementById("country");
var country = e.options[e.selectedIndex].text;
var jtype=document.getElementsByName("jobtype")[0].value;
var minex=document.getElementsByName("minex")[0].value;
	var maxex=document.getElementsByName("maxex")[0].value;
	if((((minex==="0") && (maxex==="2") )||((minex==="0") && (maxex==="1") )||((minex==="1") && (maxex==="2"))) && (country==="India")&&(jtype==="Permanent")){

	$("#limit").show();
	$("#rfee").hide();
	//$('#rfee').removeAttr('selected');
	$("#radio").show();
	}else if((((minex==="0") && (maxex==="2")) || ((minex==="0") && (maxex==="1")) || ((minex==="1") && (maxex==="2"))) && (country!="India")&&(jtype==="Contract")){

	$("#limit").show();
	$("#rfee").show();
	}
	else{
		$("#limit").hide();
	//$("#rfee").show();
	//$('#rfee').removeAttr('selected');
	$("#rfee1").hide();

	}
 if((country=="UK") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Europe") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="US") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Middle East") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Africa") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Australia") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Canada") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Singapore") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Malaysia") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Indonesia") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Russia") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Japan") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="China") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Hong Kong") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Vietnam") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="Philippines") && (jtype=="Contract")){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		$("#one").hide(); 
		$("#two").show();
		document.getElementById("rfee").innerHTML = "";
		 document.getElementById('dayrate').innerHTML = 'Day Rate';
	}
else if((country=="India") && (jtype=="Contract")){
  document.getElementById('dayrate').innerHTML = 'Monthly Salary Range';
		    $("#radio").hide();
		  $("#radio1").show();
		  $("#rfee").hide();
		   $("#one").hide(); 
		$("#two").show();
		   document.getElementById('nfee').checked = true;
}	
	
else if(country!="India"){

$("#indiacurrency").hide();
		$("#othercurrency").show();
		$("#one").hide(); 
		$("#two").show();
		
		}
		
else {
  
		$("#indiacurrency").show();
		$("#othercurrency").hide();
		$("#othercurrency1").hide();
		$("#two").hide();
       	$("#one").show();
		

}		
		});
</script>

		<script>
        $(function() {
            function split( val ) {
                return val.split( /,\s*/ );
            }
            function extractLast( term ) {
                return split( term ).pop();
            }
            
            $( "#joblocation" ).bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).autocomplete( "instance" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                minLength: 1,
                source: function( request, response ) {
                    // delegate back to autocomplete, but extract the last term
                    $.getJSON("search.php", { term : extractLast( request.term )},response);
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    return false;
                }
            });
        });
        </script>
        
        <script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || 
{widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};
var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;
s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>
</html>
