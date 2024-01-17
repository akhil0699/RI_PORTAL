<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email=$iam='';
$content=$errormsg='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$adminrights=$_SESSION['adminrights'];
$cid=$_SESSION['cid'];
$admin=$_SESSION['admin'];
$rcountry=$_SESSION['country'];


require_once '../config.php';
if(isset($_GET['jobid'])){
$jobid=$_GET['jobid'];

mysqli_query($link,"update request set status='Disengaged' where jobid='$jobid' and recruiterid='$mid'");
//count and update engage
$res=mysqli_query($link,"select count(*) from request where jobid='$jobid' and status not in('Requested','Rejected','Disengaged')");
$row=mysqli_fetch_assoc($res);
if(!$row['count'])
mysqli_query($link,"update jobs set status='open' where jobid='$jobid'");
header('location: job');
exit();
}
else
$content="Application is not possible.";
}	
else{
$errormsg="Access denied";	
}
?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Recruiters Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
    <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="../employer/css/bootstrap.min.css" rel="stylesheet">
    <link href="../employer/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../employer/css/fonts/css/font-awesome.min.css" media="screen, projection">
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../employer/js/bootstrap.min.js"></script>

         
</head>
<body>

<div class="header-inner">
<div class="header-top">
<a class="navbar-brand" href="#"><img src="../employer/images/color-logo.png"></a>
</div>
</div>

<div class="summary-wrapper">
<div class="col-sm-12 account-summary no-padding">

<div class="col-sm-3 no-padding">
<div class="account-navi">
  <ul class="nav nav-tabs">
  <li><a href="../employer/dashboard" role="tab"><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
	<li><a href="../employer/submit-a-job" role="tab" ><i class="fa fa-fw fa-search"></i>Post a job</a>   </li>
     <li><a href="../employer/jobs" role="tab" ><i class="fa fa-fw fa-search"></i>Posted Jobs</a>   </li>
   
    <li><a href="../employer/logout" role="tab" ><i class="fa fa-fw fa-files-o"></i> Logout</a>    </li>
   
  </ul>
</div>
</div>

<div class="col-sm-9">
<div class="account-navi-content">
  <div class="tab-content">

    <section class="main-content-wrapper">
      <div class="pageheader pageheader--buttons">
          <div class="row">
            <div class="col-md-6">
              <h4>Candidate Application</h4>
            </div>
            <div class="col-md-6">
            <div class="panel-body panel-body--buttons text-right">

              </div>
            </div>
          </div>
        </div>


  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-body">

          <div class="row">
            <div class="col-md-12 table-responsive">
              <?php if(!$errormsg){ 
			  			if($content)
                        echo $content;
						}
			  		else echo $errormsg; ?>
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




 </body>
</html>
