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
$content='';
if(isset($_POST['submit'])){
require_once 'insert-candidate.php';
}
$countryarray=array();
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
$countryres=mysqli_query($link,"select * from country");
$countrypresent = mysqli_num_rows($countryres);
if($countrypresent > 0){ 
while($row = mysqli_fetch_array($countryres)){ 
$countryarray[]=$row;
}
}
 ?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Add New Candidate | Recruiters Hub</title>
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
	   /**if($rcountry=='India'){echo'
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
    <li><a href="disengaged" role="tab"><i class="fa fa-scissors"></i>CLOSED/DISENGAGED</a>  
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
<li><a href="hire-resource" role="tab" > <i class="fa fa-database"></i>Bench Hiring </a> </li>
    <h4>Jobseeker Section (Jobsite)</h4>

<li><a href="candidate-search" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> CV Search </a>    </li>
    <li><a href="post-job" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Post Jobs </a>    </li>
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
        
    
    <a href="https://recruitinghub.com/RighttoRepresent.docx" target="_blank"><b>Download Right to Represent</b> (Not Mandatory)</a> <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Take Right-to-Represent consent from candidate. Win client's duplicate issue, take consent from Candidate now (NOT MANDATORY)"><i class="fa fa-question-circle"></i>
    </span>
    <h3 class="panel-title"> Add New Candidate (Step 1) | You need to then submit the candidate to an engaged job (Final step)  </h3>
  </header>
        <div class="panel-body">
<?php if($content) echo $content; else{ ?>
<form action="add_new" method="post" enctype="multipart/form-data">
    
<div class="form-group col-sm-12">
<label class="col-sm-2">Upload CV <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Our New Parsing Technology will auto fill details in below columns once you upload CV"><i class="fa fa-question-circle"></i>
    </span> (*)</label>
<div class="col-sm-10">
<input name="cv" type="file" id="resume" required>
<p class="help-block">Please AVOID (') in profile file name while saving the file |  Allowed file types: PDF, Word Documents, Max File Size: 4 MB</p>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Candidate Name (*)</label>
<div class="col-sm-10">
<input class="form-control" required  placeholder="e.g George Thomas" name="fname" type="text">
<p class="help-block"><b>Is the above "Candidate Name" looking good? Ensure Correct Candidate Name</b></p>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Candidate Email (*)</label>
<div class="col-sm-10">
<input class="form-control" required  placeholder="e.g george.thomas@gmail.com" name="email" type="email">
<p class="help-block"><b>Is the above "Candidate Email id" looking good? Ensure Correct Candidate Email id</b></p>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Contact number (*)</label>
<div class="col-sm-10">
<input class="form-control" required  placeholder="e.g +44 07473788874" name="contactnumber" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Current Employer (*)</label>
<div class="col-sm-10">
<input class="form-control" required  placeholder="e.g Tesco Ltd" name="currentemployer" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Current Job Title (*)</label>
<div class="col-sm-10">
<input class="form-control" required  placeholder="e.g Java Developer" name="currentjobtitle" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Total Experience (*)</label>
<div class="col-sm-10">
<div class="col-sm-3 no-padding">
<input class="numeric integer required form-control" required placeholder="Years (e.g 4)" name="minex" type="text">
</div>
<div class="col-sm-3">
<input class="numeric integer required form-control" required placeholder="Months (e.g 6)" name="maxex" type="text">
</div>
<div class="col-sm-2 no-padding">
In Years
</div>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Job Type (*) </label>
<div class="col-sm-3">
<select class="form-control" required="required" id="jobtype" name="jobtype">
<option value="">Job Type</option>
<option value="Permanent">Permanent</option>
<option value="Contract">Contract</option>
</select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Current Salary/Rate (*) </label>
<div class="col-sm-3">
<input class="form-control" required placeholder="in hundreds or thousands" name="currentsalary" type="number">
<p class="help-block">e.g.400 or 80000 </p>
</div>
<div class="col-sm-3">
<select class="form-control" required="required" id="currency1" name="currentcurrency">
<option value="">Currency</option>
<option value="GBP £">GBP £</option>
<option value="EURO €">EURO €</option>
<option value="USD $">USD $</option>
<option value="Rs.">Rs.</option>
<option value="AED">AED</option>
<option value="SGD S$">SGD S$</option>
<option value="CHF SFr.">CHF SFr.</option>
<option value="EGP E£">EGP E£</option>
<option value="SEK kr">SEK kr</option>
<option value="PLN zl">PLN zl</option>
<option value="BGN">BGN</option>
<option value="CAD $">CAD $</option>
<option value="MXN">MXN</option>
<option value="BRL">BRL</option>
<option value="KRW">KRW</option>
<option value="MYR">MYR</option>
<option value="SAR">SAR</option>
<option value="AUD">AUD</option>
<option value="ZAR">ZAR</option>
</select>
</div>
<div class="col-sm-3">
<select class="form-control" required="required" name="typesalary" id="typesalary">
<option value="">Duration</option>
<option value="Per Year">Per Year </option>
<option value="Per Hour">Per Hour </option>
<option value="Per Day">Per Day </option>
<option value="Per Month">Per Month </option>
</select>
</div>
</div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Exp Salary/End Rate (*) </label>
<div class="col-sm-3">
<input class="form-control" required name="desiredsalary" placeholder="in hundreds or thousands" type="number"><p class="help-block">e.g.600 or 100000 (for CONTRACT role submit end rate all inclusive to client (include our average markup of 20%)</p>
</div>
<div class="col-sm-3">
<select class="form-control" id="currency2" name="expectedcurrency">
<option value="">Currency</option>
<option value="GBP £">GBP £</option>
<option value="EURO €">EURO €</option>
<option value="USD $">USD $</option>
<option value="Rs.">Rs.</option>
<option value="AED">AED</option>
<option value="SGD S$">SGD S$</option>
<option value="CHF SFr.">CHF SFr.</option>
<option value="EGP E£">EGP E£</option>
<option value="SEK kr">SEK kr</option>
<option value="PLN zl">PLN zl</option>
<option value="BGN">BGN</option>
<option value="CAD $">CAD $</option>
<option value="MXN">MXN</option>
<option value="BRL">BRL</option>
<option value="KRW">KRW</option>
<option value="MYR">MYR</option>
<option value="SAR">SAR</option>
<option value="AUD">AUD</option>
<option value="ZAR">ZAR</option>
</select>
</div>
<div class="col-sm-3">
<select class="form-control" required="required" name="typesalary1" id="typesalary1">
<option value="">Duration</option>
<option value="Per Year">Per Year </option>
<option value="Per Hour">Per Hour </option>
<option value="Per Day">Per Day </option>
<option value="Per Month">Per Month </option>
</select>
</div>
</div>

<div class="form-group col-sm-12">  
<label class="col-sm-2"> Current Country (*)</label>
<div class="col-sm-10">
<select name="country" input class="form-control" name="country" required="true">
<option value="">Select Candidate Country (*)</option> 
<?php
foreach($countryarray as $row){ 
echo '<option value="'.$row['country'].'">'.$row['country'].'</option>';
}
?>                        
</select>
</div></div>

<div class="form-group col-sm-12">  
<label class="col-sm-2"> Current City (*)</label>
<div class="col-sm-10">
<input class="form-control" required aria-required="true" name="location" placeholder="e.g London" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Nationality (*)</label>
<div class="col-sm-10">
<input class="form-control" required name="nationality" placeholder="e.g British or Indian or American or Singaporean - for expats please specify the visa status e.g Tier2 General or H1B or PR" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Notice Period (*)</label>
<div class="col-sm-10">
<input class="form-control" required name="notice" placeholder="e.g 30 days or 2 Weeks or 1 Month" type="text">
</div></div>

<div class="form-group col-sm-12">
<div class="col-sm-2"></div>
<div class="checkbox col-sm-10">
 <label><input  value="1" name="relocate" type="checkbox"> Willing to relocate   </label>
 </div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Additional info</label>
 <div class="col-sm-10">
<textarea class="form-control" aria-required="true" name="additionalinfo" placeholder="type additional information about the Candidate that you may have gathered during your conversation with the candidate"></textarea>
</div></div>

<div class="form-group  uploadcv col-sm-12">
 						<p><strong>CV Copy Pasted in below box by default</strong></p>
          					<textarea rows="5" cols="80" class="form-control"  name="cvcopy"  required="true" placeholder="Please also copy & paste your candidate's full CV so it will be easy for employers to search for keywords from within the CV"></textarea>   
						</div>
						
<div class="form-group col-sm-12">
<label class="col-sm-2">Upload Video/additional file of Candidate (Not Mandatory)</label>
<div class="col-sm-10">
<input name="video" type="file" >
<p class="help-block">Allowed file types: MPEG,MP4,MOV,AVI,FLV or any video format. Video length not more than 5 minutes.</p>
</div></div>

 <div class="col-sm-12 form-group">
     <input type="checkbox" value="1" name="terms" required/>
     <label><small>I certify that I have spoken to this candidate and have explained about the job opportunity. Further, I have taken consent from the candidate and have no objection in uploading the CV on Recruiting Hub. Read our Privacy Policy here <a class="title" href="#" data-toggle="modal" data-target="#employer-policies">Privacy Policy</a>.</small><label>
     
               </div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<input name="submit" value="Add Candidate" class=" btn btn-primary" type="submit">
</div></div>
</form>
<?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="employer-policies" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="engage-modal-label">Privacy Policy</h4>
          </div>
          <div class="modal-body" id="engage-modal-body">
          <?php include_once 'privacy.php'; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>    
      
      
      
     </div>
     </div>
     </div>
<div class="clearfix"></div>
</div>
</div>

<script>


$('#resume').change((e)=>{

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

console.log(data);
if(data.length > 0){
  let result = JSON.parse(data);

  let {text,name,email,mobile,location,country,skills,exp} = result[0]

  if(text && $('[name=cvcopy]').val() === ''){
    $('[name=cvcopy]').val(text);
  }


  if(name && $('[name=fname]').val() === ''){
    $('[name=fname]').val(name);
  }
  if(mobile && $('[name=contactnumber]').val() === ''){
    $('[name=contactnumber]').val(mobile[0]);
  }
  if(email && $('[name="email"]').val() === ''){
    $('[name="email"]').val(email[0]);
  }
  if(location &&  $('[name="location"]').val() === ''){
    $('[name="location"]').val(location[0]);
  }
  if(country && $('[name="country"]').val() === ''){
    $('[name="country"]').val(country[0]);
  }

  // if(exp){

  //   $('[name="country"]').val(country[0]);
  //   objectExtract(skills)
  // }

  if(exp && $('[name="minex"]').val() === ''){



    $('[name="minex"]').val(exp[0].split(' ')[0]);
   // objectExtract(skills)
  }

  


}
    
  }
});

//console.log(formData)
})


$(document).ready(function(){
$("#currency1").change(function() {
    var id = $(this).val();
    $("#currency2").val(id);
 });  
$("#currency2").change(function() {
    var id1 = $(this).val();
    $("#currency1").val(id1);
 }); 
$("#typesalary").change(function() {
    var sid = $(this).val();
    $("#typesalary1").val(sid);
 });  
$("#typesalary1").change(function() {
    var sid1 = $(this).val();
    $("#typesalary").val(sid1);
 });   
 });
</script>

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>
</html>
