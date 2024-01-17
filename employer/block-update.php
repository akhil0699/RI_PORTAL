<?php

session_start();

require_once '../config.php';

if(isset($_POST['add']))

{

$rid=$_POST['rid'];

$rcid=$_POST['rcid'];

$memberid=$_POST['memberid'];

$ecid=$_POST['ecid'];

$block=1;



/** $query=mysqli_query("select ratedby,ratedto from recruiterrating where ratedby <> ratedto and ratedto='$id'");

$rerate = mysqli_result($query, 0);

if($rerate)

 $query=mysqli_query("update recruiterrating set feedback='$feedback',rating='$rating' where ratedby='$companyid'and ratedto='$id'");

 else ***/

$query = mysqli_query($link,"insert into block(eid,ecid,rid,rcid,block)values('$memberid','$ecid','$rid','$rcid','$block') ");



}

if(isset($_POST['remove']))

{

$rid=$_POST['rid'];        

$rcid=$_POST['rcid'];

$memberid=$_POST['memberid'];

$ecid=$_POST['ecid'];

$block=0;



/**$query=mysqli_query("select ratedby,ratedto from recruiterrating where ratedby <> ratedto and ratedto='$id'");

$rerate = mysqli_result($query, 0);

if($rerate)

 $query=mysqli_query("update recruiterrating set feedback='$feedback',rating='$rating' where ratedby='$companyid'and ratedto='$id'");

 else***/

$query = mysqli_query($link,"update block set block=0 where rid='$rid' and eid='$memberid' and ecid='$ecid' and rcid='$rcid'");



}

header("location: rating"); 

		exit();

 

 

 

  ?>