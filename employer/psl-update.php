<?php

session_start();

require_once '../config.php';


if(isset($_POST['add']))

{

$rid=$_POST['rid'];

$rcid=$_POST['rcid'];

$memberid=$_POST['memberid'];

$ecid=$_POST['ecid'];

$psl=1;



 $checkquery=mysqli_query("select * from psl where eid=$memberid and ecid=$ecid and rid=$rid and rcid=$rcid");
//var_dump($checkquery);
 $rerate = mysqli_num_rows($checkquery);
//var_dump($rerate);

if($rerate >0 || $rerate==NULL) {
  $query = mysqli_query($link,"insert into psl(eid,ecid,rid,rcid,psl)values('$memberid','$ecid','$rid','$rcid','$psl') ");
}else
$query = mysqli_query($link,"update psl set psl=1 where rid='$rid' and eid='$memberid' and ecid='$ecid' and rcid='$rcid'");

}


if(isset($_POST['remove']))

{

$rid=$_POST['rid'];        

$rcid=$_POST['rcid'];

$memberid=$_POST['memberid'];

$ecid=$_POST['ecid'];

$psl=0;



/**$query=mysqli_query("select ratedby,ratedto from recruiterrating where ratedby <> ratedto and ratedto='$id'");

$rerate = mysqli_result($query, 0);

if($rerate)

 $query=mysqli_query("update recruiterrating set feedback='$feedback',rating='$rating' where ratedby='$companyid'and ratedto='$id'");

 else***/

$query = mysqli_query($link,"update psl set psl=0 where rid='$rid' and eid='$memberid' and ecid='$ecid' and rcid='$rcid'");



}

header("location: rating"); 

		exit();

 

 

 

  ?>