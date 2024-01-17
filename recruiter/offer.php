<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email=$iam=$candidatemsg='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$adminrights=$_SESSION['adminrights'];
$cid=$_SESSION['cid'];
$admin=$_SESSION['admin'];
$rcountry=$_SESSION['country'];
}
else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login'); 
exit;
}
require_once '../config.php';
			
$candidatearray=array();
//jobs posted recently
$content='';
$employerres=$jobtitleres=$candres='';
if(isset($_GET['submit'])){
if(isset($_GET['employer']) && ($_GET['employer']!='')){
$employer=$_GET['employer'];
$param='employer='.$employer;
$employerres="and d.name = '$employer'";
}
if(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')){
$jobtitle=$_GET['jobtitle'];
$param='jobtitle='.$jobtitle;
$jobtitleres="and a.jobtitle like '%$jobtitle%'";
}
if(isset($_GET['candidate']) && ($_GET['candidate']!='')){
$candidate=$_GET['candidate'];
$param='candidate='.$candidate;
$candres="and b.fname = '$candidate'";
}
}

/******if(isset($_POST['jobtitle']) && ($_POST['jobtitle']!='')  && isset($_POST['employer']) && ($_POST['employer']!='')){
	$jobtitle=$_POST['jobtitle'];
	$employer=$_POST['employer'];
	$candidateres = mysqli_query($link,"
	SELECT 
		a.jobid,a.jobtitle as jobtitle,	a.memberid,a.fee, b.id, b.fname as fname, b.contactnumber,b.cv as cv,b.desiredsalary,b.currentjobtitle,c.status as candidatestatus,c.viewnotes,c.submitdate,c.candidateid as candidateid,d.name
	FROM 
		submitted_candidates c,	jobs a,	candidates b, companyprofile d, members e 
	WHERE 
		a.jobid=c.jobid and b.id=c.candidateid and c.status='Filled' and a.jobtitle='$jobtitle' and e.memberid=a.memberid and 
		d.id=e.companyid and d.name = '$employer' and c.recruiterid='$mid' 
	ORDER BY 
		submitdate desc
		"); 
	}
elseif(isset($_POST['jobtitle']) && ($_POST['jobtitle']=='')  && isset($_POST['employer']) && ($_POST['employer']!='')){
	$employer=$_POST['employer'];
	$candidateres = mysqli_query($link,"
	SELECT 
		a.jobid,a.jobtitle as jobtitle,	a.memberid,a.fee, b.id, b.fname as fname, b.contactnumber,b.cv as cv,b.desiredsalary,b.currentjobtitle,c.status as candidatestatus, c.viewnotes, c.submitdate,c.candidateid as candidateid, d.name
	FROM 
		submitted_candidates c,	jobs a,	candidates b, companyprofile d, members e 
	WHERE 
		a.jobid=c.jobid and b.id=c.candidateid and c.status='Filled' and e.memberid=a.memberid and 
		d.id=e.companyid and d.name = '$employer' and c.recruiterid='$mid' 
	ORDER BY 
		submitdate desc
		"); 
	}
elseif(isset($_POST['jobtitle']) && ($_POST['jobtitle']!='')  && isset($_POST['employer']) && ($_POST['employer']=='')){
	$jobtitle=$_POST['jobtitle'];
	$candidateres = mysqli_query($link,"
	SELECT 
		a.jobid,a.jobtitle as jobtitle,	a.memberid,a.fee, b.id, b.fname as fname, b.contactnumber,b.cv as cv,b.desiredsalary,b.currentjobtitle,c.status as candidatestatus,
		c.viewnotes,c.submitdate,c.candidateid as candidateid,d.name
	FROM 
		submitted_candidates c,	jobs a,	candidates b, companyprofile d, members e
	WHERE 
		a.jobid=c.jobid and b.id=c.candidateid and c.status='Filled' and a.jobtitle='$jobtitle'  and e.memberid=a.memberid and 
		d.id=e.companyid and c.recruiterid='$mid' 
	ORDER BY 
		submitdate desc
		"); 
	}
	else{
	$candidateres = mysqli_query($link,"
	SELECT 
		a.jobid,a.jobtitle as jobtitle,	a.memberid,a.fee, b.id, b.fname as fname, b.contactnumber,b.cv as cv,b.desiredsalary,b.currentjobtitle,c.status as candidatestatus,
		c.viewnotes,c.submitdate,c.candidateid as candidateid,d.name
	FROM 
		submitted_candidates c,	jobs a,	candidates b, companyprofile d, members e 
	WHERE 
		a.jobid=c.jobid and b.id=c.candidateid and c.status='Filled' and e.memberid=a.memberid and 
		d.id=e.companyid  and c.recruiterid='$mid' 
	ORDER BY 
		submitdate desc
		"); 
	}*******/
//$candidateres = mysqli_query($link,"	SELECT a.jobid,a.jobtitle as jobtitle, a.memberid,a.fee, b.id, b.fname as fname, b.contactnumber,b.cv as cv,b.desiredsalary,b.currentjobtitle,c.status as candidatestatus, c.viewnotes,c.submitdate,c.candidateid as candidateid,d.name FROM submitted_candidates c, jobs a, candidates b, companyprofile d, members e,request f WHERE a.jobid=c.jobid and b.id=c.candidateid and c.status='Filled' and e.memberid=a.memberid and d.id=e.companyid and c.recruiterid='$mid' and c.jobid=f.jobid and c.recruiterid=f.recruiterid and f.filledcount > 0 ORDER BY submitdate desc ");
$sql = "
	SELECT 
		a.jobid,a.jobtype,c.viewed,c.viewedtime,f.firstname as subuser,f.email as subuseremail,c.employernotes,c.amnotes,c.recruiternotes,a.jobtitle as jobtitle,	a.memberid,a.fee, b.id, b.fname as fname, b.contactnumber,b.billable as billable,b.net as net,b.agencyshare as agencyshare,b.paymentadv as paymentadv,b.notes as notes,b.expectedcurrency as expectedcurrency,b.doj as doj,b.paymentdate as paymentdate,b.cv as cv,b.desiredsalary,b.currentjobtitle,c.status as candidatestatus,
		c.viewnotes,c.submitdate,c.candidateid as candidateid,d.name
	FROM 
		submitted_candidates c,	jobs a,	candidates b, companyprofile d, members e, members f
	WHERE 
		a.jobid=c.jobid and b.id=c.candidateid and c.status='Offered' and e.memberid=a.memberid and d.id=e.companyid  and f.companyid='$cid' and c.recruiterid=f.memberid and submitdate>=CURDATE()-INTERVAL 6 MONTH $employerres $jobtitleres $candres
	ORDER BY 
		submitdate desc
		"; 
	
$param="";
$params=$_SERVER['QUERY_STRING'];
if (strpos($params,'page') !== false) {
    $key="page";
	parse_str($params,$ar);
	$param=http_build_query(array_diff_key($ar,array($key=>"")));
}
else
$param=$params;
include('ps_pagination.php');
$pager = new PS_Pagination($link, $sql, 20, 10, $param);

$candidateres = $pager->paginate();

if(@mysqli_num_rows($candidateres)){ 
    while($row = mysqli_fetch_array($candidateres)){ 
	$candidatearray[]=$row;
				}
				
	$pages =  $pager->renderFullNav();
	$total= $pager->total_rows;
	$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
}
else
$candidatemsg="No offered candidates yet. Please check Filled folder.";
?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Offer | Recruiters Hub</title>
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
       <li><a href="rmanage-notification"><i class="fa fa-users"></i>Manage Notification</a></li>
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
    <li><a href="disengaged" role="tab"><i class="fa fa-scissors"></i>CLOSED/DISENGAGED</a>  
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
    <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messaging </a>    </li>
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
  <div class="tab-content">
 
    
    <section class="main-content-wrapper">
        <div class="pageheader pageheader--buttons">
          
        </div>

  <div class="row">
    <div class="col-xs-12">
      <a class="btn btn-primary" href="add_new">
    <i class="fa fa-plus"></i> Add New Candidate
</a>
 <a class="btn btn-primary" href="offer-download<?php if($param)
 echo'?'.$param; ?>">
   <i class="fa fa-file-excel-o" ></i> Download in Excel
</a>
      <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> OFFERED CANDIDATES > Follow up closely with your candidates until they join</h3>
  </header>
  
  <div class="panel-body">
        <div class="text-left">
 Go to -> <a href="add_new" id="one">ADD NEW CANDIDATE</a> -> <a href="all" id="two"><i class="fa fa-list"></i> ALL</a> -> <a href="awaiting-feedback" id="three"><i class="fa fa-clock-o"></i> AWAITING FEEDBACK</a> -> <a href="interview" id="four"><i class="fa fa-user"></i> SHORTLISTED</a> -> <a href="offer" id="five"><i class="fa fa-download"></i> <strong>OFFERED</strong></a> -> <a href="rejected" id="six"><i class="fa fa-trash"></i> REJECTED</a> -> <a href="filled" id="seven"><i class="fa fa-money"></i> FILLED</a> || <a href="new-msg" id="eight"><i class="fa fa-fw fa-envelope-o"></i> SEND MESSAGE TO EMPLOYER</a>
</div>

        <div class="panel-body">
        <div class="text-right">
<!-- <a class="btn btn-primary" href="offer-download<?php if($param)
 echo'?'.$param; ?>">
   <i class="fa fa-file-excel-o" ></i> Download in Excel
</a> -->
</div>
<div class="table-responsive">
   <?php if(!$candidatemsg){ ?>
   <div class="col-sm-12">
 <form method="get" action="offer">
       <div class="form-group">
        <br>
        <!-- <label>Search Candidates</label><br><br> -->
        <div class="col-sm-3">
        	<input name="jobtitle" class="form-control ui-autocomplete-input"  placeholder="Search by Job" autocomplete="off" type="text">
        </div>
        <div class="col-sm-3">
                                            <input name="employer" class="form-control ui-autocomplete-input" id="employer" placeholder="Search by Employer" autocomplete="off" type="text">
        </div> 
        <div class="col-sm-3">
                                            <input name="candidate" class="form-control ui-autocomplete-input" id="candidate" placeholder="Search by Candidate" autocomplete="off" type="text">
        </div>
        <div class="col-sm-3">
        <div class="form-group">
                                            <input value="Search" class="btn btn-primary" name="submit" type="submit">
                                             </div>
        </div>
        </div>  
        </form>                                          
        </div>
        <h3>Total Offered:<?php echo $total; ?> (displaying last 6 months submissions)</h3>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Candidate Name / ID</th>
                  <th>Job Title / Job ID / Employer Name</th>
                  <?php if($adminrights)
                  echo'<th>Recruiter</th>';
				  ?>
                  <th>Phone</th>
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
		  
  <td><b><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['id'].'>'.$row['fname'].'</a></b><br>
  <small>Submitted:'.$row['submitdate'].' / '.$row['candidateid'].'</small> | '.$row['jobtype'].' <br> <small>Employer Notes: '.$row['employernotes'].'</small> <br> <small>Recruiter Notes: '.$row['recruiternotes'].'</small> <br> <small>AM Notes: '.$row['amnotes'].'</small> <br> <small>'.$row['comment'].'</small></td>
  
  <td> <a class="title" href="#" data-toggle="modal" data-target="#engage-modal" id='.$row['jobid'].'>'.$row['jobtitle'].'</a><br><b>'.$row['name'].'</b> / <small>'.$row['jobid'].'</small> </td>';
  
  if($adminrights)
   echo '<td>'.$row['subuser'].'</td>
  
	<td>'.$row['contactnumber'].'</td>
  <td>'.$row['expectedcurrency'].''.$row['desiredsalary'].' '.$row['typesalary'].'</td>  
  <td><span class="badge badge-default">'.$row['candidatestatus'].'</span></td>
  <td class="text-right">
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
      <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>Add Notes</b></a></li>
	  	 	<li><a title="Message Employer" href="new-msg?eid='.$row['memberid'].'">Message Employer</a></li>
			
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
	
/**** $( "#jobtitle" ).autocomplete({
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
    });	****/
$( "#employer" ).autocomplete({
	minLength: 2,
	source:'esearch.php',
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
    
     $('#notes-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),
		element=$(e.relatedTarget),
            cid = element.attr('id'),
			jobid = element.data('id');
			
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'recruiternotes.php',
            data: {CID:cid,jobid:jobid},
            success: function(data) {
				$modal.find('#notes-modal-label').html(data);
                $modal.find('#notes-modal-body').html( $('#feedback_name').data('value'));
				
            }
        });
    });
</script>

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>
</html>
