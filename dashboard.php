<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
	}
$mid=$name=$email=$iam=$noresrcmsg='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$cid=$_SESSION['cid'];
$ecountry=$_SESSION['country'];
$notification='';
}
else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login'); 
exit;
}
require_once '../config.php';

$live=$filled=$total=0;
$countres = mysqli_query($link,"SELECT count(*) as total,sum(case when status not in ('Filled','Closed') then 1 else 0 end)  as live, sum(case when status = 'Filled' then 1 else 0 end) as filled FROM jobs WHERE memberid='$mid'"); 
$isposted = mysqli_num_rows($countres);
    while($row = mysqli_fetch_array($countres)){ 
		if($row['live'])
		$live=$row['live'];
		else
		$live=0;
		if($row['filled'])
		$filled=$row['filled'];
		else
		$filled=0;
		$total=$row['total'];
				}

$jobarray=$engagedarray=$candidatearray=array();
//jobs posted recently
$engagedmsg=$candidatemsg=$content='';
$jobres = mysqli_query($link,"SELECT * FROM jobs WHERE memberid='$mid' and status not in ('Closed') and status not in ('Filled') order by postdate desc limit 0, 3"); 
$isposted = mysqli_num_rows($jobres);
if($isposted > 0){ 
	$i=0;
    while($row = mysqli_fetch_array($jobres)){ 
			 $jobarray[]=$row;
			 $jobarray[$i]['requests']='0';
			 $jobid=$row['jobid'];
			 $res=mysqli_query($link,"SELECT * FROM request WHERE jobid='$jobid'");
			 $isrequested = mysqli_num_rows($res);
			 if($isrequested){
				$jobarray[$i]['requests'] = $isrequested;
				}
									
				$i++;
		}		
}
else
$content="You have not posted any jobs yet, click LATEST JOBS POSTED link above to see if your team posted any jobs.";


//latest requests
$engagedres = mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.joblocation as joblocation,a.postdate as postdate,b.memberid as recruiter,b.firstname as recruitername,d.name as companyname, d.id as id, c.requestid as requestid,c.jobid FROM request c,jobs a,members b,companyprofile d WHERE a.memberid='$mid' and a.jobid=c.jobid and c.status='Requested' and b.memberid=c.recruiterid and b.companyid=d.id order by startdate desc limit 0, 3");
 
$isengaged = mysqli_num_rows($engagedres);
if($isengaged > 0){ 
    while($row = mysqli_fetch_array($engagedres)){ 
		$engagedarray[]=$row;
				}
		
}
else
$engagedmsg="No request has been raised.";

//submitted candidates

$candidateres = mysqli_query($link,"SELECT a.jobtitle as jobtitle, b.fname as candidatename,b.cv as cv,c.id as submitid,c.status as candidatestatus,c.viewnotes,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and submitdate >= CURDATE()-INTERVAL 6 MONTH order by submitdate desc limit 0, 3"); 
$issubmitted = mysqli_num_rows($candidateres);
if($issubmitted > 0){ 
    while($row = mysqli_fetch_array($candidateres)){ 
		$candidatearray[]=$row;
				}
}
else
$candidatemsg="No candidates are submitted for any of your jobs in last 6 months. Click SUBMITTED CANDIDATES link above.";

// unread new messages
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
//$rating=mysqli_result(mysqli_query($link,"select AVG(rating) from recruiterrating where ratedto='$mid'"),0);
$submittedsql="select count(id) from submitted_candidates where status='Awaiting Feedback' and jobid in(select jobid from jobs where memberid='$mid') and submitdate>=(select lastlogin from members where memberid='$mid')";
                                 
								   $result = mysqli_query($link,$submittedsql);
									if(mysqli_num_rows($result)){
									 $submittedcounts=mysqli_fetch_array($result);
									$submittedcount=$submittedcounts['0'];
									 }
?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Dashboard | Employers Hub</title>
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
<a class="btn btn-primary" href="https://www.recruitinghub.com/mobileapp"target=_blank>Download Employer App</a> 
</div>
<ul class="navbar-right">
  <li class="dropdown">
    <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">
  <img src="images/user.png" class="img-circle" alt="">     <span class="text"><?php echo $name; ?></span><span class="caret"></span> </a>
    <ul class="dropdown-menu" role="menu"><li><a href="company-profile"><i class="fa fa-user"></i>Company Profile</a>   </li>
      <li><a href="edit-profile"><i class="fa fa-gears"></i>Your Account</a></li>
       <li><a href="manage-users"><i class="fa fa-users"></i>Manage Users</a></li>
  <!--  <?php 
	   if($ecountry=='India'){echo'
	   <li><a href="subscription-india"><i class="fa fa-money"></i> Manage Subscription</a></li>';}
	   else{echo'
	    <li><a href="subscription-uk"><i class="fa fa-money"></i> Manage Subscription</a></li>';
		}echo'
     <li><a href="subscription-status"><i class="fa fa-info-circle"></i> Subscription Status</a></li>
	   ';
	   ?>-->
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
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback (<?php echo $submittedcount; ?>)</a>  </li>
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

    <div class="col-md-7 ui-sortable">
      <div class="panel panel-default" id="job">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"><a class="dashboard_view_all" title="View All" href="jobs"> Latest Jobs posted | Use our Chat widget for Support from our team</a>    </h3>
  </header>
  <div class="panel-body table-responsive">
   <div class="text-right">
                    <a class="btn btn-primary" href="postdashboard">
    <i class="fa fa-plus"></i> Post New Job
</a>
              </div>
  <?php if(!$content){ ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Job Title</th>
            <th>Post date</th>
            <th>No of Agencies</th>
          </tr>
        </thead>
        <tbody>
       <?php
	    foreach($jobarray as $row){
			
			echo'
          <tr>
  <td><a class="title" href="#" data-toggle="modal" data-target="#engage-modal" id='.$row['jobid'].'>'.$row['jobtitle'].'</a></td>
  <td>'.$row['postdate'].'</td>
  <td>'.$row['requests'].' </td>
</tr>';

}
?>
 </tbody>
 </table>
 
 <div class="text-right">

                    <a class="btn btn-primary" href="jobs">View Jobs</a>
                    
                     <a class="btn btn-primary" href="all">View Candidates (<?php echo $submittedcount; ?>)</a>
              </div>
 <?php } else echo $content; ?>

 </div>
 <div class="modal fade" id="engage-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="engage-modal-label"></h4>
          </div>
          <div class="modal-body" id="engage-modal-body">
          </div>
          <div class="modal-footer" id="engage-modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <a class="btn btn-primary" href=""> Edit </a>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="recruiter-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="recruiter-modal-content">
        
        </div>
      </div>
    </div>
    
</div>



<!--<div class="panel panel-default" id="candidate_activity">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title">
      <a class="dashboard_view_all" title="View All" href="all">Submitted Candidates</a>    </h3>
  </header>

  <div class="panel-body table-responsive">
  <?php if(!$candidatemsg){ ?>
      <table class="table table-striped">
  <thead>
    <tr>
      <th>Candidate details</th>
      <th>View CV</th>
      <th>Cover Letter</th>
      <th>Candidate status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
	  
	    foreach($candidatearray as $row){
			echo'
          <tr>
  <td><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['candidateid'].'>'.$row['candidatename'].'</a> / '.$row['jobtitle'].'<br>
    <small>Submitted: '.$row['submitdate'].'</small> </td>
  <td><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['candidateid'].'>CV</a></td>
   <td><a class="title" href="#" data-toggle="modal" data-target="#cover-modal" id='.$row['submitid'].'>viewnotes</a></td>
     <td>'.$row['candidatestatus'].'</td>
  <td>';
   if($row['candidatestatus']=="Awaiting Feedback" || $row['candidatestatus']=="Shortlisted"){
	  	   echo'
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">';
	  	if($row['candidatestatus']=="Awaiting Feedback")
		{
		echo '
		    <li><a title="Select Candidate" href="select-candidate?candidateid='.$row['candidateid'].'">Shortlist</a></li>
			<li><a title="Mark as Duplicate" href="mark-duplicate-candidate?candidateid='.$row['candidateid'].'">Duplicate</a></li>';
			}
			else{
			 echo '<li><a title="Fill vacancy" href="fill-candidate?candidateid='.$row['candidateid'].'">Fill vacancy</a></li>';
			}
			echo'
			<li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>CV Reject</a></li>
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
  <div class="text-right">
    <a class="btn btn-primary" href="all">View Candidates</a>
              </div>
 <?php } else echo $candidatemsg; ?>
  </div>
</div>
<?php $rsql=mysqli_query($link,"select id,resourceid,empidofresource,engagedby,type,engagestatus,finalstatus,date from resourceengagedetails where empidofresource=$mid limit 0,3"); 

$engagedresource = mysqli_num_rows($rsql);
if($engagedresource > 0){ 
    while($row = mysqli_fetch_array($rsql)){ 
		$resrcengagearray[]=$row;
				}
}else{
$noresrcmsg='No Engagement Request found.';
}
?>



<div class="panel panel-default" id="engagement">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title">
      <a class="dashboard_view_all" title="View All" href="requests">Request from Agencies</a> </h3>
  </header>
  <div class="panel-body table-responsive">
    <?php if(!$engagedmsg){ ?>
                <table class="table table-striped jobs-list">
                  <thead>
                    <tr>
                      <th>Title/Location/Posted</th>
                      <th>Request from Agencies</th>
                      <th>Actions</th>
  
                     
                    </tr>
                  </thead>
                  <tbody>
                   <?php
	    foreach($engagedarray as $row){
			echo'
          <tr>
  <td><a class="title" href="#" data-toggle="modal" data-target="#engage-modal" id='.$row['jobid'].'>'.$row['jobtitle'].'</a> <br>
   <small>'.$row['joblocation'].'</small> /  <small>Posted: '.$row['postdate'].'</small> </td>
 <td><a class="title" href="#" data-toggle="modal" data-target="#recruiter-modal" data-value= "'.$row['requestid'].'"   id="'.$row['id'].'">'.$row['recruitername'].' / '.$row['companyname'].'</a></td>
  <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
	  
            <li><a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['requestid'].'>Approve</a></li>
			<li><a class="title" href="#" data-toggle="modal" data-target="#reject-modal" id='.$row['requestid'].'>Reject</a></li>
      </ul>
    </div>
  </td>
</tr>';
}
?>
                   
 </tbody>
 </table>
  <div class="text-right">
                    <a class="btn btn-primary" href="requests">View All</a>
              </div>
  <?php } else echo $engagedmsg; ?>
  </div>
</div>-->

<div class="panel panel-default" id="candidate_activity">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title">
      <a class="dashboard_view_all" title="View All" href="all">VIEW CANDIDATES<td> (<?php echo $submittedcount; ?>) </td></a>   </h3>
  </header>

  <div class="panel-body table-responsive">
  <?php if(!$new) echo "No new candidates, click above link to view all candidates"; else{?>
      <table class="table table-striped">
  <thead>
    <tr>
      <a class="btn btn-primary" href="all">View Candidates (<?php echo $submittedcount; ?>)</a>
          </tr>
  </thead>
  <tbody>
   
          
  </tbody>
</table>
<?php } ?>
  </div>
</div>


<div class="panel panel-default" id="candidate_activity">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title">
      <a class="dashboard_view_all" title="View All" href="msg">Messages (Unread) <td> <?php echo $new; ?></td></a>   </h3>
  </header>

  <div class="panel-body table-responsive">
  <?php if(!$new) echo "No new messages"; else{?>
      <table class="table table-striped">
  <thead>
    <tr>
      <a class="btn btn-primary" href="msg">View Messages (<?php echo $new; ?>)</a>
          </tr>
  </thead>
  <tbody>
   
          
  </tbody>
</table>
<?php } ?>
  </div>
</div>

<div class="panel panel-default" id="candidate_activity">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title">
      <a class="dashboard_view_all" title="Resource Request" href="view-all-resource-engagement">Bench Resource Request from Agencies/ Employers </a>    </h3>
  </header>

  <div class="panel-body table-responsive">
  <?php if(!$noresrcmsg){ ?>
      <table class="table table-striped">
  <thead>
    <tr>
      <th>Resource ID</th>
      <th>Requested By</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
	  
	    foreach($resrcengagearray as $row){ $getemp=mysqli_fetch_assoc(mysqli_query($link,"select a.*,b.* from members a,companyprofile b where a.memberid='".$row['engagedby']."' and a.companyid=b.id")); 
			echo' 
          <tr> 
  <td><a class="title" href="#" data-toggle="modal" data-target="#resource-detail-modal" id='.$row['resourceid'].'>'.$row['resourceid'].'<br>
    <small>Submitted: '.$row['date'].'</small> </td>
 <td>'.$getemp['name'].'</td>';
 if($row['finalstatus']==''){echo'<td><div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
	  <li><a title="Accept" href="resource-request-action?eid='.$row['engagedby'].'&&resrcid='.$row['resourceid'].'&&a=at">Approve</a></li>
	  <li><a title="Reject" href="resource-request-action?eid='.$row['engagedby'].'&&resrcid='.$row['resourceid'].'&&a=rt">Reject</a></li>
      </ul>
    </div></td>';}else{ if($row['finalstatus']==1){
	echo '<td>Approved</td>';
	}else{
	echo '<td>Rejected</td>';
	}
	}
echo'</tr>';
}
?>
  </tbody>
</table>
  <div class="text-right">
                    <a class="btn btn-primary" href="view-all-resource-engagement">View All</a>
              </div>
 <?php } else echo $noresrcmsg; ?>
  </div>
</div>


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
    
    <div class="modal fade" id="cover-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="cover-modal-label">Cover Letter</h4>
          </div>
          <div class="modal-body" id="cover-modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
</div>


 </div> 
  
    <div class="col-md-5 column ui-sortable">
    
 

<div class="panel panel-default widget-mini" id="account_statistic">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"></h3> <h3 class="panel-title"> Statistics | <a class="dashboard_view_all" title="View All" href="https://www.recruitinghub.com/faq" target=_blank>View FAQ's</a> | <a class="dashboard_view_all" title="View All" href="https://www.recruitinghub.com/locations" target=_blank>Our Offices</a> | <a class="dashboard_view_all" title="View All" href="https://www.recruitinghub.com/RecruitingHub.com%20(Agreement).pdf" target=_blank>Download Agreement</a> | <a class="dashboard_view_all" title="View All" href="https://www.recruitinghub.com/recruiterbookademo" target=_blank>Book a Demo</a> | <a class="dashboard_view_all" title="View All" href="https://www.recruitinghub.com/bankdetails" target=_blank>RH Company Details</a> </h3>
  </header>
  <div class="panel-body">
    <div class="panel-body col-sm-4">
  <small><span class="total text-center"><?php echo $live; ?></span></small>
  <small><span class="title text-center">Live Jobs</span></small>
</div>
<div class="panel-body col-sm-4">
  <small><span class="total text-center"><?php echo $total; ?></span></small>
  <small><span class="title text-center">Total Jobs</span></small>
</div>
<div class="panel-body col-sm-4">
  <small><span class="total text-center"><?php echo $filled; ?></span></small>
  <small><span class="title text-center">Jobs filled </span></small>
</div>
</div>
</div>
<?php 
 $ad=mysqli_query($link,"select * from employerad");
 if(mysqli_num_rows($ad)>0){

while($res=mysqli_fetch_array($ad)){
$adarray[]=$res;
}

 foreach($adarray as $row){echo'
<div class="images" id="candidate_activity">
 
<a href="'.$row['landingurl'].'" target=_blank><img src="../activation/employerad/'.$row['employerad'].'" alt="'.$row['employerad'].'" class="img-responsive"></a>
  
</div><br/><br/>';
}
}else{ echo ''; }
?>
</div>             

    
      
  <div class="modal fade" id="feedback-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="feedback-modal-label"></h4>
          </div>
          <div class="modal-body" id="feedback-modal-body">
          </div>
         
        </div>
      </div>
    </div>
    <div class="modal fade" id="resource-detail-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="resource-detail-modal-label"></h4>
          </div>
          <div class="modal-body" id="resource-detail-modal-body">
          </div>
         
        </div>
      </div>
    </div>
    <div class="modal fade" id="reject-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="reject-modal-label"></h4>
          </div>
          <div class="modal-body" id="reject-modal-body">
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
$(document).ready(function(){
	 //$('[data-toggle="popover"]').popover();
	 
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
				$modal.find('#engage-modal-footer a').attr("href", "edit-a-job?id="+ essayId);
            }
        });
    });
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
	$('#cover-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
            essayId = e.relatedTarget.id;
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'cover-detail.php',
            data: 'EID=' + essayId,
            success: function(data) {
				$modal.find('#cover-modal-body').html(data);
            }
        });
	});
	$('#resource-detail-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
            essayId = e.relatedTarget.id;
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'resource-detail.php',
            data: 'EID=' + essayId,
            success: function(data) {
				$modal.find('#resource-detail-modal-body').html(data);
            }
        });
	});
	$('#recruiter-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
            button = $(e.relatedTarget),
			essayId=button.attr('id'),
			requestid=button.data('value');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'recruiter-detail.php',
            data: {EID:essayId,requestid:requestid},
            success: function(data) {
				$modal.find('#recruiter-modal-content').html(data);
				//$modal.find('#recruiter-modal-label').html( $('#company_name').data('value'));}        
				  }
        });
		
	});
	
	
$('#feedback-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
            essayId = e.relatedTarget.id;
        $.ajax({
            cache: false,
            type: 'GET',
            url: 'approve-request.php',
            data: 'requestid=' + essayId,
            success: function(data) {
				$modal.find('#feedback-modal-label').html(data);
                 //$modal.find('#feedback-modal-body').html( $('#feedback_name').data('value'));
				
            }
        });
    });
	
$('#reject-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
            essayId = e.relatedTarget.id;
        $.ajax({
            cache: false,
            type: 'GET',
            url: 'reject-request.php',
            data: 'requestid=' + essayId,
            success: function(data) {
				$modal.find('#reject-modal-label').html(data);
                 //$modal.find('#feedback-modal-body').html( $('#feedback_name').data('value'));
				
            }
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