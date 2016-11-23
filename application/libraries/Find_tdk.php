<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Find_tdk {

        public function __construct()
        {
                // Do something with $params
        }
        public function get_tdk($Url){

          $meta = file_get_contents($Url);
          // echo $meta;
          preg_match('/<title>([^<]*)<\/title>/',$meta,$title);
          $test["case_title"] = $title[1]."<br>";
          // print_r($title[1]);
          preg_match('/<meta name="description".content="([^"]*)">/',$meta,$description);
          // print_r($description[1]);
          $test["case_description"] =  $description[1]."<br>";
          return $test;
        }


        public function get_tdktest($Url,$gacode){
          $gacode = '/'.str_replace("-","\-",$gacode).'/';

                    // 借用
              // $this_header = array("charset=UTF-8");
              $ch = curl_init();
              $timeout = 5;
              // curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
              curl_setopt($ch, CURLOPT_URL, $Url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
              curl_setopt($ch, CURLOPT_USERAGENT, "Google Bot");
              $html = curl_exec($ch);
              $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
              curl_close($ch);
              $test["case_alive"] = $httpCode;
              $dom = new DOMDocument();
              # Parse the HTML from Google.
              # The @ before the method call suppresses any warnings that
              # loadHTML might throw because of invalid HTML in the page.
              $coding = mb_detect_encoding($html);

              @$dom->loadHTML('<?xml encoding="UTF-8">' .$html);
              foreach ($dom->childNodes as $item)
                  if ($item->nodeType == XML_PI_NODE)
                      $dom->removeChild($item); // remove hack
              $dom->encoding = 'UTF-8'; // insert proper
              $f=0; //flag for error output
              foreach($dom->getElementsByTagName('title') as $link) {
                      $test["case_title"] = $link->nodeValue;
                      if($coding != "UTF-8"){
                        $test["case_title"] = mb_convert_encoding($test["case_title"], "UTF-8", "auto");
                      }
                      $f=1;
              }

              foreach($dom->getElementsByTagName('script') as $link) {
                      $script = $link->nodeValue;
                      $test["case_gacode_check"] = 0;
                      if (preg_match($gacode,$script)) {
                        $test["case_gacode_check"] = preg_match($gacode,$script);
                        break;
                      }

              }

              foreach($dom->getElementsByTagName('meta') as $link) {
                      if( strtolower($link->getAttribute('name')) == 'description'){
              		 $test["case_description"] = $link->getAttribute('content');
                   if($coding != "UTF-8"){
                     $test["case_description"] = mb_convert_encoding($test["case_description"], "UTF-8", "auto");
                   }

              	}

                      if( strtolower($link->getAttribute('name')) == 'keywords'){
                   $test["case_keyword"] = $link->getAttribute('content');
                   if($coding != "UTF-8"){
                     $test["case_keyword"] = mb_convert_encoding($test["case_keyword"], "UTF-8", "auto");
                   }

                }

              }
              if($f==0){
                $test["case_title"] = "error";
                $test["case_description"] = "error";
                $test["case_keyword"] = "error";
              }

              return $test;

        }
}
