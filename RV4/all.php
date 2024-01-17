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
$param="";
if(isset($_GET['jobid'])){
$jobid=$_GET['jobid'];
$param="&jobid=".$jobid;
}
require_once '../config.php';
			
$jobarray=array();
$candidatemsg='';
$filter='';
$fi=0;
if(isset($_GET['status']) && ($_GET['status']!='')){
	$status=$_GET['status'];
	if($status=='New')
	$filter="status='New'";
	
	elseif($status=='Awaiting Feedback')
	$filter="status='Awaiting Feedback'";
	
	elseif($status=='Shortlisted')
	$filter="status='Shortlisted'";
	
	elseif($status=='Duplicate')
	$filter="status='Duplicate'";
	
	elseif($status=='CV Rejected')
	$filter="prestatus='Awaiting Feedback'";
	
	elseif($status=='Interview Reject')
	$filter="prestatus='Shortlisted'";
	
	elseif($status=='Offered')
	$filter="status='Offered'";
	
	elseif($status=='Filled')
	$filter="status='Filled'";
	
	elseif($status=='Offer Rejected')
	$filter="prestatus='Offered'";
	
	$fi++;
	
}
if(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')){
	$jobtitle=$_GET['jobtitle'];
	if($fi>0)
		$filter.=" and jobtitle like '%$jobtitle%'";
	else
		$filter.="jobtitle like '%$jobtitle%'";
	$fi++;
}
if(isset($_GET['employer']) && ($_GET['employer']!='')){
	$employer=$_GET['employer'];
	if($fi>0)
		$filter.=" and employer = '$employer'";
	else
		$filter="employer = '$employer'";
	$fi++;
}

if(isset($_GET['candidate']) && ($_GET['candidate']!='')){
	$candidate=$_GET['candidate'];
	if($fi>0)
		$filter.=" and fname like '%$candidate%'";
	else
		$filter.="fname like '%$candidate%'";
	$fi++;
}
if($filter=='')
$filter=1;
if($adminrights){
$sql="SELECT r.* from ((SELECT a.status,a.prestatus,a.comment,a.employernotes,a.amnotes,a.viewed,a.viewedtime,b.jobid,b.status as jobstatus,b.jobtitle,d.name as employer,e.fname,e.jobtype,e.desiredsalary,e.expectedcurrency,e.typesalary,e.contactnumber,e.id,e.addeddate,f.firstname as subuser,f.email as subuseremail,a.id as sid FROM submitted_candidates a,jobs b,members c, companyprofile d,candidates e,members f,companyprofile g WHERE a.candidateid=e.id and a.jobid=b.jobid and b.memberid=c.memberid and c.companyid=d.id and f.companyid=g.id and g.id='$cid' and a.recruiterid=f.memberid) UNION (SELECT  'New' as status,NULL as viewed,NULL as viewedtime,NULL as prestatus,NULL as comment,NULL as employernotes,NULL as amnotes,NULL as jobid,NULL as jobtitle,NULL as jobstatus,NULL as name,a.fname,a.jobtype,a.desiredsalary,a.expectedcurrency,a.typesalary,a.contactnumber,a.id,a.addeddate,b.firstname as subuser,b.email as subuseremail,NULL as sid from candidates a,members b,companyprofile c where b.companyid=c.id and c.id='$cid' and a.recruiterid=b.memberid and a.id not in(select distinct candidateid from submitted_candidates))) r where $filter and addeddate>=CURDATE() - INTERVAL 6 MONTH order by addeddate desc";


}else{

$sql="SELECT r.* from ((SELECT a.status,a.prestatus,a.comment,a.amnotes,a.employernotes,a.viewed,a.viewedtime,b.jobid,b.status as jobstatus,b.jobtitle,d.name as employer,e.fname,e.jobtype,e.desiredsalary,e.expectedcurrency,e.typesalary,e.contactnumber,e.id,e.addeddate,a.id as sid FROM submitted_candidates a,jobs b,members c, companyprofile d,candidates e WHERE a.candidateid=e.id and a.jobid=b.jobid and b.memberid=c.memberid and c.companyid=d.id and a.recruiterid='$mid') UNION (SELECT  'New' as status,NULL as prestatus,NULL as comment,NULL as employernotes,NULL as amnotes,NULL as jobid,NULL as viewed,NULL as viewedtime,NULL as jobtitle,NULL as jobstatus,NULL as name,fname,jobtype,desiredsalary,expectedcurrency,typesalary,contactnumber,id,addeddate,NULL as sid from candidates where recruiterid='$mid' and id not in(select distinct candidateid from submitted_candidates))) r where $filter and addeddate>=CURDATE() - INTERVAL 6 MONTH order by addeddate desc";

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
$candidatemsg="No candidates submitted by you yet! Use '<b>Add New Candidate</b>' button above to start.";

?>

<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>All Candidates | Recruiters Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
     <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
        <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.4.custom.min.css"/>
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
         
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
        <a class="btn btn-primary" href="add_new">
    <i class="fa fa-plus"></i> Add New Candidate
</a> <a class="btn btn-primary" href="https://recruitinghub.com/Candidate Submissions.pdf" target="_blank">
    <i class="fa fa"></i> Check if you Submitted Candidates Properly?
</a>  
<a class="btn btn-primary" href="https://recruitinghub.com/RighttoRepresent.docx" target="_blank">
    <i class="fa fa"></i> Download Righ to Represent
</a> 
<a class="btn btn-primary" href="https://recruitinghub.com/Zero Tolerance Policy.pdf" target="_blank">
    <i class="fa fa"></i> Zero Tolerance Policy
</a>
      <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> All Candidates -> <a href="add_new">
    <i class="fa fa-plus"></i> Add New Candidate
</a> and Submit to Engaged Jobs <!--<span class="label label-primary">Agencies have 6 months ownership over submitted candidates if not duplicated by Client - Keep in good touch with your candidates</span></h3>
    <span class="label label-primary">While we respect your hardwork in sourcing candidates, be rest assured we chase clients constantly for feedback. <br>Please deal with our staff with due respect while using the chat facility. We have zero tolerance on the abuse of our staff.</span> -->
  </header>
  
  <div class="panel-body">
        <div class="text-left">
 Go to -> <a href="add_new" id="one">ADD NEW CANDIDATE</a> -> <a href="all" id="two"><strong><i class="fa fa-list"></i> ALL</strong></a> -> <a href="awaiting-feedback" id="three"><i class="fa fa-clock-o"></i> AWAITING FEEDBACK</a> -> <a href="interview" id="four"><i class="fa fa-user"></i> SHORTLISTED</a> -> <a href="offer" id="five"><i class="fa fa-download"></i> OFFERED</a> -> <a href="rejected" id="six"><i class="fa fa-trash"></i> REJECTED</a> -> <a href="filled" id="seven"><i class="fa fa-money"></i> FILLED</a> || <a href="new-msg" id="eight"><i class="fa fa-fw fa-envelope-o"></i> SEND MESSAGE TO EMPLOYER</a>
</div>

        <div class="panel-body">
        <div class="text-right">
 <a class="btn btn-primary" href="all-download<?php if($param)
 echo'?'.$param; ?>">
   <i class="fa fa-file-excel-o" ></i> Download in Excel
</a>
</div>
<div class="table-responsive">
   <?php if(!$candidatemsg){ ?>
   <div class="col-sm-12">
    <form method="get" action="all">
       <div class="form-group">
        <br>
        <!-- <label>Search Candidates</label><br><br> -->
        <div class="col-sm-2">
        	<input name="jobtitle" class="form-control ui-autocomplete-input" placeholder="Search by Job" autocomplete="off" type="text">
        </div>
        <div class="col-sm-3">
                                            <input name="employer" class="form-control ui-autocomplete-input" id="employer" placeholder="Search by Employer" autocomplete="off" type="text">
        </div>
        <div class="col-sm-3">
                                            <input name="candidate" class="form-control ui-autocomplete-input" id="candidate" placeholder="Search by Candidate" autocomplete="off" type="text">
        </div> 
         <div class="col-md-2">
                    <Select class="form-control"  name="status" type="text">
                    <option value="">Status</option>
                    <option value="New">New</option>
                    <option value="Awaiting Feedback">Awaiting Feedback</option>
                     <option value="Shortlisted">Shortlisted</option>
                     <option value="Duplicate">Duplicate</option>
                      <option value="CV Rejected">CV Rejected</option>
                      <option value="Interview Reject">Interview Reject</option>
                      <option value="Offered">Offered</option>
                      <option value="Offer Rejected">Offer Rejected</option>
                      <option value="Filled">Filled</option>
                      
                                         </Select>
                  </div> 
        
        <div class="col-sm-2">
        <div class="form-group">
                                            <input value="Search" class="btn btn-primary" name="submit" type="submit">
                                             </div>
        </div>
        </div>  
        </form>                           
        </div>
        <h3>Total Candidates:<?php echo $total; ?> (displaying last 6 months submissions)</h3>
        <div class="text-center">
 <b>No feedback yet? Click Open the Profile and click "<b>Add Notes</b>" to follow up with Client (Please be polite to clients when chasing for feedback)</b>
</div>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Candidate Name/ID/Status</th>
                  <th>Job Title/ID/Employer</th>
                  <?php if($adminrights)
                  echo'<th>Recruiter</th>';
				  ?>
                  <th>Phone</th>
                  <th>Exp Salary/Rate</th>
                  <th>Action</th>
                </tr>
              </thead>
              
              
              <tbody>
                <?php
	    foreach($candidatearray as $row){
	 $reason=htmlspecialchars_decode($row['comment'],ENT_QUOTES);
          if($row['viewed'] == 0){
          $viewed = '<small style="
          font-size: 10px;
          color: #a02121;
      ">Yet to be Viewed by Client</small>';
        }else{

          $viewed = '<small style="
          font-size: 10px;
          color: #21a048;
      "> Viewed on '.$row['viewedtime'].' </small>';

        }
	if($row['prestatus']=='Awaiting Feedback')
				$row['status']="CV Rejected";
			elseif($row['prestatus']=='Shortlisted')
				$row['status']="Interview Rejected";
				elseif($row['prestatus']=='Offered')
				$row['status']="Offer Rejected";
        
			echo'
          <tr>
		   <td><b><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['id'].'>'.$row['fname'].' </a></b>'.$viewed.'<br>
   <small>Added: '.$row['addeddate'].' / '.$row['id'].'</small> | '.$row['jobtype'].' <br><small>Candidate Status</small> <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="STATUS OF CANDIDATE SUBMITTED BY YOU"><i class="fa fa-question-circle"></i>
    </span> -> <span class="badge badge-default">'.$row['status'].'</span> <br> <small>Job Status</small> <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="STATUS OF JOB (BASED ON ANY ONE OF THE CANDIDATE SUBMITTED BY YOU OR OTHER AGENCY"><i class="fa fa-question-circle"></i>
    </span> -> <span class="badge badge-default">'.$row['jobstatus'].'</span> <br> <small>Employer Notes for this Candidate -> '.$row['employernotes'].'</small> <br> <small>AM Notes for this Candidate -> '.$row['amnotes'].'</small> <br> <small>'.$row['comment'].'</small> </td>';
   
  if($row['jobtitle'])
   echo '<td><a class="title" href="/recruiter/all?jobtitle='.urlencode($row['jobtitle']).'&candidate=&employer=&status=&submit=Search" >'.$row['jobtitle'].'</a> / <small>'.$row['jobid'].'</small><br><b>'.$row['employer'].'</b> </td>';
  else
   echo "<td>Not Submitted Yet</td>";
  if($adminrights)
   echo '<td>'.$row['subuser'].'</td>';
   
	echo'<td>'.$row['contactnumber'].'</td>
  <td>';
  if($row['expectedcurrency'])
  	echo $row['expectedcurrency'];
  if($row['desiredsalary'])
  	echo $row['desiredsalary'];
  if($row['typesalary'])
  	echo $row['typesalary'];
  else
   echo "Not Mentioned";
  echo '</td>  
  
  <td>';
  echo'
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">';
	  if($row['status']!='New')
	  	 	echo'<li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['id'].' data-id='.$row['jobid'].'><b>Add Notes</b></a></li>
	  	 	<li><a title="submit to a Job" href="application?candidateid='.$row['id'].'&'.$param.'"><b>Submit to a Job</b></a></li>';
			else
			echo'<li><a title="submit to a Job" href="application?candidateid='.$row['id'].'&'.$param.'"><b>Submit to a Job</b></a></li>';
			
			if($row['status']!='AwaitingFeedback')
	  	 	echo'<li><a title="Edit Profile" href="edit-candidatedetail?candidateid='.$row['id'].'&'.$param.'">Edit Profile</a></li>
	  	 	';
	  	 	
	  	 			if($row['status']=='Awaiting Feedback')
	  	 	echo'<li><a title="Delete Submission" class="confirmation" href="delete-submission?id='.$row['sid'].'")>Delete Submission</a></li>
	  	 	';
	  	 
			
      echo'    
          
       
      </ul>
    </div>
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
    
    <div class="modal fade" id="duplicate-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="duplicate-modal-label"></h4>
          </div>
          <div class="modal-body" id="duplicate-modal-body">
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
	
	/***$( "#jobtitle" ).autocomplete({
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
    });	*****/
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
    
     $('#duplicate-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),
		element=$(e.relatedTarget),
            cid = element.attr('id'),
			jobid = element.data('id');
			
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'recduplicatenotes.php',
            data: {CID:cid,jobid:jobid},
            success: function(data) {
				$modal.find('#duplicate-modal-label').html(data);
                $modal.find('#duplicate-modal-body').html( $('#feedback_name').data('value'));
				
            }
        });
    });
    
     $('.confirmation').on('click', function () {
        return confirm('Are you sure to delete this candidate submission?');
    });
    
</script>

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>
 </body>
</html>
