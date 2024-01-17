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
if(isset($_POST['submit'])){
require_once 'insert-recruiterjobs.php';
}
$res=mysqli_query($link,"select sector from jobsectors");
if(mysqli_num_rows($res)){
	while($row=mysqli_fetch_assoc($res)){
		$sector[]=$row;
	}
}
$compname=mysqli_query($link,"select b.name from members a,companyprofile b where a.companyid=b.id and a.memberid='$mid'");
if(mysqli_num_rows($compname)){
	while($rows=mysqli_fetch_assoc($compname)){
		$autocname=$rows;
	}
}

 ?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Post Jobs | Recruiters Hub</title>
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
    <li><a href="disengaged" role="tab"><i class="fa fa-fw fa-files-o"></i>CLOSED / DISENGAGED</a>    </li>
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
    <h3 class="panel-title"> Recruiting for Clients? Post Jobs on our Jobsite for Free | Also Search our Free CV Database from this link -> <a href="candidate-search">CV Search</a></h3>
  </header>
        <div class="panel-body">
<?php if(!$content) {?>
<form method="post" action="post-job" enctype="multipart/form-data" id="jobpost">
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
<label class="col-sm-4">Country</label>
<div class="col-sm-8">
<select class="form-control" required="required" name="country" id="country" onChange="get_states();">
<option value="">Select Country</option>
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

</select>
</div></div>


<div class="form-group col-sm-12" >
<label class="col-sm-4">Location</label>
<div class="col-sm-8" required="required" id="joblocations" >

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
<label class="col-sm-4">Email id (where you want to receive responses)</label>
<div class="col-sm-8">
<input class="form-control" required name="contactemail" type="email">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-4">Contact Number</label>
<div class="col-sm-8">
<input class="form-control" name="contactnumber" type="text">
</div></div>



<div class="form-group col-sm-12" >
<label class="col-sm-4">Job type</label>
<div class="col-sm-8">
<select class="form-control" required name="jobtype" id="jobtype">
<option selected="selected" value="Permanent">Permanent</option>

<option value="Contract">Contract</option></select>
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4"> Notice Period (in days)</label>
<div class="col-sm-8">
<input class="numeric integer required form-control" required placeholder="How soon are you looking to close this position? e.g 30 days" name="notice" type="text">
</div>
</div>-->

<div class="form-group col-sm-12">
<label class="col-sm-4"> Agency Name</label>
<div class="col-sm-8">
<input class="numeric integer required form-control" value="<?php echo $autocname['name']; ?>" name="companyname" type="text" readonly>
</div>
</div>

<div class="form-group col-sm-12">
<label class="col-sm-4"> Total Experience (in years)</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<input class="numeric integer required form-control" required placeholder="Min" name="minex" type="text">
</div>
<div class="col-sm-3">
<input class="numeric integer required form-control" required placeholder="Max" name="maxex" type="text">
</div>
<div class="col-sm-2 no-padding">

</div>
</div></div>

<div class="form-group col-sm-12" id="indiacurrency" >
<label class="col-sm-4">Currency</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<select class="form-control" name="indiacurrency" type="text">
<option value="INR">INR</option>
</select>
</div>
</div></div>

<div class="form-group col-sm-12" id="othercurrency">
<label class="col-sm-4">Currency</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<select class="form-control" name="othercurrency"  type="text">
<option value="GBP">GBP</option>
<option value="AED">AED</option>
<option value="INR">INR</option>
<option value="EURO">EURO</option>
<option value="USD">USD</option>
<option value="SAR">SAR (Saudi riyal)</option>
<option value="QAR">QAR (Qatari riyal)</option>
<option value="OMR">OMR (Omani rial)</option>
<option value="BHD">BHD (Bahraini dinar)</option>
<option value="KWD">KWD (Kuwaiti Dinar)</option>
<option value="CAD">CAD (Canadian dollar)</option>
<option value="AUD ">AUD (Audtralian dollar)</option>
<option value="JPY">JPY (Japanese Yen)</option>
<option value="MYR">MYR (Malaysian ringgit)</option>
<option value="SGD">SGD (Singapore dollar)</option>
<option value="IDR">IDR (Indonesian rupiah)</option>
<option value="CNY">CNY (China Yuan Renminbi)</option>
<option value="KRW">KRW (Korean won)</option>
<option value="RUB">RUB (Russian ruble)</option>
<option value="NZD">NZD (New Zealand dollar)</option>
<option value="HKD">HKD (Hong Kong dollar)</option>
<option value="THB">THB (Thai baht)</option>
<option value="PHP">PHP (Philippine peso)</option>
<option value="MAD">MAD (Moroccan Dirham)</option>
<option value="EGP">EGP (Egyptian Pound)</option>
<option value="SEK">SEK (Swedish Krona)</option>
<option value="CHF">CHF (Swiss Franc)</option>
<option value="ZAR">ZAR (South African Rand)</option>
<option value="ETB">ETB (Ethiopian Birr)</option>
<option value="PLN">PLN (Polish zloty)</option>
<option value="BGN">BGN (Bulgarian Lev)</option>
<option value="BRL">BRL (Brazilian real)</option>
<option value="MXN">MXN (Mexican Peso)</option>
<option value="VND">VND (Vietnamese Dong)</option>
</select>
</div>
</div></div>

<div class="form-group col-sm-12" id="one">
<label class="col-sm-4">  Salary Range / Rate</label>
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
<label class="col-sm-4" id="twolabel"> Salary Range / Rate</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
<input class="numeric integer required form-control"  placeholder="Min e.g 300" name="from" type="text">
</div>
<div class="col-sm-3">
<input class="numeric integer required form-control"  placeholder="Max e.g 400" name="to" type="text">
</div>
 <div class="col-sm-2 no-padding" id="eg">
</div>
</div></div>

<div class="form-group col-sm-12" id="ratetype" >
<label class="col-sm-4" id="ratetype">Salary Duration</label>
<div class="col-sm-8">
<div class="col-sm-3 no-padding">
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
<textarea class="form-control" required rows="6" placeholder="copy & paste job description here" name="description"></textarea>
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
<textarea class="form-control" aria-required="true" name="keyskills"></textarea>
<p class="help-block">E.g Java, Autocad, Piping Engineer, Investment banking etc. </p>
</div></div>

<!--<div class="form-group col-sm-12">
<label class="col-sm-4"> Education</label>
 <div class="col-sm-8">
 <input class="form-control" required  name="degree" type="text">

</div></div>-->

<!--<div class="form-group col-sm-12">
<label class="col-sm-4">Consider relocation?</label>
 <div class="col-sm-8">
<input class="boolean optional" value="1" name="considerrelocation" type="checkbox" />
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
        <input class="datepicker" name="closingdate" placeholder="YYYY-MM-DD" type="text" />
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
	
	<?php for($c=1;$c<21;$c++){ ?>
	<option value="<?php echo $c; ?>"><?php echo $c; ?></option>
	<?php } ?>
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
	$(function(){
  $('#jobpost').submit(function(){
    $("input[type='submit']", this)
      .val("Please Wait while the job is getting posted on our Jobsite")
    return true;
  });
});
	 });
 </script>	 

 <script>
 $('#jobtype').on('change',function(){
var e = document.getElementById("country");
var country = e.options[e.selectedIndex].text;
 
 if( ($(this).val()==="Contract") && (country=="UK") ){
  document.getElementById('twolabel').innerHTML = 'Rate';
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
function get_states() {
 // Call to ajax function
    var country = $('#country').val();
 
    $.ajax({
	 
        type: "POST",
        url: "get-city.php", // Name of the php files
        data: 'EID=' +country,
        success: function(html)
        {
		$("#joblocations").html(html);
        }
    });
}
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
