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
}

$param="";
if(isset($_GET['jobid'])){
$jobid=$_GET['jobid'];
$param="&jobid=".$jobid;
}

require_once '../config.php';
			
$jobarray=array();
//jobs posted recently
$candidatemsg='';


//$candidateres = mysql_query("SELECT a.*,b.status FROM Candidates a, submitted_candidates b,jobs c WHERE b.jobid=c.jobid and c.memberid='$mid' and a.id=b.candidateid"); 
if(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['candidate']) && ($_GET['candidate']!='') && isset($_GET['agency']) &&($_GET['agency']!='') && isset($_GET['status']) &&($_GET['status']!='')){

	$jobtitle=$_GET['jobtitle'];
	$candidate=$_GET['candidate'];
	$agency=$_GET['agency'];
	$status=$_GET['status'];
	if($status=='Screen Rejected')
	$filter=" c.prestatus='Submitted'";
	elseif($status=='Interview Reject')
	$filter=" c.prestatus='Interviewing'";
	elseif($status=='Offer Rejected')
	$filter=" c.prestatus='Offered'";
	else 
	$filter=" c.status='$status'";
	
$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and a.jobtitle like '%$jobtitle%' and ( b.fname like '%$candidate%' or c.candidateid='$candidate') and c.recruiterid= d.memberid and d.companyid=e.id and e.name ='$agency' and $filter order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['candidate']) && ($_GET['candidate']!='') && isset($_GET['agency']) &&($_GET['agency']=='') && isset($_GET['status']) &&($_GET['status']==''))
{
$jobtitle=$_GET['jobtitle'];
$candidate=$_GET['candidate'];

$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and a.jobtitle like '%$jobtitle%' and ( b.fname like '%$candidate%' or c.candidateid='$candidate') and c.recruiterid= d.memberid and d.companyid=e.id  order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['candidate']) && ($_GET['candidate']=='') && isset($_GET['agency']) &&($_GET['agency']!='') && isset($_GET['status']) &&($_GET['status']==''))
{
$jobtitle=$_GET['jobtitle'];
$agency=$_GET['agency'];

$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and a.jobtitle like '%$jobtitle%'  and c.recruiterid= d.memberid and d.companyid=e.id and e.name ='$agency' order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['candidate']) && ($_GET['candidate']=='') && isset($_GET['agency']) &&($_GET['agency']=='') && isset($_GET['status']) &&($_GET['status']!=''))
{
$jobtitle=$_GET['jobtitle'];
$status=$_GET['status'];
if($status=='Screen Rejected')
	$filter="c.prestatus='Submitted'";
	elseif($status=='Interview Reject')
	$filter=" c.prestatus='Interviewing'";
	elseif($status=='Offer Rejected')
	$filter=" c.prestatus='Offered'";
	else 
	$filter="c.status='$status'";
$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and a.jobtitle like '%$jobtitle%'  and c.recruiterid= d.memberid and d.companyid=e.id  and $filter order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']=='')  && isset($_GET['candidate']) && ($_GET['candidate']!='') && isset($_GET['agency']) &&($_GET['agency']!='') && isset($_GET['status']) &&($_GET['status']==''))
{
$candidate=$_GET['candidate'];
$agency=$_GET['agency'];

$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid  and ( b.fname like '%$candidate%' or c.candidateid='$candidate') and c.recruiterid= d.memberid and d.companyid=e.id and e.name ='$agency'  order by submitdate desc";

}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']=='')  && isset($_GET['candidate']) && ($_GET['candidate']!='') && isset($_GET['agency']) &&($_GET['agency']=='') && isset($_GET['status']) &&($_GET['status']!=''))
{
$candidate=$_GET['candidate'];
$status=$_GET['status'];
if($status=='Screen Rejected')
	$filter=" c.prestatus='Submitted'";
	elseif($status=='Interview Reject')
	$filter=" c.prestatus='Interviewing'";
	elseif($status=='Offer Rejected')
	$filter=" c.prestatus='Offered'";
	else 
	$filter="c.status='$status'";
$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and ( b.fname like '%$candidate%' or c.candidateid='$candidate') and c.recruiterid= d.memberid and d.companyid=e.id and $filter order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']=='')  && isset($_GET['candidate']) && ($_GET['candidate']=='') && isset($_GET['agency']) &&($_GET['agency']!='') && isset($_GET['status']) &&($_GET['status']!=''))
{
$agency=$_GET['agency'];
$status=$_GET['status'];
if($status=='Screen Rejected')
	$filter="c.prestatus='Submitted'";
	elseif($status=='Interview Reject')
	$filter="c.prestatus='Interviewing'";
	elseif($status=='Offer Rejected')
	$filter="c.prestatus='Offered'";
	else 
	$filter="c.status='$status'";
$sql="SELECT a.jobtitle as jobtitlea.jobid,,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid  and c.recruiterid= d.memberid and d.companyid=e.id and e.name ='$agency' and $filter order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['candidate']) && ($_GET['candidate']=='') && isset($_GET['agency']) &&($_GET['agency']=='') && isset($_GET['status']) &&($_GET['status']=='')){

$jobtitle=$_GET['jobtitle'];

$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and a.jobtitle like '%$jobtitle%' and c.recruiterid= d.memberid and d.companyid=e.id  order by submitdate desc";

}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']=='')  && isset($_GET['candidate']) && ($_GET['candidate']!='') && isset($_GET['agency']) &&($_GET['agency']=='') && isset($_GET['status']) &&($_GET['status']=='')){

$candidate=$_GET['candidate'];

$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid  and ( b.fname like '%$candidate%' or c.candidateid='$candidate') and c.recruiterid= d.memberid and d.companyid=e.id  order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']=='')  && isset($_GET['candidate']) && ($_GET['candidate']=='') && isset($_GET['agency']) &&($_GET['agency']!='') && isset($_GET['status']) &&($_GET['status']=='')){

$agency=$_GET['agency'];

$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.recruiterid= d.memberid and d.companyid=e.id and e.name ='$agency' order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']=='')  && isset($_GET['candidate']) && ($_GET['candidate']=='') && isset($_GET['agency']) &&($_GET['agency']=='') && isset($_GET['status']) &&($_GET['status']!='')){
$status=$_GET['status'];
if($status=='Screen Rejected')
	$filter=" c.prestatus='Submitted'";
	elseif($status=='Interview Reject')
	$filter=" c.prestatus='Interviewing'";
	elseif($status=='Offer Rejected')
	$filter=" c.prestatus='Offered'";
	else 
	$filter="c.status='$status'";
$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid  and c.recruiterid= d.memberid and d.companyid=e.id and $filter order by submitdate desc";
}

elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['candidate']) && ($_GET['candidate']!='') && isset($_GET['agency']) &&($_GET['agency']!='') && isset($_GET['status']) &&($_GET['status']=='')){

$jobtitle=$_GET['jobtitle'];
$candidate=$_GET['candidate'];
$agency=$_GET['agency'];

$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and a.jobtitle like '%$jobtitle%' and ( b.fname like '%$candidate%' or c.candidateid='$candidate') and c.recruiterid= d.memberid and d.companyid=e.id and e.name ='$agency'  order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']=='')  && isset($_GET['candidate']) && ($_GET['candidate']!='') && isset($_GET['agency']) &&($_GET['agency']!='') && isset($_GET['status']) &&($_GET['status']!='')){

$candidate=$_GET['candidate'];
$agency=$_GET['agency'];
$status=$_GET['status'];
if($status=='Screen Rejected')
	$filter="c.prestatus='Submitted'";
	elseif($status=='Interview Reject')
	$filter=" c.prestatus='Interviewing'";
	elseif($status=='Offer Rejected')
	$filter=" c.prestatus='Offered'";
	else 
	$filter="and c.status='$status'";
$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid  and ( b.fname like '%$candidate%' or c.candidateid='$candidate') and c.recruiterid= d.memberid and d.companyid=e.id and e.name ='$agency' and  $filter order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['candidate']) && ($_GET['candidate']=='') && isset($_GET['agency']) &&($_GET['agency']!='') && isset($_GET['status']) &&($_GET['status']!='')){

$jobtitle=$_GET['jobtitle'];
$agency=$_GET['agency'];
$status=$_GET['status'];
if($status=='Screen Rejected')
	$filter=" c.prestatus='Submitted'";
	elseif($status=='Interview Reject')
	$filter=" c.prestatus='Interviewing'";
	elseif($status=='Offer Rejected')
	$filter=" c.prestatus='Offered'";
	else 
	$filter=" c.status='$status'";
$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and a.jobtitle like '%$jobtitle%'  and c.recruiterid= d.memberid and d.companyid=e.id and e.name ='$agency' and  $filter order by submitdate desc";
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['candidate']) && ($_GET['candidate']!='') && isset($_GET['agency']) &&($_GET['agency']=='') && isset($_GET['status']) &&($_GET['status']!='')){

$jobtitle=$_GET['jobtitle'];
$candidate=$_GET['candidate'];
$status=$_GET['status'];
if($status=='Screen Rejected')
	$filter=" c.prestatus='Submitted'";
	elseif($status=='Interview Reject')
	$filter=" c.prestatus='Interviewing'";
	elseif($status=='Offer Rejected')
	$filter=" c.prestatus='Offered'";
	else 
	$filter=" c.status='$status'";
$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b ,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and a.jobtitle like '%$jobtitle%' and ( b.fname like '%$candidate%' or c.candidateid='$candidate') and c.recruiterid= d.memberid and d.companyid=e.id  and  $filter order by submitdate desc";
}

else{
$sql="SELECT a.jobtitle as jobtitle,a.jobid,d.email as recruiteremail,e.name as agency,e.registerid, b.fname as candidatename,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.prestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile e,members d WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.recruiterid= d.memberid and d.companyid=e.id order by submitdate desc";

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
include('../ps_pagination.php');
$pager = new PS_Pagination($link, $sql, 20, 10, $param);
$candidateres = $pager->paginate();

if( mysql_num_rows($candidateres)){ 
    while($row = mysql_fetch_array($candidateres)){ 
			$candidatearray[]=$row;	
				}
$pages =  $pager->renderFullNav();

/*$issubmitted = mysql_num_rows($candidateres);
if($issubmitted > 0){ 
    while($row = mysql_fetch_array($candidateres)){
			$candidatearray[]=$row;
				} */
}
else
$candidatemsg="No candidates found";
?>

<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Employers Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
    <link rel="icon" type="image/favicon.png" href="https://www.recruiting-hub.com/images/favicon.png" />
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
<a class="btn btn-primary" href="submit-a-job">
     Helpline number +91 7810022022
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

     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Jobs</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="submit-a-job" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add new</a>  </li>
   <li><a href="jobs" role="tab" ><i class="fa fa-list"></i> Active</a> </li>
    <li><a href="inactive" role="tab" ><i class="fa fa-list"></i> Inactive</a> </li>
    <li><a href="closed" role="tab" ><i class="fa fa-clock-o"></i> Closed</a>  </li>
    <li><a href="filled" role="tab" > <i class="fa fa-users"></i> Filled</a>  </li>
  
    </ul>
   </div>
   </li>
     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu1" data-toggle="collapse" aria-controls="candidatesDropdownMenu1" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Candidates</a>

   <div class="collapse" id="candidatesDropdownMenu1">
   <ul class="nav-sub" style="display: block;">
   
    <li><a href="all" role="tab" ><i class="fa fa-list"></i> All</a> </li>
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
    <li><a href="interview" role="tab" > <i class="fa fa-users"></i> Interviewing</a>  </li>
        <li><a href="offer" role="tab" > <i class="fa fa-trash"></i> Offer</a> </li>
    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i> Rejected</a> </li>
    </ul>
   </div>
   </li>
   
     <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messages</a>    </li>
     <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Feedback Rating</a> </li>
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
    <h3 class="panel-title"> Candidates </h3>
  </header>
        <div class="panel-body">
        <div class="text-right">
 <a class="btn btn-primary" href="all-download<?php if($param)
 echo'?'.$param; ?>">
   <i class="fa fa-file-excel-o" ></i> Download
</a>
</div>
<div class="table-responsive">

   <?php if(!$candidatemsg){ ?>
    <div class="col-sm-12">
     <form method="get" action="all">
       <div class="form-group">
        <br>
        <label>Search Jobs</label><br><br>
        <div class="col-sm-2">
        	<input name="jobtitle" class="form-control ui-autocomplete-input"  placeholder="Search by Job" autocomplete="off" type="text">
        </div>
          <div class="col-sm-2 ">
                                            <input name="candidate" class="form-control ui-autocomplete-input" id="candidate" placeholder="Search by candidate" autocomplete="off" type="text">
        </div>
         <div class="col-sm-2 ">
                                            <input name="agency" class="form-control ui-autocomplete-input" id="agency" placeholder="Search by agency" autocomplete="off" type="text">
        </div>
         <div class="col-sm-2 ">
                                            <select class="form-control ui-autocomplete-input" name="status" id="status">
                                            <option value="">Search By Status</option>
                                            <option value="Submitted">Submitted</option>
                                            <option value="Screen Rejected">Screen Rejected</option>
                                            <option value="Interviewing">Interviewing</option>
                                            <option value="Interview Reject">Interview reject</option>
                                            <option value="Offered">Offered</option> 
                                            <option value="Offer Rejected">Offer Reject</option> 
                                            <option value="Filled">Filled</option> 
                                            <option value="Rejected">Rejected</option>
                                             
                                             
                                            </select> 
                                            
        </div>
        
        
        <div class="col-sm-2">
        <div class="form-group">
                                            <input value="Search" class="btn btn-primary" name="submit" type="submit">
                                             </div><br><br>
        </div>
        </div>  
        </form>                                  
        </div>
            <table class="table table-striped">
              <thead>
                <tr>
                
                  <th>Candidate Name/candidateID</th>
                 
                  <th>Job Title/JobID</th>
                 
                   <th>Agency /RecruiterID</th>
                  <th>Contact Number</th>
                  <th>Exp Salary</th>
                  <th>Status</th>
                  <th>Action</th>
                 
                </tr>
              </thead>
              <tbody>
                <?php
	    foreach($candidatearray as $row){
				
			echo'
          <tr>
		 
 <td><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['candidateid'].'>'.$row['candidatename'].'</a><br>
    <small>Submitted: '.$row['submitdate'].' / '.$row['candidateid'].'</small> </td>
	
	<td>'.$row['jobtitle'].'<br><small>'.$row['jobid'].'</small></td>
	
	 <td><a class="title" href="#" data-toggle="modal" data-target="#recruiter-modal" id='.$row['registerid'].'>'.$row['agency'].'</a><br>
    <small>'.$row['recruiterid'].'</small> </td>
    
	<td>'.$row['contactnumber'].'</td>
  <td>'.$row['desiredsalary'].'</td>  
  <td><span class="badge badge-default">'.$row['candidatestatus'].'</span></td>
  <td>';
  if($row['candidatestatus']=="submitted" || $row['candidatestatus']=="Interviewing"){
	  	   echo'
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">';
	  	if($row['candidatestatus']=="submitted")
		{
		echo '
		    <li><a title="Select Candidate" href="select-candidate?candidateid='.$row['candidateid'].'">Shortlist</a></li>
			<li><a title="Reject Candidate" href="reject-candidate?candidateid='.$row['candidateid'].'">Screen Reject</a></li>
			<li><a title="Mark as Duplicate" href="mark-duplicate-candidate?candidateid='.$row['candidateid'].'">Duplicate</a></li>';
			}
			else{
			 echo '<li><a title="Fill vacancy" href="fill-candidate?candidateid='.$row['candidateid'].'">Fill vacancy</a></li>
			 <li><a title="Reject Candidate" href="reject-candidate?candidateid='.$row['candidateid'].'">Interview Reject</a></li>';
			}
			echo'
		</ul>
    </div>';
	}
	else {
		
	}
	echo '
  </td>
</tr>';
}
?>
</tbody>
</table>
 <?php echo $pages; } else echo $candidatemsg; ?> 
</div>
      </div>
      </div>
    </div>
  </div>
</section>
    
      <div class="modal fade" id="candidate-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="candidate-modal-label"></h4>
          </div>
          <div class="modal-body" id="candidate-modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="recruiter-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="recruiter-modal-label"></h4>
          </div>
          <div class="modal-body" id="recruiter-modal-body">
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
	$('#candidate-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
            essayId = e.relatedTarget.id;
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'candidate-detail.php',
            data: 'EID=' + essayId,
            success: function(data) {
				$modal.find('#candidate-modal-body').html(data);
                $modal.find('#candidate-modal-label').html( $('#candidate_name').data('value'));
				
            }
        });
    });
	$('#recruiter-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
            essayId = e.relatedTarget.id;
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'recruiter-detailpage.php',
            data: 'EID=' + essayId,
            success: function(data) {
				$modal.find('#recruiter-modal-body').html(data);
                $modal.find('#recruiter-modal-label').html( $('#company_name').data('value'));
				
            }
        });
    });
	/*** $( "#jobtitle" ).autocomplete({
	minLength: 2,
	source:'jsearch.php',
	 response: function(event, ui) {
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                $("#e").text("No results found");
            } else {
                $("#e").empty();
            }
    },
	change: function(event, ui) {
            if (!ui.item) {
               $(this).val('');
            }
    }
    });********/
	$( "#agency" ).autocomplete({
	minLength: 2,
	source:'rsearch.php',
	 response: function(event, ui) {
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                $("#e").text("No results found");
            } else {
                $("#e").empty();
            }
    },
	change: function(event, ui) {
            if (!ui.item) {
               $(this).val('');
            }
    }
    });				
</script>

 </body>
</html>
