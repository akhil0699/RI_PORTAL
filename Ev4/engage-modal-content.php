<?php
$job='';
require_once '../config.php';
if(isset($_POST['EID']))
$jobid=$_POST['EID'];

$jobres =mysqli_query($link,"SELECT a.*,b.accountmanager,b.replacementperiod,companyprofile.description as companydescription FROM jobs a,employers b,companyprofile,members WHERE a.jobid='$jobid' and a.memberid=members.memberid and members.companyid=companyprofile.id and companyprofile.name=b.companyname"); 
$isposted =mysqli_num_rows($jobres);
if($isposted > 0){ 
    while($row =mysqli_fetch_array($jobres)){ 
		$job=$row;
				}
}
if($job){
?>
<div id="job_title" data-value="<b><?php echo $job['jobtitle'];?></b> | Job Id: <b><?php echo $job['jobid'];?></b> | 1st Posted: <?php echo date("F j, Y",strtotime($job['postdate']));  ?> | Last Updated: <b><?php echo date("F j, Y",strtotime($job['updatedate']));  ?></b>"> </div>
<table class="table table-striped">
<thead></thead>
  <tbody>
    <tr>
      <td>Job Sector:</td>
      <td><?php echo $job['jobsector'];?> </td>
    </tr>
    <tr>
      <td>Job Type:</td>
      <td><?php echo ucwords($job['jobtype']);?> </td>
    </tr>
    
    <tr>
      <td>Working:</td>
      <td><?php echo ucwords($job['working']);?> </td>
    </tr>
    
    <tr>
      <td>Country:</td>
      <td><?php echo $job['country'];?> </td>
    </tr>
    
     <tr>
      <td>Location:</td>
      <td><?php echo $job['joblocation'];?> </td>
    </tr>
    
    <tr>
      <td>City:</td>
      <td><?php echo $job['city'];?> </td>
    </tr>
    
    <tr>
      <td>Total Experience:</td>
      <td><?php if($job['minex'] || $job['maxex']) echo $job['minex'].' - '.$job['maxex'].' (years)'; else echo "Not Mentioned";?></td>
    </tr>
     <tr>
      <td>Currency:</td>
          <td><?php if($job['currency']=='GBP') { echo '&pound'; }elseif($job['currency']=='USD'){echo '$';  }else { echo $job['currency']; } ?> </td>
     </tr>
    <tr>
     <?php if(($job['country']=='UK') && ($job['jobtype']=='Contract')) { 

      echo'<td>Day Rate</td>'; }elseif(($job['country']=='US') && (($job['jobtype']=='Contract')||($job['jobtype']=='Corp-To-Corp'))) { echo '<td>Hourly/Day Rate</td>'; }else{ echo '<td>Salary</td>';  } ?>

      
      <td><?php if(($job['country']=='India')&&($job['jobtype']=='Contract')){  if($job['minlakh']) echo $job['minlakh']; else echo '0';echo '-';if($job['maxlakh']) echo $job['maxlakh'];else echo '0';   if($job['currency']=='GBP')echo '&pound;';elseif($job['currency']=='USD') echo '$';else echo $job['currency'];     } elseif($job['country']=='India'){echo'Rs. ';if($job['minlakh']) echo $job['minlakh'].',';  if($job['minthousand']==0) echo '00,'; else echo $job['minthousand'].','; echo '000'; echo' - '; if($job['maxlakh']) echo $job['maxlakh'].',';  if($job['maxthousand']==0) echo '00,'; else echo $job['maxthousand'].','; echo '000';}else{ if($job['minlakh']) echo $job['minlakh']; else echo '0';echo '-';if($job['maxlakh']) echo $job['maxlakh'];else echo '0';   if($job['currency']=='GBP')echo '&pound;';elseif($job['currency']=='USD') echo '$'; else echo $job['currency'];    }?></td>
    </tr>
    
   <?php if((($job['country']!='UK') && ($job['jobtype']!='Contract')) &&(($job['country']!='US') && ($job['jobtype']!='Contract')) &&(($job['country']!='US') && ($job['jobtype']!='Corp-To-Corp'))) { ?>
    <tr>
      <td>Placement Fee:</td>
      <td>
      <?php if($job['fee']){ ?>  <?php echo $job['fee'];?> <?php } else { ?> (<?php  echo $job['currency'] ?> / ) <?php echo $job['rfee']; }?><?php if($job['ratetype']) echo '/'.$job['ratetype']; else echo ''; ?> %</b> of the Candidate's Offered Annual Base Salary (ABS/CTC) as our one time placement fee.
      </td>
    </tr>
    <?php } ?>

<?php //if( (($job['minex']=='0')&&($job['maxex']=='2'))||(($job['minex']=='1')&&($job['maxex']=='2'))||(($job['minex']=='0')&&($job['maxex']=='1'))){ ?>
      
      <!--<tr>
        <td>Account Manager:</td>
        <td><?php echo $job['accountmanager'];?>
        </td>
      </tr>-->

    <!--<tr>
      <td>Reason for Vacancy:</td>
      <td><?php echo $job['vacancyreason'];?></td>
    </tr>-->

   <!-- <tr>
      <td>Education:</td>
      <td><?php echo $job['degree'];?></td>
    </tr>-->

    <tr>
      <td>IR35:</td>
      <td><?php echo $job['ir35'];?></td>
    </tr>
    
    <!--<tr>
      <td>Stages of interview process:</td>
      <td><?php echo $job['interviewstages'];?> stages</td>
    </tr>

    <tr>
      <td>Predicted time for CV feedback:</td>
      <td><?php echo $job['feedback'];?></td>
    </tr>-->

    <!--<tr>
      <td>Key Skills:</td>
      <td><?php echo $job['keyskills'];?></td>
      </tr>-->
      
	<tr>
      <td>Notice Period:</td>
      <td><?php echo $job['notice'];?></td>
      </tr>
      
      <tr>
      <td>No of Vacancies:</td>
      <td><?php echo $job['novacancies'];?> Vacancy</td>
      </tr>
      
      <tr>
      <td>CV limit:</td>
      <td><?php echo $job['cvlimit'];?> CV's Per Agency</td>
      </tr>
      
      <tr>
        <td>Agency Limit:</td>
        <td><?php echo $job['agencycount'].'/'.$job['agencylimit'];?> Agencies
        </td>
      </tr>
<?php //} ?>
      
      <?php if($job['description1']){ ?>
    <tr>
    <td>Job Description (uploaded as file)</td>
    <td><a href="../employer/jobdescription/<?php echo $job['memberid'] ?>/<?php echo $job['jobid'] ?>/<?php echo $job['description1']; ?>" target="_blank"><?php echo $job['description1']; ?></a>
    </td>
    </tr>    
    <?php } ?>
      
      <!--<tr>
      <td>CV Submission Closing:</td>
      <td><?php echo $job['closingdate'];?></td>
      </tr>-->
      
      <tr>
      <td>Interviewer Comments:</td>
      <td><?php echo $job['interviewcomments'];?></td>
      </tr>
	
     

  </tbody>
</table>


<hr>

  <p><textarea rows="5" cols="80" class="form-control" readonly><?php echo $job['description'];?></textarea></p>

<hr>
<p><?php echo $job['companydescription'];?></p>

<?php } ?>
 