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
else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login.php'); 
exit;
}
require_once '../config.php';
			
$jobarray=array();
//jobs posted recently
$candidatemsg='';
if(isset($_POST['jobtitle']) && ($_POST['jobtitle']!='')  && isset($_POST['recruiter']) && ($_POST['recruiter']!='')){
	$jobtitle=$_POST['jobtitle'];
	$recruiter=$_POST['recruiter'];
	$candidateres = mysql_query("SELECT a.jobtitle as jobtitle, b.fname as fname,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='submitted' and a.jobtitle='$jobtitle' and e.memberid=c.recruiterid and d.id=e.companyid and d.name = '$recruiter' order by submitdate desc"); 
	}
elseif(isset($_POST['jobtitle']) && ($_POST['jobtitle']=='')  && isset($_POST['recruiter']) && ($_POST['recruiter']!='')){
	$recruiter=$_POST['recruiter'];
	$candidateres = mysql_query("SELECT a.jobtitle as jobtitle, b.fname as fname,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b,companyprofile d,members e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='submitted' and e.memberid=c.recruiterid and d.id=e.companyid and d.name = '$recruiter' order by submitdate desc"); 
	}
elseif(isset($_POST['jobtitle']) && ($_POST['jobtitle']!='')  && isset($_POST['recruiter']) && ($_POST['recruiter']=='')){
	$jobtitle=$_POST['jobtitle'];
	$candidateres = mysql_query("SELECT a.jobtitle as jobtitle, b.fname as fname,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='submitted' and a.jobtitle='$jobtitle' order by submitdate desc"); 
	}
else
{
$candidateres = mysql_query("SELECT a.jobtitle as jobtitle, b.fname as fname,b.contactnumber,b.cv as cv,c.status as candidatestatus,c.viewnotes,b.desiredsalary,c.submitdate,c.candidateid as candidateid, c.recruiterid as recruiterid FROM submitted_candidates c,jobs a,candidates b WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='submitted' order by submitdate desc"); 
}
$issubmitted = mysql_num_rows($candidateres);
if($issubmitted > 0){ 
    while($row = mysql_fetch_array($candidateres)){
			$candidatearray[]=$row;
				}
}
else
$candidatemsg="No pending feedbacks.";
?>

<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>hub</title>
    <meta name="application-name" content="Chennai Properties" />
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
<a class="btn btn-primary" href="submit-a-job.php">
     Helpline number +91 7339111044
</a>
</div>
<ul class="navbar-right">
  <li class="dropdown">
    <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">
  <img src="images/user.png" class="img-circle" alt="">     <span class="text"><?php echo $name; ?></span><span class="caret"></span> </a>
    <ul class="dropdown-menu" role="menu"><li><a href="company-profile.php"><i class="fa fa-user"></i>Company Profile</a>   </li>
      <li><a href="edit-profile.php"><i class="fa fa-gears"></i>Your Account</a></li>
       <li><a href="manage-users.php"><i class="fa fa-users"></i>Manage Users</a></li>
      <li class="divider"></li>
      <li> <a href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></li>
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
  <li><a href="dashboard.php" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>

     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Jobs</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="submit-a-job.php" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add new</a>  </li>
   <li><a href="jobs.php" role="tab" ><i class="fa fa-list"></i> Active</a> </li>
    <li><a href="inactive.php" role="tab" ><i class="fa fa-list"></i> Inactive</a> </li>
    <li><a href="closed.php" role="tab" ><i class="fa fa-clock-o"></i> Closed</a>  </li>
    <li><a href="filled.php" role="tab" > <i class="fa fa-users"></i> Filled</a>  </li>
  
    </ul>
   </div>
   </li>
     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu1" data-toggle="collapse" aria-controls="candidatesDropdownMenu1" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Candidates</a>

   <div class="collapse" id="candidatesDropdownMenu1">
   <ul class="nav-sub" style="display: block;">
   
    <li><a href="all.php" role="tab" ><i class="fa fa-list"></i> All</a> </li>
    <li><a href="awaiting-feedback.php" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
    <li><a href="interview.php" role="tab" > <i class="fa fa-users"></i> Interviewing</a>  </li>
        <li><a href="offer.php" role="tab" > <i class="fa fa-trash"></i> Offer</a> </li>
    <li><a href="rejected.php" role="tab" > <i class="fa fa-trash"></i> Rejected</a> </li>
    </ul>
   </div>
   </li>
   
     <li><a href="msg.php" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messages</a>    </li>
   
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
    <h3 class="panel-title"> Awaiting feedback </h3>
  </header>
  
        <div class="panel-body">
<div class="table-responsive">
   <?php if(!$candidatemsg){ ?>
    <div class="col-sm-12">
     <form method="post" action="awaiting-feedback.php">
       <div class="form-group">
        <br>
        <label>Search Jobs</label><br><br>
        <div class="col-sm-4">
        	<input name="jobtitle" class="form-control ui-autocomplete-input" id="jobtitle" placeholder="Search by Job" autocomplete="off" type="text">
        </div>
        <div class="col-sm-4">
                                            <input name="recruiter" class="form-control ui-autocomplete-input" id="recruiter" placeholder="Search by Recruiter" autocomplete="off" type="text">
        </div> 
        <div class="col-sm-4">
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
                  <th>Candidate Name</th>
                  <th>Job Title</th>
                  <th>View CV</th>
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

  <td><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['candidateid'].'>'.$row['fname'].'</a><br>
    <small>Submitted: '.$row['submitdate'].'</small> </td>
	<td>'.$row['jobtitle'].'</td>
   <td><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['candidateid'].'>CV</a></td>
	<td>'.$row['contactnumber'].'</td>
  <td>'.$row['desiredsalary'].'</td>  
  <td><span class="badge badge-default">'.$row['candidatestatus'].'</span></td>
  <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
	  	 	 <li><a title="Select Candidate" href="select-candidate.php?candidateid='.$row['candidateid'].'">Shortlist</a></li>
			<li><a title="Reject Candidate" href="reject-candidate.php?candidateid='.$row['candidateid'].'">Screen Reject</a></li>
			<li><a title="Mark as Duplicate" href="mark-duplicate-candidate.php?candidateid='.$row['candidateid'].'">Duplicate</a></li>
      </ul>
    </div>
  </td>
</tr>';
}
?>
</tbody>
</table>
 <?php } else echo $candidatemsg; ?> 
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
	
	 $( "#jobtitle" ).autocomplete({
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
    });	
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

 </body>
</html>
