<?php if (session_status() == PHP_SESSION_NONE) {

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

require_once '../config.php';

if(isset($_POST['EID']))

$reid=$_POST['EID'];

$sql=mysqli_fetch_assoc(mysqli_query($link,"select * from resourceengagedetails where resourceid=$reid")); 

$getemp=mysqli_fetch_assoc(mysqli_query($link,"select a.*,b.* from members a,companyprofile b where a.memberid='".$sql['empidofresource']."' and a.companyid=b.id")); 

$sql1=mysqli_fetch_assoc(mysqli_query($link,"select * from resource where id=$reid "));

?>

<div id="job_title" data-value="<?php echo $job['jobtitle'];?>"> </div>

<table class="table table-striped">

<thead></thead>

  <tbody>
    

    <tr>

      <td>Resource From:</td>

      <td><b><?php echo $getemp['name'];?></b> </td>

    </tr>
    
    <tr>

      <td>Contact Person:</td>

      <td><b><?php echo $getemp['firstname'];?></b> </td>

    </tr>
    
    <tr>

      <td>Phone:</td>

      <td><b><?php echo $getemp['mobile'];?></b> </td>

    </tr>
    
    <tr>

      <td>Company Email:</td>

      <td><?php echo $getemp['email'];?> </td>

    </tr>
    
     <tr>

      <td>Resource Name:</td>

      <td><b><?php echo $sql1['consultant'];?></b> </td>

    </tr>
    
     <tr>

      <td>Resource Title:</td>

      <td><?php echo $sql1['jobtitle'];?> </td>

    </tr>
    
    <tr>

      <td>Country:</td>

      <td><?php echo $getemp['country'];?> </td>

    </tr>
    
    <tr>

      <td>City:</td>

      <td><?php echo $getemp['city'];?> </td>

    </tr>
    
    <tr>

      <td>Availability:</td>

      <td><?php echo $sql1['availability'];?> </td>

    </tr>
    
    <tr>

      <td>Visa:</td>

      <td><?php echo $sql1['visa'];?> </td>

    </tr>
    
    <tr>

      <td>Consultant Profile:</td>

      <?php  if(file_exists ('../employer/resourcecvs/'.$sql['empidofresource'].'/'.$reid.'/'.$sql1['cv'].'')) { ?>
      <td><a href="<?php echo '../employer/resourcecvs/'.$sql['empidofresource'].'/'.$reid.'/'.$sql1['cv']; ?>" target="_blank"  title="view cv"><b>Download Resource Profile</b></a></td>
      <?php } else{ ?>
      <td> CV not found </td>
      <?php } ?></a> </td>

    </tr>
    
    <tr>

      <td>Assessment:</td>

      <?php  if(file_exists ('../employer/resourceassessment/'.$sql['empidofresource'].'/'.$reid.'/'.$sql1['assessment']) && $sql1['assessment']) { ?>
      <td><a href="<?php echo '../employer/resourceassessment/'.$sql['empidofresource'].'/'.$reid.'/'.$sql1['assessment']; ?>" target="_blank"  title="view cv">Assessment</a></td>
      <?php } else{ ?>
      <td> Assessment not found </td>
      <?php } ?></a> </td>

    </tr>
    
    <tr>

      <td>Our Bench Hiring is a free service and companies or agencies are requested to directly engage with clients to discuss the arrangement of bench resources.</td>
      
      <td>Please ask us for service level agreement templates in case required</td>

    </tr>
   