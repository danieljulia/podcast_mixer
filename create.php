<?php
/**
Created by Daniel Julià
Pimpampum.net
*/

error_reporting(-1);
ini_set('display_errors', 1);

require "parser.php";
include("template.class.php");

/*
It's a must to publish and make public the sheet as this example
https://docs.google.com/spreadsheets/d/14r4weMnEsL7qPy2y-C5S-PstCcEOiw6cw8kLEBfxp14/edit?usp=sharing
and then can be exported easily as csv
https://docs.google.com/spreadsheets/d/1HZzq8imLd0Rb0IKY6eUlQ3ND1_EUiPXvdjkbSfZRhLc/export?format=csv&id=1HZzq8imLd0Rb0IKY6eUlQ3ND1_EUiPXvdjkbSfZRhLc
*/


if(!isset($_GET['code'])){
  $code="14r4weMnEsL7qPy2y-C5S-PstCcEOiw6cw8kLEBfxp14";
}else{
  $code=$_GET['code'];
}
$spreadsheet_url="https://docs.google.com/spreadsheets/d/$code/export?format=csv&id=$code";
//print $spreadsheet_url;

if(!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";

if (($handle = fopen($spreadsheet_url, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $spreadsheet_data[] = $data;
    }
    fclose($handle);
    //print "<pre>";
  //  print_r($spreadsheet_data);
  //  print "</pre>";
    $r=0;
    $pods=array();
      $feed = new Template("templates/feed.tpl");

    foreach($spreadsheet_data as $row){

      if($r!=0){

        $pod = new Template("templates/pod.tpl");

        $pod->set("author", $row[1]);

        $pod->set("title", $row[0]);
        $pod->set("description", $row[2]);
        $pod->set("media_url", $row[3]);

        $pod->set("date", date('r',time()));

        $pods[]=$pod;
      }

      if($r==1){ //podcast info

            $feed->set("title", $row[6]);
            $feed->set("link", $row[7]);
            $feed->set("description", $row[8]);
            $feed->set("author", $row[9]);
            $feed->set("image", $row[10]);
            $feed->set("subtitle", $row[11]);
            $feed->set("summary", $row[12]);
            $feed->set("date", date('r',time()));
            //todo
      }

      $r++;
    }



    $pds = Template::merge($pods);

    $feed->set("items", $pds);

    //$feed->set("items", $pods);

    print $feed->output();
}
else
    die("Problem reading csv");
