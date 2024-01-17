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
$admin=$_SESSION['admin'];
$ecountry=$_SESSION['country'];
$cid=$_SESSION['cid'];

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
			
$jobarray=array();
//jobs GETed recently
$candidatemsg='';
if(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')){
$jobtitle=$_GET['jobtitle'];
$param='jobtitle='.$jobtitle;
$jobtitleres="and a.jobtitle like '%$jobtitle%'";
}

if(isset($_GET['candidate']) && ($_GET['candidate']!='')){
$candidate=$_GET['candidate'];
$param='candidate='.$candidate;
$candidateres=" and ( b.fname like '%$candidate%' or c.candidateid='$candidate')";
}

if(isset($_GET['recruiter']) && ($_GET['recruiter']!='')){
$recruiter=$_GET['recruiter'];
$param='recruiter='.$recruiter;
$recruiterres=" and d.name = '$recruiter'";
}

if(isset($_GET['jobowner']) && ($_GET['jobowner']!='')){
  $jobowner=$_GET['jobowner'];
  $jobownerres=" and a.memberid = '$jobowner'";
  }


  

if($adminrights){
$sql = "SELECT a.jobtitle as jobtitle,a.jobid,b.fname as fname,b.email as email,b.notice as notice,d.name as agency,d.registerid,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid, c.viewed as viewed,c.employernotes,c.recruiternotes,c.viewedtime,s.firstname as subuser FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e,members s WHERE s.companyid='$cid' and s.memberid=a.memberid and a.jobid=c.jobid and b.id=c.candidateid and c.status='Shortlisted'  and d.id=e.companyid and e.memberid=c.recruiterid  and submitdate >= CURDATE()-INTERVAL 6 MONTH $jobtitleres $candidateres $recruiterres $jobownerres order by submitdate desc";

//$candidateres = mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.jobid,b.fname as fname,b.email as email,b.notice as notice,d.name as agency,d.registerid,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid, c.viewed as viewed,c.employernotes,c.recruiternotes,c.viewedtime,s.firstname as subuser FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e,members s WHERE s.companyid='$cid' and s.memberid=a.memberid and a.jobid=c.jobid and b.id=c.candidateid and c.status='Shortlisted'  and d.id=e.companyid and e.memberid=c.recruiterid  and submitdate >= CURDATE()-INTERVAL 6 MONTH $jobtitleres $candidateres $recruiterres $jobownerres order by submitdate desc";
}else
{
$sql = "SELECT a.jobtitle as jobtitle,a.jobid,b.fname as fname,b.email as email,b.notice as notice,d.name as agency,d.registerid,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid, c.viewed as viewed,c.employernotes,c.recruiternotes,c.viewedtime,e.firstname as subuser FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='Shortlisted'  and d.id=e.companyid and e.memberid=c.recruiterid  and submitdate >= CURDATE()-INTERVAL 6 MONTH $jobtitleres $candidateres $recruiterres order by submitdate desc";
} 

$submittedsql="select count(id) from submitted_candidates where status='Shortlisted' and jobid in(select jobid from jobs where memberid='$mid') and submitdate>=(select lastlogin from members where memberid='$mid')";

$result = mysqli_query($link,$submittedsql);
if(mysqli_num_rows($result)){
$submittedcounts=mysqli_fetch_array($result);
$submittedcount=$submittedcounts['0'];
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

if(@mysqli_num_rows($candidateres)){ 
    while($row = mysqli_fetch_array($candidateres)){ 
      $candidatearray[]=$row;	
        }
$pages =  $pager->renderFullNav();
$total= $pager->total_rows;

/*$issubmitted = mysqli_num_rows($candidateres);
if($issubmitted > 0){ 
    while($row = mysqli_fetch_array($candidateres)){
      $candidatearray[]=$row;
        } */
}else{
  $candidatemsg="No candidates present under shortlisted stage.";

}

?>

<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shortlisted | Employers Hub</title>
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
	<style>
          .page_link {  display: inline-block;  }.page_link a {  color: black;  float: left;  padding: 5px 16px; background:#eee; text-decoration: none;}
          .page_link a.active {
  background-color: #4CAF50;
  color: white;
}

.page_link a:hover:not(.active) {background-color: #ddd;}

          </style>
         
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
  <!--<li><a href="requests" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Request from Agencies</a></li>-->

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
   <!-- <li><a href="interview" role="tab" > <i class="fa fa-users"></i> Interviewing</a>  </li> -->
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
 
    
    <section class="main-content-wrapper">
        <div class="pageheader pageheader--buttons">
          
        </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> Shortlisted Candidates <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Agencies have 6 months ownership over submitted candidates if not duplicated by you within 48 hours of cv submission and recruited by you within 6 months from the date of cv submission">(CV Ownership) <i class="fa fa-question-circle"></i>
    </span> </h3>
  </header>
  
  <div class="panel-body">
        <div class="text-left">
 Go to -> <a href="all" id="one">ALL CANDIDATES</a> -> <a href="awaiting-feedback" id="two">AWAITING FEEDBACK</a> -> <a href="shortlist" id="three"><strong>SHORTLISTED</strong></a> -> <a href="offer" id="four">OFFERED</a> -> <a href="rejected" id="five">REJECTED</a> -> <a href="filled" id="six">FILLED</a> || <a href="jobs" id="seven">JOBS ACTIVE</a> || <a class="btn btn-primary" href="interview-download<?php if($param)
 echo'?'.$param; ?>">
   <i class="fa fa-file-excel-o" ></i> Download in Excel
</a>
</div>

    <!--    <div class="panel-body">
        <div class="text-right">
 <a class="btn btn-primary" href="interview-download<?php if($param)
 echo'?'.$param; ?>">
   <i class="fa fa-file-excel-o" ></i> Download in Excel
</a>
</div> --> 

<div class="table-responsive">
<div class="col-sm-12">
     <form method="get" action="shortlist">
       <div class="form-group">
        
     <div class="col-sm-2">
            <input name="jobtitle" class="form-control ui-autocomplete-input"  placeholder="Job Title" autocomplete="off" type="text">
        </div>
        
        <div class="col-sm-2">
                                            <input name="candidate" class="form-control ui-autocomplete-input" id="candidate" placeholder="Candidate Name" autocomplete="off" type="text">
        </div>
        
        <div class="col-sm-2">
                                            <input name="recruiter" class="form-control ui-autocomplete-input" id="recruiter" placeholder="Agency Name" autocomplete="off" type="text">
        </div>
        <?php if($adminrights){ ?>
        <div class="col-sm-2 ">
                                            <select class="form-control" name="jobowner" id="jobowner">
                                            <option value="">Job Owner</option>
                                           <?php 
											
											 $sql1=mysqli_query($link,"select firstname,memberid from members where companyid='$cid'"); 
											 
							if(mysqli_num_rows($sql1)){
								while($r=mysqli_fetch_array($sql1)){
									$r1[]=$r['firstname'].'-'.$r['memberid'];
                
								}
							}
							foreach($r1 as $r2){
                $explode = explode('-',$r2);
								echo '<option value="'.$explode[1].'">'.$explode[0].'</option>';
							}
							 ?>
                                            </select> 
                                            
        </div>
        <?php }?>
        
        <div class="col-sm-3">
        <div class="form-group">
                                            <input value="Search" class="btn btn-primary" name="submit" type="submit">
                                             </div>
        </div>
        </div>  
        </form>                                  
        </div>
   <?php if(!$candidatemsg){ ?>
  
        
        <h3>Total Shortlisted <b><?php echo $jobtitle; ?></b> - <b><?php echo $total; ?></b></h3>
        
         <form method="POST" id="form1">
        <div class="form-group">
        <div class="col-sm-6">
         <div class="checkbox"><label><input value="1" name="allcandi" type="checkbox" id="select-all">Select All</label></div>
         </div>
         <div class="col-sm-6">
         <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
	  	 	<li><a class="btn"   onclick="submitForm('offer-candidate')" id="shortlist">Offer</a></li>
			<li><a  class="btn"  onclick="submitForm('reject-candidate-allcheck')" id="interviewreject">Interview Reject</a></li>
		
			
      </ul>
    </div>
    </div>
    </div>
   
    
            <table class="table table-striped">
              <thead>
                <tr>
              
                  <th>Candidate Name/ID</th>
                  <th>Job Title/ID</th>
                  <th>Action</th>
                  <th> Agency/ID</th>
                  <th>Candidate Email/Phone/Notice</th>
                  <?php if($adminrights)
                  echo'<th>Job Owner</th>';
				  ?>
               
                  
                </tr>
              </thead>
              
              
              <tbody>
                <?php
	    foreach($candidatearray as $row){
			echo'
          <tr>
		  
  <td ><input type="checkbox"  value="'.$row['candidateid'].','.$row['jobid'].'" name="allcheck[]"> <b><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['candidateid'].'>'.$row['fname'].'</a></b><br>
    <small>Submitted: '.$row['submitdate'].' / '.$row['candidateid'].'</small> | '.$row['jobtype'].' <br><small>Employer Notes: <b>'.$row['employernotes'].'</b></small> <br><small>Recruiter Notes: <b>'.$row['recruiternotes'].'</b></small></td>
    
   <td><a href="/employer/shortlist?jobtitle='.$row['jobtitle'].'&candidate=&agency=&status=&submit=Search" >'.$row['jobtitle'].' - <small>'.$row['jobid'].'</small> <br> <small><span class="badge badge-default">'.$row['candidatestatus'].'</span></small></td>
    
 <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">	  	 	
    <li><a title="Offer Candidate" href="offer-candidate?candidateid='.$row['candidateid'].'"><b>Offer</b></a></li>
		
			<li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Interview Reject</a></li>
			 <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>
			
			
      </ul>
    </div>
  </td>    
    

 
   <td><a class="title" href="#" data-toggle="modal" data-target="#recruiter-modal" id='.$row['registerid'].'>'.$row['agency'].'</a><br>
    <small>'.$row['recruiterid'].'</small> </td>
  <td >'.$row['email'].'<br>'.$row['contactnumber'].'<br>'.$row['notice'].'</td>';
    
     if ($adminrights) echo '<td><b>'.$row['subuser'].'</b></td>
 
  
</tr>';
}
?>
</tbody>
</table>
</form>
 <?php echo $pages; } else echo $candidatemsg; ?> 
</div>

      </div>
      </div>
    </div>
  </div>
</section>
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
    
    <div class="modal fade" id="notes-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="notes-modal-label"></h4>
          </div>
          <div class="modal-body" id="notes-modal-body">
          </div>
         
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
<script language='JavaScript'>
  $('#select-all').click(function(event) {   
    if(this.checked) {
      // Iterate each checkbox
      $(':checkbox').each(function() {
        this.checked = true;                        
      });
    }
    else {
      // Iterate each checkbox
      $(':checkbox').each(function() {
        this.checked = false;
      });
    }
  });
function submitForm(action) {
    var form = document.getElementById('form1');
    form.action = action;
    form.submit();
  }
</script>

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
    });*****/	
	
	$( "#recruiter" ).autocomplete({
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
 <script>
$('#feedback-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),
		element=$(e.relatedTarget),
            essayId = element.attr('id'),
			jobids = element.data('id');
			
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'reject-candidate.php',
            data: {EID:essayId,jobids:jobids},
            success: function(data) {
				$modal.find('#feedback-modal-label').html(data);
                $modal.find('#feedback-modal-body').html( $('#feedback_name').data('value'));
				
            }
        });
    }); 
    
    $('#notes-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),
		element=$(e.relatedTarget),
            cid = element.attr('id'),
			jobid = element.data('id');
			
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'employernotes.php',
            data: {CID:cid,jobid:jobid},
            success: function(data) {
				$modal.find('#notes-modal-label').html(data);
                $modal.find('#notes-modal-body').html( $('#feedback_name').data('value'));
				
            }
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
