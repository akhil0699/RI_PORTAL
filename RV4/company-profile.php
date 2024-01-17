<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$adminrights=$_SESSION['adminrights'];
$cid=$_SESSION['cid'];
$admin=$_SESSION['admin'];
$rcountry=$_SESSION['country'];


}
require_once '../config.php';
$erows=array();
$sql=mysqli_query($link,"select * from companyprofile ");
while($erow=mysqli_fetch_array($sql)){
	$erows[]=$erow;
}
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
$res=mysqli_query($link,"select sector,id from jobsectors");
if(mysqli_num_rows($res)){
	while($row=mysqli_fetch_assoc($res)){
		$sector[]=$row;
	}
}
$content='';
$companyres = mysqli_query($link,"SELECT a.id as id,a.name as companyname, a.sectors as sectors,a.profile as companyprofile,b.firstname as name,a.address1,a.address2,a.address3,a.city,a.postcode,a.phone,a.benname,a.benbank,a.bensort,a.benacc,a.benswift,a.taxinfo,a.description,a.logo as logo,a.website,a.country,a.state, b.designation as designation,b.location as location,b.experience as experience FROM companyprofile a,members b WHERE a.id= b.companyid and b.memberid='$mid'"); 
$issubmitted = mysqli_num_rows($companyres);
if($issubmitted > 0){ 
    while($row = mysqli_fetch_array($companyres)){ 
		$company=$row;
				}
}
else
$content="No data.";
$tit= htmlspecialchars_decode($company['companyname'],ENT_QUOTES);
						$urlpart=str_replace(' ','-', strtolower ("$tit"));
						$urlpart=preg_replace('/[^A-Za-z0-9\-]/', '',$urlpart);
						$urlpart=str_replace('--','-', $urlpart);
?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Recruitment Agency Profile | Recruiters Hub</title>
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
<li><a href="hire-resource" role="tab" > <i class="fa fa-database"></i>Bench Hiring </a> </li>
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
  <?php if($content) echo $content; else{?>
	<section class="main-content-wrapper">
      <div class="pageheader pageheader--buttons">
          <div class="row">
                 <div class="col-md-12">
                 
       
             <!--  Go to ->      <a href="edit-profile" id="one"><b><i class="fa fa-briefcase"></i> EDIT RECRUITMENT AGENCY PROFILE</b></a> || <a href="edit-profile" id="two"><i class="fa fa-edit"></i> <b>EDIT YOUR PERSONAL PROFILE</b></a>   ||   <a href="<?php echo '../agency/'.$urlpart.'/'.$mid.'' ?>" target="_blank" id="three"><i class="fa fa-share-alt"></i> <b>VIEW & SHARE YOUR RECRUITER PROFILE</b></a>   ||   <a href="becomefranchise" id="four"><i class="fa fa-hand-o-right"></i> <b>BECOME OUR FRANCHISE PARTNER</b></a>   ||   <a href="https://www.recruitinghub.com/subsidiaryapply" target="_blank" id="five"><i class="fa fa-copyright"></i> <b>LET's START IN YOUR COUNTRY, BECOME OUR FOREIGN SUBSIDIARY</b></a> 
                     </div>-->
                     
                
       <a class="btn btn-primary" href="<?php echo '../agency/'.$urlpart.'/'.$mid.'' ?>" target="_blank" ><i class="fa fa-share-alt"></i> View & Share your Vendor Profile
</a>        

<a class="btn btn-primary" href="<?php echo 'https://www.recruitinghub.com/recruiter/becomefranchise' ?>" ><i class="fa fa-hand-o-right"></i> Become our Franchise Partner
</a>     

<a class="btn btn-primary" href="<?php echo 'https://www.recruitinghub.com/recruiter/becomesubsidiary' ?>"  > <i class="fa fa-copyright"></i> Let's start in your Country. Become our Foreign Subsidiary
</a> 

       <!--<a class="btn btn-primary" href="<?php echo 'badge.png' ?>" target="_blank" >Download our badge to publish on your agency website
</a> -->        
       <!--<a class="btn btn-primary" href="<?php echo 'badge-7.png' ?>" target="_blank" >Badge2
</a> -->
            </div>
          </div>
     	  </div>
		</section>

    <div class="col-md-7 ui-sortable no-padding">
      

	<div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
        <h3 class="panel-title"> Recruitment Vendor Profile | Agency ID - <?php echo $company['id']; ?> <a class="btn btn-primary" href="edit-company"> <i class="fa fa-edit"></i> Edit </a></h3>
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
                     <?php echo $company['companyprofile']; ?>
                  </div>
           
                </div>
       </div>
       
	   </div>



   
  
  <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
        <h3 class="panel-title"> Recruitment Agency Details </h3>
      </header>
      <div class="panel-body">
    
   <div class="col-md-12">
  <span class="title text-center"><b>Recruitment Agency Name</b> :  <?php echo $company['companyname']; ?></span>
  <hr>
  </div>
  <div class="col-md-12">
 <span class="title text-center"><b>Address</b> :  <?php echo $company['city']; ?>, <?php echo $company['state']; ?>, <?php echo $company['country']; ?> </span>
  <hr>
  </div>
  
  <!--<div class="col-md-12">
  <small><span class="title text-center">City:  <?php echo $company['city']; ?></span></small>
  <hr>
  </div>
   <div class="col-md-12">
  <small><span class="title text-center">Post Code / Zip Code / Pin Code :  <?php echo $company['postcode']; ?></span></small>
  <hr>
  </div>
  
   <div class="col-md-12">
  <small><span class="title text-center">State:  <?php echo $company['state']; ?></span></small>
  <hr>
  </div>
  
  <div class="col-md-12">
  <small><span class="title text-center">Country:  <?php echo $company['country']; ?></span></small>
  <hr>
  </div>-->
  
   <div class="col-md-12">
<span class="title text-center"><b>Phone</b> :  <?php echo $company['phone']; ?> | <b>Website</b>:  <?php echo $company['website']; ?></span>
  <hr>
  </div>

  </div>
       
	   </div>
  

  
 <!-- <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
        <h3 class="panel-title">Your Unique Employer Sign Up Link </h3>
      </header>
      <div class="panel-body">
  
<div class="col-md-12">
 <span class="title text-center"><b>Your Unique Employer Sign Up Link -></b> : 
 <a id="copy" href="<?php echo 'https://www.recruitinghub.com/fprecruiter-employers?cp='.strtolower(str_replace(' ','-',$company['companyname'])).'_'.$company['id'].' '; ?> " target="_blank">Copy Link <i class="fa fa-solid fa-copy"></i> <p id="copied"></p>
</a>

</span>
  <hr>
  </div>
  
   </div>
       
	   </div> -->
  
   <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
        <h3 class="panel-title">Want to delete your agency account? </h3>
      </header>
      <div class="panel-body">
   <div class="col-md-12">
 Email to <b>support@recruitinghub.com</b> from your registered email id with a reason for deletion and quote your Agency ID ->  <b><?php echo $company['id']; ?></b>
 
 <br><br>
 Please note: You will lose the monetary benefits of all CV submissions (if any) made in last 6 months if your agency account is deleted.</span>
  <hr>
  </div>
  

</div>
       
	   </div>
  
  </div>
    <div class="col-md-5 column ui-sortable no-padding-right">
    
 

<div class="panel panel-default widget-mini" id="account_statistic">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> Personal Details  <a class="btn btn-primary" href="edit-profile">
    <i class="fa fa-edit"></i> Edit </a> <a class="btn btn-primary" href="add-user">
    <i class="fa fa-plus"></i> Add Users </a></h3>
  </header>
  <div class="panel-body">
    
   <div class="col-md-12">
  <small><span class="title text-center">Name :  <?php echo $company['name']; ?></span></small>
  <hr>
  </div>
  <div class="col-md-12">
  <small><span class="title text-center">Title :  <?php echo $company['designation']; ?></span></small>
  <hr>
  </div>
  
  
  
  <!--<div class="col-md-12">
  <small><span class="title text-center">Years Recruitment Experience :  <?php echo $company['experience']; ?></span></small>
  <hr>
  </div>-->
  
   <div class="col-md-12">
  <small><span class="title text-center">Sectors :  <?php  $exp=explode(',',$company['sectors']);
foreach($sector as $s){
if((in_array($s['sector'], $exp))|| (in_array($s['id'], $exp)))  { $selected[]=$s['sector'];} } echo implode(', ',$selected)?></span></small>
  <hr>
  </div>
</div>
</div>
</div>     

<div class="col-md-5 column ui-sortable no-padding-right">
    
 

<div class="panel panel-default widget-mini" id="account_statistic">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> GET PAID YOUR PLACEMENT FEE (Update your Company/Recruiter Bank details)  </h3>
  </header>
  <div class="panel-body">
    
  <a class="btn btn-primary" href="edit-company">
    <i class="fa fa-briefcase"></i> Add Bank Details
</a></b><br><br>
  <div class="col-md-12">
  <span class="title text-center"><b>Beneficiary Name</b> :  <?php echo $company['benname']; ?> | <b>Beneficiary Bank</b> :  <?php echo $company['benbank']; ?></span> 
  <hr>
  </div>
  
  <div class="col-md-12">
 <span class="title text-center"><b>Beneficiary A/c No</b> :  <?php echo $company['benacc']; ?> | <b>Beneficiary Sort Code / ABA / IFSC</b> :  <?php echo $company['bensort']; ?></span>
  <hr>
  </div>
  
  <div class="col-md-12">
 <span class="title text-center"><b>Beneficiary Swift Code</b> :  <?php echo $company['benswift']; ?> | <b>Tax Info</b> :  <?php echo $company['taxinfo']; ?></span>
  <hr>
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

<script>

 $('#copy').on('click',function(){

 var link = $(this).attr('href')

navigator.clipboard.writeText(link);

$('#copied').text("✓ Link Copied");

  return false;
 }) 

</script>


 </body>
</html>
