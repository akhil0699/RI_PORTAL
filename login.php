<?php
session_start();
$errorMsg='';
function test_input($data) {
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
return $data;
}
if(isset($_SESSION['notification'])){
$errorMsg=$_SESSION['notification'];
unset($_SESSION['notification']);
}

if (isset($_POST['login'])) {
$email=test_input($_POST['email']);
$password=test_input($_POST['password']);
require_once '../config.php';
$sql = mysqli_query($link,"SELECT * FROM members WHERE email='$email' AND password='$password'"); 
$login_check = mysqli_num_rows($sql);
if($login_check > 0){ 
while($row = mysqli_fetch_array($sql)){ 
$memberarray=$row;
}
$memberid = $memberarray['memberid'];	
$_SESSION['country']=$memberarray["country"];
$_SESSION['email'] = $memberarray["email"];	
$_SESSION['name'] = $memberarray["firstname"];	
$_SESSION['emailactivated'] = $memberarray["emailactivated"];	

if($memberarray['active'] && $memberarray['emailactivated']=='1')
{		
$_SESSION['mid'] = $memberid;	
$_SESSION['email'] = $memberarray["email"];	
$_SESSION['name'] = $memberarray["firstname"];
$_SESSION['iam']=$memberarray["iam"];	
$_SESSION['mobile']=$memberarray["mobile"];	
$_SESSION['country']=$memberarray["country"];
$_SESSION['admin']=$memberarray["admin"];
$_SESSION['cid']=$memberarray["companyid"];
$_SESSION['adminrights']=$memberarray["adminrights"];

mysqli_query($link,"UPDATE members SET lastlogin=now() WHERE memberid='$memberid'");
if($memberarray['iam']=='Employer'){
header("location: ../employer/dashboard"); 
exit();
}
elseif($memberarray['iam']=='Recruiter'){
header("location: ../recruiter/dashboard"); 
exit();
}	
}else if ($memberarray['emailactivated']=='0'){
    $errorMsg='<p style="
            font-size: 14px;
            color: #a02121;
        ">You have still not verified your email id yet. Click here to resend the verification link -> <b><a href="https://www.recruitinghub.com/resendverification">CLICK HERE</a></b></p>';
}
else {
$firstname= $memberarray["firstname"];
//send_mail($email,$memberid,$password);
$errorMsg='<p style="
            font-size: 14px;
            color: #a02121;
        ">Your employer account has been restricted! Use live chat to talk to us. <br><br>
        If your recruiter account has been restricted! Follow this link -><b><a href="https://www.recruitinghub.com/recruiterrestrictedaccount" target="_blank">CLICK HERE</a></b></p>';
}
}
else

{
$errorMsg='<p style="
            font-size: 14px;
            color: #a02121;
        ">Email id or Password is incorrect. Please try again or click forgot password link to reset password.</p>';
}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0" />
<title>Employer Login Hub</title>

<link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />

<!-- GOOGLE WEB FONT -->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">

<!-- BASE CSS -->
<link href="../lia_css/bootstrap.min.css" rel="stylesheet">
<link href="../lia_css/lia_style.css" rel="stylesheet">

<script src="../lia_js/modernizr.js"></script>

</head>	

<body style="background: #f9f9f9;">

<div id="preloader">
<div data-loader="circle-side"></div>
</div><!-- /Preload -->

<div id="loader_form">
<div data-loader="circle-side-2"></div>
</div><!-- /loader_form -->


<div class="container full-height">
<div class="row sign_row_height">
<div class="col-md-5">

<div class="container">
<div class="row">
<div class="col-md-12">
<div class="tab_sec">
<div class="tab-content" id="nav-tabContent">
<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
<div class="row">
<div class="col-md-6">
<div class="box1">
<div class="box_pp">
<div class="sign_box_num">
<p>1</p>
</div>
<p class="text-center"><img src="../lia_img/money-off.svg" width="40px" class="box_img"></p>
<p class="box_txt">Success Fee or Fixed Fee Plans</p>
</div>
</div>
</div>
<div class="col-md-6">
<div class="box1">
<div class="box_pp">
<div class="sign_box_num">
<p>2</p>
</div>

<p class="text-center"><img src="../lia_img/candidate.svg" width="40px" class="box_img"></p>
<p class="box_txt">Receive Candidates 2X faster</p>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-6">
<div class="box1">
<div class="box_pp">
<div class="sign_box_num">
<p>3</p>
</div>
<p class="text-center"><img src="../lia_img/tools.svg" width="40px" class="box_img"></p>
<p class="box_txt">Free ATS & PSL Tools</p>
</div>
</div>
</div>
<div class="col-md-6">
<div class="box1">
<div class="box_pp">
<div class="sign_box_num">
<p>4</p>
</div>
<p class="text-center"><img src="../lia_img/online_recruitment.svg" width="40px" class="box_img"></p>
<p class="box_txt">Single Vendor Management System (VMS)</p>
</div>
</div>
</div>
</div>

<div class="row">
<div class="col-md-6">
<div class="box1">
<div class="box_pp">
<div class="sign_box_num">
<p>5</p>
</div>
<p class="text-center"><img src="../lia_img/payment.svg" width="50px" class="box_img"></p>
<p class="box_txt">Choose your Recruitment plan</p>
</div>
</div>
</div>
<div class="col-md-6">
<div class="box1">
<div class="box_pp">
<div class="sign_box_num">
<p>6</p>
</div>
<p class="text-center"><img src="../lia_img/candidate.svg" width="50px" class="box_img"></p>
<p class="box_txt">Free Bench Hiring</p>
</div>
</div>
</div>
</div>

</div>

</div>
</div>
</div>
</div>
	
</div>

</div>
<!-- /content-left -->
<div class="col-md-7">
<div class="container">
<div class="row">
<div class="col-md-8">
<p class="text-center"><a href="/"><img src="../lia_img/output-onlinepngtools (1).png" alt=""></a></p>
</div>
</div>
<div class="row">
<div class="col-md-10">
<div class="sign_box">
<div class="row">
<div class="col-12 col-md-3">
   <h3>Sign in</h3> 
</div>
<div class="col-12 col-md-9">
<div class="signup_sec">
<p class="text-right">No Employer Account? <a href="https://www.recruitinghub.com/signup">Sign up</a> </p>
</div>

<div class="col-12 col-md-9">
<div class="signup_sec">
<p class="text-right">Vendor? <a href="https://www.recruitinghub.com/recruiter/login">Login</a> </p>
</div>

</div>
</div></div>

<!--<p>Find Candidates 2X faster</p>-->
<h5>As an Employer</h5>

<form action="login.php" method="post">
<div class="form-group">
<?php if($errorMsg) echo $errorMsg;?>
</div>

<div class="form-group">
<label>E-mail</label>
<input name="email" class="form-control" type="email" required placeholder="Enter your Company Email id"/>
</div>
<div class="form-group">
<label>Password</label>
<input name="password" class="form-control" type="password" required placeholder="Enter your Password"/>
</div>

<div class="form-group">
<a href="https://www.recruitinghub.com/forgetpassword" class="forgot_btn">Forgot password?</a>
</div>

<input value="Login" name="login" class="forward forward-login submit_btn" type="submit"  />
</form>

</div>
</div>
<div class="col-md-2"></div>
</div>
</div>
</div>

<!-- /content-right-->
</div>
<!-- /row-->
</div>
<!-- /container-fluid -->


<!-- COMMON SCRIPTS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="../js/jquery.chained.min.js"></script>
<script src="../lia_js/common_scripts.js"></script>
<!-- <script src="lia_js/jquery-3.5.1.min.js"></script> -->
<script src="../lia_js/functions.js"></script>
<!-- <script src="lia_js/velocity.min.js"></script> -->
<!-- Wizard script -->
<!-- <script src="lia_js/survey_func.js"></script> -->

<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || 
{widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};
var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;
s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>
</body>
</html>