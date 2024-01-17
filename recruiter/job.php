<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
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
if(isset($_GET['candidateid'])){
$candidateid=$_GET['candidateid'];
$param="&candidateid=".$candidateid;
}

require_once '../config.php';

$block = '';
$block = 'memberid not in (select m.memberid from block b,members m where m.companyid=b.ecid and b.block=1 and b.rcid='.$cid.') and';
			
$jobarray=array();
//jobs posted recently
$engagedmsg=$candidatemsg=$content='';

if($adminrights){
		if(isset($_GET['employer']) && ($_GET['employer']!='')){
			$employer=$_GET['employer'];
			$sql = "SELECT a.*,b.firstname as contactperson,f.firstname as subuser,f.email as subuseremail,d.name as employer,d.id as empid,c.requestid as requestid,c.startdate as startdate,c.status as requeststatus,c.cvcount as applicants,c.jobid , a.status as jobstatus FROM jobs a,members b ,request c, companyprofile d,members f,companyprofile g WHERE f.companyid=g.id and c.recruiterid=f.memberid and g.id='$cid' and c.jobid=a.jobid and a.memberid=b.memberid and b.companyid=d.id and d.name = '$employer' and updatedate>=CURDATE() - INTERVAL 6 MONTH and c.status in('Engaged','Requested') and a.status not in('Hold','Closed') order by updatedate desc"; 
		}elseif
		(isset($_GET['keywords']) && ($_GET['keywords']!='')){
		    $keywords=$_GET['keywords'];
		$sql = "SELECT a.*,b.firstname as contactperson,f.firstname as subuser,f.email as subuseremail,d.name as employer,d.id as empid,c.requestid as requestid,c.startdate as startdate,c.status as requeststatus,c.cvcount as applicants,c.jobid , a.status as jobstatus FROM jobs a,members b ,request c, companyprofile d,members f,companyprofile g WHERE (a.jobtitle like '%$keywords%' ||  a.keyskills like '%$keywords%') and f.companyid=g.id and c.recruiterid=f.memberid and g.id='$cid' and c.jobid=a.jobid and a.memberid=b.memberid and b.companyid=d.id and updatedate>=CURDATE() - INTERVAL 6 MONTH and c.status in('Engaged','Requested') and a.status not in('Hold','Closed') order by updatedate desc";} 
		else{
		$sql ="SELECT a.*,b.firstname as contactperson,f.firstname as subuser,f.email as subuseremail,d.name as employer,d.id as empid,c.requestid as requestid,c.startdate as startdate,c.status as requeststatus,c.cvcount as applicants,c.jobid , a.status as jobstatus FROM jobs a,members b ,request c, companyprofile d,members f,companyprofile g WHERE f.companyid=g.id and c.recruiterid=f.memberid and g.id='$cid' and c.jobid=a.jobid and a.memberid=b.memberid and b.companyid=d.id and updatedate>=CURDATE() - INTERVAL 6 MONTH  and c.status in('Engaged','Requested') and a.status not in('Hold','Closed') order by updatedate desc"; 
		}

}
else{
		if(isset($_GET['employer']) && ($_GET['employer']!='')){
			$employer=$_GET['employer'];
			$sql ="SELECT a.*,b.firstname as contactperson,d.name as employer,d.id as empid,c.requestid as requestid,c.startdate as startdate,c.status as requeststatus,c.cvcount as applicants FROM jobs a,members b ,request c, companyprofile d, a.status as jobstatus WHERE c.recruiterid='$mid' and c.jobid=a.jobid and a.memberid=b.memberid and b.companyid=d.id and d.name = '$employer' and updatedate>=CURDATE() - INTERVAL 6 MONTH order by updatedate desc"; 
		}
		else{
		$sql ="SELECT a.*,b.firstname as contactperson,d.name as employer,d.id as empid,c.requestid as requestid,c.startdate as startdate,c.status as requeststatus,c.cvcount as applicants FROM jobs a,members b ,request c, companyprofile d, a.status as jobstatus WHERE c.recruiterid='$mid' and c.jobid=a.jobid and a.memberid=b.memberid and b.companyid=d.id and updatedate>=CURDATE() - INTERVAL 6 MONTH order by updatedate desc"; 
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
    while($row = mysqli_fetch_array($jobres)){ 
			$jobarray[]=$row;	
				}
$pages =  $pager->renderFullNav();
$total= $pager->total_rows;
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
/**$isposted = mysqli_num_rows($jobres);
if($isposted > 0){ 
    while($row = mysqli_fetch_array($jobres)){ 
		$jobarray[]=$row;
				}***/
}
else
$content="You have no engaged jobs in last 6 months.";

?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Engaged Roles | Recruiters Hub</title>
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
    <li><a href="interview" role="tab" > <i class="fa fa-users"></i> Shorlisted</a>  </li>
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
  <div class="tab-content">

    <section class="main-content-wrapper">
        


  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
       <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> Engaged Jobs | Client name displayed next to Job title | Start submitting candidates by using the action button and earn 2X faster | Read the Best Practices before submitting any candidate -> <a href="https://www.recruitinghub.com/recruiter/bestpractices-recruiter"><i class="btn-primary fa fa-fw fa-search"></i> <b>BEST PRACTICES</b></a></h3>
  </header>
        <div class="panel-body">

          <div class="row">
            <div class="col-md-12 table-responsive">
             <?php if(!$content){ ?>
             <div class="col-sm-12">
     <form method="get" action="job">
       <div class="form-group">
        
       <!-- <label>Search Employer</label><br><br> -->
       
       <div class="row">
                  <div class="col-md-2">
                    <input placeholder="Keyword Search" class="form-control" name="keywords" type="text">
                  </div>
                  
        <div class="col-sm-6 no-padding">
        <input name="employer" class="form-control ui-autocomplete-input" id="employer" placeholder="Search by Employer Name (Start typing Client name and you will receive the list in the drop down)" autocomplete="off" type="text">
        </div>
        <div class="col-sm-4">
        <div class="form-group">
                                            <input value="Search" class="btn btn-primary" name="submit" type="submit">
                                             </div>
                                           
                                             
                                             <a href="search"><i class="btn-primary fa fa-fw fa-search"></i> <b>SEARCH NEW ROLES</b></a> 
                                             
                                    <!--         <a href="candidate-search"><i class="btn-primary fa fa-fw fa-users"></i> <b>FREE CV DATABASE SEARCH</b></a> 
                                             
                                             <a href="post-job"><i class="btn-primary fa fa-fw fa-paper-plane"></i> <b>POST JOBS</b></a> -->
                                             
                                 <!--            <a href="https://www.recruitinghub.com/bestpractices-recruiter" target=_blank><i class="btn-primary fa fa-fw fa-search"></i> BEST PRACTICES</a>-->
        </div>
        </div>  
        </form>                                  
        </div>
        <h3>Total Active/Engaged Roles:<?php echo $total; ?> (Displaying Last 6 Months Data) </h3>
                <table class="table table-striped jobs-list">
                  <thead>
                    <tr>
                      <th>Job Title</th>
                      <th>Client</th>
                       <th>Actions</th>
                       <th>Total Submissions <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Total No of Candidates Submitted by all the Agencies Working on this role including you"><i class="fa fa-question-circle"></i>
    </span></th>
                       <th>Fee</th>
                      <th>Location</th>
					  <th>Salary</th>
                      <th>Status <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Open - Means the requirement needs cvs immediately, Shortlisted - Means the requirement still needs cvs but one of the candidates submitted for this requirement by you or other vendors is shortlisted"><i class="fa fa-question-circle"></th>
             
                      <?php if($adminrights)
					  echo'<th>Recruiter</th>';
					  ?>
                 <!--     <th>CV Limit</th>
                     <th>Agency Limit</th>-->

                    </tr>
                  </thead>
                  <tbody>
 	<?php
	    foreach($jobarray as $row){
		//if ($row['status']=='open')
		//$row['status']=$row['requeststatus'];
		if($row['ratetype']){
		$ratetype='/'.$row['ratetype'];
		}
		else{
		$ratetype='';
		}
$checkpriority=$row['priority'];
$checkrpo=$row['rpo'];
		if(($row['fee']=='')&&(($row['country']=='UK')||($row['country']=='US')||($row['country']=='Europe')||($row['country']=='Africa')||($row['country']=='Australia')||($row['country']=='Singapore')||($row['country']=='Middle East')||($row['country']=='China')||($row['country']=='Russia')||($row['country']=='Japan')||($row['country']=='Canada')||($row['country']=='Philippines')||($row['country']=='Malaysia')||($row['country']=='New Zealand'))&&($row['jobtype']=='Contract'||'Corp-To-Corp')){
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
  <td> <a class="title" href="#" data-toggle="modal" data-target="#engage-modal" id='.$row['jobid'].'><b>'.$row['jobtitle'].'</b></a> <br>';	
  	if(($checkpriority!='0')){echo' <span class="label label-primary">Priority</span>'; }
	echo '
	<small>Updated: <b>'.$row['updatedate'].'</b></small> /<small>Job ID: '.$row['jobid'].'</small> </td>
	
	<td>';
	
    if(!(in_array($row['requeststatus'],array("Requested","Rejected"))))
  	echo '<a class="title engage-trigger" href="#" data-toggle="modal" data-target="#employer-modal" id='.$row['empid'].' ><b>'.$row['employer'].'</b></a>  <br></td>
	
	<td>
	
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">';
		 if($row['requeststatus']!='Requested' &&$row['requeststatus']!='Rejected' &&$row['requeststatus']!='Disengaged'  && !in_array($row['jobstatus'],array('Closed','Hold'))){
      echo'<li><a title="submit Candidate" href="application?jobid='.$row['jobid'].'&'.$param.'"><b>Submit Candidate</b></a></li>
      <li><a title="Message Employer" href="new-msg?eid='.$row['memberid'].'&jobid='.$row['jobid'].'">Message Employer</a></li>
       <li><a title="Disengage" class="confirmation" href="disengage?jobid='.$row['jobid'].'">Disengage</a></li>';
     }
	
		
	echo'		
      </ul>
    </div>
  </td>
  
  <td><b><a href="/recruiter/all?jobtitle='.urlencode($row['jobtitle']).'&candidate=&employer=&status=&submit=Search" >'.subcount($link,$row['jobid']).'</b></td>
  
  <td>'.$rfee.' '.$sym.' '.$ratetype.'</td>';
	
	 echo '<td><small>'.$row['joblocation'].'</small> <br><b>'.$row['country'].'</b><td>';
	 
	if(($row['country']=='India')&&($row['jobtype']=='Contract')){  if($row['minlakh']) echo $row['minlakh']; else echo '0';echo '-';if($row['maxlakh']) echo $row['maxlakh'];else echo '0';   if($row['currency']=='GBP')echo '&pound;';else echo $row['currency'];     } elseif($row['country']=='India'){echo'Rs.';if($row['minlakh']) echo $row['minlakh'].',';  if($row['minthousand']==0) echo '00,'; else echo $row['minthousand'].','; echo '000'; echo' - '; if($row['maxlakh']) echo $row['maxlakh'].',';  if($row['maxthousand']==0) echo '00,'; else echo $row['maxthousand'].','; echo '000';}else{ if($row['minlakh']) echo $row['minlakh']; else echo '0';echo '-';if($row['maxlakh']) echo $row['maxlakh'];else echo '0';   if($row['currency']=='GBP')echo '&pound;'; elseif($row['currency']=='USD')echo '$'; else echo $row['currency'];    }
	echo '</td>
	<td>';
	if($row['status'] == "Filled")
	echo $row['status'].'('.$row['filledvacancy'].'/'.$row['novacancies'].')<br>'.$row['requeststatus'].'';
	else 
	echo $row['status'];echo '<br>'.$row['requeststatus'].' on '.$row['startdate'].'';
	echo '</td>';
   
   
	if($adminrights)
	 echo '<td>'.$row['subuser'].'</td>
	

  
 
   
</tr>';
}
?>
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
    <div class="modal fade" id="employer-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="employer-modal-content">
     
         
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
    });
	 $('#employer-modal').on('show.bs.modal', function(e) {
        var $modal = $(this),
            essayId = e.relatedTarget.id;
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'employer-detail.php',
            data: 'EID=' + essayId,
            success: function(data) {
				$modal.find('#employer-modal-content').html(data);
                
				
            }
        });
    });
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
    
    $('.confirmation').on('click', function () {
        return confirm('Are you sure you want to dis-engage from this role as it cannot be re-engaged by you again?');
    });

</script>

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

 </body>
</html>
