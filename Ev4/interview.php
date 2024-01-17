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
if(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['recruiter']) && ($_GET['recruiter']!='') && isset($_GET['candidate']) && ($_GET['candidate']!='')){
	$jobtitle=$_GET['jobtitle'];
	$recruiter=$_GET['recruiter'];
	$candidate=$_GET['candidate'];
	$candidateres = mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.jobid,d.name as agency,d.registerid,b.fname as fname,b.email as email,b.notice as notice,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='Interviewing' and a.jobtitle like '%$jobtitle%' and e.memberid=c.recruiterid and d.id=e.companyid and d.name = '$recruiter' and ( b.fname like '%$candidate%' or c.candidateid='$candidate')  order by submitdate desc"); 	
	}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']=='')  && isset($_GET['recruiter']) && ($_GET['recruiter']!='') && isset($_GET['candidate']) && ($_GET['candidate']!='')){
	$recruiter=$_GET['recruiter'];
	$candidate=$_GET['candidate'];
	$candidateres = mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.jobid,d.name as agency ,d.registerid, b.fname as fname,b.email as email,b.notice as notice,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='Interviewing' and e.memberid=c.recruiterid and d.id=e.companyid and d.name = '$recruiter' and (b.fname like '%$candidate%' or c.candidateid='$candidate') order by submitdate desc"); 
		
	}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['recruiter']) && ($_GET['recruiter']=='') && isset($_GET['candidate']) && ($_GET['candidate']!='')){
	$jobtitle=$_GET['jobtitle'];
	$candidate=$_GET['candidate'];
	$candidateres = mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.jobid,d.name as agency ,d.registerid, b.fname as fname,b.email as email,b.notice as notice,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='Interviewing' and a.jobtitle like '%$jobtitle%' and  d.id=e.companyid and e.memberid=c.recruiterid and (b.fname like '%$candidate%' or c.candidateid='$candidate') order by submitdate desc"); 	
	}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']=='')  && isset($_GET['recruiter']) && ($_GET['recruiter']!='')&& isset($_GET['candidate']) && ($_GET['candidate']=='')){
	$recruiter=$_GET['recruiter'];
	$candidateres = mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.jobid,d.name as agency ,d.registerid, b.fname as fname,b.email as email,b.notice as notice,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='Interviewing' and d.name = '$recruiter' and d.id=e.companyid and e.memberid=c.recruiterid  order by submitdate desc"); 
}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']=='')  && isset($_GET['recruiter']) && ($_GET['recruiter']=='')&& isset($_GET['candidate']) && ($_GET['candidate']!='')){
	$candidate=$_GET['candidate'];
	$candidateres = mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.jobid,d.name as agency ,d.registerid, b.fname as fname,b.email as email,b.notice as notice,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='Interviewing' and   d.id=e.companyid and e.memberid=c.recruiterid and (b.fname like '%$candidate%' or c.candidateid='$candidate') order by submitdate desc");

}
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['recruiter']) && ($_GET['recruiter']!='')&& isset($_GET['candidate']) && ($_GET['candidate']=='')){
	$jobtitle=$_GET['jobtitle'];
	$recruiter=$_GET['recruiter'];
	$candidateres = mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.jobid,d.name as agency ,d.registerid, b.fname as fname,b.email as email,b.notice as notice,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='Interviewing' and a.jobtitle like '%$jobtitle%' and  d.id=e.companyid and e.memberid=c.recruiterid  and d.name = '$recruiter' order by submitdate desc");

}	
elseif(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')  && isset($_GET['recruiter']) && ($_GET['recruiter']=='')&& isset($_GET['candidate']) && ($_GET['candidate']=='')){
	$jobtitle=$_GET['jobtitle'];
$candidateres = mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.jobid,d.name as agency ,d.registerid, b.fname as fname,b.email as email,b.notice as notice,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='Interviewing' and a.jobtitle like '%$jobtitle%' and  d.id=e.companyid and e.memberid=c.recruiterid   order by submitdate desc");

}	
else
{
$candidateres = mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.jobid,d.name as agency,d.registerid,b.fname as fname,b.email as email,b.notice as notice,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.jobtype,b.desiredsalary,b.expectedcurrency,b.typesalary,c.submitdate,c.candidateid as candidateid,c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='Interviewing' and d.id=e.companyid and e.memberid=c.recruiterid order by submitdate desc"); 
}
$param=($_SERVER['QUERY_STRING']);
$issubmitted = mysqli_num_rows($candidateres);
if($issubmitted > 0){ 
    while($row = mysqli_fetch_array($candidateres)){
			$candidatearray[]=$row;
				}
}
else
$candidatemsg="No candidates present under interviewing stage.";
?>

<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interviewing | Employers Hub</title>
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
<a class="btn btn-primary" href="submit-a-job">
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
  <li><a href="submit-a-job" role="tab"><i class="fa  fa-fw fa-plus"></i>Post New Job</a></li>
  <li><a href="requests" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Request from Agencies</a></li>

     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Jobs</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="submit-a-job" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Post New Job</a>  </li>
   <li><a href="jobs" role="tab" ><i class="fa fa-list"></i> Active</a> </li>
    <li><a href="inactive" role="tab" ><i class="fa fa-list"></i> Inactive</a> </li>
    <li><a href="closed" role="tab" ><i class="fa fa-clock-o"></i> Closed</a>  </li>
    <li><a href="filled" role="tab" > <i class="fa fa-users"></i> Filled</a>  </li>
  
    </ul>
   </div>
   </li>
     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu1" data-toggle="collapse" aria-controls="candidatesDropdownMenu1" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Candidates (ATS)</a>

   <div class="collapse" id="candidatesDropdownMenu1">
   <ul class="nav-sub" style="display: block;">
   
    <li><a href="all" role="tab" ><i class="fa fa-list"></i> All</a> </li>
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
    <li><a href="interview" role="tab" > <i class="fa fa-users"></i> Interviewing</a>  </li>
        <li><a href="offer" role="tab" > <i class="fa fa-envelope"></i> Offer</a> </li>
    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i> Rejected</a> </li>
    <li><a href="filledcandidates" role="tab" > <i class="fa fa-users"></i>Filled</a> </li>
    </ul>
   </div>
   </li>
   
     <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messaging (<?php echo $new; ?>)</a>    </li>
    <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Feedback Rating</a> </li>
     <li><a href="psl-list" role="tab" > <i class="fa fa-list"></i>PSL</a> </li>
     <li><a href="company-profile" role="tab" > <i class="fa fa-plus"></i>Company Profile</a> </li>
     <li><a href="add-user" role="tab" > <i class="fa fa-plus"></i>Add Users</a> </li>
     <li><a href="view-all-resource-engagement" role="tab" > <i class="fa fa-database"></i>Request for Your Bench Resources <span class="label label-primary">Free</span></a> </li>  
    <li><a href="resources" role="tab" > <i class="fa fa-database"></i>Bench Hiring <span class="label label-primary">Free</span></a> </li>  </ul>
   
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
    <h3 class="panel-title"> Interviewing Candidates <span class="label label-primary">Agencies have 6 months ownership over submitted candidates if recruited by you</span></h3>
  </header>
  
  <div class="panel-body">
        <div class="text-left">
 <a>Go to -></a> <a href="all" id="one">ALL</a> | <a href="awaiting-feedback" id="two">AWAITING FEEDBACK</a> | <a href="interview" id="three"><strong>SHORTLISTED</strong></a> | <a href="offer" id="four">OFFERED</a> | <a href="rejected" id="five">REJECTED</a>
</div>

        <div class="panel-body">
        <div class="text-right">
 <a class="btn btn-primary" href="interview-download<?php if($param)
 echo'?'.$param; ?>">
   <i class="fa fa-file-excel-o" ></i> Download in Excel
</a>
</div>
<div class="table-responsive">
   <?php if(!$candidatemsg){ ?>
    <div class="col-sm-12">
     <form method="get" action="interview">
       <div class="form-group">
        
        <label>Search Interviewing Candidates</label><br><br>
        <div class="col-sm-3">
        	<input name="jobtitle" class="form-control ui-autocomplete-input"  placeholder="Search by Job" autocomplete="off" type="text">
        </div>
         <div class="col-sm-3">
                                            <input name="recruiter" class="form-control ui-autocomplete-input" id="recruiter" placeholder="Search by Agency" autocomplete="off" type="text">
        </div> 
        <div class="col-sm-3">
        <input name="candidate" class="form-control ui-autocomplete-input" id="candidate" placeholder="Search by Candidate Name" autocomplete="off" type="text">
        </div>
        <div class="col-sm-3">
        <div class="form-group">
                                            <input value="Search" class="btn btn-primary" name="submit" type="submit">
                                             </div>
        </div>
        </div>  
        </form>                                  
        </div>
         <form method="POST" id="form1">
        <div class="form-group">
        <div class="col-sm-6">
         <div class="checkbox"><label><input value="1" name="allcandi" type="checkbox" id="select-all">Check All</label></div>
         </div>
         <div class="col-sm-6">
         <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
	  	 	<li><a class="btn"   onclick="submitForm('offer-candidate')" id="shortlist">Offer</a></li>
			<li><a  class="btn"  onclick="submitForm('reject-candidate-allcheck')" id="screenreject">Interview Reject</a></li>
			<li><a title="Message Agency" href="new-msg?eid='.$row['memberid'].'">Message Agency</a></li>
			
      </ul>
    </div>
    </div>
    </div>
   
    
            <table class="table table-striped">
              <thead>
                <tr>
              
                  <th>Candidate Name/ID</th>
                 
                  <th>Job Title/ID</th>
                 
                  <th> Agency/ID</th>
                  <th>Candidate Email id</th>
                  <th>Notice</th>
                  
                  <th>Contact</th>
                  <th>Exp Salary/Rate</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              
              
              <tbody>
                <?php
	    foreach($candidatearray as $row){
			echo'
          <tr>
		  
  <td ><input type="checkbox"  value="'.$row['candidateid'].','.$row['jobid'].'" name="allcheck[]"><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['candidateid'].'>'.$row['fname'].'</a><br>
    <small>Submitted: '.$row['submitdate'].' / '.$row['candidateid'].'</small> | '.$row['jobtype'].' </td>
	
  <td>'.$row['jobtitle'].'<br><small>'.$row['jobid'].'</small></td>
 
   <td><a class="title" href="#" data-toggle="modal" data-target="#recruiter-modal" id='.$row['registerid'].'>'.$row['agency'].'</a><br>
    <small>'.$row['recruiterid'].'</small> </td>
  <td >'.$row['email'].'</td>
   <td >'.$row['notice'].'</td>
   
	<td>'.$row['contactnumber'].'</td>
  <td>'.$row['expectedcurrency'].''.$row['desiredsalary'].' '.$row['typesalary'].'</td> 
  <td><span class="badge badge-default">'.$row['candidatestatus'].'</span></td>
  <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">	  	 	
			<li><a title="Offer Candidate" href="offer-candidate?candidateid='.$row['candidateid'].'">Offer</a></li>
		
			<li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Interview Reject</a></li>
			<li><a title="Message Agency" href="new-msg?eid='.$row['memberid'].'">Message Agency</a></li>
			
      </ul>
    </div>
  </td>
</tr>';
}
?>
</tbody>
</table>
</form>
 <?php } else echo $candidatemsg; ?> 
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
    </script>

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || 
{widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};
var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;
s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>
</html>
