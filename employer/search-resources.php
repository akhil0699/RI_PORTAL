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

$errormsg =$noti='';

$content='';

$leadcount=0;

require_once '../config.php';
$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];

$employers=array();

$keywordres=$skillsres=$locres=$expres=$availres=$visares=$countryres='';

if(isset($_GET['search'])){

if(isset($_GET['keyword']) && ($_GET['keyword']!='')){

$keyword=$_GET['keyword'];

$keywordres="and ((a.jobtitle like '%$keyword%')||(a.consultant like '%$keyword%'))";

}

if(isset($_GET['skills'])&&($_GET['skills']!='')){
$skills=$_GET['skills'];
//if($prm>0)
$skillsres="and skills like '%$skills%'";
//else
//$skillsres="skills like '%$skills%'";
}

if(isset($_GET['loc']) && ($_GET['loc']!='')){

$loc=$_GET['loc'];

$locres=" and a.currentlocation like '%$loc%' ";

}

if(isset($_GET['country']) && ($_GET['country']!='')){

$country=$_GET['country'];

$countryres=" and a.country ='$country' ";

}

if(isset($_GET['exp']) && ($_GET['exp']!='')){

$exp=$_GET['exp'];

$expres="and a.totalexperience like '%$exp%'";

}

if(isset($_GET['avail']) && ($_GET['avail']!='')){

$avail=$_GET['avail'];

$availres="and a.availability  like '%$avail%'";

}

if(isset($_GET['visa']) && ($_GET['visa']!='')){

$visa=$_GET['visa'];

$visares="and a.visa  like '%$visa%'";

}

}

$empres=mysqli_query($link,"SELECT a.id,a.emid,a.consultant,a.postdate,a.jobtitle,a.skills,a.totalexperience,a.currentlocation,a.country,a.availability,a.cv,a.cv1,a.visa,a.postdate from resource a,members b where a.emid!='$mid' and a.emid=b.memberid $keywordres $skillsres $locres $countryres $expres $availres $visares order by postdate desc");


if(mysqli_num_rows($empres)){

while($row=mysqli_fetch_assoc($empres)){

$employers[]=$row;

}

}

else{

$noti='No Resources found';

}

$sql=mysqli_query($link,"select * from citybycountry where country='UK'");

while($res=mysqli_fetch_array($sql)){

$row[]=$res;

}

?>

<!doctype html>

<html>

<head>

     <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Search Bench Resources | Employers Hub</title>

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
  
  <li><a href="postdashboard" role="tab"><i class="fa  fa-fw fa-plus"></i>Post New Job</a></li>

  <!--<li><a href="requests" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Request from Agencies</a></li>-->

     <li class="candidates-nav nav-dropdown ">

  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">

    <i class="fa fa-fw fa-caret-right"></i> Jobs</a>



   <div class="collapse" id="candidatesDropdownMenu">

   <ul class="nav-sub" style="display: block;">

    <li><a href="postdashboard" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Post New Job</a>  </li>

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
   <li><a href="view-all-resource-engagement" role="tab" > <i class="fa fa-database"></i>Approve Request for Your Bench Resources </a> </li>  
   <li><a href="view-resources" role="tab" > <i class="fa fa-database"></i>View Your Bench Resources </a> </li>  
   <li><a href="search-resources" role="tab" > <i class="fa fa-database"></i>Search Bench Resources from Others </a> </li>  
   
  </ul>
   </div>
   </li>
   

   

</div>

</div>

<div class="col-sm-10">

<div class="account-navi-content">

<div class="tab-content">   

<div class="account-navi-content">
<div class="tab-content">   
<section class="panel panel-default">
<header class="panel-heading panel-heading--buttons wht-bg">

<b><a href="resources"><i class="fa fa-upload"></i>Upload More Bench Resources</a></b> ||

<b><a href="view-all-resource-engagement"><i class="fa fa-send"></i>Approve Request's</a></b> ||
<b><a href="view-resources"><i class="fa fa-eye"></i>View Your Bench Resources</a> </b></section>
</header>  

<div class="row">

<div class="col-xs-12">

<section  class="panel panel-default">

<header class="panel-heading panel-heading--buttons wht-bg">

<h4 class="gen-case">Search our Database of Bench Resources Uploaded by other Employers </h4>

</header>

<div class="panel-body minimal">			

<div class="table-responsive ">

<div class="col-sm-12">

                <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <div class="form-group">

                <div class="col-sm-2">

                <input name="keyword" class="form-control" placeholder="Search by Title"  type="text">

                </div>
                
                <div class="col-sm-2">

                <input name="skills" class="form-control" placeholder="Search by skills"  type="text">

                </div>

                                <div class="col-sm-2">



                <select class="form-control" name="country">

<option value="">Choose Country</option>

<option value="UK">UK</option>

<option value="US">US</option>

<option value="Middle East">Middle East</option>

<option value="India">India</option>

<option value="Europe">Europe</option>

<option value="Africa">Africa</option>

<option value="Malaysia">Malaysia</option>

<option value="Singapore">Singapore</option>

<option value="China">China</option>

<option value="Thailand">Thailand</option>

<option value="Indonesia">Indonesia</option>

<option value="Australia">Australia</option>

<option value="Philippines">Philippines</option>

<option value="Hong Kong">Hong Kong</option>

<option value="Japan">Japan</option>

<option value="New Zealand">New Zealand</option>

<option value="North Korea">North Korea</option>

<option value="South Korea">South Korea</option>

<option value="Russia">Russia</option>

<option value="Canada">Canada</option>

<option value="Mexico">Mexico</option>

<option value="South America">South America</option>

</select>

</div>

                <div class="col-sm-2">

                <input name="loc" class="form-control"  placeholder="Search by city" type="text">

                <!--<select name="loc" class="form-control"  placeholder="Search by Location" type="text">

                <option value="">Location</option>

                <?php 

                foreach($row as $c){

                echo '<option value='.$c['city'].'>'.$c['city'].'</option>';

                }

                ?>

                </select>-->

                

                </div>

                <!--<div class="col-sm-2">

                <input name="exp" class="form-control" placeholder="Experience" autocomplete="off" type="text">

                </div> -->

                <div class="col-sm-2">

                <select name="avail" class="form-control" placeholder="Search by Availability" autocomplete="off" type="text">									

                <option value="">Availability</option>

                <option value="Immediate">Immediate</option>

                <option value="2 Weeks Notice">2 Weeks Notice</option>

                </select>

                </div> 

                

                

                <!--</div></div><div class="col-sm-12">

                                <div class="form-group">



                <div class="col-sm-3">

                <input name="visa" class="form-control" placeholder="Visa Status" type="text">

                <!--<select name="visa" class="form-control" placeholder="Visa Status" autocomplete="off" type="text">									

                <option value="">Visa</option>

                <option value="British/ILR">British/ILR</option>

                <option value="Tier1 General">Tier1 General</option>

                <option value="Tier2 General">Tier2 General</option>

                <option value="Tier2 General Dependant">Tier2 General Dependant</option>

                <option value="Tier2 ICT">Tier2 ICT</option>

                <option value="Tier2 ICT Dependant">Tier2 ICT Dependant</option>

                <option value="EU Citizen">EU Citizen</option>

                </select>-->

                </div> 

                <div class="col-sm-2">

                <input value="Search" class="btn btn-primary" name="search" type="submit">

                </div>

                </div>

                </div>  

                </form>    
                

</div>

				<?php if($noti){ echo $noti; }else{ ?>

                <table class="table table-striped">

                <thead>

                <tr>

                

                <th>Consultant</th>

                <th>Title</th>
                
                <th>Action</th>

                <th>Total Experience</th>

                <th>Location</th>

                <th> Availability</th>

                <th>Visa Status</th>
                
                <th>Skills</th>

                </tr>

                </thead>

                <tbody>

					<?php

                    foreach($employers as $row){ $reid=$row['id']; $statussql=mysqli_fetch_assoc(mysqli_query($link,"select * from resourceengagedetails where resourceid=$reid and engagedby=$mid limit 0,1"));

			    $sql1=mysqli_query($link,"select * from resourceengagedetails where resourceid=$reid and engagedby=$mid limit 0,1");

				$cnt=mysqli_num_rows($sql1);

                    echo'

                    <tr>

                    <td >'.$row['consultant'].'<br><small>'.$row['postdate'].'</small></td> 
                    
                    <td >'.$row['jobtitle'].'</td>';

                    if(($statussql['finalstatus']==1)&&($statussql['engagestatus']==1)){ echo'<td><a class="title" href="#" data-toggle="modal" data-target="#resource-modal" id="'.$row['id'].'"><b>(APPROVED) View Resource Details</b></a></td>'; }elseif(($statussql['finalstatus']=='')&&($statussql['engagestatus']==1)){echo '<td>Requested</td>'; }elseif(($statussql['finalstatus']==0)&&($statussql['engagestatus']==1)){echo '<td>Not Approved</td>'; }elseif(!$cnt){echo '<td>

                <div class="btn-group">

                <a class="title" href="engage-other-resource?resrcid='.$row['id'].'"><b>Engage</b></a>

                </div>

                </td>

                    <td >'.$row['totalexperience'].'</td>

					<td >'.$row['currentlocation'].' <br>'.$row['country'].'</td>

                    <td >'.$row['availability'].'</td>

                    <td >'.$row['visa'].'</td>
                    
                    <td >'.$row['skills'].'</td>

                </tr>';

                }

				}

                    ?>

                    </tbody>

                    </table>

                   <?php } ?>

</div>

</div>

</div>

</div>

</div>

</section>

</div>

</div>

</div>

<div class="modal fade" id="feedback-modal" tabindex="-1" role="dialog" aria-hidden="true">

<div class="modal-dialog modal-sm">

<div class="modal-content" id="feedback-modal-body">

</div>

</div>

</div>

<div class="modal fade" id="resource-modal" tabindex="-1" role="dialog" aria-hidden="true">

<div class="modal-dialog modal-sm">

<div class="modal-content" id="resource-modal-body">

</div>

</div>

</div>

<script>

$('#resource-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),

            essayId = e.relatedTarget.id;

        $.ajax({

            cache: false,

            type: 'POST',

            url: 'resource-modal.php',

            data: 'EID=' + essayId,

            success: function(data) {

				$modal.find('#resource-modal-body').html(data);

                $modal.find('#resource-modal-label').html( $('#job_title').data('value'));

				

            }

        });

    });

$('#feedback-modal').on('show.bs.modal', function(e) {

var $modal = $(this),

element=$(e.relatedTarget),

essayId = element.attr('id'),

cname=element.data('id');

$.ajax({

cache: false,

type: 'POST',

url: 'feedback-modal.php',

data: { EID : essayId,cname : cname},

success: function(data) {

$modal.find('#feedback-modal-body').html(data);

}

});

});

</script>

</body>

</html>