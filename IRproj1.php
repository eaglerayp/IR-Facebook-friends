<?php
set_time_limit(600);
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

$facebook = new Facebook(array(
  'appId'  => '433417393393767',
  'secret' => '16c4d1af1f5a1b425fdcf0630e8df7fb',
  'cookie' => false,
));

function getid ($facebook,$uid){

  $HL =new HashMap;
  $HC =new HashMap;  
  $user_profile = $facebook->api($uid.'/feed?fields=comments.fields(from,id),likes.fields(name),message,from,story_tags&limit=40');
 // print_r($user_profile);

  $catch=json_encode($user_profile);
  //echo $catch;
  //print $de->{'data'}[$i]->{'comments'}->{'data'}[$j]
  $totallcount=0;
  $totalccount=0;
  $cidlist;
  $lidlist;
  $de= json_decode($catch);
  //print_r ($de);
  $arr=($de->{'data'});
  //print_r $arr[0];
  $datacount=count($arr);
  for($i=0;$i<$datacount;$i++){//comments id
    $dataarr=($de->{'data'}[$i]->{'comments'}->{'data'});
    $data2count=count($dataarr);
    $pid=new HashMap;
      for($j=0;$j<$data2count;$j++){
        //print "COMMENT:";
        $cid=($de->{'data'}[$i]->{'comments'}->{'data'}[$j]->{'from'}->{'id'});
        if($pid->containsKey($cid)){
          $HC->put($cid,0.5);
        }
        else{
          $pid->put($cid,0);
          $HC->put($cid,1);
        }
        $totalccount++;
      }
    $dataarr=($de->{'data'}[$i]->{'likes'}->{'data'});
    $data2count=count($dataarr);
      for($j=0;$j<$data2count;$j++){//likes id
        $lid=($de->{'data'}[$i]->{'likes'}->{'data'}[$j]->{'id'});
        $HL->put($lid,1);
        $totallcount++;
      } 
      $mid=($de->{'data'}[$i]->{'from'}->{'id'});//get message fromid
      $HC->put($mid,1);
      $totalccount++;
      $ka=array_keys((array)($de->{'data'}[$i]->{'story_tags'}));
      for ($x=0;$x<count($ka);$x++){//get story tagid
        $type=($de->{'data'}[$i]->{'story_tags'}->{$ka[$x]}[0]->{'type'});
        if ($type=="user"){
          $sid=($de->{'data'}[$i]->{'story_tags'}->{$ka[$x]}[0]->{'id'});
          $HC->put($sid,1);
        }
      }
  }

  //echo $de; 
  //echo ("</br>LIKES:$totallcount</br>");
  //echo ("COMMENTS:$totalccount</br>");
  $HC->putAll($HC);
  $HC->putAll($HL);
 // echo array_sum($HC->values());
  $HL->removeAll();
  $HC->remove($uid);
  $HC->maxAll($HC,10);
  return $HC;
}
function friendlist($facebook){
  $friend= $facebook->api('me?fields=friends.fields(id)');//get friendlist
  $post= json_encode($friend);
  $posta= json_decode($post);
  $ac=count($posta->{'friends'}->{'data'});
  for ($i=0;$i<$ac;$i++){
    $fid=($posta->{'friends'}->{'data'}{$i}->{'id'});
    $flist[]=$fid;
  }
  return $flist;
}

// Create our Application instance (replace this with your appId and secret).


// Get User ID
$user = $facebook->getUser();
if(!$user){
  $loginUrl = $facebook->getLoginUrl((array('scope' => 'email,user_birthday,read_stream, publish_stream, read_friendlists, photo_upload, friends_status, xmpp_login,publish_actions,user_status')));
}
// Login or logout url will be needed depending on current user state.


// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.



if ($user) {
  $logoutUrl = $facebook->getLogoutUrl(array( 'next' => 'http://localhost/hw3/logout.php' ));
} else {
  $loginUrl = $facebook->getLoginUrl((array('scope' => 'email,user_birthday,read_stream, publish_stream, read_friendlists, photo_upload, friends_status, xmpp_login,publish_actions,user_status')));
}
// This call will always work since we are fetching public data.


?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <head>
    <title>FB交友圈活躍王</title>
    <link href="IRproj1.css" media="all" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Galindo' rel='stylesheet' type='text/css'> 
  </head>
  <body>
    <h1>FB-php App Activest Friends</h1>

    <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
      <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

    <!--<h3>PHP Session</h3>-->
    <pre><?php //print_r($_SESSION); ?></pre>

    <?php if ($user): ?>
     <div class="USER"> <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">
    </div>
      <pre><?php 
if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    // $user_profile = $facebook->api('me/feed&scope=read_stream');
  $flist= friendlist($facebook);
  $H = new Hashmap;
  $HUSERLIST = new Hashmap;
  $user_id =$facebook->getUser();
  $H1=getid($facebook,$user_id);//1st time get user's
 // echo array_sum($H->values());
  //print_r ($H);
  $H->putAll($H1);
  $one=($H1->keys());
  $HUSERLIST->put($user_id,$one);
  //print_r ($one);
  echo "</br>";
  for($i=0;$i<(count($one));$i++){//2nd get user's 1st
    //echo ($one[$i])."</br>";
   // echo $i."</br>";
    $H2=getid($facebook,$one[$i]);
    $f1=$H1->get($one[$i]);
    $H->mulAll($H2,(0.7+(0.2*$f1)));
    $one2=($H2->keys());
    $HUSERLIST->put($one[$i],$one2);
  }
    $H->putAll($H1);
    $H->remove($user_id);
   //echo "H:".array_sum($H->values());
    $H->Hsort();
   // print_r ($H);
    $endk=$H->keys();
    $enflist=array_diff($endk,$flist);
    $eflist=array_diff($endk,$enflist);
    //print_r ($endk);
    $ecou=count($endk);
    echo "<div class=\"HOT\"> HOT Friends:</br>";
    $TOP= $facebook->api(end($eflist).'?fields=name,link');
    echo "<div class=\"TOP\">TOP1:<a href=\"$TOP[link]\" target=\"_blank\" >$TOP[name]</a><img src=\"https://graph.facebook.com/".end($eflist)."/picture\"></br></div>";
    $TOP= $facebook->api(prev($eflist).'?fields=name,link');
    echo "<div class=\"TOP\">TOP2:<a href=\"$TOP[link]\" target=\"_blank\" >$TOP[name]</a><img src=\"https://graph.facebook.com/".current($eflist)."/picture\"></br></div>";
    $TOP= $facebook->api(prev($eflist).'?fields=name,link');
    echo "<div class=\"TOP\">TOP3:<a href=\"$TOP[link]\" target=\"_blank\" >$TOP[name]</a><img src=\"https://graph.facebook.com/".current($eflist)."/picture\"></br></div>";
    echo "</div>";
   // echo "$ecou</br>";
   // print_r ($enflist);
    $encou=count($enflist);
   // echo "$encou</br>";
    echo "<div class=\"HOTN\"> HOT NoFriends:</br>";
    $TOP= $facebook->api(end($enflist).'?fields=name,link');
    echo "<div class=\"TOP\">TOP1:<a href=\"$TOP[link]\" target=\"_blank\" >$TOP[name]</a><img src=\"https://graph.facebook.com/".end($enflist)."/picture\"></br></div>";
    $TOP= $facebook->api(prev($enflist).'?fields=name,link');
    echo "<div class=\"TOP\">TOP2:<a href=\"$TOP[link]\" target=\"_blank\" >$TOP[name]</a><img src=\"https://graph.facebook.com/".current($enflist)."/picture\"></br></div>";
    $TOP= $facebook->api(prev($enflist).'?fields=name,link');
    echo "<div class=\"TOP\">TOP3:<a href=\"$TOP[link]\" target=\"_blank\" >$TOP[name]</a><img src=\"https://graph.facebook.com/".current($enflist)."/picture\"></br></div>";
    echo "</div>";
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
