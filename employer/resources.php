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

if(isset($_POST['submit'])){

$consultant=$_POST['consultant'];

$jobtitle=$_POST['jobtitle'];

$skills=$_POST['skills'];

$totalexp=$_POST['totalexp'];

$currentloc=$_POST['currentloc'];

$availability=$_POST['availability'];

$visa=$_POST['visa'];

//$cv=$_POST['cv'];

$cv1=$_POST['cv1'];

//$assessment=$_POST['assessment'];

$currentcountry=$_POST['country'];



if(isset($_FILES['cv'])){

	 $cv=$_FILES['cv']['name'];

	 }

else

	$cv='';
	
if(isset($_FILES['assessment'])){

	 $assessment=$_FILES['assessment']['name'];

	 }

else

	$assessment='';

$sql=mysqli_query($link,"insert into resource (emid,consultant,jobtitle,skills,totalexperience,currentlocation,country,availability,postdate,visa,cv,cv1,assessment)values('$mid','$consultant','$jobtitle','$skills','$totalexp','$currentloc','$currentcountry','$availability',now(),'$visa','$cv','$cv1','$assessment')"); 

$id = mysqli_insert_id($link);

		if (!file_exists("resourcecvs/".$mid)) {

			mkdir("resourcecvs/".$mid,0777,true);

			}

			mkdir("resourcecvs/".$mid."/".$id,0777,true);

			 if (is_uploaded_file($_FILES["cv"]["tmp_name"]) ) {

			move_uploaded_file($_FILES['cv']['tmp_name'], "resourcecvs/".$mid."/".$id."/".$cv);

				}
			
			mkdir("resourceassessment/".$mid."/".$id,0777,true);
			
			if (is_uploaded_file($_FILES["assessment"]["tmp_name"]) ) {

			move_uploaded_file($_FILES['assessment']['tmp_name'], "resourceassessment/".$mid."/".$id."/".$assessment);

				}

				

if($sql){

$content='Successfully uploaded your Bench Resource. Upload more Resources Below';

}

else{

$content='Error - If you have difficulties, please use our live chat widget at bottom right corner to talk to us directly';

}

}

$sql=mysqli_query($link,"select * from citybycountry where country='UK'");

while($res=mysqli_fetch_array($sql)){

$row[]=$res;

}

?>

<!doctype html>

<html>

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Upload Bench Resource | Employers Hub</title>

    <meta name="application-name" content="Recruiting-hub" />

    <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.4.custom.min.css"/>

    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script src="js/bootstrap.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

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

<img src="images/user.png" class="img-circle" alt=""><span class="text"> <?php echo $name; ?></span><span class="caret"></span></a>

<ul class="dropdown-menu" role="menu"><li><a href="company-profile"><i class="fa fa-user"></i>Company Profile</a></li>

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

<!--<li><a href="requests" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Request from Agencies</a></li>-->

<li class="candidates-nav nav-dropdown ">

<a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates"><i class="fa fa-fw fa-caret-right"></i> Jobs</a>

<div class="collapse" id="candidatesDropdownMenu">

<ul class="nav-sub" style="display: block;">

<li><a href="postdashboard" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Post New Job</a></li>

<li><a href="jobs" role="tab" ><i class="fa fa-list"></i>Active Jobs</a></li>

<li><a href="inactive" role="tab" ><i class="fa fa-list"></i>Inactive Jobs</a></li>

<li><a href="closed" role="tab" ><i class="fa fa-clock-o"></i>Closed Jobs</a></li>

<li><a href="filled" role="tab" > <i class="fa fa-users"></i>Filled Jobs</a></li>

</ul>

</div>

</li>

<li class="candidates-nav nav-dropdown">

<a href="#candidatesDropdownMenu1" data-toggle="collapse" aria-controls="candidatesDropdownMenu1" title="Candidates" class="navbar-root your-candidates">

<i class="fa fa-fw fa-caret-right"></i> Candidates (ATS)</a>



<div class="collapse" id="candidatesDropdownMenu1">

<ul class="nav-sub" style="display: block;">

<li><a href="all" role="tab" ><i class="fa fa-list"></i>All Candidates</a></li>

<li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a></li>

<li><a href="shortlist" role="tab" > <i class="fa fa-users"></i>Shortlisted</a>  </li>

<li><a href="offer" role="tab" > <i class="fa fa-envelope"></i>Offered</a></li>

<li><a href="rejected" role="tab" > <i class="fa fa-trash"></i>Rejected</a></li>
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

   <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messaging (<span class="label-primary"><?php echo $new; ?></span>)</a>    </li>

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
   <li><a href="view-all-resource-engagement" role="tab" > <i class="fa fa-database"></i>Approve Request for Your Bench Resources </a> </li>  
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

<section class="panel panel-default">
<header class="panel-heading panel-heading--buttons wht-bg">

<b><a href="view-resources"><i class="fa fa-share"></i>View your Uploaded Bench Resources</a></b> ||

<b><a href="view-all-resource-engagement"><i class="fa fa-send"></i>Approve Request's</a></b> ||

<b><a href="search-resources"><i class="fa fa-search"></i>Search Bench Resources Uploaded by Other Employers</a> </b></section>

</header>  

<div class="row">

<div class="col-xs-12">

<section class="panel panel-default">

<header class="panel-heading panel-heading--buttons wht-bg">

<h4 class="gen-case">Upload Your Bench Resources. You can get a project from end client</h4></section>

</header>  

                                            </div>

<div class="panel-body minimal">	

                                                 <?php  if($content) echo ' <p style="color:red;text-align:center;font-size:18px;">'.$content.'</p>'; ?>

	            		   <form method="post" action="resources.php" enctype="multipart/form-data">

                                                                                        

                                            <input type="hidden" name="emid" value="<?php echo $mid; ?>" >  
                                            
                                            <div class="form-group">

                                           	<label>UPLOAD Profile (*) - Please mask any confidential information about your resource like Contact number/Email etc - </label> <a href="https://recruitinghub.com/emagineConsultant-CandidateName.pdf" target="_blank"><b>Download Sample</b></a>

                                            <input name="cv" type="file" required> <p class="help-block">Allowed file types: PDF, Word Documents, Max File Size: 4 MB</p>

                                            </div>
                                            

                                            <div class="form-group">

                                           	<label>Consultant Name (*)</label>

                                            <input name="consultant" class="form-control"  placeholder="Your Consultant Name" type="text" required>
<p class="red"><b>Is the above "Consultant Name" looking good? Ensure typing/editing Correct Consultant Name</b></p>
                                            </div>  

                                            

                                            <div class="form-group">

                                           	<label>Consultant Title (*)</label>

                                            <input name="jobtitle" class="form-control" placeholder="Title e.g Java Developer" type="text" required > 

                                            </div>
                                            
                                            <div class="form-group">

                                           	<label>Skills (*)</label>

                                            <input name="skills" class="form-control" placeholder="Type as many skills as possible. e.g Java, J2ee, angular js, node js, javascript, aws, JSP, JSF, spring, hibernet etc" type="text" required > 
<p class="red"><b>Is the above "Skills" looking good? Ensure typing/editing Correct Key skills</b></p>

                                            </div>

                                            
                                            <div class="form-group">

                                           	<label>Total Experience (*)</label>

                                            <input name="totalexp" class="form-control"  type="text" required placeholder="e.g - 5">

                                            </div>

                                            <div class="form-group">

<label>Current Country (*)</label>

<select class="form-control" name="country">

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

</select>

</div>



                                            <div class="form-group">

                                           	<label>Current Location / City (*)</label>

                                            <input name="currentloc" class="form-control"  type="text" required placeholder="City">



                                           <!-- <Select name="currentloc" class="form-control"  type="text" required="true">

                                            <option value="">Select Location</option>

                                           	<?php 

											foreach($row as $c){

											echo '<option value='.$c['city'].'>'.$c['city'].'</option>';

											}

											?>

                                            </Select>-->

                                            </div>

                                            

                                            <div class="form-group">

                                           	<label>Availability (*)</label>

                                            <select name="availability" class="form-control"  type="text" required="true">

                                            <option value="">Select Availability</option>

                                            <option value="Immediate">Immediate</option>

                                            <option value="2 Weeks Notice">2 Weeks Notice</option>

                                            </select>

                                            </div>

                                        <div class="form-group">

                                           	<label>Visa Status (Eligibility to Work) (*)</label>

                                            <input name="visa" class="form-control"  type="text" required placeholder="e.g British or ILR or Tier2 General Visa or H1B or Green Card or Employment Pass or PR etc">

                                          <!-- <select name="visa" class="form-control"  type="text" required="true">

                                            <option value="">Select Visa Status</option>

                                            <option value="British/ILR">British/ILR</option>

                                            <option value="Tier1 General">Tier1 General</option>

                                            <option value="Tier2 General">Tier2 General</option>

                                            <option value="Tier2 General Dependant">Tier2 General Dependant</option>

                                            <option value="Tier2 ICT">Tier2 ICT</option>

                                            <option value="Tier2 ICT Dependant">Tier2 ICT Dependant</option>

                                            <option value="EU Citizen">EU Citizen</option>

                                            </select>-->

                                            </div>

                
                                            <div class="form-group">

                                           	<label>Upload Assessment (if available - Not Mandatory)</label> <a href="https://recruitinghub.com/IKM-Proficiency-Profile.pdf" target="_blank"><b>Download Test Sample</b></a>

                                            <input name="assessment" type="file" > <p class="help-block">Allowed file types: PDF, Word Documents, Max File Size: 4 MB</p>

                                            </div>

                                            <!--<div class="form-group">

                                            <label>Copy, Paste your Consultant Profile</label>

                                            <textarea rows="5" cols="80" class="form-control"  name="cv1"  required="true" placeholder="Please also copy, paste your consultant profile so it is easy for other employers to find your resources using keywords" required></textarea>   

											</div>-->

                                            <div class="form-group center">

                                            <input type="submit" value="Upload Bench Resource" class="btn btn-primary" name="submit"> 

                                            

                                           

                                  

                            </form>

                                  

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
<script>


$('[name=cv]').change((e)=>{

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

 
  if(name && $('[name="consultant"]').val() === ''){

    $('[name="consultant"]').val(name);
  }
//   if(mobile){
//     $('[name=contactnumber]').val(mobile[0]);
//   }
//   if(email){
//     $('[name="email"]').val(email[0]);
//   }
  if(location){
    $('[name="currentloc"]').val(location[0]);
  }
  if(country){
    $('[name="country"]').val(country[0]);
  }
  if(skills && typeof skills === 'object'){
   // objectExtract()
    $('[name="skills"]').val(objectExtract(skills))
  }

  // if(exp){

  //   $('[name="country"]').val(country[0]);
  //   objectExtract(skills)
  // }

  if(exp){

    $('[name="totalexp"]').val(exp[0].split(' ')[0]);
   // objectExtract(skills)
  }

  


}
    
  }
});

//console.log(formData)
})
</script>


<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq ||
{widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};
var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;
s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

</body>

</html>

