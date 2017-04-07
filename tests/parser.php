<?php
error_reporting(-1);
ini_set('display_errors', 1);
//ini_set('display_startup_errors',1);

//ok
//$url='https://mossegalapoma.cat/programant-assistents-veu-perfils-multidisciplinaris-programa-318/';
//ok
//$url='http://dinamics.ccma.cat/public/podcast/catradio/xml/8/3/podprograma1738.xml';
//ok
//$url='http://sverigesradio.se/sida/avsnitt/869310?programid=4977'; //page with relative path mp3
$url='http://haciendoelsueco.com/532-publicidad-multicultural/';

$media=get_media_from_url($url);
print $media;

function get_media_from_url($url){
  $file_parts = pathinfo($url);
  $sound_url="";

  if(isset($file_parts['extension'])){
    $ext=$file_parts['extension'];
    if($ext=="xml" || $ext=="XML"){
      $sound_url=parseXML($url);
    }
  }
  if($sound_url==""){
    $sound_url=parseHtml($url);
  }

  return $sound_url;

}


function parseXML($url){
  $html = file_get_contents($url);
  $doc= new SimpleXMLElement($html);
  foreach($doc->channel->item as $item){
    //print_r((string)$item->guid);
    $media_url=(string)$item->enclosure[0]['url'];


    return $media_url;
  }
  return "";
}

function parseHtml($url){
  $html = file_get_contents($url);
  $doc=new DOMDocument();
  @$doc->loadHTML($html);


  $links = array();
  foreach($doc->getElementsByTagName('a') as $elem) {
    $link=$elem->getAttribute('href');

    if( strpos( $link, '.mp3' ) !== false ) {

      if( strpos( $link, 'http' ) !== false ) {
        return $link;
      }else{
        //if not starts with http:// means its relative...
        $result = parse_url($url);
          return $result['scheme']."://".$result['host'].$link;

      }



      return $link;
    }
  }
  return "";
}
