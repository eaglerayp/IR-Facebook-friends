<?php
set_time_limit(900);
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require 'facebook-php-sdk/src/facebook.php';
require 'Hash.php';
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '433417393393767',
  'secret' => '16c4d1af1f5a1b425fdcf0630e8df7fb',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

// Login or logout url will be needed depending on current user state.
if ($user) {
 $logoutUrl = $facebook->getLogoutUrl(array( 'next' => 'http://localhost/hw3/logout.php' ));
} else {
  $loginUrl = $facebook->getLoginUrl();
}

// This call will always work since we are fetching public data.
/* $user_id="100000100071899";
 $con = mysql_connect("localhost", "root", "");
    mysql_query("SET nameS utf8");
    if ( !  @mysql_select_db(irpfc) )
    die("無法使用資料庫");
    echo"";
  $HFlist= new HashMap;
  $result =mysql_query("SELECT DISTINCT fid
  FROM  `muflist` 
  WHERE uid =$user_id");
  while($row = mysql_fetch_array($result)){
    $flist[]=$row[0];
  }//end flist
  mysql_close($con); 
  //print_r ($flist);
  $fnum=count($flist);
  echo "FNUM:".($fnum)."</br>";
  $fnum=100;
  for ($i=0;$i<$fnum;$i++){
  echo "$i</br>";
  $muflist=array();
  $con = mysql_connect("localhost", "root", "");
  mysql_select_db(irpfc);
  $result =mysql_query("SELECT mid
  FROM  `muflist` 
  WHERE uid =$user_id AND fid=$flist[$i]");
    mysql_close($con); 
  while($row = mysql_fetch_array($result)){
    $muflist[]=$row[0];
  }
  $HFlist->put($flist[$i],$muflist);
  }//end muflist H flist>muflist*/

  
 // print_r ($cluster);
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <head>
    <title>Friends classfier</title>
    <link href="IRproj2.css" media="all" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Galindo' rel='stylesheet' type='text/css'> 
  </head>
  <body>
    <h1>FB php app Friends classfier</h1>

    <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
      <div>
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>


    <?php if ($user): ?>
      <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

      <h3>Your User Object (/me)</h3>
<pre><?php 
if ($user) {
  try {   
    // Proceed knowing you have a logged in user who's authenticated.
  $user_id = $facebook->getUser();
  //echo $user_id;
  $friend= $facebook->api('me?fields=friends.limit(500).fields(id,name,link)');
  $post= json_encode($friend);
  $posta= json_decode($post);
  $fnum=count($posta->{'friends'}->{'data'});
 // echo $fnum;
 // print_r ($posta->{'friends'}->{'data'});
  $HFlist = new HashMap;
  for ($i=0;$i<$fnum;$i++){
  //  echo $i;
    $fid=($posta->{'friends'}->{'data'}{$i}->{'id'});
    $fname=($posta->{'friends'}->{'data'}{$i}->{'name'});
    $flink=($posta->{'friends'}->{'data'}{$i}->{'link'});
    $fedu= array();
    $flist[]=$fid;
    $fnamelist[]=$fname;
    $flinklist[]=$flink;
    $mufr= $facebook->api($fid.'?fields=mutualfriends.fields(id)');
    $muf=json_decode(json_encode($mufr));
    $muc=count($muf->{'mutualfriends'}->{'data'});
    $muflist= array();
    for($j=0;$j<$muc;$j++){
      $mufid=($muf->{'mutualfriends'}->{'data'}{$j}->{'id'});
   //   mysql_query("INSERT INTO `muflist`(`uid`, `fid`, `mid`) VALUES ($user_id,$fid,$mufid)");
   //   echo ("INSERT INTO `muflist`(`uid`, `fid`, `mid`) VALUES ($user_id,$fid,$mufid)</br>");
      $muflist[]=$mufid;
    }
    $HFlist->put($fid,$muflist);
  }
echo "FNUM:".count($HFlist->keys());
  $cosmatrix=array();
  //compute cos matrix
  for ($i=0;$i<$fnum;$i++){
    for($j=$i;$j<$fnum;$j++){
      $cos=0;
      $flist1=$HFlist->get($flist[$i]);
      $flist2=$HFlist->get($flist[$j]);
      $len1=pow(count($flist1),0.5)+0.00000001;
      $len2=pow(count($flist2),0.5)+0.00000001;
      $equ=array_intersect($flist1,$flist2);
      $same=count($equ);
      $mother = $len1*$len2;
      $cos=$same/$mother;
      $cosmatrix[$i][$j]=$cos;
      $cosmatrix[$j][$i]=$cos;
    }
  }//end cosmatrix

  //echo ($cosmatrix[0][1])."</br>";
  $cluster= new HashMap;
  for($i=0;$i<$fnum;$i++){
    $cluster->put($i,array($i));
  }
 // print_r ($cluster);
  for($i=0;$i<$fnum;$i++){
    $I[]=1;
  }
 // print_r ($I);
  $tempmax=1;
  $round;
  for($round=1;$tempmax>0.2&&$round<($fnum-($fnum/20));$round++){
    $tempmax=(-1000);
    for($f1=0;$f1<$fnum;$f1++){
      for($f2=0;$f2<$fnum;$f2++){
        if((($cosmatrix[$f1][$f2])>$tempmax)&&($f1!=$f2)&&($I[$f1]==1)&&($I[$f2]==1)){
    //      echo "YA";
          $tempmax=$cosmatrix[$f1][$f2];
          $maxf1=$f1;
          $maxf2=$f2;
        }
      }
    }//end search
 // echo "MAX1:$maxf1</br>";echo "MAX2:$maxf2</br>";
  $c1=$cluster->get($maxf1);
  $c2=$cluster->get($maxf2);
  $c3=array_merge($c1,$c2);
  asort($c3);
  $cluster->remove($maxf1);
  $cluster->remove($maxf2);
  $cluster->put($maxf1,$c3);
  $len1=pow(count($HFlist->get($flist[$maxf1])),0.5)+0.00000001;
  $len2=pow(count($HFlist->get($flist[$maxf2])),0.5)+0.00000001;
  for($i=0;$i<$fnum;$i++){
    $cosmatrix[$maxf1][$i]=(($len1*$cosmatrix[$maxf1][$i])+($len2*$cosmatrix[$maxf2][$i]))/($len1+$len2);
    $cosmatrix[$i][$maxf1]=$cosmatrix[$maxf1][$i];
  }
  $I[$maxf2]=0;
  }//end round for*/
  echo "R:$round</br>";
  echo "MAX:$tempmax</br>";
  $cid=($cluster->keys());
 // print_r ($cid);
  for($i=0;$i<count($cid);$i++){
    $ca=($cluster->get($cid[$i]));
    echo "<div class=\"HOT\">";
    for($j=0;$j<count($ca);$j++){
      if(($j%6)==0){echo "<div class=\"TOP\">";}
      echo"<a href=\"".$flinklist[$ca[$j]]."\" target=\"_blank\" >".$fnamelist[$ca[$j]]."</a><img src=\"https://graph.facebook.com/".$flist[$ca[$j]]."/picture\">";
      if(($j%6)==5){echo "</br></div>";}
    }
    if(($j%6)!=0){echo "</br></div>";}
    echo "</br>";
    echo "</br>";
    echo "</br>";
    echo "</div>";
  }
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
      ?></pre>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>
  </body>
</html>
