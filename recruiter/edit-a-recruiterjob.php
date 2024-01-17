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
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
if(isset($_GET['jobid'])){
	$jobid= $_GET['jobid'];
	$jobres=mysqli_query($link,"select * from recruiterjobs where recruiterjobid='$jobid'");
	if(mysqli_num_rows($jobres)){
			$job=mysqli_fetch_assoc($jobres);
		}
}
$res=mysqli_query($link,"select sector from jobsectors");
if(mysqli_num_rows($res)){
	while($row=mysqli_fetch_assoc($res)){
		$sector[]=$row;
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
	$recruiterjobid=$_POST['jobid'];
	$jobtitle=test_input($_POST['jobtitle']);
$novacancies=test_input($_POST['novacancies']);
$jobsector=test_input($_POST['jobsector']);
$country=test_input($_POST['country']);
 //$joblocation=test_input($_POST['joblocation']);
 if (isset($_POST['joblocation'])){
$joblocation=implode(',',$_POST['joblocation']);
}
else {
$joblocation='';
}
$email=$_POST['contactemail'];
$contactnumber=$_POST['contactnumber'];
$jobtype=test_input($_POST['jobtype']);
$minex=test_input($_POST['minex']);
$maxex=test_input($_POST['maxex']);
if($_POST['country']=='India'){
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
//$salary=test_input($_POST['salary']);
//$fullpackage=test_input($_POST['fullpackage']);
$description=test_input($_POST['description']);
$companyprofile=test_input($_POST['companyprofile']);

//$benefits=test_input($_POST['benefits']);
/**if (isset($_POST['benefits'])){
$benefits=implode(',',$_POST['benefits']);}
else {$benefits='';}***/
$degree=$_POST['degree'];
$keyskills=test_input($_POST['keyskills']);

if (isset($_POST['considerrelocation']))
$considerrelocation=$_POST['considerrelocation'];
else
$considerrelocation=0;
/*if (isset($_POST['relocationassistanc']))
$relocationassistanc=$_POST['relocationassistanc'];
else
$relocationassistanc=0;*/
$companyname=$_POST['companyname'];
$closingdate=$_POST['closingdate'];
if (isset($_POST['interviewstages']))
$interviewstages=$_POST['interviewstages'];
else
$interviewstages='';
//$start_date = date('Y-m-d ');
//$end_date = date('Y-m-d ', strtotime("+60 days"));

$sql=mysqli_query($link,"update  recruiterjobs set memberid='$mid',companyname='$companyname',jobtitle='$jobtitle',novacancies='$novacancies',jobsector='$jobsector',country='$country',joblocation='$joblocation',jobtype='$jobtype',contactemail='$email',contactnumber='$contactnumber',minex='$minex',maxex='$maxex',minlakh='$minlakh',minthousand='$minthousand',maxlakh='$maxlakh',maxthousand='$maxthousand',currency='$currency',description='$description',companyprofile='$companyprofile',keyskills='$keyskills',degree='$degree',relocate='$considerrelocation',closingdate='$closingdate',interviewstages='$interviewstages',updateddate=now() where recruiterjobid='$recruiterjobid'")or die("Error in query"); 

$content="Saved changes successfully.";	

}

 ?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Edit Job | Recruiters Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
     <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
    <link rel="stylesheet" href="css/datepicker.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.4.custom.min.css"/>   
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
    <h3 class="panel-title"> Post your Job</h3>
  </header>
        <div class="panel-body">
<?php if(!$content) {?>
<form method="post" action="edit-a-recruiterjob" enctype="multipart/form-data" id="jobpost">
<div class="form-group col-sm-12">
<label class="col-sm-4">Job Title</label>
<div class="col-sm-8">
<input class="form-control" value="<?php echo $job['jobtitle']; ?>"  name="jobtitle" type="text">
</div></div>
<input type="hidden" name="jobid" type="text" value="<?php echo $job['recruiterjobid']; ?>">
<div class="form-group col-sm-12">
<label class="col-sm-4">No of Vacancies</label>
<div class="col-sm-8">
<select class="form-control"   name="novacancies">
<option value="">Select the vacancy count</option>
<?php 
 
for($i=1;$i<=100;$i++){
	if($job['novacancies']== $i)
	echo '<option value="'.$i.'" selected>'.$i.'</option>';
	else
	echo '<option value="'.$i.'">'.$i.'</option>';
}
?></select>
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
<select class="form-control" name="country" id="country" onChange="get_states();">
<?php  $countryarray=array('India','US','UAE','UK','Europe','Africa','Malaysia','Singapore','Middle East','China','Thailand','Indonesia','Australia','Philippines','Hong Kong','Japan','New Zealand','North Korea','South Korea','Russia','Canada','Mexico','South America');
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
<div class="col-sm-8" id="joblocation">
<div class="city-checkbox" id="location-filter">
<ul>
<?php 
$res=mysqli_query($link,"SELECT city FROM citybycountry WHERE country='".$job['country']."' ORDER BY city");

if(mysqli_num_rows($res)){
	while($row=mysqli_fetch_assoc($res)){
		$loca[]=$row;
	}
}
$exp=explode(',',$job['joblocation']);
foreach($loca as $locs){
if((in_array($locs['city'], $exp)))  {
echo'
<li style="list-style:none">
<div class="checkbox"><label><input name="joblocation[]" value="'.$locs['city'].'" type="checkbox" checked>'.$locs['city'].'</label></div>
</li>';
}else{
echo'
<li style="list-style:none">
<div class="checkbox"><label><input name="joblocation[]" value="'.$locs['city'].'" type="checkbox">'.$locs['city'].'</label></div>
</li>';
 }}?></ul></div>
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4">Vacancy reason</label>
<div class="col-sm-8">
<select required="required" aria-required="true" name="vacancyreason" class="form-control">
<option selected="selected" value="Growth/Expansion">Growth/Expansion</option>
<option value="Replacement">Replacement</option>
<option value="Internal Restructure">Internal Restructure</option>
</select>
</div></div>--->
<div class="form-group col-sm-12">
<label class="col-sm-4">Contact Email</label>
<div class="col-sm-8">
<input class="form-control" name="contactemail" type="email" value="<?php echo $job['contactemail']; ?>">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Contact Number</label>
<div class="col-sm-8">
<input class="form-control" name="contactnumber" type="text" value="<?php echo $job['contactnumber']; ?>">
</div></div>



<div class="form-group col-sm-12" >
<label class="col-sm-4">Job type</label>
<div class="col-sm-8">
<select class="form-control" name="jobtype" id="jobtype">
<option <?php if($job['jobtype']== "Permanent") echo "selected";?> value="Permanent">Permanent</option>

<option <?php if($job['jobtype']== "Contract") echo "selected";?>  value="Contract">Contract</option></select>
</div></div>


<div class="form-group col-sm-12">
<label class="col-sm-4"> Agency Name</label>
<div class="col-sm-8">
<input class="numeric integer required form-control" value="<?php echo $job['companyname']?>" name="companyname" type="text" readonly>
</div>
</div>

<div class="form-group col-sm-12">
<label class="col-sm-4"> Total Experience</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<input class="numeric integer required form-control" value="<?php echo $job['minex'];?>" name="minex" type="text">
</div>
<div class="col-sm-3">
<input class="numeric integer required form-control" value="<?php echo $job['maxex'];?>" name="maxex" type="text">
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
<option value="INR" selected>INR</option>';
else
echo'<option value="INR">INR</option>';
echo'<option value="GBP">GBP</option>';
echo'<option value="USD">USD</option>';
echo'<option value="AED">AED</option>';
echo'<option value="EURO">EURO</option>';
echo'<option value="EGP">EGP</option>';
echo'<option value="SEK">SEK</option>';
echo'<option value="CHF">CHF</option>';
echo'<option value="ZAR">ZAR</option>';
echo'<option value="ETB">ETB</option>';
echo'<option value="PLN">PLN</option>';
echo'<option value="BGN">BGN</option>';
echo'<option value="BRL">BRL</option>';
echo'<option value="MXN">MXN</option>';
echo'<option value="VND">VND</option>';
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
$currencyarray=array('INR','AED','GBP','EURO','USD','SAR','QAR','OMR','BHD','KWD','CAD','AUD','JPY','MYR','SGD','IDR','CNY','KRW','RUB','NZD','HKD','THB','PHP','MAD','EGP','SEK','CHF','ZAR','ETB','PLN','BGN','BRL','MXN','VND'); 
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

<div class="form-group col-sm-12" id="one">
<label class="col-sm-4">  Annual Salary Range</label>
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
<option value=" ">Thousands</option>
<option value="00">00</option>
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
<option value=" ">Lakhs</option>
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
<option value=" ">Thousands</option>
<option value="00">00</option>
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
<label class="col-sm-4" id="twolabel"> Annual Salary Range</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<input class="numeric integer required form-control"  value="<?php echo $job['minlakh']; ?>" name="from" type="text">
</div>
<div class="col-sm-3">
<input class="numeric integer required form-control"  value="<?php echo $job['maxlakh']; ?>" name="to" type="text">
</div>
 <div class="col-sm-2 no-padding" id="eg">
e.g 300£ - 450£
</div>
</div></div>


 <div class="form-group col-sm-12">  
<label class="col-sm-4">Description</label>
<div class="col-sm-8">
<textarea class="form-control"  rows="6" name="description"><?php echo $job['description']; ?></textarea>
</div></div>


<!--<div class="col-sm-12 form-group">
      <label class="col-sm-4">Benefits</label>
       <div class="col-sm-8">
       <ul class="list-unstyled">
    <li class="checkbox"><label><input type="checkbox" value="Car" name="benefits[]" />Car</label></li>
  	<li class="checkbox"><label><input type="checkbox" value="Pension" name="benefits[]" />Pension</label></li>
    <li class="checkbox"><label><input type="checkbox" value="Car Allowance" name="benefits[]" />Car Allowance</label></li>
    <li class="checkbox"><label><input type="checkbox" value="Commission/Bonus" name="benefits[]" />Commission/Bonus</label></li>
    <li class="checkbox"><label><input type="checkbox" value="Healthcare/Dental" name="benefits[]" />Healthcare/Dental</label></li>
    <li class="checkbox"><label><input type="checkbox" value="Other" name="benefits[]" />Other(discuss with your RH Account manager)</label></li>
    </ul>
    </div>          
</div>--->

<div class="form-group col-sm-12">
<label class="col-sm-4">Key skills</label>
 <div class="col-sm-8">
<textarea class="form-control"  name="keyskills"><?php echo $job['keyskills']; ?></textarea>
<p class="help-block">E.g Java, Autocad, Piping Engineer, Investment banking etc. </p>
</div></div>
<div class="form-group col-sm-12">
<label class="col-sm-4"> Education</label>
 <div class="col-sm-8">
 <input class="form-control" value="<?php echo $job['degree'];?>"  name="degree" type="text">

</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4">Consider relocation?</label>
 <div class="col-sm-8">
<input  value="1" name="considerrelocation" type="checkbox" <?php if($job['relocate']) echo "Checked"; ?>/>
</div></div>-->
<!---<div class="form-group col-sm-12">
<label class="col-sm-4"> Feedback timescale</label>
 <div class="col-sm-8">
<select required="required" name="feedback" class="form-control"><option value=""></option>
<option selected="selected" value="24-48 hours">24-48 hours</option>
<option value="48-72 hours">48-72 hours</option>
<option value="1 week">1 week</option></select>
</div></div>--->

<!--<div class="form-group col-sm-12">
<label class="col-sm-4"> CV submission closing?</label>
 <div class="col-sm-8">
        <input class="datepicker" name="closingdate" value="<?php echo  $job['closingdate'];?>" type="text" />
</div></div>

<div class="form-group col-sm-12">
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

<!--<div class="form-group col-sm-12">
<label class="col-sm-4"> Interview comments</label>
<div class="col-sm-8">
<textarea class="text form-control" name="interviewcomments"></textarea>
<p class="help-block">E.g. Technical test required before the interview</p>
</div></div>--->

<!--<div class="form-group col-sm-12">
<label class="col-sm-4">  CV Limit</label>
<div class="col-sm-8">
<select class="select required form-control form-control" required="required" aria-required="true" name="cvlimit">
	
	
    <option value="50">50</option>
    <option value="100">100</option>
 </select>
 <p class="help-block">The maximum number of CVs each engaged recruiter can submit</p>
</div></div>-->

<!--<div class="form-group col-sm-12">
<label class="col-sm-4"> Recruiter fee (Excluding applicable local taxes)</label>
<div class="col-sm-8">
<select required="required" name="fee" class="form-control"><option value=""></option>
<option value="5%">5%</option>
<option value="6%">6%</option>
<option value="7%">7%</option>
<option value="8%">8%</option>
<option selected="selected" value="8.33%">8.33%</option>
<option value="10%">10%</option>
<option value="12%">12%</option>
<option value="12.5%">12.5%</option>
<option value="15%">15%</option>
<option value="20%">20%</option>
<option value="25%">25%</option>
</select>
<p class="help-block">We recommend you to set a minimum fee between 8.33-10% of the Candidate's annual salary. Recruiter fee excluding applicable local taxes.</p>
</div></div>-->

<div class="form-group">
<div class="col-sm-offset-2 col-sm-8">
<input name="submit" value="Update Job" class=" btn btn-primary" type="submit">
</div></div>
</form>
<?php } else echo $content;?>
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
	</script>
 <script>
	function demo(){
	$("#one").show();
	$("#two").hide();
	$("#othercurrency").hide();
	}
	$(document).ready(function() {
	demo();
	$('#country').on('change',function(){
		demo();
        if( $(this).val()==="India"){
		$("#indiacurrency").show();
		$("#othercurrency").hide();
		$("#two").hide();
       	$("#one").show();
        }
        else{ 
		$("#indiacurrency").hide();
		$("#othercurrency").show();
		$("#one").hide(); 
		$("#two").show();
        }
    });
	
	 });
 </script>
 <script>
function get_states() {
 // Call to ajax function
    var country = $('#country').val();
 
    $.ajax({
	 
        type: "POST",
        url: "get-city.php", // Name of the php files
        data: 'EID=' +country,
        success: function(html)
        {
		$("#joblocation").html(html);
        }
    });
}
</script>
 <script>
 $('#jobtype').on('change',function(){
var e = document.getElementById("country");
var country = e.options[e.selectedIndex].text;
 
 if( ($(this).val()==="contract") && (country=="UK") ){
  document.getElementById('twolabel').innerHTML = 'Day Rate';
 }
 });
 
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
        $(function() {
            function split( val ) {
                return val.split( /,\s*/ );
            }
            function extractLast( term ) {
                return split( term ).pop();
            }
            
            $("#joblocation").bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).autocomplete( "instance" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                minLength: 1,
                source: function( request, response ) {
                    // delegate back to autocomplete, but extract the last term
                    $.getJSON("location-search.php", { term : extractLast( request.term )},response);
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
 </body>
</html>
