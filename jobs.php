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
$cid=$_SESSION['cid'];
$ecountry=$_SESSION['country'];
$adminrights=$_SESSION['adminrights'];
$admin=$_SESSION['admin'];
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
	$jobtitleres=$recruiterres='';		
$company = mysqli_fetch_array(mysqli_query($link,'select companyid from members where memberid ="'.$mid.'" '));
$companyid = $company['companyid'];

$jobarray=array();
//jobs posted recently
$content='';
if(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')){
$jobtitle=$_GET['jobtitle'];
$param='jobtitle='.$jobtitle;
$jobtitleres="and jobtitle like '%$jobtitle%'";
if($adminrights){
  $sql = "SELECT j.*,m.firstname as subuser FROM jobs j,members m WHERE j.memberid=m.memberid and m.companyid='$companyid' and j.status not in ('Closed','Filled','Hold') and j.jobtitle like '%$jobtitle%' order by j.updatedate desc"; 


}else{
  $sql = "SELECT * FROM jobs WHERE memberid='$mid' and status not in ('Closed','Filled','Hold') $jobtitleres order by updatedate desc"; 
}
}else{
if($adminrights){

  $sql = "SELECT j.*,m.firstname as subuser FROM jobs j,members m WHERE j.memberid=m.memberid and m.companyid='$companyid' and j.status not in ('Closed','Filled','Hold') order by updatedate desc"; 

}else{
  $sql = "SELECT * FROM jobs WHERE memberid='$mid' and status not in ('Closed','Filled','Hold') order by updatedate desc"; 
}

}

function subcount($link,$jid){
 $ccount = mysqli_fetch_array(mysqli_query($link,('SELECT COUNT(id) as ccount  FROM submitted_candidates  where jobid = "'.$jid.'"')));
 
  //echo  $jid ;
   return  $ccount['ccount'];

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
$jobres = $pager->paginate();

if(@mysqli_num_rows($jobres)){ 
    while($row =mysqli_fetch_array($jobres)){ 
			$jobarray[]=$row;	
				}
$pages =  $pager->renderFullNav();
$total= $pager->total_rows;
 $today = date("Y-m-d");
/*$issubmitted =mysqli_num_rows($candidateres);
if($issubmitted > 0){ 
    while($row =mysqli_fetch_array($candidateres)){
			$candidatearray[]=$row;
				} */
}
else
$content="No Active Jobs found";
?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Active Jobs | Employers Hub</title>
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
  <li><a href="submit-a" role="tab"><i class="fa  fa-fw fa-plus"></i>Post New Job</a></li>
 <!-- <li><a href="requests" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Request from Agencies</a></li>-->

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

    <section class="main-content-wrapper">
      


  <div class="row">
    <div class="col-xs-12">
   
                    <a class="btn btn-primary" href="postdashboard">
    <i class="fa fa-plus"></i> Post New Job
</a>
      
      <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> List of Active Jobs | <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Click Action and Edit Job so it can be shown at top of recruiters marketplace">Not Receiving Enough CV's <i class="fa fa-question-circle"></i>
    </span> | <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Click View Agencies -> Select All & Click Action -> Message Agency -> Type your Message and send so it can be sent to all engaged agencies">Want to communicate to all agencies at once? <i class="fa fa-question-circle"></i>
    </span> </h3>
  </header>
  
  <div class="panel-body">
        <div class="text-left">
 Go to -> <a href="postdashboard" id="one">POST NEW JOB</a> -> <a href="jobs" id="two"><strong>ACTIVE</strong></a></a> -> <a href="inactive" id="three">INACTIVE (HOLD)</a> -> <a href="closed" id="four">CLOSED</a> -> <a href="filled" id="five">FILLED</a> || <a href="all" id="six">ALL CANDIDATES</a>
</div>

        <div class="panel-body">

          <div class="row">
            <div class="col-md-12 table-responsive">
              <?php if(!$content){ ?>
              <div class="col-sm-12">
             <form method="GET" action="jobs" >
       <div class="form-group">
        
        <!--<label>Search Active Jobs</label><br><br>-->
        <div class="col-sm-3">
        	<input name="jobtitle" class="form-control ui-autocomplete-input" id="jobtitle" placeholder="Search by Job" autocomplete="off" type="text">
        </div>
         
        <div class="col-sm-3">
        <div class="form-group">
                                            <input value="Search" class="btn btn-primary" name="submit" type="submit">
                                             </div>
        </div>
        </div>  
        </form>
              </div>
              <h3>Total Active Jobs:<?php echo $total; ?></h3>
                <table class="table table-striped jobs-list">
                  <thead>
                    <tr>
                      <th>Title/Location/Job ID/Posted</th>
                      <th>Candidates</th>
                      <?php if($adminrights){ ?>
                      <th>Job Owner</th>
                      <?php } ?>
                      <th>Country</th>
                      <th>Salary Range / End Rate</th>
                      <th>Fee/End Rate</th>
                      <th>Recruiters</th>
  					 <th>Action</th>
                     
                     <?php  foreach($jobarray as $row){
                    $rfee=$row['fee'];
		$parray1=array('5','7','8','8.33','10','12','12.5','15','20','25');
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
$checkpriority=$row['priority'];
					 echo'
          <tr>
  <td><a class="title" href="#" data-toggle="modal" data-target="#engage-modal" id='.$row['jobid'].'>'.$row['jobtitle'].'</a><br>';
						  if(($checkpriority!='0')){echo' <span class="label label-primary">Priority</span>'; } echo'
   <small>'.$row['joblocation'].'</small> / <small>'.$row['jobid'].'</small> / <small>Updated: '.$row['updatedate'].'</small> </td>
   
 <td><b><a href="/employer/all?jobtitle='.urlencode($row['jobtitle']).'&candidate=&agency=&status=&submit=Search" >'.subcount($link,$row['jobid']).'</b></td>';

if($adminrights){
  echo '<td><b>'.$row['subuser'].'</b></td>';
}

   echo'<td>'.$row['country'].'</td>
  
    <td>';
		if(($row['country']=='India')&&($row['jobtype']=='Contract')){  if($row['minlakh']) echo $row['minlakh']; else echo '0';echo '-';if($row['maxlakh']) echo $row['maxlakh'];else echo '0';   if($row['currency']=='GBP')echo '&pound;';elseif($row['currency']=='USD') echo '$';else echo $row['currency'];     } elseif($row['country']=='India'){echo'Rs. ';if($row['minlakh']) echo $row['minlakh'].',';  if($row['minthousand']==0) echo '00,'; else echo $row['minthousand'].','; echo '000'; echo' - '; if($row['maxlakh']) echo $row['maxlakh'].',';  if($row['maxthousand']==0) echo '00,'; else echo $row['maxthousand'].','; echo '000';}else{ if($row['minlakh']) echo $row['minlakh']; else echo '0';echo '-';if($row['maxlakh']) echo $row['maxlakh'];else echo '0';   if($row['currency']=='GBP') echo '&pound;';elseif($row['currency']=='USD')echo '$'; else echo $row['currency'];    }
  echo	'</td>
 <td>'.$row['fee'].' '.$sym.' </td>
 
   <td><a class="title" href="#" data-toggle="modal" data-target="#view-modal" id='.$row['jobid'].'><b>View Agencies</b></a></td>
   

 <td>
  <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
  <ul class="dropdown-menu dropdown-menu--right" role="menu">
  <li><a title="Edit Job" href="edit-a-job?id='.$row['jobid'].'">Edit Job</a></li>
	<li><a title="Temp Hold" href="job-action?jobid='.$row['jobid'].'&amp;a=inactive">Temp Hold</a></li>
		<li><a title="Close the job" href="job-action?jobid='.$row['jobid'].'&amp;a=close">Close Job</a></li>
	<li><a title="Close the job" href="job-action?jobid='.$row['jobid'].'&amp;a=filled">Mark as Filled</a></li>
	</ul>
	</div>
  </td>

</tr>';
}
?>
                     
                    </tr>
                  </thead>
                  <tbody>
                  
                   
 </tbody>
 </table>
  <?php echo $pages; } else echo $content; ?>
    </div>
   </div>
  </div>
   </div>
    </div>
  </div>
</section>
    
      
     </div>
     </div>
     </div>
<div class="clearfix"></div>
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
          <div class="modal-footer" id="engage-modal-footer">
              <a class="btn btn-primary" href=""> Edit Job </a>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="view-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="engage-modal-label"></h4>
          </div>
          <div class="modal-body" id="engage-modal-body">
          </div>
         
        </div>
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
				$modal.find('#engage-modal-footer a').attr("href", "edit-a-job?id="+ essayId);
            }
        });
    });
	
	 $('#view-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
            essayId = e.relatedTarget.id;
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'view-recruiters.php',
            data: 'EID=' + essayId,
            success: function(data) {
				$modal.find('#engage-modal-body').html(data);
                $modal.find('#engage-modal-label').html( $('#job_title').data('value'));
				
            }
        });
    });
	 $('#engaged-agencies').on('show.bs.modal', function(e) {
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

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || 
{widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};
var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;
s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>
</html>
