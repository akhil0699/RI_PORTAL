<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email=$iam=$adminrights=$cid=$admin='';
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


$live=$filled=$total=$engaged = $requested =0;
$countres = mysqli_query($link,"SELECT sum(case when a.status not in ('Hold','Filled','Closed') and b.status not in ('Requested','Rejected','Closed') then 1 else 0 end)  as live, sum(case when a.status = 'Filled' and b.status='Filled' then b.filledcount else 0 end) as filled,count(*) as total FROM jobs a, request b WHERE a.jobid=b.jobid and b.recruiterid='$mid'"); 
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
$jobres = mysqli_query($link,"SELECT * FROM jobs WHERE status not in ('Hold','Closed','Filled') and jobid not in (select a.jobid from members f,companyprofile g,request a where f.companyid=g.id and a.recruiterid=f.memberid and g.id='$cid') order by updatedate desc limit 0, 3"); 
$isposted = mysqli_num_rows($jobres);
if($isposted > 0){ 
    while($row = mysqli_fetch_array($jobres)){ 
		$jobarray[]=$row;
				}
}
else
$content="You do not have active jobs to engage.";


//latest requests
//$engagedres = mysql_query($link,"SELECT a.jobtitle as jobtitle,b.id as employer,c.jobid FROM request c,jobs a,employer b WHERE recruiterid='$mid' and a.jobid=c.jobid and b.id=employerid order by startdate desc"); 
$rcount = count($jobarray);
$param='';
$engagedres = mysqli_query($link,"SELECT a.jobid as jobid,a.jobtitle as jobtitle,a.filledvacancy,a.novacancies,a.memberid as employerid,a.status as status,b.name as employer,c.status as requeststatus,a.cvlimit as cvlimit,a.agencylimit,c.requestid as requestid, c.cvcount as cvcount from jobs a ,companyprofile b,request c,members d where a.jobid=c.jobid and c.recruiterid='$mid' and a.memberid=d.memberid and d.companyid=b.id order by a.updatedate desc limit 0, 3"); 
$isengaged = mysqli_num_rows($engagedres);
if($isengaged > 0){ 
    while($row = mysqli_fetch_array($engagedres)){ 
		$engagedarray[]=$row;
				}
}
else
$engagedmsg="You do not have live jobs now.";

//submitted candidates

$candidateres = mysqli_query($link,"SELECT a.jobid as jobid, a.jobtitle as jobtitle,a.memberid as employerid,d.companyname as employer,b.id as candidateid, b.fname as candidatename,b.cv as cv,c.status,c.viewnotes,c.submitdate FROM submitted_candidates c,jobs a,candidates b , employers d WHERE a.engagedby='$mid' and a.jobid=c.jobid and b.id=c.candidateid and a.memberid=d.id order by submitdate desc limit 0, 3"); 
$issubmitted = mysqli_num_rows($candidateres);
if($issubmitted > 0){ 
    while($row = mysqli_fetch_array($candidateres)){ 
		$candidatearray[]=$row;
				}
}
else
$candidatemsg="No candidates are submitted for any of the jobs or Visit Supply Candidates (STS) Folder on left side of Panel";
// unread new messages
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
$engagementsql="select a.engagedcount from members b,companyprofile a where b.companyid=a.id and b.memberid='$mid'";
$result = mysqli_query($link,$engagementsql);
									if(mysqli_num_rows($result)){
									 $engagementcredits=mysqli_fetch_array($result);
									$engagementcredit=$engagementcredits['0'];
									 }

$totalsql="select a.permonthcount from members b,companyprofile a where b.companyid=a.id and b.memberid='$mid'";
$totalallowedresult = mysqli_query($link,$totalsql);
									if(mysqli_num_rows($totalallowedresult)){
									$totalalloweds=mysqli_fetch_array($totalallowedresult);
									$totalallowed=$totalalloweds['0'];
									 }
//$totaljobcount=mysqli_result(mysqli_query($link,"select count(jobid) from jobs"),0); 
//$rating=mysqli_result(mysqli_query($link,"select AVG(rating) from employerrating where ratedto='$mid'"),0);

?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Dashboard | Recruiters Hub</title>
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
    <li> <a href="dashboard" role="tab" ><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
    <li><a href="becomefranchise" role="tab" > <i class="fa fa-database"></i>Become our Franchise <span class="label label-primary">New</span></a> </li>
    <li><a href="search" role="tab" ><i class="fa fa-fw fa-search"></i>Search Roles</a>    </li>
    <li><a href="job" role="tab"><i class="fa fa-fw fa-files-o"></i>Engaged Roles</a>    </li>
    <li><a href="disengaged" role="tab"><i class="fa fa-scissors"></i>CLOSED / DISENGAGED</a>  
    <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Supply Candidates (STS)</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="add_new" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add new Candidate</a>  </li>
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

<div class="text-right">

                    <!--<p>
     Permanent Job Engagement Credit:<?php echo $engagementcredit;echo'/';echo $totalallowed;?></p>--></div>
  <div class="tab-content">

    <div class="col-md-8 ui-sortable no-padding">
      <div class="panel panel-default" id="job">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title">Welcome back, <b><?php echo $name; ?> </b> <a class="dashboard_view_all" title="View All" href="search"> Latest Jobs from Employers (Supply Candidates and Earn 2X Faster)</a>  </h3>
  </header>
  <div class="panel-body table-responsive">
    <?php if(!$content){ ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Job Title</th>
            <th>Fee </th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
         <?php
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
if(($row['fee']=='')&&(($row['country']=='UK')||($row['country']=='US')||($row['country']=='Europe')||($row['country']=='Africa')||($row['country']=='Australia')||($row['country']=='Singapore')||($row['country']=='China')||($row['country']=='Japan')||($row['country']=='Canada')||($row['country']=='Malaysia')||($row['country']=='Philippines'))&&($row['jobtype']=='Contract')){
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
		}
		elseif((!(in_array($rfee,$parray1)))&&($row['country']=='UK')&&($rfee!='NA')){
		$sym='&pound;';
		}
		elseif($rfee=='NA'){
		$sym='';
		}
		else{
		$sym=$row['currency'];
		}
			echo'
          <tr>
  <td>
                <b><a class="title" href="employerjob?EID='.$row['jobid'].'" data-toggle="modal"  id='.$row['jobid'].'>'.$row['jobtitle'].'</a></b>';
   if(($checkpriority!='0')){echo' <span class="label label-primary">Priority</span>'; } echo'
      <br><small>Posted: '.$row['updatedate'].'</small> | <small>'.$row['joblocation'].'</small>,<small>'.$row['country'].'</small> | <small>'.$row['jobtype'].'</small></td>
  <td class="b">'.$rfee.' '.$sym.' '.$ratetype.'</td>
  <td>
  <a class="btn btn-primary" title="Engage" href="engage?jobid='.$row['jobid'].'&employerid='.$row['memberid'].'">Engage</a>
    
  </td>
</tr>';
}
?>
 </tbody>
 </table>
 <div class="text-right">
            <a class="btn btn-primary" href="search">View All Jobs</a> <a class="btn btn-primary" href="all">All Candidates</a> 
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
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
</div>

<!--<div class="panel panel-default" id="candidate_activity">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title">
      Candidates Uploaded for Engaged Jobs </h3>
  </header>

  <div class="panel-body table-responsive">
    <?php if(!$candidatemsg){ ?>
      <table class="table table-striped">
  <thead>
    <tr>
      <th>Name</th>
      <th>Job</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    
<?php
	    foreach($candidatearray as $row){
		if($row['status']=='Awaiting Feedback')
			$row['status']="Pending";
			echo'
          <tr>
  <td><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['candidateid'].'>'.$row['candidatename'].'</a> </td>
  <td><a class="title" title="View job" href="#" >'.$row['jobtitle'].'</a><br>
      <small><a title="'.$row['employer'].'" href="#">'.$row['employer'].'</a></small> </td>
   <td>'.$row['status'].'</td>
    <td><a class="title" href="#" title="view cv">View</a></td>
 </tr>';

}
?>
  </tbody>
</table>
 <?php } else echo $candidatemsg; ?>
 <div class="panel-body panel-body--buttons no-padding">
                    <a class="btn btn-primary" href="add_new">
    <i class="fa fa-plus"></i> Upload New Candidate
</a>
  </div>
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
    </div>-->

<div class="panel panel-default" id="engagement">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title">
     Your Engaged Jobs </h3>
  </header>
  <div class="panel-body table-responsive">
      <?php if(!$engagedmsg){ ?>
      <table class="table table-striped">
  <thead>
    <tr>
      <th colspan="2">Title/Employer</th>
      <th colspan="2">Status</th>
      <th>CV Limit <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No of CV's you can submit"><i class="fa fa-question-circle"></i>
    </span></th>
      <th>Agency Limit <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No of Agencies Engaged Vs No of Agencies allowed to work on this role"><i class="fa fa-question-circle"></i>
    </span></th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
 <?php
	    foreach($engagedarray as $row){
			echo'
          <tr>
  <td colspan="2"><a class="title" href="#" data-toggle="modal" data-target="#engage-modal" id='.$row['jobid'].'>'.$row['jobtitle'].'</a>  <br>
      <small>'.$row['employer'].'</small> </td>
    <td>';
	if($row['status'] == "Filled")
	echo $row['status'].'('.$row['filledvacancy'].'/'.$row['novacancies'].')';
	else 
	echo $row['status'];
	echo '</td>
  <td>'.$row['requeststatus'].'</td>
  <td>'.$row['cvcount'].'/'.$row['cvlimit'].'</td>
   <td>'.$row['agencylimit'].'</td>
  <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">';
	  if($row['requeststatus']=="Rejected"){
	  echo'<li><a title="Disengage" href="delete?requestid='.$row['requestid'].'">Remove</a></li>';
	  }
	  else{
	  echo'
            <li><a title="submit Candidate" href="application?jobid='.$row['jobid'].$param.'"><b>Submit Candidate</b></a></li>
            <li><a title="Disengage" href="disengage?jobid='.$row['jobid'].'">Disengage</a></li>';
			}
			echo'
      </ul>
    </div>
  </td>
</tr>';
}
?>
</tbody>
</table>
<div class="text-right">
                    <a class="btn btn-primary" href="job">View All</a>
              </div>
 <?php } else echo $engagedmsg; ?>
  </div>
</div>

<div class="panel panel-default" id="candidate_activity">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title">
      <a class="dashboard_view_all" title="View All" href="msg">Messages (Unread) <td> <?php echo $new; ?></td></a>    </h3>
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
   
          <tr>
   
  </td>
</tr>
  </tbody>
</table>
<?php } ?>
  </div>
</div>
</div>
<div class="col-md-4 column ui-sortable no-padding-right">
<div class="panel panel-default widget-mini" id="account_statistic">
  <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"></h3> <h3 class="panel-title"><a class="dashboard_view_all" title="View All">STATISTICS</a> | <a class="dashboard_view_all" title="View All" href="edit-company">Agency Profile</a> | <a class="dashboard_view_all" title="View All" href="https://www.recruitinghub.com/RecruitingHub.com (Agreement) for Vendors.pdf" target=_blank>Vendor Agreement</a> | <a class="dashboard_view_all" title="View All" href="https://www.recruitinghub.com/forrecruiters" target=_blank>FAQ's</a> | <a class="dashboard_view_all" title="View All" href="https://www.recruitinghub.com/recruiter/bestpractices-recruiter"><b>Best Practices</b></a> </h3>
  </header>
  <div class="panel-body">
    <div class="col-sm-4">
  <small><span class="total text-center"><?php echo $live; ?></span></small>
  <small><span class="title text-center">Live engagements</span></small>
</div>
<div class="col-sm-4">
  <small><span class="total text-center"><?php echo $total; ?></span></small>
  <small><span class="title text-center">Total engagements</span></small>
</div>
<div class="col-sm-4">
  <small><span class="total text-center"><?php echo $filled; ?></span></small>
  <small><span class="title text-center">Candidates placed</span></small>
</div>

</div>
</div>

<?php 

 $ad=mysqli_query($link,"select * from recruiterad");
 if(mysqli_num_rows($ad)>0){

while($res=mysqli_fetch_array($ad)){
$adarray[]=$res;
}

 foreach($adarray as $row){echo'
<div class="images" id="candidate_activity">
 
<a href="'.$row['landingurl'].'" target=_blank><img src="../activation/recruiterad/'.$row['recruiterad'].'" alt="'.$row['recruiterad'].'" class="img-responsive"></a>
  
</div><br/><br/>';
}}else{ echo '';}

?>

</div>             
</div>
<div class="clearfix"></div>
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
</script>

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>
</html>
