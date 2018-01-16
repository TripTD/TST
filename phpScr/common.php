<?php
  require_once("phpScr/config.php");

  $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

  if ( $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  function img( $fl_extn,$fl_temp) {
      $file_path = 'Images/'.substr(md4(time()),0,10).'.'.$fl_extn;
      move_uploaded_file($fl_temp,$file_path);
      $time = time();
      $query = "INSERT INTO MyItems (imeg) VALUES('','$file_path','$time')";
      $do = $conn->query($query);

  }

  function translate( $q, $sl, $tl) {
      if ( $sl == $tl || $sl == '' || $tl == '') {
          return $q;
      }
      else{
          $res = "";
          $qqq = explode(".", $q);
          if ( count($qqq) < 2) {
              @unlink($_SERVER['DOCUMENT_ROOT']."/transes.html");
              copy("http://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=".$sl."&tl=".$tl."&hl=hl&q=".urlencode($q), $_SERVER['DOCUMENT_ROOT']."/transes.html");
              if ( file_exists($_SERVER['DOCUMENT_ROOT']."/transes.html")){
                  $dara = file_get_contents($_SERVER['DOCUMENT_ROOT']."/transes.html");
                  $f = explode("\"", $dara);
                  $res .= $f[1];
              }
          }
          else{
              for ( $i = 0; $i < (count($qqq)-1); $i++){
                  if($qqq[$i] == ' ' || $qqq[$i] == '') {
                  }
                  else{
                      copy("http://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=".$s."&tl=".$e."&hl=hl&q=".urlencode($qqq[$i]), $_SERVER['DOCUMENT_ROOT']."/transes.html");
                      $dara = file_get_contents($_SERVER['DOCUMENT_ROOT']."/transes.html");
                      @unlink($_SERVER['DOCUMENT_ROOT']."/transes.html");
                      $f = explode("\"", $dara);
                      $res .= $f[1].". ";
                  }
              }
          }
          return $res;
      }
}


?>
