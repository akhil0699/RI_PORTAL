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
$cid=$_SESSION['cid'];
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
if(isset($_POST['rid']))
 $rid=$_POST['rid'];
 $cname=$_POST['cname']; 
 $rcid=$_POST['rcid'];
 //$ecid=$_POST['ecid'];
$show = $_POST['showAdd'];

 $content='';

 $context  = "add";

 if($show == "false"){
    $context = 'remove'; 

 }

?>
 <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Do you want to <?php echo  $context." ". $cname ;?> to your PSL?</h4>
          </div>
           <div class="modal-body" > 
           <div> 
                   <form action="psl-update" method="post" id="feedback">
     			   <input type="hidden" name="rid" value="<?php echo $rid ?> " />
                   <input type="hidden" name="ecid" value="<?php echo $cid ?> " />
                    <input type="hidden" name="memberid" value="<?php echo $mid ?> " />
                   <input type="hidden" name="rcid" value="<?php echo $rcid ?> " />
                <input type="hidden" name="cname" value="<?php echo $cname ?> " />
                
                <?php if($show == "true") { ?>
                   <div class="form-group">
                   <button type="submit" class="btn btn-default-new-form" name="add">ADD</button>
                    </div>
                    <?php } else { ?>
                    <div class="form-group">
                   <button type="submit" class="btn btn-default-new-form" name="remove">REMOVE</button>
                    </div>
                    <?php } ?>
    				</form>
                    </div>
                    
			 </div>
          