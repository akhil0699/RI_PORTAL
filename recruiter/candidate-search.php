<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

else{
$notification='Please login to access the page';
$_SESSION['notification']=$notification;
header('location: login'); 
exit;
}
require_once '../config.php';

$paramnum=1;$name='';

$keyword=$city=$skills=$noticeperiod=$higherqualification=$experience=$salaryfrom=$location=$salaryto=$countri='';

 

$search=array();

 

 

if(isset($_GET['search'])){

 

if(isset($_GET['getcountry']) && ($_GET['getcountry']!='')){

 

 

                        $coun=$_GET['getcountry'];

 

                        if($coun=='india')

 

                                    $countri="and a.country='India'";

        elseif($coun=='us')

                                    $countri="and a.country ='United States Of America (US)'";

                        elseif($coun=='uk')

                                    $countri="and a.country='United Kingdom (UK)'";

                        elseif($coun=='france')

                                    $countri="and a.country='France'";

                        elseif($coun=='middleeast')

                                    $countri="and a.country='United Arab Emirates (UAE)'";

                        elseif($coun=='singapore')

                                    $countri="and a.country='Singapore'";

                        elseif($coun=='malaysia')

                                    $countri="and a.country='Malaysia'";

                        elseif($coun=='australia')

                                    $countri="and a.country='Australia'";

                        elseif($coun=='canada')

                                    $countri="and a.country='Canada'";

                        elseif($coun=='japan')

                                    $countri="and a.country='Japan'";

                        elseif($coun=='africa')

                                    $countri="and a.country='Nigeria'";

                        elseif($coun=='germany')

                                    $countri="and a.country='Germany'";

                        elseif($coun=='poland')

                                    $countri="and a.country='Poland'";

                        elseif($coun=='italy')

                                    $countri="and a.country='Italy'";

                        elseif($coun=='netherlands')

                                    $countri="and a.country='Netherlands'";

                        elseif($coun=='belgium')

                                    $countri="and a.country='Belgium'";

                        elseif($coun=='switzerland')

                                    $countri="and a.country='Switzerland'";

                        elseif($coun=='hungary')

                                    $countri="and a.country='Hungary'";

                        elseif($coun=='austria')

                                    $countri="and a.country='Austria'";

                        elseif($coun=='romania')

                                    $countri="and a.country='Romania'";

                        elseif($coun=='spain')

                                    $countri="and a.country='Spain'";

                        elseif($coun=='portugal')

                                    $countri="and a.country='Portugal'";

                        elseif($coun=='norway')

                                    $countri="and a.country='Norway'";

                        elseif($coun=='sweden')

                                    $countri="and a.country='Sweden'";

                        elseif($coun=='denmark')

                                    $countri="and a.country='Denmark'";

                        elseif($coun=='newzealand')

                                    $countri="and a.country='New Zealand'";

                        elseif($coun=='thailand')

                                    $countri="and a.country='Thailand'";

                        elseif($coun=='china')

                                    $countri="and a.country='China'";

                        elseif($coun=='finland')

                                    $countri="and a.country='Finland'";

                        elseif($coun=='luxembourg')

                                    $countri="and a.country='Luxembourg'";

                        elseif($coun=='russia')

                                    $countri="and a.country='Russia'";

                        elseif($coun=='greece')

                                    $countri="and a.country='Greece'";

                        elseif($coun=='brazil')

                                    $countri="and a.country='Brazil'";

                        elseif($coun=='israel')

                                    $countri="and a.country='Israel'";

                        elseif($coun=='qatar')

                                    $countri="and a.country='Qatar'";

                        elseif($coun=='saudiarabia')

                                    $countri="and a.country='Saudi Arabia'";

                        elseif($coun=='mexico')

                                    $countri="and a.country='Mexico'";

                        elseif($coun=='turkey')

                                    $countri="and a.country='Turkey'";

                        elseif($coun=='egypt')

                                    $countri="and a.country='Egypt'";

                        elseif($coun=='czechrepublic')

                                    $countri="and a.country='Czech Republic'";

                        elseif($coun=='southafrica')

                                    $countri="and a.country='South Africa'";

                        elseif($coun=='morocco')

                                    $countri="and a.country='Morocco'";

                        elseif($coun=='argentina')

                                    $countri="and a.country='Argentina'";

                        elseif($coun=='vietnam')

                                    $countri="and a.country='Vietnam'";

                        elseif($coun=='croatia')

                                    $countri="and a.country='Croatia'";

                        elseif($coun=='philippines')

                                    $countri="and a.country='Philippines'";

                        elseif($coun=='chile')

                                    $countri="and a.country='Chile'";

                        elseif($coun=='peru')

                                    $countri="and a.country='Peru'";

                        elseif($coun=='srilanka')

                                    $countri="and a.country='Sri Lanka'";

                        elseif($coun=='slovakia')

                                    $countri="and a.country='Slovakia'";

                        elseif($coun=='kenya')

                                    $countri="and a.country='Kenya'";

                        elseif($coun=='estonia')

                                    $countri="and a.country='Estonia'";

                        elseif($coun=='bulgaria')

                                    $countri="and a.country='Bulgaria'";

                        elseif($coun=='lithuania')

                                    $countri="and a.country='Lithuania'";

                        elseif($coun=='kazakhstan')

                                    $countri="and a.country='Kazakhstan'";

                        elseif($coun=='ukraine')

                                    $countri="and a.country='Ukraine'";

                        elseif($coun=='belarus')

                                    $countri="and a.country='Belarus'";

                        elseif($coun=='oman')

                                    $countri="and a.country='Oman'";

                        elseif($coun=='ireland')

                                    $countri="and a.country='Ireland'";

                                    else

                                    $countri='';                

           

            }                      

 

if(isset($_GET['keyword']) && ($_GET['keyword']!='')){

$_SESSION['mark']=$_GET['keyword'];

 

                                    $paramnum++;

 

                                    $keyword=$_GET['keyword'];

 

                                    if($paramnum> 1)

 

                                                $keyword="and (a.name like'%$keyword%' or a.email like'%$keyword%' or a.cv like '%$keyword%')";

                                                //$keyword="and (a.cv like '%$keyword%')";

 

                                    else

 

                                                $keyword="(a.name like'%$keyword%' or a.email like'%$keyword%' or a.cv like '%$keyword%')";

 

                        }

 

if(isset($_GET['city']) && ($_GET['city']!='')){

 

                        $paramnum++;

 

                        $city=$_GET['city'];

 

                        $loc=array();

 

                         $loc=explode(", ",$city);

 

                        if($paramnum> 1)                              

 

                                     $location="and city in ('".implode("',' ",$loc)."')";

 

                        else

 

                                    $location="city in ('".implode("',' ",$loc)."')";

 

                                    }

 

if(isset($_GET['skills']) && ($_GET['skills']!='')){

 

                        $paramnum++;

 

                        $skills=$_GET['skills'];

 

                        if($paramnum> 1)

 

                                    $skills="and b.skills like  '%$skills%'";

 

                        else

 

                                    $skills="b.skills like '%$skills%'";

 

                                    }

 

if(isset($_GET['noticeperiod']) && ($_GET['noticeperiod']!='')){

 

                        $paramnum++;

 

                        $noticeperiod=$_GET['noticeperiod'];

 

                        if($paramnum> 1)

 

                                    $noticeperiod="and b.noticeperiod like  '%$noticeperiod%'";

 

                        else

 

                                    $noticeperiod="b.noticeperiod like '%$noticeperiod%'";

 

                                    }

 

                                   

 

if(isset($_GET['higherqualification']) &&($_GET['higherqualification']!='')){

 

                        $paramnum++;

 

                        $higherqualification=$_GET['higherqualification'];

 

                        if($paramnum> 1)

 

                                    $higherqualification="and c.higherqualification  like  '%$higherqualification%'";

 

                        else

 

                                    $higherqualification="c.higherqualification like '%$higherqualification%'";

 

                        }

 

if(isset($_GET['experiencefrom']) && ($_GET['experiencefrom']!='') && isset($_GET['experienceto']) && ($_GET['experienceto']!='')){

 

 

 

$experiencefrom=$_GET['experiencefrom'];

 

$experienceto=$_GET['experienceto'];

 

                        $paramnum++;

 

                        if($paramnum>1)

 

                                    $experience="and a.years between $experiencefrom and  $experienceto ";

 

                        else

 

                                    $experience="a.years between  $experiencefrom and  $experienceto ";

 

                        }

 

elseif(isset($_GET['experiencefrom']) && ($_GET['experiencefrom']=='') && isset($_GET['experienceto']) && ($_GET['experienceto']!='')){

 

 

 

$experiencefrom=$_GET['experiencefrom'];

 

$experienceto=$_GET['experienceto'];

 

 

 

$paramnum++;

 

                        if($paramnum>1)

 

                                    $experience="and a.years <= $experienceto";

 

                        else

 

                                    $experience="a.years between < =$experienceto ";

 

                        }

 

 

 

elseif(isset($_GET['experiencefrom']) && ($_GET['experiencefrom']!='') && isset($_GET['experienceto']) && ($_GET['experienceto']=='')){

 

 

 

$experiencefrom=$_GET['experiencefrom'];

 

$experienceto=$_GET['experienceto'];

 

 

 

$paramnum++;

 

                        if($paramnum>1)

 

                                    $experience="and a.years >= $experiencefrom";

 

                        else

 

                                    $experience="a.years between >= $experiencefrom ";

 

                        }

 

                       

 

/**if(isset($_GET['salarylakhsfrom']) && ($_GET['salarylakhsfrom']!='') && isset($_GET['salarythousandfrom']) && ($_GET['salarythousandfrom']!='')){

 

$salarylakhs=$_GET['salarylakhsfrom'];

 

$salarythousand=$_GET['salarythousandfrom'];

 

                        $paramnum++;

 

                        if($paramnum>1)

 

                                    $salaryfrom="and b.salarylakhs  >= $salarylakhs and b.salarythousand >= $salarythousand";

 

                        else

 

                                    $salaryfrom=" b.salarylakhs  >= $salarylakhs and b.salarythousand >= $salarythousand ";

 

                        }

 

                       

 

elseif(isset($_GET['salarylakhsfrom']) && ($_GET['salarylakhsfrom']!='') && isset($_GET['salarythousandfrom']) && ($_GET['salarythousandfrom']=='')){

 

$salarylakhs=$_GET['salarylakhsfrom'];

 

$salarythousand=$_GET['salarythousandfrom'];

 

                        $paramnum++;

 

                        if($paramnum>1)

 

                                    $salaryfrom="and b.salarylakhs  >= $salarylakhs ";

 

                        else

 

                                    $salaryfrom=" b.salarylakhs  >= $salarylakhs  ";

 

                                   

 

                        }

 

elseif(isset($_GET['salarylakhsfrom']) && ($_GET['salarylakhsfrom']=='') && isset($_GET['salarythousandfrom']) && ($_GET['salarythousandfrom']!='')){

 

 

 

$salarylakhs=$_GET['salarylakhsfrom'];

 

$salarythousand=$_GET['salarythousandfrom'];

 

                        $paramnum++;

 

                        if($paramnum>1)

 

                                    $salaryfrom="and b.salarythousand >= $salarythousand";

 

                        else

 

                                    $salaryfrom=" b.salarythousand >= $salarythousand ";

 

                        }          

 

if(isset($_GET['salarylakhsto']) && ($_GET['salarylakhsto']!='') && isset($_GET['salarythousandto']) && ($_GET['salarythousandto']!='')){

 

 

 

$salarylakhs=$_GET['salarylakhsto'];

 

$salarythousand=$_GET['salarythousandto'];

 

                        $paramnum++;

 

                        if($paramnum>1)

 

                                    $salaryto="and b.salarylakhs  <= $salarylakhs and b.salarythousand <= $salarythousand";

 

                        else

 

                                    $salaryto=" b.salarylakhs  <= $salarylakhs and b.salarythousand <= $salarythousand ";

 

                        }          

 

if(isset($_GET['salarylakhsto']) && ($_GET['salarylakhsto']=='') && isset($_GET['salarythousandto']) && ($_GET['salarythousandto']!='')){

 

 

 

$salarylakhs=$_GET['salarylakhsto'];

 

$salarythousand=$_GET['salarythousandto'];

 

                        $paramnum++;

 

                        if($paramnum>1)

 

                                    $salaryto="and  b.salarythousand <= $salarythousand";

 

                        else

 

                                    $salaryto="  b.salarythousand <= $salarythousand ";

 

                        }

 

if(isset($_GET['salarylakhsto']) && ($_GET['salarylakhsto']!='') && isset($_GET['salarythousandto']) && ($_GET['salarythousandto']=='')){

 

 

 

$salarylakhs=$_GET['salarylakhsto'];

 

$salarythousand=$_GET['salarythousandto'];

 

                        $paramnum++;

 

                        if($paramnum>1)

 

                                    $salaryto="and b.salarylakhs  <= $salarylakhs";

 

                        else

 

                                    $salaryto=" b.salarylakhs  <= $salarylakhs";

 

                        }**/    

 

                                   

                                    }                                                          

 

if(isset($_GET['country']) && ($_GET['country']!='')){

 

 

                        $coun=$_GET['country'];

 

                        if($coun=='india')

 

                                    $countri="and a.country='India'";

        elseif($coun=='us')

                                    $countri="and a.country ='United States Of America (US)'";

                        elseif($coun=='uk')

                                    $countri="and a.country='United Kingdom (UK)'";

                        elseif($coun=='france')

                                    $countri="and a.country='France'";

                        elseif($coun=='middleeast')

                                    $countri="and a.country='United Arab Emirates (UAE)'";

                        elseif($coun=='singapore')

                                    $countri="and a.country='Singapore'";

                        elseif($coun=='malaysia')

                                    $countri="and a.country='Malaysia'";

                        elseif($coun=='australia')

                                    $countri="and a.country='Australia'";

                        elseif($coun=='canada')

                                    $countri="and a.country='Canada'";

                        elseif($coun=='japan')

                                    $countri="and a.country='Japan'";

                        elseif($coun=='africa')

                                    $countri="and a.country='Nigeria'";

                        elseif($coun=='germany')

                                    $countri="and a.country='Germany'";

                        elseif($coun=='poland')

                                    $countri="and a.country='Poland'";

                        elseif($coun=='italy')

                                    $countri="and a.country='Italy'";

                        elseif($coun=='netherlands')

                                    $countri="and a.country='Netherlands'";

                        elseif($coun=='belgium')

                                    $countri="and a.country='Belgium'";

                        elseif($coun=='switzerland')

                                    $countri="and a.country='Switzerland'";

                        elseif($coun=='hungary')

                                    $countri="and a.country='Hungary'";

                        elseif($coun=='austria')

                                    $countri="and a.country='Austria'";

                        elseif($coun=='romania')

                                    $countri="and a.country='Romania'";

                        elseif($coun=='spain')

                                    $countri="and a.country='Spain'";

                        elseif($coun=='portugal')

                                    $countri="and a.country='Portugal'";

                        elseif($coun=='norway')

                                    $countri="and a.country='Norway'";

                        elseif($coun=='sweden')

                                    $countri="and a.country='Sweden'";

                        elseif($coun=='denmark')

                                    $countri="and a.country='Denmark'";

                        elseif($coun=='newzealand')

                                    $countri="and a.country='New Zealand'";

                        elseif($coun=='thailand')

                                    $countri="and a.country='Thailand'";

                        elseif($coun=='china')

                                    $countri="and a.country='China'";

                        elseif($coun=='finland')

                                    $countri="and a.country='Finland'";

                        elseif($coun=='luxembourg')

                                    $countri="and a.country='Luxembourg'";

                        elseif($coun=='russia')

                                    $countri="and a.country='Russia'";

                        elseif($coun=='greece')

                                    $countri="and a.country='Greece'";

                        elseif($coun=='brazil')

                                    $countri="and a.country='Brazil'";

                        elseif($coun=='israel')

                                    $countri="and a.country='Israel'";

                        elseif($coun=='qatar')

                                    $countri="and a.country='Qatar'";

                        elseif($coun=='saudiarabia')

                                    $countri="and a.country='Saudi Arabia'";

                        elseif($coun=='mexico')

                                    $countri="and a.country='Mexico'";

                        elseif($coun=='turkey')

                                    $countri="and a.country='Turkey'";

                        elseif($coun=='egypt')

                                    $countri="and a.country='Egypt'";

                        elseif($coun=='czechrepublic')

                                    $countri="and a.country='Czech Republic'";

                        elseif($coun=='southafrica')

                                    $countri="and a.country='South Africa'";

                        elseif($coun=='morocco')

                                    $countri="and a.country='Morocco'";

                        elseif($coun=='argentina')

                                    $countri="and a.country='Argentina'";

                        elseif($coun=='vietnam')

                                    $countri="and a.country='Vietnam'";

                        elseif($coun=='croatia')

                                    $countri="and a.country='Croatia'";

                        elseif($coun=='philippines')

                                    $countri="and a.country='Philippines'";

                        elseif($coun=='chile')

                                    $countri="and a.country='Chile'";

                        elseif($coun=='peru')

                                    $countri="and a.country='Peru'";

                        elseif($coun=='srilanka')

                                    $countri="and a.country='Sri Lanka'";

                        elseif($coun=='slovakia')

                                    $countri="and a.country='Slovakia'";

                        elseif($coun=='kenya')

                                    $countri="and a.country='Kenya'";

                        elseif($coun=='estonia')

                                    $countri="and a.country='Estonia'";

                        elseif($coun=='bulgaria')

                                    $countri="and a.country='Bulgaria'";

                        elseif($coun=='lithuania')

                                    $countri="and a.country='Lithuania'";

                        elseif($coun=='kazakhstan')

                                    $countri="and a.country='Kazakhstan'";

                        elseif($coun=='ukraine')

                                    $countri="and a.country='Ukraine'";

                        elseif($coun=='belarus')

                                    $countri="and a.country='Belarus'";

                        elseif($coun=='oman')

                                    $countri="and a.country='Oman'";

                        elseif($coun=='ireland')

                                    $countri="and a.country='Ireland'";

                                    else

                                    $countri='';                

           

            }                      

                                   

 

//$sql="select a.id,a.name,a.email,a.country,a.years,a.months,a.registereddate,a.lastlogindate,a.lastmodified,b.city,b.salarylakhs,b.salarythousand,b.noticeperiod,b.currentcompany,b.designation,b.skills  from canditate a, employment b ,education c where a.id=b.canditateid and a.id=c.canditateid and b.type='current' and a.access=1 a.emailactivated=1 $keyword $location $skills $noticeperiod  $higherqualification  $experience $salaryfrom  $salaryto $countri order by a.id desc ";

 

$sql="select a.id,a.name,a.email,a.country,a.years,a.months,a.registereddate,a.lastlogindate,a.lastmodified,b.city,b.salarylakhs,b.salarythousand,b.noticeperiod,b.currentcompany,b.designation,b.skills  from canditate a, employment b where a.id=b.canditateid  and b.type='current' and a.access=1  $keyword $location $skills $noticeperiod  $higherqualification  $experience  $countri order by a.lastmodified desc ";

 

 

/*while($row=mysql_fetch_array($sql)){

 

 

 

$search[]=$row;

 

 

 

}           */

 

$param="";

 

$params=$_SERVER['QUERY_STRING'];

 

if (strpos($params,'page') !== false) {

 

    $key="page";

 

            parse_str($params,$ar);

 

            $param=http_build_query(array_diff_key($ar,array($key=>"")));

 

}

 

else

 

$param=$params;

 

include('ps_pagination.php');

 

$pager = new PS_Pagination($link, $sql, 10, 10, $param);

 

$jobres = $pager->paginate();

 

 

 

if(@mysqli_num_rows($jobres)){

 

    while($row = mysqli_fetch_array($jobres)){

 

                                    $search[]=$row;        

 

                                                }

 

$pages =  $pager->renderFullNav();

 

$total= $pager->total_rows;

$nb_new_pm = mysqli_fetch_array(mysqli_query($link,'select count(*) as new_msg_count from personalmsg where ((fromuser="'.$mid.'" and fromread="no") or (touser="'.$mid.'" and toread="no")) and conversationid="1"'));
$new = $nb_new_pm['new_msg_count'];

 

//$rcount = count($jobarray);                                               

 

}

 

else

 

$content="So far there are no jobs awaiting feedback.";

 

?><!doctype html>

 

<html>

 

<head>

 

     <meta charset="utf-8">

 

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

 

    <meta name="viewport" content="width=device-width, initial-scale=1">

 

     <title>CV Search | Recruiters Hub</title>

 

    <meta name="application-name" content="Recruiting-hub" />

     <link rel="icon" type="image/favicon.png" href="https://www.recruitinghub.com/images/favicon.png" />

 

            <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">

 

    <link href="css/bootstrap.min.css" rel="stylesheet">

 

    <link href="css/style.min.css" rel="stylesheet">

 

    <link rel="stylesheet" type="text/css" href="css/fonts/css/font-awesome.min.css" media="screen, projection">

 

    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.4.custom.min.css"/>

 

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

 

    <script src="js/bootstrap.min.js"></script>

 

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

 

            <script src="js/jquery.autocomplete.multiselect.js"></script>

 

            <script>

 

        $(function() {

 

            function split( val ) {

 

                return val.split( /,\s*/ );

 

            }

 

            function extractLast( term ) {

 

                return split( term ).pop();

 

            }

 

           

 

            $("#citylocation" ).bind( "keydown", function( event ) {

 

                if ( event.keyCode === $.ui.keyCode.TAB &&

 

                    $( this ).autocomplete( "instance" ).menu.active ) {

 

                    event.preventDefault();

 

                }

 

            })

 

                                    .autocomplete({

 

                minLength: 2,

 

                source: function( request, response ) {

 

                    // delegate back to autocomplete, but extract the last term

 

                    $.getJSON("citysearch.php", { term : extractLast( request.term )},response);

 

                },

 

                focus: function() {

 

                    // prevent value inserted on focus

 

                    return false;

 

                },

 

                select: function( event, ui ) {

 

                    var terms = split( this.value );

 

                    // remove the current input

 

                    terms.pop();

 

                    // add the selected item

 

                    terms.push( ui.item.value );

 

                    // add placeholder to get the comma-and-space at the end

 

                    terms.push( "" );

 

                    this.value = terms.join( ", " );

 

                    return false;

 

                }

 

            });

 

        });

 

        </script>

 

 
<style>
          .page_link {  display: inline-block;  }.page_link a {  color: black;  float: left;  padding: 5px 16px; background:#eee; text-decoration: none;}
          .page_link a.active {
  background-color: #4CAF50;
  color: white;
}

.page_link a:hover:not(.active) {background-color: #ddd;}

          </style>
 

        

 

</head>

 

<body>

 

 

 

<div class="header-inner">

 

<div class="header-top">

 

<a class="navbar-brand" href="#"><img src="images/color-logo.png"></a>

 

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

<h4>Vendor Section (Business)</h4>

    <li><a href="dashboard" role="tab" ><i class="fa  fa-fw fa-tachometer"></i>Dashboard</a></li>
<li><a href="becomefranchise" role="tab" > <i class="fa fa-database"></i>Become our Franchise <span class="label label-primary">New</span></a> </li>
    <li><a href="search" role="tab" ><i class="fa fa-fw fa-search"></i>Search Roles</a>    </li>

    <li><a href="job" role="tab"><i class="fa fa-fw fa-files-o"></i>Engaged Roles</a>    </li>
    
    <li><a href="disengaged" role="tab"><i class="fa fa-fw fa-files-o"></i>CLOSES / DISENGAGED</a>    </li>

    <li class="candidates-nav nav-dropdown ">

  <a href="#candidatesDropdownMenu" data-toggle="collapse" aria-controls="candidatesDropdownMenu" title="Candidates" class="navbar-root your-candidates">

    <i class="fa fa-fw fa-caret-right"></i> Supply Candidates (STS)</a>

 

   <div class="collapse" id="candidatesDropdownMenu">

   <ul class="nav-sub" style="display: block;">

    <li><a href="add_new" role="tab" ><i class="fa fa-fw fa-plus-square"></i> Add New Candidate</a>  </li>

    <li><a href="all" role="tab" ><i class="fa fa-list"></i>All Candidates</a> </li>

    <li><a href="awaiting-feedback" role="tab" ><i class="fa fa-clock-o"></i> Awaiting feedback</a>  </li>

    <li><a href="interview" role="tab" > <i class="fa fa-users"></i> Shortlisted</a>  </li>

     <li><a href="offer" role="tab" > <i class="fa fa-envelope"></i>Offered</a>  </li>

      <li><a href="filled" role="tab" > <i class="fa fa-users"></i>Filled</a>  </li>

    

    <li><a href="rejected" role="tab" > <i class="fa fa-trash"></i> Rejected</a> </li>

    </ul>

   </div>

   </li>

    <li><a href="msg" role="tab" ><i class="fa fa-fw fa-envelope-o"></i> Messaging (<?php echo $new; ?>) </a>    </li>

    <li><a href="rating" role="tab" > <i class="fa fa-users"></i> Feedback Rating</a> </li>

<li><a href="psl-list" role="tab" > <i class="fa fa-list"></i>PSL</a> </li>

<li class="candidates-nav nav-dropdown ">
  <a href="#candidatesDropdownMenu1" data-toggle="collapse" aria-controls="candidatesDropdownMenu1" title="Candidates" class="navbar-root your-candidates">
    <i class="fa fa-fw fa-caret-right"></i> Agency Settings</a>

   <div class="collapse" id="candidatesDropdownMenu1">
   <ul class="nav-sub" style="display: block;">
    <li><a href="company-profile" role="tab" > <i class="fa fa-briefcase"></i>Agency Profile</a> </li>
    <li><a href="add-user" role="tab" > <i class="fa fa-plus"></i>Add Users</a> </li>
    <li><a href="edit-profile" role="tab" > <i class="fa fa-plus"></i>Your Profile</a> </li>
    
    </ul>
   </div>
   </li>

 <li><a href="hire-resource" role="tab" > <i class="fa fa-database"></i>Bench Hiring <span class="label label-primary">Free</span></a> </li>

    <h4>Jobseeker Section (Jobsite)</h4>

 

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

 

 

 

    <div class="col-md-12 ui-sortable no-padding">

 

      <div class="panel panel-default" id="job">

 

  <header class="panel-heading wht-bg ui-sortable-handle">

 

    <h3 class="panel-title"><a>Search Candidates in -> </a> <a href="candidate-search" id="one">All</a> | <a href="candidate-search?country=uk" id="two">UK</a> | <a href="candidate-search?country=india" id="three">India</a> | <a href="candidate-search?country=france" id="four">France</a> | <a href="candidate-search?country=middleeast" id="five">UAE</a> | <a href="candidate-search?country=africa" id="six">Africa</a> | <a href="candidate-search?country=us" id="seven">US</a> | <a href="candidate-search?country=singapore" id="eight">Singapore</a> | <a href="candidate-search?country=malaysia" id="nine">Malaysia</a> | <a href="candidate-search?country=australia" id="ten">Australia</a> | <a href="candidate-search?country=canada" id="eleven">Canada</a> | <a href="candidate-search?country=japan" id="eleven">Japan</a> | <a href="candidate-search?country=germany" id="twelve">Germany</a> | <a href="candidate-search?country=poland" id="thirteen">Poland</a> | <a href="candidate-search?country=italy" id="fourteen">Italy</a> | <a href="candidate-search?country=netherlands" id="fifteen">Netherlands</a> | <a href="candidate-search?country=belgium" id="sixteen">Belgium</a> | <a href="candidate-search?country=switzerland" id="seventeen">Switzerland</a> | <a href="candidate-search?country=hungary" id="eighteen">Hungary</a> | <a href="candidate-search?country=austria" id="ninteen">Austria</a> | <a href="candidate-search?country=romania" id="twenty">Romania</a> | <a href="candidate-search?country=spain" id="twentyone">Spain</a> | <a href="candidate-search?country=portugal" id="twentytwo">Portugal</a> | <a href="candidate-search?country=norway" id="twentythree">Norway</a> | <a href="candidate-search?country=sweden" id="twentyfour">Sweden</a> | <a href="candidate-search?country=denmark" id="twentyfive">Denmark</a> | <a href="candidate-search?country=newzealand" id="twentysix">New Zealand</a> | <a href="candidate-search?country=thailand" id="twentyseven">Thailand</a> | <a href="candidate-search?country=china" id="twentyeight">China</a> | <a href="candidate-search?country=finland" id="twentynine">Finland</a> | <a href="candidate-search?country=luxembourg" id="thirty">Luxembourg</a> | <a href="candidate-search?country=russia" id="thirtyone">Russia</a> | <a href="candidate-search?country=greece" id="thirtytwo">Greece</a> | <a href="candidate-search?country=brazil" id="thirtythree">Brazil</a> | <a href="candidate-search?country=israel" id="thirtyfour">Israel</a> | <a href="candidate-search?country=qatar" id="thirtyfive">Qatar</a> | <a href="candidate-search?country=saudiarabia" id="thirtysix">Saudi Arabia</a> | <a href="candidate-search?country=mexico" id="thirtyseven">Mexico</a> | <a href="candidate-search?country=turkey" id="thirtyeight">Turkey</a> | <a href="candidate-search?country=egypt" id="thirtynine">Egypt</a> | <a href="candidate-search?country=czechrepublic" id="forty">Czech Republic</a> | <a href="candidate-search?country=southafrica" id="fortyone">South Africa</a> | <a href="candidate-search?country=morocco" id="fortytwo">Morocco</a> | <a href="candidate-search?country=argentina" id="fortythree">Argentina</a> | <a href="candidate-search?country=vietnam" id="fortyfour">Vietnam</a> | <a href="candidate-search?country=croatia" id="fortyfive">Croatia</a> | <a href="candidate-search?country=philippines" id="fortysix">Philippines</a> | <a href="candidate-search?country=chile" id="fortyseven">Chile</a> | <a href="candidate-search?country=peru" id="fortyeight">Peru</a> | <a href="candidate-search?country=srilanka" id="fortynine">Sri Lanka</a> | <a href="candidate-search?country=slovakia" id="fifty">Slovakia</a> | <a href="candidate-search?country=kenya" id="fiftyone">Kenya</a> | <a href="candidate-search?country=estonia" id="fiftytwo">Estonia</a> | <a href="candidate-search?country=bulgaria" id="fiftythree">Bulgaria</a> | <a href="candidate-search?country=lithuania" id="fiftyfour">Lithuania</a> | <a href="candidate-search?country=kazakhstan" id="fiftyfive">Kazakhstan</a> | <a href="candidate-search?country=ukraine" id="fiftysix">Ukraine</a> | <a href="candidate-search?country=belarus" id="fiftyseven">Belarus</a> | <a href="candidate-search?country=oman" id="fiftyeight">Oman</a> | <a href="candidate-search?country=ireland" id="fiftynine">Ireland</a></h3>

 

  </header>

 

 

<div class="map-navigation">

 

                           

                            <div class="tab-content">

 

 

                        <div role="tabpanel" class="tab-pane active" id="residential">

 

                                                                          <form method="get" name="form1" class="clearfix" action="candidate-search">

 

                                                                                                 

 

                                                 <div class="col-sm-12 no-padding">

 

                                                  <div class="col-sm-6 no-padding">

 

                               <?php if(isset($_GET['country'])){ ?>

                               <input type="hidden" value="<?php echo $_GET['country']; ?>" name="getcountry">

                               <?php }else{ ?>

                               <input type="hidden" value=" " name="getcountry">

                               <?php } ?>


                                                        <div class="col-sm-6 ">


                                      <input name="keyword" class="form-control" id="keyword" placeholder="<?php if(isset($_GET['keyword']) && ($_GET['keyword']!='')){echo $_GET['keyword']; }else{ echo 'Search by Keyword, Skills' ; } ?>" type="text">

                                                              </div>

 

                                            <div class="col-sm-6 no-padding">


                                          <input name="city" class="form-control" id="citylocation" placeholder="<?php if(isset($_GET['city'])&& ($_GET['city']!='')){echo $_GET['city'];}else {echo 'City' ; }?>" >

                                                              </div>

                                          </div>
                                          

                           <div class="col-lg-2 no-padding">
 

                          <button type="submit" name="search"   class="btn btn-primary gtm__search_btn"><i class="rnf-search"></i>Search CV's</button>


                              </div>
                              

                              
                        <div class="col-lg-2 no-padding">
 

                          <a href="post-job" class="btn btn-primary"><i class="fa  fa-fw fa-plus"></i>Post Job</a>
                          
                          
                              </div>
                               

                                <!--<div class="col-sm-3 ">

 

                                                                                                               <select name="experiencefrom" class="form-control" id="experiencefrom"  type="text">

                                          <?php if(!($_GET['experiencefrom']) ){?>

 

                                          <option value="">Experience (From)</option>

 

                                          <?php

 

                                                                                                                          for($i=0;$i<=35;$i++)

 

                                                                                                                          echo'<option value="'.$i.'">'.$i.'</option>';

 

                                                                                                                          ?>

<?php } else{

                                                                                                                          for($i=0;$i<=35;$i++)

 

                                                                                                                          if($i==$_GET["experiencefrom"]){

 

                                                                                                                          echo'<option value="'.$i.'" selected>'.$i.'</option>';}else{echo'<option value="'.$i.'">'.$i.'</option>'; } }?>  

                                         

                                          </select>

 

                                                  </div>

 

                                     

 

                                 <!-- <div class="col-sm-3 no-padding">

 

                                                                                                               <select name="experienceto" class="form-control" id="experienceto"  type="text">

 <?php if(!($_GET['experienceto'])) {?>

                                          <option value="">Experience(To)</option>

 

                                          <?php

 

                                                                                                                          for($i=0;$i<=35;$i++)

 

                                                                                                                         

 

                                                                                                                          echo'<option value="'.$i.'">'.$i.'</option>';

 

                                                                                                                          ?>

<?php } else{

                                                                                                                          for($i=0;$i<=35;$i++)

 

                                                                                                                          if($i==$_GET["experienceto"]){

 

                                                                                                                          echo'<option value="'.$i.'" selected>'.$i.'</option>';}else{echo'<option value="'.$i.'">'.$i.'</option>'; } }?>

                                          </select>

 

                                                              </div>-->
                                        <!--<div class="col-sm-3 no-padding">

 <?php $_GET['experienceto']=''; ?>

 

                                                                                         <select name="experienceto" class="form-control" id="experienceto"  type="text">

                                         

 <?php if(isset($_GET['experienceto']) &&($_GET['experienceto']!='')){ ?>

 

                                    <option value="<?php echo $_GET['experienceto'] ?>" selected><?php echo $_GET['experienceto']; ?></option>

                                     

 <?php }else{ ?>

 

                                                                             <option value="">Experience (To)</option>        

                                                     

 <?php } ?>

                                      

                                          </select>

 

                                                              </div><div id="message"></div>-->

 

                                    

 

                                   <!-- <div class="col-lg-12 no-padding search-box-2nd">

 

                                   

 

                                   <div class="col-lg-3">

 

 

 

                    <select class="form-control required " name="salarylakhsfrom"  id="salarylakhsfrom"placeholder="Annual Salary(Lac)">

<?php if(!($_GET['salarylakhsfrom'])) { ?>

 

                                <option value="">Annual Salary(Lac)</option>

 

                                  <?php

 

for($i=0;$i<50;$i++)

 

{

 

echo ' <option value="'.$i.'">'.$i.'</option>';}

 

  for($i=50;$i<=100;$i=$i+10) {

  echo ' <option value="'.$i.'+">'.$i.'+</option>';}                            

                                   ?>

                                  

                                   <?php } else {

                                                                                                  

                                                                                                   for($i=0;$i<50;$i++)

 

{

if($i==$_GET['salarylakhsfrom']){

 

echo ' <option value="'.$i.'" selected>'.$i.'</option>';

}else{

echo ' <option value="'.$i.'">'.$i.'</option>';

}

}

 

  for($i=50;$i<=100;$i=$i+10) {

  if($i==$_GET['salarylakhsfrom']){

 

  echo ' <option value="'.$i.'+" selected>'.$i.'+</option>';}else{echo '<option value="'.$i.'+" >'.$i.'+</option>'; }}

 

 

    }?>

                            </select>

 

                    </div>

 

                                     <div class="col-lg-3 no-padding ">

 

                      <select class="form-control required " name="salarythousandfrom" id="salarythousandfrom" placeholder="Annual Salary(K)">

<?php if(!($_GET['salarythousandfrom'])) { ?>

                      <option value="">Annual Salary(K)</option>

 

                                 <?php for($i=0;$i<=100;$i=$i+5) {

 

 

 

  echo ' <option value="'.$i.'">'.$i.'</option>';}                            

 

 

                                   ?>

<?php }else{

for($i=0;$i<=100;$i=$i+5) {

 

if($i==$_GET['salarythousandfrom']){

 

  echo ' <option value="'.$i.'" selected>'.$i.'</option>';}else{ echo ' <option value="'.$i.'">'.$i.'</option>';  } }  }?>

                              </select>

 

                              </div> 

 

                             

 

                  <p class="to-for"> to </p>

 

                             

 

                               <div class="col-lg-3 annual-for-to1">

 

 

 

                    <select class="form-control required " name="salarylakhsto" id="salarylakhsto" placeholder="Annual Salary(Lac)">

 

<?php if(!($_GET['salarylakhsto'])) {?>

 

                                <option value="">Annual Salary(Lac)</option>

 

 

                                  <?php

 

 

 

for($i=0;$i<50;$i++)

 

 

 

{

 

echo ' <option value="'.$i.'">'.$i.'</option>';}

 

 

 

  for($i=50;$i<=100;$i=$i+10) {

 

 

 

  echo ' <option value="'.$i.'+">'.$i.'+</option>';}                            

 

 

 

                                   ?>

<?php } else{

for($i=0;$i<50;$i++)

 

 

 

{

 

if($i==$_GET['salarylakhsto']){

 

echo ' <option value="'.$i.'" selected>'.$i.'</option>';}else{echo ' <option value="'.$i.'">'.$i.'</option>';}}

 

 

 

  for($i=50;$i<=100;$i=$i+10) {

 

if($i==$_GET['salarylakhsto']){

 

  echo ' <option value="'.$i.'+" selected>'.$i.'+</option>';}else{ echo ' <option value="'.$i.'+">'.$i.'+</option>';}} 

 

 

   }?>

      

                              </select>

 

                    </div>

 

                                     <div class="col-lg-3 no-padding annual-for-to2">

 

                      <select class="form-control required " name="salarythousandto" id="salarythousandto"placeholder="Annual Salary(K)">

 

 

<?php if(!($_GET['salarythousandto'])) { ?>

                      <option value="">Annual Salary(K)</option>

 

 

                                 <?php for($i=0;$i<=100;$i=$i+5) {

 

 

 

  echo ' <option value="'.$i.'">'.$i.'</option>';}                            

 

 

 

                                   ?>

<?php }else { for($i=0;$i<=100;$i=$i+5) {

if($i==$_GET['salarythousandto']){

 

 

  echo ' <option value="'.$i.'" selected>'.$i.'</option>';}else{echo ' <option value="'.$i.'">'.$i.'</option>';}}        }?>

                              </select>

 

                              </div>

 

                   

 

                    </div>--><div id="error"></div>

 

   

                                     <div class="col-lg-12 no-padding search-box-2nd">

 

                                   <!--<div class="col-lg-3">

 

                                   <input name="skills" class="required form-control" placeholder="<?php if(isset($_GET['skills'])&&($_GET['skills']!='')){echo $_GET['skills']; }else{ echo 'Skills'; } ?>" type="text">

 

                                                              </div>-->

 

                                    <!--<div class="col-lg-3 no-padding ">

 

                                    <select class="form-control required" name="noticeperiod">

<?php if(!($_GET['noticeperiod'])) { ?> 

 

                                <option value="">Notice period</option>

 

 

                                 <option value="15 Days or less">15 Days or less</option>

 

 

                                <?php

 

 

 

                                                                                                $month="months";

 

 

 

                                                                                                $noticeperiod='';

 

 

 

for ($i=1; $i<=3; $i++) {

 

 

$noticeperiod.= '<option value='.$i.$month.'>'.$i.$month.'</option>';

 

 

 } echo $noticeperiod; 

 

 

 ?>

 

  <option value="More than 3 Months">More than 3 Months</option>

 

<?php } else {  

$np=array('15 Days or less','1months','2months','3months','Currently Serving Notice','More than 3 Months');

foreach($np as $n){

if($n==$_GET['noticeperiod']){

echo '<option value='.$n.' selected>'.$n.'</option>';}

else{

echo '<option value='.$n.'>'.$n.'</option>';

}

}

}?>

                               </select>

 

                                                    </div>-->

 

                               <!--<div class="col-lg-3">

 

 

                                                              <select class="form-control required" name="higherqualification" id="higherqualification">

 

 

<?php if(!($_GET['higherqualification'])){ ?>

                                                                                                                                <option value="">Highest Qualification </option>

 

 

 

                                                                                                                           <option value="doctorate">Doctorate</option>

 

 

 

                                                                <option value="masters">Masters</option>

 

 

 

                                                                <option value="undergraduate">Undergraduate</option>

<?php } else { 

 

$np=array('doctorate','masters','undergraduate');

foreach($np as $n){

if($n==$_GET['higherqualification']){

echo '<option value='.$n.' selected>'.$n.'</option>';}

else{

echo '<option value='.$n.'>'.$n.'</option>';

}

}

 

} ?>

 

                                                                                                                                                                                    </select>

 

 

                                                                                                  </div>-->

 

                                                                                                <!---     <div class="col-lg-3 no-padding">

 

                                                              <select class="form-control required" name="country" >

<option value="">Select Country</option>

<?php $counres=mysqli_query($link,"select * from country");

 

while($row=mysqli_fetch_assoc($counres)){

echo'<option value='.$row['country'].'>'.$row['country'].'</option>';

}

?>

 

                                                                                                                                                                                    </select>

 

 

                                                                                                  </div>-->

 

                                 

 

                                   

 

                    </div>

                       </div>

 

                                     </form>

 

                     </div>

 

                      </div>

 

                          </div> 

 

</div>

 

 </div>

 

     </div>

 

     <div class="clearfix"></div>

 

     </div>

 

     </div>

 

 

<div class="col-sm-10">

 

<div class="account-navi-content">

 

<?php if(empty($search)) echo 'No results found';else{ ?>

 

  <div class="tab-content">

 

  <h3>Total Results:<?php echo $total; ?></h3>

 

<?php foreach($search as $row) {?>

 

    <div class="col-md-8 ui-sortable no-padding">

 

      <div class="panel panel-default" id="job">

 

  <header class="panel-heading wht-bg ui-sortable-handle">

 

    <h3 class="panel-title"><strong> <?php echo $row['name']; ?> <?php if($row['country']=='India'){ ?>

 <a class="btn21" href="candidate-profile?id=<?php echo $row['id'];?>" target="_blank">View CV</a><?php }else{ ?>

  <a class="btn21" href="candidate-profiles?id=<?php echo $row['id'];?>" target="_blank">View CV</a>

 <?php } ?></strong></h3>

 

  </header>

 

  <div class="Description">


<div class="detailpage-header no-padding">


<div id="job-title-wrap no-padding">

 

            <div class="pull-left col-lg-12"><strong></i>Current Designation: </span> <?php echo $row['designation']; ?><br> Key Skills: <?php echo $row['skills'];?></strong>

</div>



                <div class="clearfix"></div>


            </div>

</div>


 <div id="job-summary-box1" class="result-part"> 

 
<p><span class="style6"><strong>Email:</strong></span> <?php echo $row['email'];?> <span class="label label-primary">Verified</span></p>


<p><span class="style6"><strong>Current Company: </strong></span> <?php echo $row['currentcompany'];?></p>


<p><span class="style6"><strong>Experience: </strong></span> <?php echo $row['years'];?> years and <?php echo  $row['months'];?> months</p>

 

<p><span class="style6"><strong>Salary/Rate:</strong></span> <?php echo $row['salarylakhs'];?>  and <?php echo $row['salarythousand'];?> thousand</p>

<p><span class="style6"><strong>Current Location: </strong></span><?php echo $row['country'];?>, <?php echo $row['city'];?> | <strong>Notice Period:</strong> </span> <?php echo $row['noticeperiod'];?> | <strong>Last Modified Date:</strong> </span> <?php echo $row['lastmodified'];?></p>
 

<div class="search-view">

<p class="cta1-box ">

<?php if($row['country']=='India'){ ?>

 <a class="btn21" href="candidate-profile?id=<?php echo $row['id'];?>" target="_blank">View</a><?php }else{ ?>

  <a class="btn21" href="candidate-profiles?id=<?php echo $row['id'];?>" target="_blank">View</a>

 <?php } ?>

 </p>

</div>

</div>

</div> 

</div>

</div>


<?php } ?>


    </div>

     <div class="clearfix"></div>


  <?php echo $pages;


}?>   

     </div>
    </div>

<div class="clearfix"></div>

</div>

</div>

<script>

               $(document).ready(function(){

               $('#experiencefrom').on('change',function(){        

             

               var from=$('#experiencefrom').val();

              

             /** for(var i = from ; i <= 200; i++) { 

               $.each(to, function(index ,key) {

             $('#maxAmount').append($('<option>', {

                    value: to.key,

                    text : to.value ,

                }));

                                                });

                        }**/

                       

                        for (var i = from ; i <= 35; i++) {

    var added = document.createElement('option');

    var select1 = $('#experienceto');

           

    added.value = i;

    added.innerHTML = i;

    select1.append(added);

}

 

});

});

   </script>

<script>

 

 

 

var $min= $("#experiencefrom");

 

var $max = $("#experienceto");

 

var $msg = $("#message");

 

//Apply a single change event to fire on either dropdown

 

$min.add($max).change(function () {

 

    //Have some default text to display, an empty string

 

    var text = "";

 

 

 

    //Cache the vales as variables so we don't have to keep getting them

 

    //We will parse the numbers out of the string values

 

    var minVal = parseInt($min.val(),10);

 

    var maxVal = parseInt($max.val(),10)

 

 

 

    //Determine if both are numbers, if so then they both have values

 

    var bothHaveValues = !isNaN(minVal) && !isNaN(maxVal);

 

 

 

    if(bothHaveValues){

 

        if(minVal > maxVal){

 

            text += 'Minimum experience should be less than maximum';

 

        }else if(maxVal < minVal){

 

            text += 'Maximum experience should be greater than minimum';

 

        }

 

    }

 

 

 

    //Display the text

 

    $msg.html(text);

 

});

 

 

 

 

 

var $minimum= $("#salarylakhsfrom");

 

var $maximum = $("#salarylakhsto");

 

var $msg = $("#error");

 

var $minSal= $("#salarythousandfrom");

 

var $maxSal= $("#salarythousandto");         

 

 

 

$minimum.add($maximum).add($maxSal).change(function () {

 

 

 

    var text = "";

 

 

 

  

 

    var minVal = parseInt($minimum.val(),10);

 

    var maxVal = parseInt($maximum.val(),10)

 

 

 

   

 

    var bothHaveValues = !isNaN(minVal) && !isNaN(maxVal);

 

 

 

    if(bothHaveValues){

 

        if(minVal > maxVal){

 

            text += 'Minimum salary should be less than maximum';

 

        }else if(maxVal < minVal){

 

            text += 'Maximum salary should be greater than minimum';

 

        }

 

                        else if(minVal==maxVal){

 

                                                           

 

                                               

 

                                                var minThousand = parseInt($minSal.val(),10);

 

                                                 var maxThousand  = parseInt($maxSal.val(),10)

 

                                                 var bothHaveValues = !isNaN(minThousand) && !isNaN(maxThousand);

 

                       

 

                                    if(bothHaveValues){

 

                                                if(minThousand  >= maxThousand ){

 

                                                            text += 'Minimum salary should be less than maximum';

 

                                                }else if(maxThousand <= minThousand){

 

                                                            text += 'Maximum salary should be greater than minimum';

 

                                                }

 

                                    }

 

            }

 

            }

 

 

 

    //Display the text

 

    $msg.html(text);

 

});

 

</script>

 
<script type="text/javascript">
var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"1f8211f2f5aa69bfd2e64274cb249d69f747f435e0dfe9837445d3ed8ad8672546be72282208d9c4fa10ef18b9f5d348", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

</body>

 

</html>

 
