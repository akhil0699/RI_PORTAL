<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$profile=array();

include_once('../config.php');
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$adminrights=$_SESSION['adminrights'];
$cid=$_SESSION['cid'];
$admin=$_SESSION['admin'];
$rcountry=$_SESSION['country'];

$profile1=$profile2=array();

}

else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login'); 
exit;
}
require_once '../config.php';

if(isset($_SESSION['mark'])){
$highlight=$_SESSION['mark'];
}else{
$highlight='';
}

if(isset($_GET['id'])){

$canditateid=$_GET['id'];

$sql=mysqli_query($link,"select a.id,a.name,a.email,a.cv,a.resume,a.resume1,a.video,a.years,a.months,a.isd,a.contact,a.intype,b.designation,b.currentcompany,b.salarylakhs,b.salarythousand,b.city,a.country,a.registereddate,a.lastmodified,b.noticeperiod,b.skills,b.fromyear,b.toyear,b.frommonths,b.tomonths,c.higherqualification,c.specialization,c.course,c.institute,c.coursetype,c.yearofpassing from canditate a,employment b,education c where a.id=b.canditateid and a.id=c.canditateid  and b.type='current' and a.id=$canditateid");



while($row=mysqli_fetch_array($sql)){

$profile=$row;

}

$sql1=mysqli_query($link,"select a.id,a.name,a.email,a.cv,a.resume,a.resume1,a.video,a.years,a.months,a.isd,a.contact,a.intype,b.designation,b.currentcompany,b.salarylakhs,b.salarythousand,b.city,a.country,a.registereddate,a.lastmodified,b.noticeperiod,b.skills,b.fromyear,b.toyear,b.frommonths,b.tomonths,c.higherqualification,c.specialization,c.course,c.institute,c.coursetype,c.yearofpassing from canditate a,employment b,education c where a.id=b.canditateid and a.id=c.canditateid  and b.type='previous' and a.id=$canditateid");

while($row1=mysqli_fetch_array($sql1)){

$profile1[]=$row1;

}


$sql2=mysqli_query($link,"select a.id,a.name,a.email,a.cv,a.resume,a.resume1,a.video,a.years,a.months,a.isd,a.contact,a.intype,b.designation,b.currentcompany,b.salarylakhs,b.salarythousand,b.city,a.country,a.registereddate,a.lastmodified,b.noticeperiod,b.skills,b.fromyear,b.toyear,b.frommonths,b.tomonths,c.higherqualification,c.specialization,c.course,c.institute,c.coursetype,c.yearofpassing from canditate a,employment b,education c where a.id=b.canditateid and a.id=c.canditateid  and b.type='other' and a.id=$canditateid");

while($row2=mysqli_fetch_array($sql2)){

$profile2[]=$row2;

}

}

?>

<!doctype html>

<html>

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

     <title><?php echo $profile['name'];?> | Recruiters Hub</title>

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

<!--<a class="btn btn-primary" href="search">-->

<!--     Sales Support +91 7339111042-->

<!--</a>-->

<ul class="navbar-right">

  <li class="dropdown">

    <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">

  <img src="images/user.png" class="img-circle" alt="">     <span class="text"><?php echo $_SESSION['name']; ?></span><span class="caret"></span> </a>

    <ul class="dropdown-menu" role="menu"><li><a href="company-profile"><i class="fa fa-user"></i>Agency Profile</a>   </li>

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
<h4>Client Section (Business)</h4>
    <li><a href="dashboard" role="tab" ><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
    <li><a href="flexirecruiter" role="tab" ><i class="fa  fa-fw fa-tachometer"></i>Flexi Recruiter <span class="label label-primary">New</span></a></li>
    <li><a href="search" role="tab" ><i class="fa fa-fw fa-search"></i>Search Jobs</a>    </li>
    <li><a href="job" role="tab"><i class="fa fa-fw fa-files-o"></i>Engaged Jobs</a>    </li>
    <li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Supply Candidates (STS)</a>

   <div class="collapse" id="candidatesDropdownMenu">
   <ul class="nav-sub" style="display: block;">
    <li><a href="add_new" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add new</a>  </li>
    <li><a href="all" role="tab" ><i class="fa fa-list"></i> All</a> </li>
    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>
    <li><a href="interview" role="tab" > <i class="fa fa-users"></i> Interviewing</a>  </li>
     <li><a href="offer" role="tab" > <i class="fa fa-users"></i>Offered</a>  </li>
      <li><a href="filled" role="tab" > <i class="fa fa-users"></i>Filled</a>  </li>
     
    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i> Rejected</a> </li>
    </ul>
   </div>
   </li>
    <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messages </a>    </li>
    <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Feedback Rating</a> </li>
<li><a href="psl-list" role="tab" > <i class="fa fa-list"></i>PSL</a> </li>
<li><a href="add-user" role="tab" > <i class="fa fa-plus"></i>Add Users</a> </li>
<li><a href="hire-resource" role="tab" > <i class="fa fa-database"></i>Bench Hiring <span class="label label-primary">Free</span></a> </li>
    <h4>Jobseeker Section (Jobsite)</h4>

<li><a href="candidate-search" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> CV Search <span class="label label-primary">Free</span></a>    </li>
    <li><a href="post-job" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Post Jobs <span class="label label-primary">Free</span></a>    </li>
    <li><a href="manage-responses" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Manage responses</a>    </li>
    <li><a href="manage-jobs" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Manage Jobs</a>    </li>
  </ul>
</div>


</div>

 
     <div class="col-sm-10"  id="paragraph">

<div class="account-navi-content" >

  <div class="tab-content">

<?php if(empty($profile)) echo 'No such Candidate found';else{ ?>
<?php if($highlight!='') { ?>
<input type="hidden" id="searchandmark" value="<?php echo $highlight; ?>">
<?php } ?>
   <div class="col-md-12 ui-sortable no-padding">

      										<div class="panel panel-default" id="job">

                                              <div class="panel-heading wht-bg ui-sortable-handle">

                                                <h3 class="panel-title"> Candidate Profile - Personal Info</h3>

                                              </div>

											  <div class=" col-sm-12 Description cv-desc">

												<div class="detailpage-header no-padding">

													<div id="job-title-wrap " class="cv-title-wrap">



            	<div class="pull-left col-lg-7 font-process-cv" > <?php echo $profile['name'];?><br><?php echo $profile['designation'];?>

                <div class="col-lg-12 no-padding">

<div class="col-lg-2 no-padding"><strong>Key Skills	:</strong></div>

<div class="col-lg-10 no-padding"><p><?php echo $profile['skills'];?></p></div>

<div class="clearfix"></div>

</div>

</div>

 <div class="col-lg-5 view-cv-photo">

              												<img src="image/person1.jpg" class="img-responsive" alt="">

               											 </div>

                										<div class="clearfix"></div>

            											</div>

													</div>



 <div id="job-summary-box1" class="cv-view-form">  





<div class="col-lg-6 no-padding">

<div class="col-lg-4 no-padding"><strong>Email :</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['email'];?> <span class="label label-primary">Verified</span></p></div>

<div class="clearfix"></div>



<div class="col-lg-4 no-padding"><strong>Contact:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['isd']; ?> <?php echo $profile['contact']; ?></p></div>

<div class="clearfix"></div>





<div class="col-lg-4 no-padding"><strong>Total Experience	:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['years'];?> years <?php if($profile['months']) echo 'and '.$profile['months'].' months';?></p></div>

<div class="clearfix"></div>

<div class="col-lg-4 no-padding"><strong>Notice Period	:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['noticeperiod'];?></p></div>

<div class="clearfix"></div>

<div class="col-lg-4 no-padding"><strong>Preferred Job Type:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['intype'];?></p></div>

<div class="clearfix"></div>

<div class="col-lg-4 no-padding"><strong>Location:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['city']; ?>, <?php echo $profile['country']; ?></p></div>

<div class="clearfix"></div>

<div class="col-lg-4 no-padding"><strong>Annual Salary:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['salarylakhs'];?> lakhs <?php if($profile['salarythousand']) echo 'and '.$profile['salarythousand'].' thousand'; ?></p></div>

<div class="clearfix"></div>

<div class="col-lg-4 no-padding"><strong>Register Date:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['registereddate']; ?></div>



<div class="clearfix"></div>


</div>

</div>

 </div>

 </div>

</div>

<div class="col-md-12 ui-sortable no-padding">

      								<div class="panel panel-default" id="job">

  										<div class="panel-heading wht-bg ui-sortable-handle">

    										<h3 class="panel-title"> Employment Info</h3>

 										 </div>

  									<div class=" col-sm-12 Description cv-desc">

										<div class="detailpage-header no-padding">

											<div id="job-title-wrap " class="cv-title-wrap">

            									

                								<div class="col-lg-5 view-cv-photo">

              										<img src="image/person1.jpg" class="img-responsive" alt="">

                								</div>

                								<div class="clearfix"></div>

            								</div>

										</div>

                                        <div id="job-summary-box1" class="cv-view-form"> 

<div class="col-lg-6 no-padding"> 



<div class="col-lg-12 no-padding"><strong><?php if($profile['currentcompany']){echo $profile['currentcompany'];  echo ' as '.$profile['designation'].'';}else{echo '';}?></strong></div>



<div class="col-lg-6 no-padding"><p> <?php if($profile['fromyear']){echo $profile['frommonths'] .' '. $profile['fromyear']; }else{echo '';}?>  <?php if($profile['toyear']){ echo '-'.$profile['tomonths'] .' '. $profile['toyear'];}else {echo '';}?></p></div>

<div class="clearfix"></div>


<?php foreach($profile1 as $prev){ ?>
<div class="col-lg-12 no-padding"><strong><?php if($prev['currentcompany']){echo $prev['currentcompany'];  echo ' as ' .$prev['designation'].'';}else{ echo ' ';}?></strong></div>



<div class="col-lg-6 no-padding"><p> <?php if($prev['fromyear']){echo $prev['frommonths'] .' '. $prev['fromyear']; }else{echo '';}?>  <?php if($prev['toyear']){ echo '-'.$prev['tomonths'] .' '. $prev['toyear'];}else {echo '';}?></p></div>
<?php } ?>
<div class="clearfix"></div>

      
<?php foreach($profile2 as $other){ ?>
<div class="col-lg-12 no-padding"><strong><?php if($other['currentcompany']){echo $other['currentcompany'];  echo ' as ' .$other['designation'].'';}else{echo '';}?></strong></div>



<div class="col-lg-6 no-padding"><p> <?php if($other['fromyear']){echo $other['frommonths'] .' '. $other['fromyear']; }else{echo '';}?>  <?php if($other['toyear']){ echo '-'.$other['tomonths'] .' '. $other['toyear'];}else {echo '';}?></p></div>
<?php } ?>
<div class="clearfix"></div>

      

      

   </div>                   

</div>

 </div>

                                    </div>

								</div>

							</div>

						

 <div class="col-md-12 ui-sortable no-padding">

                          <div class="panel panel-default" id="job">

                      			<div class="panel-heading wht-bg ui-sortable-handle">

                        				<h3 class="panel-title">Education Info</h3>

                     			 </div>

  							<div class=" col-sm-12 Description cv-desc">

								<div class="detailpage-header no-padding">

									<div id="job-title-wrap " class="cv-title-wrap">

            							

                						<div class="col-lg-5 view-cv-photo">

             								 <img src="image/person1.jpg" class="img-responsive" alt="">

                						</div>

                						<div class="clearfix"></div>

            						</div>

								</div>

	 							<div id="job-summary-box1" class="cv-view-form"> 

<div class="col-lg-6 no-padding">
    
<div class="col-lg-4 no-padding"><strong>Highest Qualification	:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['higherqualification'];?></p></div>

<div class="clearfix"></div>    

<div class="col-lg-4 no-padding"><strong>Education	:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['course'];?></p></div>

<div class="clearfix"></div>



<div class="col-lg-4 no-padding"><strong>Specialization	:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['specialization'];?></p></div>

<div class="clearfix"></div>



<div class="col-lg-4 no-padding"><strong>Institute/University	:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['institute'];?></p></div>

<div class="clearfix"></div>





<div class="col-lg-4 no-padding"><strong>Year of Passing	:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['yearofpassing']; ?></p></div>

<div class="clearfix"></div>



<div class="col-lg-4 no-padding"><strong>Course Type	:</strong></div>

<div class="col-lg-8 no-padding"><p><?php echo $profile['coursetype'];?></p></div>

<div class="clearfix"></div>







  </div>

                             </div>

                           </div>

                        </div>

					</div>

				





 <div class="search-view"><p class="cta1-box "><a class="btn21" href="../resume/<?php echo $profile['id'].'/'.$profile['resume'];?>" target="_blank">Download CV</a></p> </div>
 
 <div class="search-view"><p class="cta1-box ">
<?php  if(file_exists ('../resume/'.$profile['id'].'/'.$profile['resume1']) && $profile['resume1']) {?>
      <td><a class="btn21" href="<?php echo '../resume/'.$profile['id'].'/'.$profile['resume1']; ?>" target="_blank"  title="view resume1">Download CV2</a></td>
      <?php } else{ ?>
      <td></td>
      <?php } ?></a></div>
      
       <div class="search-view"><p class="cta1-box ">
       <?php  if(file_exists ('../resume/'.$profile['id'].'/'.$profile['video']) && $profile['video']) {?>
      <td><a class="btn21" href="<?php echo '../resume/'.$profile['id'].'/'.$profile['video']; ?>" target="_blank"  title="view video">Download Video</a></td>
      <?php } else{ ?>
      <td></td>
      <?php } ?></a></div>


 </div> 



 <div> <pre><?php if($profile['cv']) echo $profile['cv'];?></pre></div>
 
 <div class="search-view"><p class="cta1-box "><a class="btn21" href="../resume/<?php echo $profile['id'].'/'.$profile['resume'];?>" target="_blank">Download CV</a></p> </div>
 
 <div class="search-view"><p class="cta1-box ">
<?php  if(file_exists ('../resume/'.$profile['id'].'/'.$profile['resume1']) && $profile['resume1']) {?>
      <td><a class="btn21" href="<?php echo '../resume/'.$profile['id'].'/'.$profile['resume1']; ?>" target="_blank"  title="view resume1">Download CV2</a></td>
      <?php } else{ ?>
      <td></td>
      <?php } ?></a></div>
      
       <div class="search-view"><p class="cta1-box ">
       <?php  if(file_exists ('../resume/'.$profile['id'].'/'.$profile['video']) && $profile['video']) {?>
      <td><a class="btn21" href="<?php echo '../resume/'.$profile['id'].'/'.$profile['video']; ?>" target="_blank"  title="view video">Download Video</a></td>
      <?php } else{ ?>
      <td></td>
      <?php } ?></a></div>

<?php }?>



    

      

      

     </div>

     <div class="clearfix"></div>

     </div>

     </div>

     

     

     

<div class="clearfix"></div>

</div>

</div>





<script>

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

				

            }

        });

    });

	

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

</script>

<script>
/**$(document).ready(function(){

if($('#searchandmark').val().length !== 0){

       $('#paragraph').each(function(){
	   
       $(this).html($(this).html().replace($('#searchandmark').val(),"<span class = 'highlight'>"+$('#searchandmark').val()+"</span>"));
    });
	
  }});**/
$(document).ready(function(){
var src_str = $("#paragraph").html();
var term = $("#searchandmark").val();
term = term.replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*");
var pattern = new RegExp("("+term+")", "gi");

src_str = src_str.replace(pattern, "<mark style='background-color:yellow;'>$1</mark>");
src_str = src_str.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/,"$1</mark>$2<mark>$4");

$("#paragraph").html(src_str);

});
</script>

 </body>

</html>

