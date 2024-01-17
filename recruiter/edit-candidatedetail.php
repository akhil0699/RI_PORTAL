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
require_once '../config.php';
$content='';
$profilecount=array();
if(isset($_GET['candidateid']) ){
$id=$_GET['candidateid'];

}
$profile="Select * from candidates where id='$id'";


/**$result=mysql_query($profile);
while($row = mysql_fetch_array($result)){

            $profilecount[]=$row;
                }***/
$profilecount=mysqli_fetch_assoc(mysqli_query($link,$profile));

if(isset($_POST['submit']))
{
require_once 'update-candidate.php';
}
 ?>
<!doctype html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Edit Candidate | Recruiters Hub</title>
    <meta name="application-name" content="Recruiting-hub" />
    <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">
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
    <ul class="dropdown-menu" role="menu"><li><a href="company-profile"><i class="fa fa-user"></i>Company Profile</a>   </li>
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
    <li><a href="search" role="tab" ><i class="fa fa-fw fa-search"></i>Search Roles</a>    </li>
    <li><a href="job" role="tab"><i class="fa fa-fw fa-files-o"></i>Engaged Roles</a>    </li>
    <li><a href="disengaged" role="tab"><i class="fa fa-scissors"></i>DISENGAGED</a>  
    <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i>Supply Candidates (STS)</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="add_new" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add New Candidate</a>  </li>
    <li><a href="all" role="tab" ><i class="fa fa-list"></i>All Candidates</a> </li>
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
    <li><a href="interview" role="tab" > <i class="fa fa-users"></i> Shortlisted</a>  </li>
     <li><a href="offer" role="tab" > <i class="fa fa-users"></i>Offered</a>  </li>
      <li><a href="filled" role="tab" > <i class="fa fa-users"></i>Filled</a>  </li>
     
    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i> Rejected</a> </li>
    </ul>
   </div>
   </li>
    <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messages </a>    </li>
    <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Feedback Rating</a> </li>
<li><a href="psl-list" role="tab" > <i class="fa fa-list"></i>PSL</a> </li>
<li><a href="hire-resource" role="tab" > <i class="fa fa-database"></i>Bench Hiring</a> </li>
    <h4>Jobseeker Section</h4>

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
    <h3 class="panel-title"> Edit Candidate Details </h3>
  </header>
        <div class="panel-body">
<?php if($content) echo $content; else{ ?>
<form action="update-candidate" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $id; ?>" >
<div class="form-group col-sm-12">
<label class="col-sm-2">Full name (*)</label>
<div class="col-sm-10">
<input class="form-control" name="fname" value="<?php echo $profilecount['fname']; ?>" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Email (*)</label>
<div class="col-sm-10">
<input class="form-control" name="email" value="<?php echo $profilecount['email']; ?>"  type="email">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Contact number (*)</label>
<div class="col-sm-10">
<input class="form-control"  name="contactnumber" value="<?php echo $profilecount['contactnumber']; ?>"  type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Current Employer (*)</label>
<div class="col-sm-10">
<input class="form-control"  name="currentemployer" value="<?php echo $profilecount['currentemployer']; ?>" type="text">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Current Job Title (*)</label>
<div class="col-sm-10">
<input class="form-control" name="currentjobtitle" value="<?php echo $profilecount['currentjobtitle']; ?>" type="text">
</div></div>
<div class="form-group col-sm-12">
<label class="col-sm-2"> Total Experience (*)</label>
<div class="col-sm-10">
<div class="col-sm-3 no-padding">
<input class="numeric integer required form-control"   name="minex" value="<?php echo $profilecount['minex']; ?>"type="text">
</div>
<div class="col-sm-3">
<input class="numeric integer required form-control"  name="maxex" value="<?php echo  $profilecount['maxex']; ?>" type="text">
</div>
<div class="col-sm-2 no-padding">
In Years
</div>

</div></div>



<div class="form-group col-sm-12">
<label class="col-sm-2"> Current Salary/Rate (*)</label>
<div class="col-sm-3">
<input class="form-control"  name="currentsalary"  placeholder="in hundreds or thousands" value="<?php echo $profilecount['currentsalary']; ?>"type="number">
<p class="help-block">e.g.400 or 80000</p>
</div>
<div class="col-sm-3">
<select class="form-control"  id="currency1" name="currentcurrency">
<option value="">Currency</option>
<?php

                                               if($profilecount['currentcurrency']=='GBP £')

                                               echo '<option value="GBP £" selected>GBP £</option>';
                                           else
                                                echo '<option value="GBP £">GBP £</option>';
                                               
                                                
                                           if($profilecount['currentcurrency']=='EURO €')

                                               echo '<option value="EURO €" selected>EURO €</option>';
                                           else
                                                echo '<option value="EURO €">EURO €</option>';
                                                
                                            if($profilecount['currentcurrency']=='USD $')

                                               echo '<option value="USD $" selected>USD $</option>';
                                           else
                                                echo '<option value="USD $">USD $</option>';
                                                
                                            if($profilecount['currentcurrency']=='Rs.')
                                                  echo'<option value="Rs." selected>Rs.</option>';
                                            else
                                                echo'<option value="Rs.">Rs.</option>';
                                                
                                                if($profilecount['currentcurrency']=='AED')
                                                 echo  '<option value="AED" selected>AED</option>';
                                            else
                                                echo  '<option value="AED">AED</option>';
                                                
                                                if($profilecount['currentcurrency']=='SGD S$')
                                                 echo  '<option value="SGD S$" selected>SGD S$</option>';
                                            else
                                                echo  '<option value="SGD S$">SGD S$</option>';
                                                
                                                if($profilecount['currentcurrency']=='CHF SFr.')
                                                 echo  '<option value="CHF SFr." selected>CHF SFr.</option>';
                                            else
                                                echo  '<option value="CHF SFr.">CHF SFr.</option>';
                                                
                                                if($profilecount['currentcurrency']=='EGP E£')
                                                 echo  '<option value="EGP E£" selected>EGP E£</option>';
                                            else
                                                echo  '<option value="EGP E£">EGP E£</option>';
                                                
                                                if($profilecount['currentcurrency']=='SEK kr')
                                                 echo  '<option value="SEK kr" selected>SEK kr</option>';
                                            else
                                                echo  '<option value="SEK kr">SEK kr</option>';
                                                
                                                if($profilecount['currentcurrency']=='PLN zl')
                                                 echo  '<option value="PLN zl" selected>PLN zl</option>';
                                            else
                                                echo  '<option value="PLN zl">PLN zl</option>';
                                                
                                                if($profilecount['currentcurrency']=='BGN')
                                                 echo  '<option value="BGN" selected>BGN</option>';
                                            else
                                                echo  '<option value="BGN">BGN</option>';
                                                
                                                if($profilecount['currentcurrency']=='CAD $')
                                                 echo  '<option value="CAD $" selected>CAD $</option>';
                                            else
                                                echo  '<option value="CAD $">CAD $</option>';
                                                
                                                if($profilecount['currentcurrency']=='MXN')
                                                 echo  '<option value="MXN" selected>MXN</option>';
                                            else
                                                echo  '<option value="MXN">MXN</option>';
                                                
                                                 if($profilecount['currentcurrency']=='BRL')
                                                 echo  '<option value="BRL" selected>BRL</option>';
                                            else
                                                echo  '<option value="BRL">BRL</option>';
                                                
                                                if($profilecount['currentcurrency']=='KRW')
                                                 echo  '<option value="KRW" selected>KRW</option>';
                                            else
                                                echo  '<option value="KRW">KRW</option>';
                                                
                                                if($profilecount['currentcurrency']=='MYR')
                                                 echo  '<option value="MYR" selected>MYR</option>';
                                            else
                                                echo  '<option value="MYR">MYR</option>';
                                                
                                                if($profilecount['currentcurrency']=='SAR')
                                                 echo  '<option value="SAR" selected>SAR</option>';
                                            else
                                                echo  '<option value="SAR">SAR</option>';
                                                
                                                if($profilecount['currentcurrency']=='AUD')
                                                 echo  '<option value="AUD" selected>AUD</option>';
                                            else
                                                echo  '<option value="AUD">AUD</option>';
                                                
                                                if($profilecount['currentcurrency']=='ZAR')
                                                 echo  '<option value="ZAR" selected>ZAR</option>';
                                            else
                                                echo  '<option value="ZAR">ZAR</option>';



                                                              ?>

</select>
</div>

<div class="col-sm-2">
<select class="form-control"  id="typesalary" name="typesalary">
<option value="">Choose one</option>
<?php

                                               if($profilecount['typesalary']=='Per Year')

                                               echo '<option value="Per Year" selected>Per Year</option>';
                                           else
                                                echo '<option value="Per Year">Per Year</option>';
                                                
                                                if($profilecount['typesalary']=='Per Hour')

                                               echo '<option value="Per Hour" selected>Per Hour</option>';
                                           else
                                                echo '<option value="Per Hour">Per Hour</option>';
                                               
                                                
                                           if($profilecount['typesalary']=='Per Day')

                                               echo '<option value="Per Day" selected>Per Day</option>';
                                           else
                                                echo '<option value="Per Day">Per Day</option>';
                                                
                                            if($profilecount['typesalary']=='Per Month')

                                               echo '<option value="Per Month" selected>Per Month</option>';
                                           else
                                                echo '<option value="Per Month">Per Month</option>';
                                                

                                                              ?>

</select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Expected Salary/Rate (*)</label>
<div class="col-sm-3">
<input class="form-control"  value="<?php echo $profilecount['desiredsalary']; ?>" name="desiredsalary" placeholder="in hundreds or thousands" type="number"><p class="help-block">e.g.600 or 100000</p>
</div>
<div class="col-sm-3">
<select class="form-control" id="currency2" name="expectedcurrency">
<option value="">Currency</option>
<?php

                                               if($profilecount['expectedcurrency']=='GBP £')
                                                  echo'<option value="GBP £" selected>GBP £</option>';
                                            else
                                                echo'<option value="GBP £">GBP £</option>';
                                            if($profilecount['expectedcurrency']=='EURO €')
                                                 echo  '<option value="EURO €" selected>EURO €</option>';
                                            else
                                                echo  '<option value="EURO €">EURO €</option>';

                                               if($profilecount['expectedcurrency']=='USD $')

                                               echo '<option value="USD $" selected>USD $</option>';
                                           else
                                                echo '<option value="USD $">USD $</option>';
                                           if($profilecount['expectedcurrency']=='Rs.')

                                               echo '<option value="Rs." selected>Rs.</option>';
                                           else
                                                echo '<option value="Rs.">Rs.</option>';
                                            if($profilecount['expectedcurrency']=='AED')

                                               echo '<option value="AED" selected>AED</option>';
                                           else
                                                echo '<option value="AED">AED</option>';
                                                
                                                if($profilecount['expectedcurrency']=='SGD S$')

                                               echo '<option value="SGD S$" selected>SGD S$</option>';
                                           else
                                                echo '<option value="SGD S$">SGD S$</option>';
                                                
                                                if($profilecount['expectedcurrency']=='CHF SFr.')

                                               echo '<option value="CHF SFr." selected>CHF SFr.</option>';
                                           else
                                                echo '<option value="CHF SFr.">CHF SFr.</option>';
                                                
                                                if($profilecount['expectedcurrency']=='EGP E£')

                                               echo '<option value="EGP E£" selected>EGP E£</option>';
                                           else
                                                echo '<option value="EGP E£">EGP E£</option>';
                                                
                                                if($profilecount['expectedcurrency']=='SEK kr')

                                               echo '<option value="SEK kr" selected>SEK kr</option>';
                                           else
                                                echo '<option value="SEK kr">SEK kr</option>';
                                                
                                                if($profilecount['expectedcurrency']=='PLN zl')
                                                 echo  '<option value="PLN zl" selected>PLN zl</option>';
                                            else
                                                echo  '<option value="PLN zl">PLN zl</option>';
                                                
                                                if($profilecount['expectedcurrency']=='BGN')
                                                 echo  '<option value="BGN" selected>BGN</option>';
                                            else
                                                echo  '<option value="BGN">BGN</option>';
                                                
                                                if($profilecount['expectedcurrency']=='MXN')
                                                 echo  '<option value="MXN" selected>MXN</option>';
                                            else
                                                echo  '<option value="MXN">MXN</option>';
                                                
                                                if($profilecount['expectedcurrency']=='BRL')
                                                 echo  '<option value="BRL" selected>BRL</option>';
                                            else
                                                echo  '<option value="BRL">BRL</option>';
                                                
                                                if($profilecount['expectedcurrency']=='KRW')
                                                 echo  '<option value="KRW" selected>KRW</option>';
                                            else
                                                echo  '<option value="KRW">KRW</option>';
                                                
                                                if($profilecount['expectedcurrency']=='MYR')
                                                 echo  '<option value="MYR" selected>MYR</option>';
                                            else
                                                echo  '<option value="MYR">MYR</option>';
                                                
                                                if($profilecount['expectedcurrency']=='SAR')
                                                 echo  '<option value="SAR" selected>SAR</option>';
                                            else
                                                echo  '<option value="SAR">SAR</option>';
                                                
                                                if($profilecount['expectedcurrency']=='AUD')
                                                 echo  '<option value="AUD" selected>AUD</option>';
                                            else
                                                echo  '<option value="AUD">AUD</option>';
                                                
                                                if($profilecount['expectedcurrency']=='ZAR')
                                                 echo  '<option value="ZAR" selected>ZAR</option>';
                                            else
                                                echo  '<option value="ZAR">ZAR</option>';



                                                              ?>
</select>
</div>

<div class="col-sm-2">
<select class="form-control"  id="typesalary" name="typesalary">
<option value="">Choose one</option>
<?php

                                           if($profilecount['typesalary']=='Per Year')
                                                  echo'<option value="Per Year" selected>Per Year</option>';
                                            else
                                                echo'<option value="Per Year">Per Year</option>';
                                            if($profilecount['typesalary']=='Per Hour')

                                               echo '<option value="Per Hour" selected>Per Hour</option>';
                                           else
                                                echo '<option value="Per Hour">Per Hour</option>';
                                            if($profilecount['typesalary']=='Per Day')
                                                 echo  '<option value="Per Day" selected>Per Day</option>';
                                            else
                                                echo  '<option value="Per Day">Per Day</option>';
                                            if($profilecount['typesalary']=='Per Month')
                                                 echo  '<option value="Per Month" selected>Per Month</option>';
                                            else
                                                echo  '<option value="Per Month">Per Month</option>';
                                           
                                                              ?>

</select>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Nationality (*)</label>
<div class="col-sm-5">
<input class="form-control"  name="nationality" placeholder="e.g British or Indian with Tier2 General Visa" value="<?php echo $profilecount['nationality']; ?>">
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Notice Period (*)</label>
<div class="col-sm-10">
<input class="form-control" name="notice" placeholder="e.g 30 days" value="<?php echo $profilecount['notice']; ?>" type="text">
</div></div>


<div class="form-group col-sm-12">  
<label class="col-sm-2"> Current City (*)</label>
<div class="col-sm-10">
<input class="form-control"  value="<?php echo $profilecount['location']; ?>" name="location" placeholder="e.g London" type="text">
</div></div>

<div class="form-group col-sm-12">
<div class="col-sm-2"></div>
<div class="checkbox col-sm-10">
<?php if($profilecount['relocate'])echo' <label><input  value="1" name="relocate" type="checkbox" checked> Willing to relocate   </label>';
else
echo' <label><input  value="0" name="relocate" type="checkbox"> Willing to relocate   </label>';?>
 </div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Additional info</label>
 <div class="col-sm-10">
<textarea class="form-control" name="additionalinfo"><?php echo $profilecount['additionalinfo']; ?></textarea>
</div></div>


<div class="form-group col-sm-12">
<label class="col-sm-2">Upload CV (*)</label>
<div class="col-sm-10">
<input name="cv" type="file" ><?php echo '<a href="candidatecvs/'.$mid.'/'.$id.'/'.$profilecount['cv'].'" target="_blank"><p>'.$profilecount['cv'].'</p> </a>'; ?>         
<p class="help-block">Allowed file types: PDF, Word Documents, Max File Size: 4 MB</p>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2">Upload Video/additional file of Candidate</label>
<div class="col-sm-10">
<input name="video" type="file" ><?php echo '<a href="video/'.$mid.'/'.$id.'/'.$profilecount['video'].'" target="_blank"><p>'.$profilecount['video'].'</p> </a>'; ?>         
<p class="help-block">Allowed file types: MPEG,MP4,MOV,AVI,FLV or any video format. Video length not more than 5 minutes.</p>
</div></div>

<div class="form-group col-sm-12">
<label class="col-sm-2"> Job Type (*)</label>
<div class="col-sm-3">
<select class="form-control"  required id="jobtype" name="jobtype">
<option value="">Job Type</option>
<?php

                                               if($profilecount['jobtype']=='Permanent')

                                               echo '<option value="Permanent" selected>Permanent</option>';
                                           else
                                                echo '<option value="Permanent">Permanent</option>';
                                               
                                                
                                           if($profilecount['jobtype']=='Contract')

                                               echo '<option value="Contract" selected>Contract</option>';
                                           else
                                                echo '<option value="Contract">Contract</option>';

                                                              ?>

</select>
</div><div>

<div class="col-sm-12 form-group">
     <input type="checkbox" value="1" name="terms" required/>
     <label><small>I certify that I have spoken to this candidate and have explained about the job opportunity. Further, I have taken the consent from the candidate and have no objection in uploading the CV on Recruiting Hub. Read our Privacy Policy here <a class="title" href="#" data-toggle="modal" data-target="#employer-policies">Privacy Policy</a>.</small><label>
     
               </div>

<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<input name="submit" value="Save Changes" class=" btn btn-primary" type="submit">
</div></div>
</form>

<?php } ?>
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
<script>
$(document).ready(function(){
$("#currency1").change(function() {
    var id = $(this).val();
    $("#currency2").val(id);
 });  
$("#currency2").change(function() {
    var id1 = $(this).val();
    $("#currency1").val(id1);
 });
 
 });
</script>



 </body>
</html>
