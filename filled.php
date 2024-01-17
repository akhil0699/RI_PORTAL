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
if(isset($_POST['submit'])){
require_once 'insert-doj.php';
}
if(isset($_POST['cid']) && isset($_POST['doj'])){

$cid = $_POST['cid'];
$doj = $_POST['doj'];
mysqli_query($link,"Update candidates SET doj='$doj' WHERE id=$cid ");
}

if(isset($_POST['cid']) && isset($_POST['bamt']) && isset($_POST['invoice'])){

$cid = $_POST['cid'];
$bamt = $_POST['bamt'];
$invoice = $_POST['invoice'];
$net = $_POST['net'];
$tax = $_POST['tax'];
$rhshare = $net * 20 / 100;
$chanshare = $net * 20 / 100;
$agencyshare  = $net * 60 / 100;


mysqli_query($link,"Update candidates SET totalinv='$invoice',tax='$tax',billable='$bamt',net='$net',rhshare='$rhshare',chanshare='$chanshare',agencyshare='$agencyshare' WHERE id=$cid ");
}


			
$jobarray=array();
//jobs posted recently
$content='';
$jobtitleres=$recruiterres='';
//$jobres = mysqli_query($link,"SELECT * FROM jobs WHERE status='filled' and memberid='$mid'"); 
if(isset($_GET['submit'])){
if(isset($_GET['candidatename']) && ($_GET['candidatename']!='')){
$candidatename=$_GET['candidatename'];
$param='candidatename='.$candidatename;
$candidateres="and b.fname like '%$candidatename%'";
}    
if(isset($_GET['jobtitle']) && ($_GET['jobtitle']!='')){
$jobtitle=$_GET['jobtitle'];
$param='jobtitle='.$jobtitle;
$jobtitleres="and a.jobtitle like '%$jobtitle%'";
}
if(isset($_GET['recruiter']) && ($_GET['recruiter']!='')){
$recruiter=$_GET['recruiter'];
$param='recruiter='.$recruiter;
$recruiterres="and e.name='$recruiter'";
}
}
$jobres= mysqli_query($link,"SELECT a.jobtitle as jobtitle,a.jobid,a.jobtype,a.joblocation,a.postdate,a.currency, b.fname as candidatename,b.billable as billable,b.doj as doj,b.currentcurrency as currentcurrency,b.net as net,b.totalinv as totalinv,b.tax as tax,c.candidateid as candidateid,c.status as candidatestatus,e.name as recruiter,a.country,a.fee FROM submitted_candidates c,jobs a,candidates b,members d,companyprofile e WHERE a.memberid='$mid' and a.jobid=c.jobid and b.id=c.candidateid and c.status='Filled' and c.recruiterid= d.memberid and d.companyid=e.id $candidateres $jobtitleres $recruiterres order by submitdate desc"); 

$isposted = mysqli_num_rows($jobres);
if($isposted > 0){ 
    while($row = mysqli_fetch_array($jobres)){ 
			$jobarray[]=$row;	
				}
}
else
$content="So far you have no filled jobs.";

?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Filled | Employers Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
     <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.4.custom.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
    <link rel="stylesheet" href="css/datepicker.css">
 	<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.4.custom.min.css"/> 
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
  <li><a href="postdashboard" role="tab"><i class="fa  fa-fw fa-plus"></i>Post New Job</a></li>
<!--<li><a href="requests" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Request from Agencies</a></li>-->
     <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Jobs</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="postdashboard" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Post New Job</a> 
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
    <li><a href="filledcandidates" role="tab" > <i class="fa fa-trash"></i>Filled</a> </li>
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
</a> </li> <a class="btn btn-primary" href="https://www.recruitinghub.com/bankdetails" target=_blank> RH Bank Details </a>       
      <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> List of Filled Jobs <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Click on DOJ - Update Date box and enter the date of candidate joining and click on Billable - Update Offer Amount box and enter what you have offered the candidate">(Update Billing) <i class="fa fa-question-circle"></i>
    </span> </h3>
  </header>
  
  <div class="panel-body">
        <div class="text-left">
 Go to -> <a href="filled" id="one"><strong>FILLED</strong></a> -> <a href="all" id="two">ALL CANDIDATES</a> -> <a href="awaiting-feedback" id="three">AWAITING FEEDBACK</a> -> <a href="shortlist" id="four">SHORTLISTED</a> -> <a href="offer" id="five">OFFERED</a> -> <a href="rejected" id="six">REJECTED</a> 
</div>

        <div class="panel-body">
         
            <div class="col-md-12 table-responsive">
             <?php if(!$content){ ?> 
             <div class="col-sm-12">
             <form method="GET" action="filled" >
       <div class="form-group">
        
        <!--<label>Search Filled List</label><br><br>-->
        
         <div class="col-sm-3">
        	<input name="candidatename" class="form-control ui-autocomplete-input" id="candidatename" placeholder="Search by Candidate" autocomplete="off" type="text">
        </div>
        
        <div class="col-sm-3">
        	<input name="jobtitle" class="form-control ui-autocomplete-input" id="jobtitle" placeholder="Search by Job Title" autocomplete="off" type="text">
        </div>
        
        <div class="col-sm-3">
                        <input name="recruiter" class="form-control ui-autocomplete-input" id="recruiter" placeholder="Search by Agency" autocomplete="off" type="text">
        </div> 
        
        <div class="col-sm-3">
        <div class="form-group">
                                            <input value="Search" class="btn btn-primary" name="submit" type="submit">
                                             </div>
        </div>
        </div>  
        </form>
              </div>
                <table class="table table-striped jobs-list">
                  <thead>
                    <tr>
                      <th>
                        Job Title
                      </th>
                      <th>Candidate</th>
                      <th>DOJ</th>
                      <th>Billable/Offer</th>
                      <th>Fee</th>
                      <th>Net Amount</th>
                      <th>Tax</th>
                      <th>Invoice Amount</th>
                      <th>Recruiting Agency</th>
                      <th>Pay your Invoice</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                   <?php
	    foreach($jobarray as $row){
$rfee=$row['fee'];
	$div = 100;
	$tax = 0;
	$invoice =0;
		$parray1=array('5','7','8','8.33','10','12','12.5','15','20','25');
		if((in_array($rfee,$parray1)))
		{
		$sym='%';
		}elseif((!(in_array($rfee,$parray1)))&&($row['country']=='India')&&($rfee!='NA')){
		$sym='Rs';
			$tax = $row['billable'] * 18 / $div;
		}elseif((!(in_array($rfee,$parray1)))&&($row['country']=='UK')&&($rfee!='NA')){
		$sym='&pound;';
		
		$tax = $row['billable'] * 20 / $div;
		
		}
		elseif($rfee=='NA'){
		$sym='';
		}
		else{
		$sym=$row['currency'];
		}
	
		if($row['jobtype'] == 'Permanent'){
		    $billable =  $row['billable'] * $row['fee'] / $div;
		    
		}else{
		    $billable =  $row['billable'];
		}
		
		
	
		if(strtolower($row['country'])=='india'){
		    		$tax = $billable * 18 / $div;
		    	
		}
		
		if($row['country']=='UK'){
		    		$tax = $billable * 20 / $div;
		}
		
		if($tax != 0 && isset($row['billable'])){
		    $invoice = $billable + $tax;
		}
		
		
	
      $cnty = $row['country'];
	$jtype =   $row['jobtype'];
			echo'
			<tr>
			<td><a class="title engage-trigger" data-toggle="modal" data-target="#engage-modal" href="#" id='.$row['jobid'].'>'.$row['jobtitle'].'</a><br>
			<small>'.$row['joblocation'].'</small> / <small>'.$row['jobid'].'</small> / <small>'.$row['jobtype'].'</small> / <small>Posted: '.$row['postdate'].'</small> </td>
			<td><b><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['candidateid'].'>'.$row['candidatename'].'</a></b><br>					  
				<small>'.$row['candidateid'].'</small></td>
			<td> <b>'.$row['doj'].'</b> <br><input class="datepicker" name="doj" onChange="dojOnChange( this.value,'.$row['candidateid'].' )" placeholder="Update Date" type="text" /> </td> 
		<td><b>'.$row['currentcurrency'].'</b><b>'.$row['billable'].'</b> <br>
			<input class="billable amount" name="billable" onChange="billableOnChange( this.value,'.$row['candidateid'].','.$row['fee'].',this)" id="amount" data-c="'. $cnty.'" data-jtype="'.$jtype.'" placeholder="Update Offer Amount" type="text" />
			
			</td>
			<td>'.$row['fee'].' '.$sym.' </td>
			<td><b>'.$row['currentcurrency'].''.$billable.'</b></td>
			<td>'.$row['currentcurrency'].''.$tax.'</td>
			<td><b>'.$row['currentcurrency'].''.$invoice.'</b></td>
			<td>'.$row['recruiter'].'</td>
			<td><a class="btn btn-primary" href="https://www.recruitinghub.com/bankdetails" target=_blank> RH Bank Details </a></td>
			
			</tr>'; 
			
}
?> 
               
 </tbody>
 </table>
  
  <?php } else echo $content; ?>
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
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		<a class="btn btn-primary" href=""> Edit </a>
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
    
    	$(function(){
			$('.datepicker').datepicker({
			  dateFormat: 'yy-mm-dd'
			});
		});
		
		
  function  dojOnChange(doj,cid){


      $.ajax({
            cache: false,
            type: 'POST',
            url: 'filled.php',
            data: 'cid=' + cid+'&doj='+doj,
            success: function(data) {
				       alert('Date of joining Updated');
window.location.reload();

   
  }
  
        });


}

  function billableOnChange(bamt,cid,fee,ths){
      
      var invoice,tax,net;
      
    
  
     c =  $(ths).attr("data-c")
      jtype = $(ths).attr("data-jtype")
     
      
      if(fee!= null){
          
          
          if(jtype == 'Permanent'){
              net = bamt * fee / 100;
          }else{
              net = bamt
          }
          
          if(c == 'India'){
              
              tax = net *18 / 100
          }
          if(c == 'UK'){
              tax = net *20 / 100
          }
         
          
         invoice = net + tax
         
         
          
      }else{
          alert('invalid Fee');
          return false;
      }
      
      
      
            $.ajax({
            cache: false,
            type: 'POST',
            url: 'filled.php',
            data: 'cid=' + cid+'&bamt='+bamt+'&invoice='+invoice+'&tax='+tax+'&net='+net,
            success: function(data) {
alert('Offer Details Updated');
window.location.reload();

   
            }
  });
  
  }
  
  
  
        
	
</script>
 </body>
</html>
