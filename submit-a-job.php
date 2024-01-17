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
if(isset($_POST['submit'])){
require_once 'insert-job.php';
}
//fetch sector values to an array
$res=mysqli_query($link,"select sector from jobsectors");
if(mysqli_num_rows($res)){
	while($row=mysqli_fetch_assoc($res)){
		$sector[]=$row;
	}
}
 ?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Post Job | Employers Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
     <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
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
    <h3 class="panel-title"> Post a Success Fee based Job on our Recruitment Agencies Marketplace (Hire 2X Faster) - Just takes a minute </h3>
  </header>
        <div class="panel-body">
<?php if(!$content) {?>
<form method="post" action="submit-a-job.php" enctype="multipart/form-data" id="jobpost">
    
<div class="form-group col-sm-12">
<label class="col-sm-4">Country / Continent</label>
<div class="col-sm-8">
<select class="form-control" name="country" id="country" >
<option value="">Choose Country</option>
<option value="UK">UK</option>
<option value="US">US</option>
<option value="Middle East">Middle East</option>
<option value="India">India</option>
<option value="Europe">Europe</option>
<option value="Africa">Africa</option>
<option value="Malaysia">Malaysia</option>
<option value="Singapore">Singapore</option>
<option value="China">China</option>
<option value="Thailand">Thailand</option>
<option value="Indonesia">Indonesia</option>
<option value="Australia">Australia</option>
<option value="Philippines">Philippines</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Japan">Japan</option>
<option value="New Zealand">New Zealand</option>
<option value="North Korea">North Korea</option>
<option value="South Korea">South Korea</option>
<option value="Russia">Russia</option>
<option value="Canada">Canada</option>
<option value="Mexico">Mexico</option>
<option value="South America">South America</option>
<option value="Vietnam">Vietnam</option>
</select>
</div></div>


<div class="form-group col-sm-12">
<label class="col-sm-4">Job Location</label>
<div class="col-sm-8" required id="joblocations">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Town / City</label>
<div class="col-sm-8">
<input type="text" required name="city" class="form-control" required> 
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Job type</label>
<div class="col-sm-8">
<select class="form-control" required="required" name="jobtype" id="jtype">
<option value="">Choose Job Type</option>
<option  value="Permanent">Permanent</option>
<option value="Contract">Contract</option></select>
</div></div>

    
<div class="form-group col-sm-12">  
<label class="col-sm-4">Job Description (Upload as file)</label>
<div class="col-sm-8">
<input type="file" required class="form-control" name="description1">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Job Title</label>
<div class="col-sm-8">
<input class="form-control" required  name="jobtitle" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">No of Vacancies</label>
<div class="col-sm-8">
<select class="form-control" required="required"  name="novacancies">
<option value="">Select No of Vacancies</option>
<?php 
for($i=1;$i<=100;$i++)
echo'<option value="'.$i.'">'.$i.'</option>';
?></select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Job Sector</label>
<div class="col-sm-8">
<select required="required" name="jobsector" class="form-control">
 <?php foreach($sector as $s){ ?>
 <option value="<?php echo $s['sector']; ?>"><?php echo $s['sector']; ?></option>
 <?php } ?>
</select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Working</label>
<div class="col-sm-8">
<select class="form-control" required="required" name="working" id="working">
<option value="">Choose Working Type</option>
<option  value="Always Remote">Always Remote</option>
<option  value="Remote until Covid">Remote until Covid</option>
<option  value="Hybrid (Office + Home)">Hybrid (Office + Home)</option>
<option value="Office only">Office only</option></select>
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4">Vacancy reason</label>
<div class="col-sm-8">
<select required="required" aria-required="true" name="vacancyreason" class="form-control">
<option selected="selected" value="Growth/Expansion">Growth/Expansion</option>
<option value="Replacement">Replacement</option>
<option value="Internal Restructure">Internal Restructure</option>
</select>
</div></div>-->

<div class="form-group col-sm-12">
<label class="col-sm-4"> Notice Period (in days)</label>
<div class="col-sm-8">
<input class="numeric integer required form-control" required placeholder="How soon are you looking to close this position? e.g 7 days" name="notice" type="text">
</div>
</div>
<div class="form-group col-sm-12" id="exp">
<label class="col-sm-4"> Experience Range</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<input class="numeric integer required form-control" required placeholder="Min e.g 2" name="minex" type="text">
</div>
<div class="col-sm-3">
<input class="numeric integer required form-control" required placeholder="Max e.g 5" name="maxex" type="text">
</div>
<div class="col-sm-2 no-padding">
In Years
</div>
</div></div>

<!--<div class="form-group col-sm-12" id="indiacurrency">
<label class="col-sm-4">Currency</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<select class="form-control" name="indiacurrency" type="text">
<option value="INR">INR (Indian Rupee)</option>
</select>
</div>
</div></div>-->

<div class="form-group col-sm-12" id="othercurrency">
<label class="col-sm-4">Currency</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<select class="form-control" name="othercurrency"  type="text">
<option value="">Select Currency</option>
<option value="INR">INR (Indian Rupee)</option>
<option value="AED">AED (UAE Dirham)</option>
<option value="GBP">GBP (British Pound)</option>
<option value="EURO">EURO</option>
<option value="USD">USD (American Dollar)</option>
<option value="SAR">SAR (Saudi riyal)</option>
<option value="QAR">QAR (Qatari riyal)</option>
<option value="OMR">OMR (Omani riyal)</option>
<option value="BHD">BHD (Bahraini Dinar)</option>
<option value="KWD">KWD (Kuwaiti Dinar)</option>
<option value="CAD">CAD (Canadian Dollar)</option>
<option value="AUD ">AUD (Australian Dollar)</option>
<option value="JPY">JPY (Japanese Yen)</option>
<option value="MYR">MYR (Malaysian Ringgit)</option>
<option value="SGD">SGD (Singapore Dollar)</option>
<option value="IDR">IDR (Indonesian Rupiah)</option>
<option value="CNY">CNY (China Yuan Renminbi)</option>
<option value="KRW">KRW (Korean Won)</option>
<option value="RUB">RUB (Russian Ruble)</option>
<option value="NZD">NZD (New Zealand Dollar)</option>
<option value="HKD">HKD (Hong Kong Dollar)</option>
<option value="THB">THB (Thai Baht)</option>
<option value="PHP">PHP (Philippine Peso)</option>
<option value="MAD">MAD (Moroccan Dirham)</option>
<option value="EGP">EGP (Egyptian Pound)</option>
<option value="SEK">SEK (Swedish Krona)</option>
<option value="CHF">CHF (Swiss Franc)</option>
<option value="ZAR">ZAR (South African Rand)</option>
<option value="ETB">ETB (Ethiopian Birr)</option>
<option value="PLN">PLN (Polish zloty)</option>
<option value="BGN">BGN (Bulgarian Lev)</option>
<option value="VND">VND (Vietnamese Dong)</option>
<option value="MXN">MXN (Mexican Peso)</option>
<option value="BRL">BRL (Brazilian real)</option>
</select>
</div>
</div></div>

<div class="form-group col-sm-12" id="othercurrency1">
<label class="col-sm-4">Currency</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<select class="form-control" name="othercurrency1"  type="text" id="oc">

<option value="GBP">GBP (British Pound)</option>

</select>
</div>
</div></div>

<div class="form-group col-sm-12" id="one">
<label class="col-sm-4" id="onelabel">  Annual Salary Range</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<select  name="minlakh" id="minAmount" class="form-control" >
<option value="">Lakhs</option>
<?php 
for($i=1;$i<201;$i++){
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
for($k=10;$k<100;$k+=10){
	echo '<option value="'.$k.'">'.$k.'</option>';
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

</select>
</div>
<div class="col-sm-3 no-padding-right">
<select  name="maxthousand"  class="form-control" >
<option value=" ">Thousands</option>
<option value="00">00</option>
<?php 
for($l=10;$l<100;$l+=10){
	echo '<option value="'.$l.'">'.$l.'</option>';
}
?>
</select>
</div>

</div></div>

<div class="form-group col-sm-12" id="two">
<label class="col-sm-4" id="dayrate"> Salary Range</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<input class="numeric integer required form-control"  placeholder="Min e.g 3000" name="from" type="text">
</div>
<div class="col-sm-3">
<input class="numeric integer required form-control"  placeholder="Max e.g 4000" name="to" type="text">
</div>
<div class="col-sm-3">
<select class="numeric integer required form-control" name="ratetype" id="ratetype">
<option value="">Duration</option>
<option value="hour">Per Hour</option>
<option value="day">Per Day</option>
<option value="month">Per Month</option>
<option value="year">Per Year</option>
</select>
</div>

</div></div>


<div class="form-group col-sm-12">  
<label class="col-sm-4">Job Description</label>
<div class="col-sm-8">
<textarea class="form-control" required rows="6" name="description" placeholder="copy & paste job description here"></textarea>
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
<label class="col-sm-4">Key Skills</label>
 <div class="col-sm-8">
<textarea class="form-control" aria-required="true" name="keyskills" placeholder="e.g Java, Angular JS etc"></textarea>
<p class="help-block">E.g Java, Autocad, Piping Engineer, Investment banking etc. To aid recruiter's search</p>
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4">Education</label>
 <div class="col-sm-8">
 <textarea class="form-control" aria-required="true" name="degree" placeholder="e.g IT Degree"></textarea>
</div></div>-->

<div class="form-group col-sm-12">
<label class="col-sm-4">IR35 Regulation (For UK Jobs only)</label>
<div class="col-sm-8">
<select class="form-control" required="required" name="ir35" id="ir35">
<option value="">Choose IR35 Type</option>
<option  value="Not Applicable">Not Applicable</option>
<option value="Inside IR35">Inside IR35</option>
<option value="Outside IR35">Outside IR35</option></select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Consider relocation?</label>
 <div class="col-sm-8">
<input class="boolean optional" value="1" name="considerrelocation" type="checkbox" />
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4"> Feedback timescale (How soon will you provide feedback?)</label>
 <div class="col-sm-8">
<select required="required" name="feedback" class="form-control">
<option selected="selected" value="24-48 hours">24-48 hours</option>
<option value="48-72 hours">48-72 hours</option>
<option value="1 week">1 week</option></select>
</div></div>-->

<!--<div class="form-group col-sm-12">
<label class="col-sm-4"> CV Submission Closing date?</label>
 <div class="col-sm-8">
        <input class="datepicker" required name="closingdate" placeholder="YYYY-MM-DD" type="text" />
</div></div>-->

<!--<div class="form-group col-sm-12">
<label class="col-sm-4">  Interview stages</label>
<div class="col-sm-8">
            <span class="radio-inline radio-inline">
            <input class="radio_buttons_inline required" required value="1" checked="checked" name="interviewstages" type="radio" />
            <label class="collection_radio_buttons" for="job_interview_stages_1">1 stage</label>
            </span>
            <span class="radio-inline radio-inline">
            <input class="radio_buttons_inline required" required aria-required="true" value="2" name="interviewstages" id="job_interview_stages_2" type="radio" />
            <label class="collection_radio_buttons" for="job_interview_stages_2">2 stages</label>
            </span>
            <span class="radio-inline radio-inline">
            <input class="radio_buttons_inline required" required aria-required="true" value="3" name="interviewstages" id="job_interview_stages_3" type="radio">
            <label class="collection_radio_buttons" for="job_interview_stages_3">3 stages</label>
            </span>
</div>
</div>-->

<div class="form-group col-sm-12">
<label class="col-sm-4"> Interviewer/Hiring Manager Comments</label>
<div class="col-sm-8">
<textarea class="text form-control" name="interviewcomments"></textarea>
<p class="help-block">E.g. Technical test required before the interview. Please upload video intro of candidate while submitting CV.</p>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">  CV Limit</label>
<div class="col-sm-8">
<select class="select required form-control form-control" required="required" aria-required="true" name="cvlimit">
	
	<?php for($c=1;$c<21;$c++){ ?>
	<option value="<?php echo $c; ?>"><?php echo $c; ?></option>
	<?php } ?>
    <option value="50">50</option>
    <option value="100">100</option>
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

<div class="form-group col-sm-12" id="radio">
<label class="col-sm-4">Choose Commission type</label>
<div class="col-sm-8">
 
 <input type="radio" name="feecheck" value="pfee"> Percentage Commission
 
   
  <input type="radio" name="feecheck" value="ffee"> Flat Fee Commission
  <br>
  </div></div>


<div class="form-group col-sm-12" id="radio1">
<label class="col-sm-4">Choose Commission type</label>
<div class="col-sm-8">
 
 <input type="radio" name="feecheck" value="pfee"> Percentage Commission
 
   
  <input type="radio" name="feecheck" value="ffee"> Flat Fee Commission
  <!--<input type="radio" name="feecheck" value="nofee"> No Commission (We add our mark up)-->
  <br>
  </div></div>



<div class="form-group col-sm-12" id="rfee">
<label class="col-sm-4"> Set a Placement fee % (Excluding applicable local taxes)</label>
<div class="col-sm-8">
<select  name="fee" class="form-control" required>
<option value="">Choose Placement fee % on Annual Base Salary of Candidate (One time Search fee) - No success, No fee</option>
<!--<option  value="5">5%</option>
<option  value="6">6%</option>-->
<option  value="7">7% (Less Preferred by Agencies)</option>
<option  value="8.33">8.33% (Average)</option>
<option value="10">10% (Preferred)</option>
<option value="12.5">12.5% (Most Preferred)</option>
<option value="15">15% (Ideal for Large Recruitment Agencies)</option>
<option value="20">20% (Executive Search)</option>
<option value="25">25% (Executive Search)</option>
</select>
<p class="help-block">We recommend you to set a minimum fee between 10%-15% of the Candidate's annual salary. Placement fee excluding applicable local taxes. Fee payable only upon successful placement plus a free replacement guarantee from us. No Success, No Fee!</p>
</div></div>

<div class="form-group col-sm-12" id="rfee1">
<label class="col-sm-4"> Flat Fee Commission </label>
<div class="col-sm-8">
<select  name="rfee" class="form-control">
<option value="">Choose Flat Fee Commission</option>
<?php for($c=5000;$c<=200000;$c+=5000){ ?>
	<option value="<?php echo $c; ?>"><?php echo $c; ?></option>
	<?php } ?>
</select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4"> Urgent?</label>
 <div class="col-sm-8">
<select required="required" name="priority" class="form-control">
<option selected="selected" value="1">Yes</option>
<option value="0">No</option>
</div></div>
</div>

<div class="form-group">
<div class="col-sm-offset-2 col-sm-8">
<input name="submit" value="Post Job" class=" btn btn-primary" type="submit">
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
		


		$('[name=description1]').change((e)=>{

var formData = new FormData();
formData.append('file', e.target.files[0]);

function objectExtract (data){

let arr =[]
 for(key in data) {
    if(data.hasOwnProperty(key)) {
        arr.push(data[key]);
    }
}
return arr.join()

}

$.ajax({
type: "POST",
url: "../parser/index.php",    
contentType: false,
processData: false,
data: formData,
success: function (data) {
 // alert(data);

//console.log(data);
if(data.length > 0){
  let result = JSON.parse(data);

  let {text,name,email,mobile,location,country,skills,exp,jobtype,course} = result[0]

 
  if(text && $('[name="description"]').val() === ''){

    $('[name="description"]').val(text);
  }
  if(skills && $('[name=keyskills]').val() === '' ){
    $('[name=keyskills]').val(objectExtract(skills));
  }
  if(country && $('[name="country"]').val() === ''){
	if(country[0] === 'Us'){

		country = 'US'
	}else if(country[0] === 'Uk'){
		country = 'UK'

		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#one").hide(); 
		$("#two").show();
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').append("<option value='GBP'>GBP (British Pound)</option>");

	}else{
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		country = country[0]
	}
    $('[name="country"]').val(country);
  }
  if(location && $('[name="city"]').val() === ''){
    $('[name="city"]').val(location[0]);
  }

  if(exp && $('[name="minex"]').val() === ''){

    $('[name="minex"]').val(exp[0].split(' ')[0]);
   // objectExtract(skills)
  }

  if(course && $('[name="degree"]').val() === ''){

$('[name="degree"]').val(exp[0].split(' ')[0]);
// objectExtract(skills)
}

if(jobtype && $('[name="jobtype"]').val() === ''){

$('[name="jobtype"]').val(jobtype[0]);
// objectExtract(skills)
console.log($('[name="country"]').val())
	if(jobtype[0]==="Contract" && ['Europe','US','UK','India','Middle East'].includes($('[name="country"]').val())){
		console.log($('[name="country"]').val())
		$("#rfee").hide();
	}else{
		$("#rfee").show();
	}

	$.ajax({
            type: "POST",
            url: "get-data.php",
            data: { country : $('[name="country"]').val() } 
        }).done(function(data){
            $("#joblocations").html(data);
        });


}



  


}
    
  }
});

//console.log(formData)
})



$('#country').on('change',function(){


	if($('[name="jobtype"]').val()==="Contract" && ['Europe','US','UK','India','Middle East'].includes($('[name="country"]').val())){
	//	console.log($('[name="country"]').val())
		$("#rfee").hide();
	}else{
		$("#rfee").show();
	}
	$.ajax({
            type: "POST",
            url: "get-data.php",
            data: { country : $('[name="country"]').val() } 
        }).done(function(data){
            $("#joblocations").html(data);
        });
});

$('#jtype').on('change',function(){


if($('[name="jobtype"]').val()==="Contract" && ['Europe','US','UK','India','Middle East'].includes($('[name="country"]').val())){
//	console.log($('[name="country"]').val())
	$("#rfee").hide();
}else{
	$("#rfee").show();
}

        // $.ajax({
        //     type: "POST",
        //     url: "get-data.php",
        //     data: { country : $('[name="country"]').val() } 
        // }).done(function(data){
        //     $("#joblocations").html(data);
        // });


});




		$(function(){
			$('.datepicker').datepicker({
			  dateFormat: 'yy-mm-dd'
			});
		});
			/*$('#joblocation').autocomplete({
				source: 'search.php',
				multiselect: true
			}); */
			/*** $('#jobpost').on('change', 'select[name="maxlakh"]', function(){
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
		
		}//end minMaxValues function***/
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
	var minex=document.getElementsByName("minex")[0].value;
	var maxex=document.getElementsByName("maxex")[0].value;
	var scountry=document.getElementsByName("country")[0].value;
	var jtype=document.getElementsByName("jobtype")[0].value;
	if((((minex==="0") && (maxex==="2")) ||((minex==="0") && (maxex==="1"))||((minex==="1") && (maxex==="2"))) && (scountry==="Indiaa")&&(jtype==="Permanent")){
	
	$('#radio').show();
	$("#limit").show();
	//$("#rfee").hide();
	//$('#rfee').removeAttr('selected');
	$("#rfee").hide();
	$("#rfee1").hide();
	 
	}
	else if((((minex==="0") && (maxex==="2")) ||((minex==="0") && (maxex==="1"))||((minex==="1") && (maxex==="2"))) && (scountry==="Indiaa")&&(jtype==="Contract")){
	
	$('#radio1').show();
	$("#limit").show();
	//$("#rfee").hide();
	//$('#rfee').removeAttr('selected');
	$("#rfee").hide();
	$("#rfee1").hide();
	 
	}
	else if((((minex==="0") && (maxex==="2")) || ((minex==="0") && (maxex==="1"))||((minex==="1") && (maxex==="2"))) && (scountry!="Indiaa")){
	$("#limit").show();
	$("#rfee").show();
	//$('#rfee').removeAttr('selected');
	//$("#rfee1").hide();	
	}
	else{
		$("#limit").show();

		//$("#limit").hide();
	//$("#rfee").show();
	//$('#rfee').removeAttr('selected');
	$("#rfee1").hide();

	}
	});
	
	$(document).on("change","input[name='feecheck']",function(){
	
  
   	var feecheck=$('[name="feecheck"]:checked').val();
	
	 if(feecheck==='pfee'){
	 
	 $("#rfee").show();
		 $("#rfee1").hide();
         $('[name="fee"]').prop('required',true);
		 var fee=document.getElementsByName("fee")[0].value;
		 document.getElementsByName("fee")[0].value(fee);
    
	 // document.getElementsByName("rfee")[0].value('');
	 }
	 
	 
	 else {	 
	
	 $("#rfee1").show();
		 $("#rfee").hide();
         $('[name="fee"]').prop('required',false);
		 var rfee=document.getElementsByName("rfee")[0].value;
		  document.getElementsByName("rfee")[0].value(rfee);
        //  $('[name="fee"]').removeAttr('required')
	  //document.getElementsByName("fee")[0].value('');
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
		//$("#rfee").prop('required',true); 
		//$('#rfee').removeAttr('selected');
		$("#rfee1").hide();

        }
	 else if(( $(this).val()!="India")){
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
	
	 if( ($(this).val()==="Contract" && (scountry=="UK"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		 document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById('dayrate').innerHTML = 'End Rate (E.g $80 - $90/hour or $400 - $450/day)';
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
		document.getElementById('dayrate').innerHTML = 'End Rate (E.g $80 - $90/hour or $400 - $450/day)';
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
		$('#oc').append("<option value='PLN'>PLN (Polish zloty)</option>");
		$('#oc').append("<option value='BGN'>BGN (Bulgarian Lev)</option>");
		}
	else if( ($(this).val()==="Contract" && (scountry=="Europe"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='EURO'>EURO</option>");
		$('#oc').append("<option value='SEK'>SEK (Swedish Krona)</option>");
		$('#oc').append("<option value='CHF'>CHF (Swiss Franc)</option>");
		$('#oc').append("<option value='PLN'>PLN (Polish zloty)</option>");
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='VND'>VND (Vietnamese Dong)</option>");
		
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='PHP'>PHP (Philippine Peso)</option>");
		
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		$('#oc').append("<option value='BRL'>BRL (Brazilian real)</option>");
		
		}
	else if( ($(this).val()==="Contract" && (scountry=="South America"))){
	
		$("#indiacurrency").hide();
		$("#othercurrency").hide();
		$("#othercurrency1").show();
		$("#rfee").hide();
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
		$('#oc').empty("<option value='GBP'>GBP (British Pound)</option>");
		$('#oc').empty("<option value='USD'>USD (American Dollar)</option>");
		$('#oc').append("<option value='BRL'>BRL (Brazilian real)</option>");
		
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		document.getElementById("rfee").innerHTML = "";
		$("#one").hide(); 
		$("#two").show();
		document.getElementById('dayrate').innerHTML = 'End Rate';
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
		  $("#ratetype").hide();
		 }
	else if	( ($(this).val()==="Permanent") && (scountry=="India")){
	$("#indiacurrency").show();
		$("#othercurrency").hide();
		$("#one").show();
		$("#two").hide();
	} 
	
    else {
		 
		$("#indiacurrency").hide();
		$("#othercurrency").show();
		$("#othercurrency1").hide();
		$("#one").hide();
		$("#two").show();
		}
	});
	
	$(function(){
  $('#jobpost').submit(function(){
    $("input[type='submit']", this)
      .val("Please wait while the job is getting posted. Normally takes 1-2 mins to load.")
    return true;
  });
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
      <script>
	  $(document).ready(function(){
    $("#country").change(function(){
	
        var selectedCountry = $("#country option:selected").val();
        $.ajax({
            type: "POST",
            url: "get-data.php",
            data: { country : selectedCountry } 
        }).done(function(data){
            $("#joblocations").html(data);
        });
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
