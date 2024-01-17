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
$candidateres=$candidatemsg='';
$candidatearray=array();
require_once '../config.php';
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];
if(isset($_GET['submit'])){
if(isset($_GET['jobtitle'])&&($_GET['jobtitle']!='') && isset($_GET['candidate']) &&($_GET['candidate']!='')){
$jobtitle=$_GET['jobtitle'];
$candidate=$_GET['candidate'];
$sql="select a.*,b.* from  appliedjobs a,recruiterjobs b where a.recruiterid='$mid' and a.jobapplied=b.recruiterjobid and b.jobtitle like '%$jobtitle%' and a.name like '%$candidate%' order by applieddate desc";
}
elseif(isset($_GET['jobtitle'])&&($_GET['jobtitle']!='') && isset($_GET['candidate']) &&($_GET['candidate']=='')){
$jobtitle=$_GET['jobtitle'];
$sql="select a.*,b.* from  appliedjobs a,recruiterjobs b where a.recruiterid='$mid' and a.jobapplied=b.recruiterjobid and b.jobtitle like '%$jobtitle%' order by applieddate desc";
}
elseif(isset($_GET['jobtitle'])&&($_GET['jobtitle']=='') && isset($_GET['candidate']) &&($_GET['candidate']!='')){
$candidate=$_GET['candidate'];
$sql="select a.*,b.* from  appliedjobs a,recruiterjobs b where a.recruiterid='$mid' and a.jobapplied=b.recruiterjobid and a.name like '%$candidate%' order by applieddate desc";
}
else{
$sql="select a.*,b.* from  appliedjobs a,recruiterjobs b where a.recruiterid='$mid' and a.jobapplied=b.recruiterjobid order by applieddate desc";
}
}else{
$sql="select a.*,b.* from  appliedjobs a,recruiterjobs b where a.recruiterid='$mid' and a.jobapplied=b.recruiterjobid order by applieddate desc";
}

$param="";
$params=$_SERVER['QUERY_STRING'];
$companyres = mysqli_query($link,"SELECT a.id as id,a.name as companyname, a.sectors as sectors,a.profile as companyprofile,b.firstname as name,a.address1,a.address2,a.address3,a.town,a.postcode,a.phone,a.benname,a.benbank,a.bensort,a.benacc,a.benswift,a.taxinfo,a.description,a.logo as logo,a.website,a.country,a.state, b.designation as designation,b.location as location,b.experience as experience FROM companyprofile a,members b WHERE a.id= b.companyid and b.memberid='$mid'"); 
$issubmitted = mysqli_num_rows($companyres);
if($issubmitted > 0){ 
    while($row = mysqli_fetch_array($companyres)){ 
		$company=$row;
				}
}
$tit= htmlspecialchars_decode($company['companyname'],ENT_QUOTES);
						$urlpart=str_replace(' ','-', strtolower ("$tit"));
						$urlpart=preg_replace('/[^A-Za-z0-9\-]/', '',$urlpart);
						$urlpart=str_replace('--','-', $urlpart);
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
}
else
$candidatemsg="No candidates applied for the job you posted.";
				
				
 ?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Manage Responses | Recruiters Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
     <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
    <link rel="stylesheet" href="css/datepicker.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.4.custom.min.css"/>   
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
	 /**  if($rcountry=='India'){echo'
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
    <li><a href="disengaged" role="tab"><i class="fa fa-fw fa-files-o"></i>CLOSED / DISENGAGED</a>    </li>
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
<li><a href="hire-resource" role="tab" > <i class="fa fa-database"></i>Bench Hiring <span class="label label-primary">Free</span></a> </li>
    <h4>Jobseeker Section (Jobsite)</h4>

<li><a href="candidate-search" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> CV Search <span class="label label-primary">Free</span></a>    </li>
    <li><a href="post-job" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Post Jobs <span class="label label-primary">Free</span></a>    </li>
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
    <div class="col-sm-12">
      <div class="panel panel-default">
      <header class="panel-heading wht-bg ui-sortable-handle">
    <h3 class="panel-title"> Manage Responses <a href="post-job" class="btn btn-primary"><i class="fa  fa-fw fa-plus"></i>Post New Job</a> <a href="manage-jobs" class="btn btn-primary"><i class="fa  fa-fw fa-plus"></i>Manage Jobs</a> <a class="btn btn-primary" href="<?php echo '../agency/'.$urlpart.'/'.$mid.'' ?>" target="_blank" > <i class="fa fa-share-alt"></i>Link to Share all your Jobs
</a></h3>
  </header>
        <div class="panel-body">
         <form method="get" action="manage-responses">
       <div class="form-group">
        
        <div class="col-sm-2">
        	<input name="jobtitle" class="form-control ui-autocomplete-input" placeholder="Search by Job" autocomplete="off" type="text">
        </div></div>
        
        <div class="col-sm-3">
        	<input name="candidate" class="form-control ui-autocomplete-input" placeholder="Search by Candidate" autocomplete="off" type="text">
        </div>
        
        <div class="form-group">
                                            <input value="Search" class="btn btn-primary" name="submit" type="submit">
                                             </div>
        </form>
        
         <?php if(!$candidatemsg){ ?>
 <table class="table table-striped">
              <thead>
                <tr>
                <th>S.No</th>
                  <th>Candidate Name/ID</th>
                  <th>Job Title/ID</th>
                  <th>Contact</th>
                  <th>Email</th>
                  <th>Applied Date</th>
                  <th>CV Download</th>
                </tr>
              </thead>
              
              
              <tbody>
               <?php
			   $i=1;
	    foreach($candidatearray as $row){
		
		echo'
          <tr>
		  <td>'.$i.'</td>
		  <td><a class="title" href="#" data-toggle="modal" data-target="#candidate-modal" id='.$row['candidateid'].'> '.$row['name'].' / '.$row['candidateid'].' </a></td>
		  <td><a href="view-appliedcand?jobid='.$row['recruiterjobid'].'&&recruiterid='.$mid.'">'.$row['jobtitle'].'</a></td>
		  <td>'.$row['contact'].'</td>
		  <td>'.$row['email'].'</td>
		  <td>'.$row['applieddate'].'</td>
		  <td><a href="https://www.recruitinghub.com/resume/'.$row['candidateid'].'/'.$row['resume'].'" target="_blank">'.$row['resume'].'</a></td>';
		 
		  if($row['status']=='') { echo'
		   <td><div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
	  <li><a title="Shortlist" href="recruiteractiononcandidate?canid='.$row['candidateid'].'&&jid='.$row['recruiterjobid'].'&&action=true">Shortlist</a></li>
	  <li><a title="Reject" href="recruiteractiononcandidate?canid='.$row['candidateid'].'&&jid='.$row['recruiterjobid'].'&&action=false">Reject</a></li>
      </ul>
    </div></td>';}else{
	echo'<td> '.$row['status'].'</td>';
	}
		  $i++;}
		  
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
 
     </div>
     </div>
     </div>
<div class="clearfix"></div>
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
            url: 'jcandidate-detail.php',
            data: 'EID=' + essayId,
            success: function(data) {
				$modal.find('#candidate-modal-body').html(data);
                $modal.find('#candidate-modal-label').html( $('#candidate_name').data('value'));
				
            }
        });		
	});
	</script>
 </body>
</html>
