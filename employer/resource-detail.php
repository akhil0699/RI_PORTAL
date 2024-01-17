<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
	}
$mid=$name=$email=$iam=$noresrcmsg='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$cid=$_SESSION['cid'];
$consultant=$_SESSION['consultant'];
$ecountry=$_SESSION['country'];
$notification='';
}
else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login'); 
exit;
}
require_once '../config.php';

if(isset($_POST['EID']))

$reid=$_POST['EID'];

$getemp=mysqli_fetch_assoc(mysqli_query($link,"select * from resource where id=$reid "));
?>

<div id="job_title" data-value="<?php echo $job['jobtitle'];?>"> </div>

<table class="table table-striped">

<thead></thead>

  <tbody>

    <tr>

      <td>Consultant:</td>

      <td><b><?php echo $getemp['consultant'];?></b> ID - <?php echo $getemp['id'];?></td>

    </tr>
    
    <tr>

      <td>Job Title:</td>

      <td><?php echo $getemp['jobtitle'];?> </td>

    </tr>
    
    <tr>

      <td>Skills:</td>

      <td><?php echo $getemp['skills'];?> </td>

    </tr>
    
    <tr>

      <td>Total Experience:</td>

      <td><?php echo $getemp['totalexperience'];?> Years</td>

    </tr>
    
    <tr>
	<tr>

      <td>Location:</td>

      <td><?php echo $getemp['currentlocation'];?> </td>

    </tr>
    <tr>

      <td>Availability:</td>

      <td><?php echo $getemp['availability'];?> </td>

    </tr>
    <tr>

      <td>Visa:</td>

      <td><?php echo $getemp['visa'];?> </td>

    </tr>
    <tr>
      <td>Profile:</td>

      <?php  if(file_exists ('resourcecvs/'.$mid.'/'.$reid.'/'.$getemp['cv'].'')) { ?>
      <td><a href="<?php echo 'resourcecvs/'.$mid.'/'.$reid.'/'.$getemp['cv']; ?>" target="_blank"  title="view cv">Download Consultant Profile</a></td>
      <?php } else{ ?>
      <td> Profile not found </td>
      <?php } ?></a> </td>

    </tr>
    
    <tr>
      <td>Assessment:</td>

      <?php  if(file_exists ('resourceassessment/'.$mid.'/'.$reid.'/'.$getemp['assessment']) && $getemp['assessment']) { ?>
      <td><a href="<?php echo 'resourceassessment/'.$mid.'/'.$reid.'/'.$getemp['assessment']; ?>" target="_blank"  title="view cv">Download Assessment</a></td>
      <?php } else{ ?>
      <td> Assessment not found </td>
      <?php } ?></a> </td>

    </tr>
    
   