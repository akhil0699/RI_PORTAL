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
$notification='';
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

//We list his messages in a table
//Two queries are executes, one for the unread messages and another for read messages
$req1 = mysqli_query($link,'select m1.id,m1.pmid,m1.subject, m1.mtimestamp, count(m2.id) as reps,members.companyid , members.email as empemails,members.memberid as empid, members.memberid as userid, CONCAT(members.firstname," ",members.lastname) as username from personalmsg as m1, personalmsg as m2,members where ((m1.fromuser="'.$mid.'" and m1.fromread="0" and members.memberid=m1.touser) or (m1.touser="'.$mid.'" and m1.toread="0" and members.memberid=m1.fromuser)) and m1.conversationid="1" and m2.id=m1.id group by m1.id order by m1.mtimestamp desc');

$req2 = mysqli_query($link,'select m1.id,m1.pmid,m1.subject, m1.mtimestamp, count(m2.id) as reps,members.companyid , members.email as empemails,members.memberid as empid, members.memberid as userid, CONCAT(members.firstname," ",members.lastname) as username from personalmsg as m1, personalmsg as m2,members where ((m1.fromuser="'.$mid.'" and m1.fromread="1" and members.memberid=m1.touser) or (m1.touser="'.$mid.'" and m1.toread="1" and members.memberid=m1.fromuser)) and m1.conversationid="1" and m2.id=m1.id group by m1.id order by m1.mtimestamp desc');

$count=(mysqli_num_rows($req1)) + (mysqli_num_rows($req2));	

?>

<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Messages | Employers Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
    <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
</head>
<body>

<div class="header-inner">
<div class="header-top">
<a class="navbar-brand" href="#"><img src="images/color-logo.png"></a>
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
          <div class="row">
                 <div class="col-md-12">
                  <a class="btn btn-primary" href="new-msg">
    <i class="fa fa-send"></i>  Send New Message
</a>
             
            </div>
          </div>
        </div>

 <div class="row">
 <div class="col-xs-12">
      <section class="panel panel-default">
        <header class="panel-heading panel-heading--buttons wht-bg">
          <h4 class="gen-case">Inbox (Unread) <?php echo intval(mysqli_num_rows($req1)); ?></h4>
        </header>
        <div class="panel-body minimal">
			
          <div class="table-responsive ">
          <?php if ($count){ ?>
			 <table class="table table-inbox table-hover" id="table_id" style="border: 1px solid #e4d9d9;">
        <thead>
                        <tr>
                        <th>ID</th>
                        <th>Subject</th>
                       <!-- <th>Nb. Replies</th> -->
<!--                         <th>Sender</th>
 -->                    <th>Recruiter Name</th>
                        <th>Agency Name</th>
                        <th>Message Date</th>
                    </tr>
                  </thead>
                    <tbody>
                <?php
                //We display the list of unread messages
                while($dn1 = mysqli_fetch_array($req1))
                {
                $join = "SELECT members.memberid, companyprofile.name as agency_name
                FROM members
                LEFT JOIN companyprofile ON members.companyid = companyprofile.id WHERE members.memberid=".$dn1['empid'];
                $result = mysqli_query($link,$join);
                $cnsd = mysqli_fetch_array($result);
                ?>
                  <tr>
                    <td><?php echo $dn1['pmid'] ?></td>
                      <td class="left"><a href="read-pm?id=<?php echo $dn1['pmid']; ?>&ids=<?php echo $dn1['empid']; ?>"><strong><?php echo $dn1['subject'].'('.($dn1['reps']).')'; ?></strong></a></td>
<!--                        <td><strong><?php //echo $name; ?></strong></td>
 -->                      <td><strong><?php echo $dn1['username']; ?></strong></td>
                      <td><strong> <?php echo $cnsd['agency_name']; ?></strong></td>
                      <td><strong><?php echo date('d/m/Y H:i:s' ,strtotime($dn1['mtimestamp'])); ?></strong></td>
                  </tr>
               <?php }
			   
                //We display the list of read messages
                while($dn2 = mysqli_fetch_array($req2))
                {
                  $join = "SELECT members.memberid, companyprofile.name as agency_name
FROM members
LEFT JOIN companyprofile ON members.companyid = companyprofile.id WHERE members.memberid=".$dn2['empid'];
$result = mysqli_query($link,$join);
$cnsd = mysqli_fetch_array($result);
                ?>
                    <tr>
                      <td><?php echo $dn2['pmid'] ?></td>
                        <td class="left"><a href="read-pm?id=<?php echo $dn2['pmid']; ?>&ids=<?php echo $dn2['empid']; ?>"><?php echo $dn2['subject'].'('.($dn2['reps']).')'; ?></a></td>
<!--                         <td><?php //echo $name; ?></td>
 -->                        <td><?php echo $dn2['username']; ?></td>
                        <td><?php echo $cnsd['agency_name']; ?></td>
                        <td><?php echo date('d/m/Y H:i:s' ,strtotime($dn2['mtimestamp'])); ?></td>
                    </tr>
                <?php
               	 }
                
                }
                else
                {
                ?>
                    <tr>
                        <td colspan="4" class="center">You have no message to read.</td>
                    </tr>
                <?php
                }
                ?>

</tbody>
</table>
     </div>
        </div>
      </section>
    </div>
    
  </div>
 </section>
       
     </div>
     </div>
     </div>
     <div class="clearfix"></div>
     </div>
     </div>


  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>
  <script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
  $(document).ready( function () {
    $.fn.dataTable.moment("DD/MM/YYYY HH:mm:ss");
    var table = $('#table_id').DataTable({
      "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                //"type":"date-eu",
                "searchable": false
            }
        ]
    });
  
    table
    .order( [[ 4, 'desc' ]] )
    .draw();
    });
  
</script>
 </body>
</html>
