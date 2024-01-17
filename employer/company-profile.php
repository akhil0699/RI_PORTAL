<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$cid=$_SESSION['cid'];
$ecountry=$_SESSION['country'];
}
require_once '../config.php';
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];

$content='';
$companyres = mysqli_query($link,"SELECT a.id as id,a.name as companyname, a.sectors as sectors,a.profile as companyprofile,a.logo as logo,a.address1,a.address2,a.address3,a.town,a.city,a.postcode,a.phone,a.description,a.website,a.country,a.state,b.firstname as name, b.designation as designation,b.photo as photo,b.location as location,b.experience as experience,c.accountmanager as accountmanager FROM companyprofile a,employers c,members b WHERE a.id= b.companyid and b.memberid='$mid'"); 
$issubmitted = mysqli_num_rows($companyres);
if($issubmitted > 0){ 
    while($row = mysqli_fetch_array($companyres)){ 
		$company=$row;
				}
}
else
$content="No data.";


?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Company Profile | Employers Hub</title>
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
  <?php if($content) echo $content; else{?>
	<section class="main-content-wrapper">
      <div class="pageheader pageheader--buttons">
          <div class="row">
                 <div class="col-md-12">
                 <a class="btn btn-primary" href="edit-company">
    <i class="fa fa-briefcase"></i>  Edit your Company Profile
</a>
<a class="btn btn-primary" href="edit-profile"> <i class="fa fa-pencil"></i> Edit your profile </a>
<a class="btn btn-primary" href="filled"> Filled Jobs </a>
<a class="btn btn-primary" href="https://www.recruitinghub.com/bankdetails" target=_blank> RH Bank Details </a>   
                              
            </div>
          </div>
     	  </div>
		</section>

    <div class="col-md-7 ui-sortable no-padding">
      

	<div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
        <h3 class="panel-title"> Company Profile | Company ID - <?php echo $company['id']; ?> </h3>
      </header>
      <div class="panel-body">
              <div class="row results-table-filter">
                <div class="col-md-12">               
                     <?php 
					 $companyprofile= htmlspecialchars_decode($company['companyprofile'],ENT_QUOTES);
					  if($company['logo'])
					 echo'
					 <img src="logo/'.$company['id'].'/'.$company['logo'].'" width="200px"  height="200px"/><br>
					 '; ?>
                     
                  </div>
                  <div class="col-md-12">               
                     <p><?php echo $companyprofile; ?></p>
                  </div>
           
                </div>
       </div>
       
	   </div>

<div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
        <h3 class="panel-title"> Company Details </h3>
      </header>
      <div class="panel-body">
    
   <div class="col-md-12">
  <small><span class="title text-center">Company Name :  <?php echo $company['companyname']; ?></span></small>
  <hr>
  </div>
  
 <!-- <div class="col-md-12">
  <small><span class="title text-center">Description :  <?php echo $company['companyprofile']; ?></span></small>
  <hr>
  </div>-->
  
  <div class="col-md-12">
  <small><span class="title text-center">Address : <?php echo $company['city']; ?>,<?php echo $company['state']; ?>,<?php echo $company['country']; ?></span></small>
  <hr>
  </div>
  
  <!--<div class="col-md-12">
  <small><span class="title text-center">Country:  <?php echo $company['country']; ?></span></small>
  <hr>
  </div>
  
  <div class="col-md-12">
  <small><span class="title text-center">State:  <?php echo $company['state']; ?></span></small>
  <hr>
  </div>
  
  <div class="col-md-12">
  <small><span class="title text-center">City:  <?php echo $company['city']; ?></span></small>
  <hr>
  </div>
  
   <div class="col-md-12">
  <small><span class="title text-center">Post Code / Zip Code / Pin Code :  <?php echo $company['postcode']; ?></span></small>
  <hr>
  </div>-->
  
   <div class="col-md-12">
  <small><span class="title text-center">Phone number :  <?php echo $company['phone']; ?></span></small>
  <hr>
  </div>
   <div class="col-md-12">
  <small><span class="title text-center">Website :  <?php echo $company['website']; ?></span></small>
  <hr>
  </div>

</div>
       
	   </div>
 </div> 
  
    <div class="col-md-5 column ui-sortable no-padding-right">
    
 

<div class="panel panel-default widget-mini" id="account_statistic">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> Personal Details  </h3>
  </header>
  <div class="panel-body">
    
   <div class="col-md-12">
  <small><span class="title text-center">Your Name :  <?php echo $company['name']; ?></span></small>
  <hr>
  </div>
  <div class="col-md-12">
  <small><span class="title text-center">Job Title :  <?php echo $company['designation']; ?></span></small>
  <hr>
  </div>
  <div class="col-md-12">
  <small><span class="title text-center">Location :  <?php echo $company['location']; ?></span></small>
  <hr>
  </div>
  <div class="col-md-12">
  <small><span class="title text-center">Country :  <?php echo $company['country']; ?></span></small>
  <hr>
  </div>
  
  <!--<div class="col-md-12">
  <small><span class="title text-center">Years Recruitment Experience :  <?php echo $company['experience']; ?></span></small>
  <hr>
  </div>-->
  
   <div class="col-md-12">
  <small><span class="title text-center">Sector :  <?php echo $company['sectors']; ?></span></small>
  <hr>
  </div>
  
  <!--<div class="col-md-12">
  <small><span class="title text-center"><b>Your Account Manager</b> :  <?php echo $company['accountmanager']; ?></span></small>
  <hr>
  </div>-->
  
  <!--<div class="col-md-12">
  <small><span class="title text-center">Terms:  <?php echo $company['replacementperiod']; ?></span></small>
  <hr>
  </div>-->
  

</div>
</div>       

<div class="panel panel-default widget-mini" id="account_statistic">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> Our Standard Payment Terms (Unless otherwise agreed)  </h3>
  </header>
  <div class="panel-body">
    
   <div class="col-md-12">
  <small><span class="title text-center"><b>Placement fee % for Permanent</b> :  As decided by you while posting the job (ranging between 7%-25%)</span></small>
  <hr>
  </div>
  
  <div class="col-md-12">
  <small><span class="title text-center"><b>Payment date</b> :  30 days from the date of candidate joining your company</span></small>
  <hr>
  </div>
  
  <div class="col-md-12">
  <small><span class="title text-center"><b>One time free replacement guarantee period</b> :  60 days from the date of candidate joining</span></small>
  <hr>
  </div>
  
  <div class="col-md-12">
  <small><span class="title text-center"><b>Make a Payment</b> :  <i class="address-text"></i><a href="https://www.recruitinghub.com/bankdetails" target="_blank">Click here for our bank details</a></span></small>
  <hr>
  </div>
 
 </div>
     </div> 
  

     <div class="clearfix"></div>
     </div>
     </div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
</div>

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || 
{widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};
var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;
s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>
</html>
