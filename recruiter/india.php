<?php if (session_status() == PHP_SESSION_NONE) {
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
require_once '../ps_pagination.php';

$block = '';
$block = 'memberid not in (select m.memberid from block b,members m where m.companyid=b.ecid and b.block=1 and b.rcid='.$cid.') and';

//$sql=mysql_query("SELECT * FROM jobs WHERE status not in('Hold','Closed','Filled') and  filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') order by updatedate desc");

$sql="SELECT * FROM jobs WHERE $block status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' and updatedate>=CURDATE() - INTERVAL 2 MONTH order by updatedate desc";

//fetch sector values to an array
$res=mysqli_query($link,"select sector from jobsectors");
if(mysqli_num_rows($res)){
	while($row=mysqli_fetch_assoc($res)){
		$sectors[]=$row;
	}
}
			
$jobarray=array();
//jobs posted recently
$content='';

if(isset($_GET['submit'])){
	
	if((isset($_GET['keywords']))&& $_GET['keywords'] != ""){
	$keywords=$_GET['keywords'];
	
	}
	else{
	$keywords="";
	}
	if((isset($_GET['jobsector'])) && $_GET['jobsector'] != "") 
		$sector=$_GET['jobsector'];
		else 
		$sector='';
		 
	if((isset($_GET['type']))&& ($_GET['type'] != "")) 
	$type=$_GET['type'];
	else 
	$type="";

	if((isset($_GET['location']))&& $_GET['location'] != "")
	$location=$_GET['location'];	
	else
	$location=""; 
	
	if((isset($_GET['status']))&& $_GET['status'] != "")
	$status=$_GET['status'];	
	else
	$status="";
	
if(($keywords) && ($sector) && ($type) && ($location) && ($status)){
 			$param='keywords='.$keywords.'&sector='.$sector.'&type='.$type.'&location='.$location.'&status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND jobsector='$sector' AND jobtype= '$type' AND joblocation like '%$location%' and status='$status' and status !='Closed' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}
	
	
elseif((!$keywords) && ($sector) && ($type) && ($location) && ($status) ){
 			$param='sector='.$sector.'&type='.$type.'&location='.$location.'&status='.$status;

			$sql = "SELECT * FROM jobs WHERE $block jobsector='$sector' AND jobtype= '$type' AND joblocation like '%$location%' and status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}
elseif(($keywords) && (!$sector) && ($type) && ($location) && ($status) ){
 			$param='keywords='.$keywords.'&type='.$type.'&location='.$location.'&status='.$status;

			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND jobtype= '$type' AND joblocation like '%$location%' and status ='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif(($keywords) && ($sector) && (!$type) && ($location) && ($status) ){
 			$param='keywords='.$keywords.'&sector='.$sector.'&location='.$location.'&status='.$status;

			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND jobsector='$sector' AND joblocation like '%$location%' and status ='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}
elseif(($keywords) && ($sector) && ($type) && (!$location)  && ($status) ){
 			$param='keywords='.$keywords.'&sector='.$sector.'&type='.$type.'&status='.$status;

			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND jobsector='$sector' AND jobtype= '$type' and status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif(($keywords) && ($sector) && ($type) && ($location)  && (!$status) ){
 			$param='keywords='.$keywords.'&sector='.$sector.'&type='.$type.'&location='.$location;

			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND jobsector='$sector' AND joblocation like '%$location%' AND jobtype= '$type' and  status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}
	
	
elseif((!$keywords) && (!$sector) && ($type) && ($location) &&($status) ){
 			$param='type='.$type.'&location='.$location.'&status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block jobtype= '$type' AND joblocation like '%$location%' and status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}
elseif(($keywords) && (!$sector) && (!$type) && ($location) &&($status) ){
 			$param='keywords='.$keywords.'&location='.$location.'&status='.status;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%') AND joblocation like '%$location%' and status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}
elseif(($keywords) && ($sector) && (!$type) && (!$location) &&($status) ){
 			$param='keywords='.$keywords.'&sector='.$sector.'&status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND jobsector='$sector' AND status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}
elseif((!$keywords) && ($sector) && ($type) && (!$location) &&($status) ){
 			$param='sector='.$sector.'&type='.$type.'&status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block jobsector='$sector' AND jobtype= '$type' AND  status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}
elseif((!$keywords) && ($sector) && (!$type) && ($location) &&($status) ){
 			$param='sector='.$sector.'&location='.$location.'&status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block jobsector='$sector'  AND joblocation like '%$location%' and status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif(($keywords) && (!$sector) && ($type) && (!$location) &&($status)){
 			$param='keywords='.$keywords.'&type='.$type.'&status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND jobtype= '$type' AND status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif((!$keywords) && ($sector) && ($type) && ($location) &&(!$status)){
 			$param='sector='.$sector.'&type='.$type.'&location='.$location;
			$sql = "SELECT * FROM jobs WHERE $block jobsector='$sector' AND jobtype= '$type' AND joblocation like '%$location%' and status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}
elseif(($keywords) && ($sector) && ($type) && (!$location) && (!$status)){
 			$param='keywords='.$keywords.'&sector='.$sector.'&type='.$type;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND jobsector='$sector' AND jobtype= '$type' and status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif(($keywords) && (!$sector) && ($type) && ($location) && (!$status)){
 			$param='keywords='.$keywords.'&type='.$type.'&location='.$location;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')   AND jobtype= '$type' AND joblocation like '%$location%' and  filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif(($keywords) && ($sector) && (!$type) && ($location) && (!$status)){
 			$param='keywords='.$keywords.'&sector='.$sector.'&location='.$location;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND jobsector='$sector' AND joblocation like '%$location%' and   filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}
	
	
	
elseif(($keywords) && (!$sector) && (!$type) && (!$location) &&($status) ){
 			$param='keywords='.$keywords.'&status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%') and status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif((!$keywords) && ($sector) && (!$type) && (!$location) &&($status) ){
 			$param='sector='.$sector.'&status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block jobsector='$sector' and status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif((!$keywords) && (!$sector) && ($type) && (!$location) &&($status) ){
 			$param='type='.$type.'&status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block jobtype= '$type' and status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif((!$keywords) && (!$sector) && (!$type) && ($location) &&($status) ){
 			$param='location='.$location.'&status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block (joblocation like '%$location%' and status='$status') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India'  order by updatedate desc";
	}	
elseif(($keywords) && ($sector) && (!$type) && (!$location) && (!$status)){
 			$param='keywords='.$keywords.'&sector='.$sector;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND jobsector='$sector' and status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif(($keywords) && (!$sector) && ($type) && (!$location) && (!$status)){
 			$param='keywords='.$keywords.'&type='.$type;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')  AND  jobtype= '$type' and status not in('Hold','Closed','Filled')  and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}	
elseif(($keywords) && (!$sector) && (!$type) && ($location) && (!$status)){
 			$param='keywords='.$keywords.'&location='.$location;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' ||  keyskills like '%$keywords%')    AND joblocation like '%$location%' and status not in('Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}	
elseif((!$keywords) && ($sector) && ($type) && (!$location) && (!$status)){
 			$param='sector='.$sector.'&type='.$type;
			$sql = "SELECT * FROM jobs WHERE $block jobsector='$sector' AND jobtype= '$type' and status not in('Hold','Closed','Filled') AND filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}	
elseif((!$keywords) && ($sector) && (!$type) && ($location) && (!$status)){
 			$param='sector='.$sector.'&location='.$location;
			$sql = "SELECT * FROM jobs WHERE $block jobsector='$sector' AND  joblocation like '%$location%' and status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif((!$keywords) && (!$sector) && ($type) && ($location) && (!$status)){
 			$param='type='.$type.'&location='.$location;
			$sql = "SELECT * FROM jobs WHERE $block jobtype= '$type' AND joblocation like '%$location%' and status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
	
		
		
elseif(($keywords) && (!$sector) && (!$type) && (!$location) && (!$status)){
 			$param='keywords='.$keywords;
			$sql = "SELECT * FROM jobs WHERE $block (jobtitle like '%$keywords%' || keyskills like '%$keywords%') and status not in('Hold','Closed','Filled')  and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}	
elseif((!$keywords) && ($sector) && (!$type) && (!$location) && (!$status)){
 			$param='sector='.$sector;
			$sql = "SELECT * FROM jobs WHERE $block jobsector='$sector' and status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}
elseif((!$keywords) && (!$sector) && ($type) && (!$location) && (!$status)){
 			$param='type='.$type;
			$sql = "SELECT * FROM jobs WHERE $block jobtype= '$type' and status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}	
elseif((!$keywords) && (!$sector) && (!$type) && ($location) && (!$status)){
 			$param='location='.$location;
			$sql = "SELECT * FROM jobs WHERE $block joblocation like '%$location%' and status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}	
elseif((!$keywords) && (!$sector) && (!$type) && (!$location) && ($status)){
 			$param='status='.$status;
			$sql = "SELECT * FROM jobs WHERE $block status='$status' and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";
	}	

							
else{
$sql="SELECT * FROM jobs WHERE $block status not in('Hold','Closed','Filled') and filledvacancy < novacancies and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') and country='India' order by updatedate desc";

}
}


$param="";
$params=$_SERVER['QUERY_STRING'];
if (strpos($params,'page') !== false) {
    $key="page";
	parse_str($params,$ar);
	$param=http_build_query(array_diff_key($ar,array($key=>"")));
}
else
$param=$params;
$pager = new PS_Pagination($link, $sql, 10, 10, $param);
$res = $pager->paginate();
if(@mysqli_num_rows($res)){
	while($rows = mysqli_fetch_assoc($res)){
		$jobarray[] = $rows;
	}
}
$pages =  $pager->renderFullNav();
$total= $pager->total_rows;
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
$rcount = count($jobarray);

if(!$rcount)
$content="No jobs for you to engage.";

?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>India | Recruiters Hub</title>
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
    <ul class="dropdown-menu" role="menu"><li><a href="company-profile"><i class="fa fa-user"></i>Company Profile</a>   </li>
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
   <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messaging (<span class="label-primary"><?php echo $new; ?></span>)</a>    </li>
    <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Feedback Rating</a> </li>
<li><a href="psl-list" role="tab" > <i class="fa fa-list"></i>PSL</a> </li>
<li><a href="company-profile" role="tab" > <i class="fa fa-briefcase"></i>Agency Profile</a> </li>
<li><a href="add-user" role="tab" > <i class="fa fa-plus"></i>Add Users</a> </li>
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
    <div class="col-xs-12">
      <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"><a>JOBS POSTED BY EMPLOYERS COUNTRY WISE -></a> <a href="search">All</a> | <a href="uk">UK</a> | <a href="india"><strong>India</strong></a> | <a href="europe">Europe</a> | <a href="middleeast">Middle East</a> | <a href="africa">Africa</a> | <a href="us">US</a> | <a href="singapore">Singapore</a> | <a href="australia">Australia</a> | <a href="china">China</a> | <a href="russia">Russia</a> | <a href="japan">Japan</a> | <a href="canada">Canada</a> | <a href="philippines">Philippines</a> | <a href="malaysia">Malaysia</a> | <a href="newzealand">New Zealand</a> | <a href="southamerica">South America</a> | <a href="northkorea">North Korea</a> | <a href="southkorea">South Korea</a>  </h3>
  </header>
        <div class="panel-body">
          <div class="row results-table-filter">
            <form id="job_filter_form" class="job_search" action="india" accept-charset="UTF-8" method="GET">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-2">
                    <label for="title_or_description_contains">Keyword Search</label>
                  </div>

                  <div class="col-md-2">
                    <label for="sector_id_equals">Sector</label>
                  </div>

                 <!-- <div class="col-md-2">
                    <label for="type_eq">Job Type</label>
                  </div>-->

                  <div class="col-md-2">
                    <label for="location_contains">City</label>
                  </div>
                  
                   <!--<div class="col-md-2">
                    <label for="location_contains">Job Status</label>
                  </div>-->
                  
                </div>
                
                

                <div class="row">
                  <div class="col-md-2">
                    <input placeholder="Keyword" class="form-control" name="keywords" type="text">
                  </div>
                  <div class="col-md-2">
                   <select name="jobsector"  class="form-control" >
                    <option value="">Select Sector</option>
                     <?php foreach($sectors as $s){ ?>
                     <option value="<?php echo htmlspecialchars($s['sector']); ?>"><?php echo $s['sector']; ?></option>
                     <?php } ?>
                    </select>

                  </div>
                  
                 <!-- <div class="col-md-2">
                    <select class="form-control" name="type" id="q_type_eq">
                    <option value="">Permanent and Contract</option>
					<option value="Permanent">Permanent only</option>
					<option value="Contract">Contract only</option></select>
                  </div>-->
                  
                  <div class="col-md-2">
                    <input placeholder="City" class="form-control" name="location" type="text">
                  </div>
                  
                  <!-- <div class="col-md-2">
                    <Select class="form-control"  name="status" type="text">
                    <option value="">Select Status</option>
                    <option value="Open">Open</option>
                     <option value="Shortlisted">Interviewing</option>
                      
                   
                    </Select>
                  </div>-->
                  
                  <div class="col-md-2 results-table-filter--search-btn">
                    <input name="submit" value="Search" class="btn btn-primary" type="submit">
                  </div>
                  <a class="btn btn-primary" href="job">
    <i class="fa fa-forward"></i>  Go to Engaged Jobs
</a> 
                </div>
              </div>
</form>         
 </div>
 <h3>Total Available Roles in India: <?php echo $total; ?></h3>
 
          <!--<div class="row">
            <div class="col-md-12">
              <ul class="pagination pagination"><li class="prev disabled"><span>← Previous</span></li> 
              <li class="active"><span>1</span></li> <li><a rel="next" href="#">2</a></li> 
              <li><a href="#">3</a></li> <li><a href="#">4</a></li> <li><a href="#">5</a></li> 
              <li><a href="#">6</a></li> <li><a href="#">7</a></li> <li><a href="#">8</a></li> <li><a href="#">9</a></li> 
              <li><a href="#">10</a></li> <li class="next"><a rel="next" href="#">Next →</a></li></ul>
            </div>
          </div>-->

        </div>
        
      </div>
    </div>
  </div>
  
<!--- <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> List of Jobs In Country </h3> 
  </header>
        <div class="panel-body">
         
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-2">
                  <a class="sort_link " href="india">India</a>
                   </div>
                  <div class="col-md-2">
                   <a class="sort_link " href="uae">UAE</a> 
                   
                  </div>
                  <div class="col-md-2">
                  <a class="sort_link " href="uk">UK</a>  
                     
                  </div>
                  <div class="col-md-2">
                  <a class="sort_link " href="europe">Europe</a>
                      
                  </div>
                   <div class="col-md-2">
                    <a class="sort_link " href="africa">Africa</a>
                  </div>
                  <div class="col-md-2">
                  <a class="sort_link "  href="us">US</a>
                  </div>
                </div>
              </div>
              
        </div>
      </div>
    </div>
  </div>---->
  <div class="row">
              <div class="col-md-12 table-responsive">
               <div class="panel panel-default">
                <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> List of Jobs posted by Employers in India | NO SUBSCRIPTION FEE TO ENGAGE ON JOBS </h3>
  </header>
        <div class="panel-body">
               <?php if(!$content){ ?>
                <table class="table table-striped jobs-list">
                  <thead>
                  <tr>
                    <th>
                      <a class="sort_link " href="#">Job Title</a> /
                      <a class="sort_link " href="#">Location</a> /
                      <a class="sort_link " href="#">Date</a> /
                      <a class="sort_link " href="#">Job ID</a>
                    </th>
                   <th><a class="sort_link " href="#">Action</a></th>
                   <th><a class="sort_link " href="#">Fee</a></th>
                   <th><a class="sort_link " href="#">CV Limit <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No of CV's you can submit"><i class="fa fa-question-circle"></i>
    </span></a></th>
                    <th><a class="sort_link " href="#">Agency Limit <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No of Agencies Engaged Vs No of Agencies allowed to work on this role"><i class="fa fa-question-circle"></i>
    </span></a></th>
                    <th><a class="sort_link " href="#">Sector</a></th>
                      <th><a class="sort_link " href="#">Job Type</a></th>
  
                  </tr>
                  </thead>
                  <tbody>
                  <?php
				  $sectors=mysqli_fetch_assoc(mysqli_query($link,"select sectors from companyprofile a,members b where a.id=b.companyid and b.memberid='$mid' "));
				  $mysector=explode(',', $sectors['sectors']);
					foreach($jobarray as $row){
					if($row['ratetype']){
					$ratetype='/'.$row['ratetype'];
					}else{
					$ratetype='';
					}
					$minex=$row['minex'];
		$maxex=$row['maxex'];
$checkjobtype=$row['jobtype'];
$checkpriority=$row['priority'];

		if(($row['fee']=='')&&(($row['country']=='UK')||($row['country']=='US'))&&($row['jobtype']=='Contract')){
		$fdisplay=$row['minlakh'];
		$fdisplay1=$row['maxlakh'];
		$rfee=$fdisplay . '-' . $fdisplay1;
		}	
		elseif($row['fee']!='0'){
		$rfee=$row['fee'];
		}
		else{
		$rfee='NA';
		}
		
		$parray1=array('5','6','7','8','8.33','10','12','12.5','15','20','25');
		if((in_array($rfee,$parray1)))
		{
		$sym='%';
		}elseif((!(in_array($rfee,$parray1)))&&($row['country']=='India')&&($rfee!='NA')){
		$sym='Rs';
		}elseif((!(in_array($rfee,$parray1)))&&($row['country']=='UK')&&($rfee!='NA')){
		$sym='&pound;';
		}
		elseif($rfee=='NA'){
		$sym='';
		}
		else{
		$sym=$row['currency'];
		}
					
					
						echo '
						 <tr>
				  		  <td>
                <a class="title" href="employerjob?EID='.$row['jobid'].'" data-toggle="modal"  id='.$row['jobid'].'><b>'.$row['jobtitle'].'</b></a>';
						  if(($checkpriority!='0')){echo' <span class="label label-primary">Priority</span>'; } echo'
						  <br>	<small>'.$row['joblocation'].'</small> /  <small>Updated: <b>'.$row['updatedate'].'</b></small> /  <small>Job ID: '.$row['jobid'].'</small> 
						 </td>
						 <td>';
						if(in_array($row['jobsector'],$mysector))
						echo '<a title="Engage" class="btn btn-primary" href="engage?jobid='.$row['jobid'].'&employerid='.$row['memberid'].'">Engage</a>';
						else
						echo '<a title="Engage" class="btn btn-primary" href="engage?jobid='.$row['jobid'].'&employerid='.$row['memberid'].'">Engage</a>';
						echo'
					  </td>
					  <td>'.$rfee.' '.$sym.' '.$ratetype.' </td>
					  <td>'.$row['cvlimit'].'</td>
				   		<td>'.$row['agencycount'].'/'.$row['agencylimit'].'</td>
						 <td>'.$row['jobsector'].'</td>
						  <td><b>'.$row['jobtype'].'</b></td>
		
					</tr>';
			}
			?>
                  
  </tbody>
</table>
  <?php  echo $pages; } else echo $content; ?>
              </div>
            </div>
            </div>
            </div>
            
</section>
   
<div class="modal fade" id="engage-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="engage-modal-label"></h4>
          </div>
          <div class="modal-body" id="engage-modal-body">
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
     </div>
<div class="clearfix"></div>
</div>
</div>
<script>
    $('#engage-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
            essayId = e.relatedTarget.id;
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'engage-modal-content.php',
            data: 'EID=' + essayId,
            success: function(data) {
				$modal.find('#engage-modal-body').html(data);
                $modal.find('#engage-modal-label').html( $('#job_title').data('value'));
				
            }
        });
    })
</script>

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>
</html>
