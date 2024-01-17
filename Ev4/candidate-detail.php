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
}
$job='';
require_once '../config.php';
if(isset($_POST['EID']))
$candidateid=$_POST['EID'];

$candidateres =mysqli_query($link,"SELECT a.*,b.email as recruiteremail,c.name as agency,sc.status as candidatestatus,sc.submitdate as submitdate,sc.candidateid as candidateid,sc.employernotes as employernotes,sc.empduplicatenotes as empduplicatenotes,sc.recduplicatenotes as recduplicatenotes,sc.recruiternotes as recruiternotes,sc.amnotes as amnotes,sc.recruiterid as recruiterid,sc.jobid from candidates a,members b, companyprofile c,submitted_candidates sc  where a.id=$candidateid and b.memberid=a.recruiterid and a.id=sc.candidateid and c.id=b.companyid"); 
$ispresent =mysqli_num_rows($candidateres);
if($ispresent > 0){ 
   $row =mysqli_fetch_array($candidateres);
}
if($row){
?>
<div id="candidate_name" data-value="<b><?php echo $row['fname'];?></b> - ID: <?php echo $row['id'];?> - Submitted: <b><?php echo date("F j, Y",strtotime($row['submitdate']));  ?></b> - Status - <b><?php echo $row['candidatestatus'];?></b> <br>Employer Notes (if any) - <b><?php echo $row['employernotes'];?></b>   <br>Employer Duplicate Notes (if any) - <b><?php echo $row['empduplicatenotes'];?></b> <br>Recruiter Notes (if any) - <b><?php echo $row['recruiternotes'];?></b> <br>AM Notes (if any) - <b><?php echo $row['amnotes'];?></b>"></div> 


  
<table class="table table-striped">
<thead></thead>
  <tbody>
      
      <tr>
      <td><b>Download CV:</b></td>
     <?php  if(file_exists ('../recruiter/candidatecvs/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['cv'])) {?>
      <td><a href="<?php echo '../recruiter/candidatecvs/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['cv']; ?>" target="_blank"  title="view cv"><b>DOWNLOAD CV</b></a></td>
      <?php } else{ ?>
      <td> CV not found </td>
      <?php } ?>
     
    </tr>
    
<tr>
      <td>Add Notes:</td>
      <td><?php 

        echo '<li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>';
?>
  </td>
    </tr>
      
  <tr class="float-right">
      <td><b>Provide Feedback:</b></td>
    <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
<?php 	if($row['candidatestatus']=="Awaiting Feedback" || $row['candidatestatus']=="Shortlisted") {   

if($row['candidatestatus']=="Awaiting Feedback") {


        echo '<li><a title="Select Candidate" href="select-candidate?candidateid='.$row['candidateid'].'"><b>Shortlist</b></a></li>

            <li> <a class="title" href="#"  data-toggle="modal" data-target="#feedback-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>CV Reject</a></li>
            <li> <a class="title" href="#"  data-toggle="modal" data-target="#duplicate-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Duplicate</a></li>
            <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>';
 } else { 

echo '<li><a title="Offer Candidate" href="offer-candidate?candidateid='.$row['candidateid'].'"><b>Offer</b></a></li>
			 <li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Interview Reject</a></li>
			 <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>'; 
 } 
echo'
		</ul>
    </div>';
	}
	
		elseif($row['candidatestatus']=="Offered"){
	  	   echo'

		   <li><a title="Fill vacancy" href="fill-candidate?candidateid='.$row['candidateid'].'"><b>Fill Vacancy</b></a></li>
		   <li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Offer Reject</a></li>
		   <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>
		   ';
			
			echo'
		</ul>
    </div>';
	}
	elseif($row['candidatestatus']=="Filled"){
	  	   echo'

		   <li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Move Back to Offer Rejected</a></li>';
			
			echo'
		</ul>
    </div>';
	}
	elseif($row['candidatestatus']=="Duplicate"){
	  	   echo'

		   <li> <a class="title" href="#"  data-toggle="modal" data-target="#duplicate-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Reply Duplicate Reason</a></li>
		<li><a title="Reconsider" href="reconsider?candidateid='.$row['candidateid'].'">Reconsider</a></li>';
			
			echo'
		</ul>
    </div>';
	}
	else { 

echo '<li><a title="Reconsider" href="reconsider?candidateid='.$row['candidateid'].'">Reconsider</a></li>';
 } ?>

      </ul>
    </div>
  </td>
  </tr>
  

  <tr>
       <td>Download Audio/Video/Additional file of Candidate:</td>
     <?php  if(file_exists ('../recruiter/video/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['video']) && $row['video']) {?>
      <td><a href="<?php echo '../recruiter/video/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['video']; ?>" target="_blank"  title="view video"><b>Download Audio/Video/Additional file of Candidate (Available)</b></a></td>
      <?php } else{ ?>
      <td> No Audio/Video/Additional file found </td>
      <?php } ?>
    </tr>
    
    <tr>
      <td>Email:</td>
      <td><?php echo $row['email'];?> </td>
    </tr>
	<tr>
      <td>Contact number:</td>
      <td><?php echo $row['contactnumber']; ?> </td>
    </tr>
    <tr>
      <td>Total Experience:</td>
      <td><?php echo $row['minex'].' years '.$row['maxex'].' months'; ?> </td>
    </tr>
    <tr>
      <td>Current Employer:</td>
      <td><?php echo $row['currentemployer'];?> </td>
    </tr>

    <tr>
      <td>Current Job Title:</td>
      <td><?php echo $row['currentjobtitle'];?> </td>
    </tr>
    
    <tr>
      <td>Job Type:</td>
      <td><?php echo $row['jobtype'];?> </td>
    </tr>
	
     <tr>
      <td>Current Salary/Rate:</td>
      <td><?php echo $row['currentcurrency'];?> <?php echo $row['currentsalary'];?> / <?php echo $row['typesalary'];?></td>
    </tr>

	 <tr>
      <td>Expected Salary/Rate:</td>
      <td><?php echo $row['currentcurrency'];?> <?php echo $row['desiredsalary'];?> / <?php echo $row['typesalary'];?></td>
    </tr>
    
	<tr>
      <td>Nationality:</td>
      <td><?php echo $row['nationality'];?> </td>
    </tr>


	 <tr>
      <td>Notice Period:</td>
      <td><?php echo $row['notice'];?> </td>
    </tr>
    
   
     <tr>
      <td>Willing to Relocate:</td>
      <td><?php if($row['relocate']) echo 'Yes'; else echo 'No';?> </td>
    </tr>
    
    <tr>
      <td>Current Country:</td>
      <td><?php echo $row['country'];?> </td>
    </tr>

	 <tr>
      <td>Current City:</td>
      <td><?php echo $row['location'];?> </td>
    </tr>
        
     <tr>
      <td>Download CV:</td>
     <?php  if(file_exists ('../recruiter/candidatecvs/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['cv'])) {?>
      <td><a href="<?php echo '../recruiter/candidatecvs/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['cv']; ?>" target="_blank"  title="view cv"><b>DOWNLOAD CV</b></a></td>
      <?php } else{ ?>
      <td> CV not found </td>
      <?php } ?>
     
    </tr>
    
   <tr>
       <td>Download Audio/Video/Additional file of Candidate:</td>
     <?php  if(file_exists ('../recruiter/video/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['video']) && $row['video']) {?>
      <td><a href="<?php echo '../recruiter/video/'.$row['recruiterid'].'/'.$row['id'].'/'.$row['video']; ?>" target="_blank"  title="view video">Download Audio/Video/Additional file of Candidate (Available)</a></td>
      <?php } else{ ?>
      <td> No Audio/Video/Additional file found </td>
      <?php } ?>
    </tr>
    
    <tr>
      <tr class="float-right">
      <td><b>Provide Feedback:</b></td>
    <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
<?php 	if($row['candidatestatus']=="Awaiting Feedback" || $row['candidatestatus']=="Shortlisted") {   

if($row['candidatestatus']=="Awaiting Feedback") {


        echo '<li><a title="Select Candidate" href="select-candidate?candidateid='.$row['candidateid'].'"><b>Shortlist</b></a></li>

            <li> <a class="title" href="#"  data-toggle="modal" data-target="#feedback-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>CV Reject</a></li>
            <li> <a class="title" href="#"  data-toggle="modal" data-target="#duplicate-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Duplicate</a></li>
            <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>';
 } else { 

echo '<li><a title="Offer Candidate" href="offer-candidate?candidateid='.$row['candidateid'].'"><b>Offer</b></a></li>
			 <li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Interview Reject</a></li>
			 <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>'; 
 } 
echo'
		</ul>
    </div>';
	}
	
		elseif($row['candidatestatus']=="Offered"){
	  	   echo'

		   <li><a title="Fill vacancy" href="fill-candidate?candidateid='.$row['candidateid'].'"><b>Fill Vacancy</b></a></li>
		   <li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Offer Reject</a></li>
		   <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>
		   ';
			
			echo'
		</ul>
    </div>';
	}
	elseif($row['candidatestatus']=="Filled"){
	  	   echo'

		   <li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Move Back to Offer Rejected</a></li>';
			
			echo'
		</ul>
    </div>';
	}
	elseif($row['candidatestatus']=="Duplicate"){
	  	   echo'

		   <li> <a class="title" href="#"  data-toggle="modal" data-target="#duplicate-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Reply Duplicate Reason</a></li>
		<li><a title="Reconsider" href="reconsider?candidateid='.$row['candidateid'].'">Reconsider</a></li>';
			
			echo'
		</ul>
    </div>';
	}
	else { 

echo '<li><a title="Reconsider" href="reconsider?candidateid='.$row['candidateid'].'">Reconsider</a></li>';
 } ?>

      </ul>
    </div>
  </td>
  </tr>
    
	<tr>
      <td>Agency:</td>
      <td><?php echo $row['agency'] ?> </td>
    </tr>
    
    
    <tr>
      <td>Share with your Hiring Manager:</td>
      <td><!-- AddToAny BEGIN -->
<div class="a2a_kit a2a_kit_size_32 a2a_default_style">
<a class="a2a_dd" href="https://www.addtoany.com/share"></a>
<a class="a2a_button_email"></a>
<a class="a2a_button_whatsapp"></a>
</div>
<script>
var a2a_config = a2a_config || {};
a2a_config.num_services = 2;
</script>
<script async src="https://static.addtoany.com/menu/page.js"></script>
<!-- AddToAny END --> </td>
    </tr>
    
  </tbody>
</table>


<hr>
 <h5>Additional Information</h5>	
  <p><?php echo $row['additionalinfo'];?></p><br />
  
  
   <h5>CV Copy</h5>
<textarea rows="5" cols="80" class="form-control"  name="cvcopy" readonly="readonly"><?php echo $row['resume'];?></textarea>    
  <hr>

<tr class="float-right">
      <td><b>Provide Feedback:</b></td>
    <td>
    <div class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu--right" role="menu">
<?php 	if($row['candidatestatus']=="Awaiting Feedback" || $row['candidatestatus']=="Shortlisted") {   

if($row['candidatestatus']=="Awaiting Feedback") {


        echo '<li><a title="Select Candidate" href="select-candidate?candidateid='.$row['candidateid'].'"><b>Shortlist</b></a></li>

            <li> <a class="title" href="#"  data-toggle="modal" data-target="#feedback-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>CV Reject</a></li>
            <li> <a class="title" href="#"  data-toggle="modal" data-target="#duplicate-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Duplicate</a></li>
            <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>';
 } else { 

echo '<li><a title="Offer Candidate" href="offer-candidate?candidateid='.$row['candidateid'].'"><b>Offer</b></a></li>
			 <li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Interview Reject</a></li>
			 <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>'; 
 } 
echo'
		</ul>
    </div>';
	}
	
		elseif($row['candidatestatus']=="Offered"){
	  	   echo'

		   <li><a title="Fill vacancy" href="fill-candidate?candidateid='.$row['candidateid'].'"><b>Fill Vacancy</b></a></li>
		   <li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Offer Reject</a></li>
		   <li> <a class="title" href="#"  data-toggle="modal" data-target="#notes-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'><b>ADD NOTES</b></a></li>
		   ';
			
			echo'
		</ul>
    </div>';
	}
	elseif($row['candidatestatus']=="Filled"){
	  	   echo'

		   <li> <a class="title" href="#" data-toggle="modal" data-target="#feedback-modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Move Back to Offer Rejected</a></li>';
			
			echo'
		</ul>
    </div>';
	}
	elseif($row['candidatestatus']=="Duplicate"){
	  	   echo'

		   <li> <a class="title" href="#"  data-toggle="modal" data-target="#duplicate-modal" data-dismiss="modal" id='.$row['candidateid'].' data-id='.$row['jobid'].'>Reply Duplicate Reason</a></li>
		<li><a title="Reconsider" href="reconsider?candidateid='.$row['candidateid'].'">Reconsider</a></li>';
			
			echo'
		</ul>
    </div>';
	}
	else { 

echo '<li><a title="Reconsider" href="reconsider?candidateid='.$row['candidateid'].'">Reconsider</a></li>';
 } ?>

      </ul>
    </div>
  </td>
  </tr>
  
<?php 
}
 ?>

<?php 

if($iam == 'Employer' ){
  try {

    $sc =mysqli_query($link,"SELECT *  from submitted_candidates where candidateid=$candidateid"); 
 


    $rowF =mysqli_fetch_array($sc);
    //var_dump($rowF);
  
  if($rowF['viewed'] == 0 && $rowF['status'] == 'Awaiting Feedback'){
    mysqli_query($link,"UPDATE submitted_candidates SET viewed=1,viewedtime=NOW() where candidateid=$candidateid");
  }
  //  mysqli_query($link,"UPDATE submitted_candidates SET viewed=1,viewedtime='".date('Y-m-d H:i:s')."' where candidateid=$candidateid");
  }
  
  catch(Exception $e) {
    echo 'Message: ' .$e->getMessage();
  }


} ?>