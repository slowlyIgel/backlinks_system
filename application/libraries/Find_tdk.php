<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Find_tdk {

        public function __construct()
        {
                // Do something with $params
        }
        public function get_tdk($Url){

          $meta = file_get_contents($Url);
          // echo $meta;
          preg_match('/<title>(.*)<\/title>/',$meta,$title);
          $test["title"] = $title[1]."<br>";
          // print_r($title[1]);
          preg_match('/<meta name="description".content="([^"]*)">/',$meta,$description);
          // print_r($description[1]);
          $test["d"] =  $description[1]."<br>";
          return $test;
        }


        public function get_tdktest($Url){
                    // 借用
          $ch = curl_init();
              $timeout = 5;
              curl_setopt($ch, CURLOPT_URL, $Url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
              $html = curl_exec($ch);
              $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
              curl_close($ch);
              $test["status"] = $httpCode;
              # Create a DOM parser object
              $dom = new DOMDocument();
              # Parse the HTML from Google.
              # The @ before the method call suppresses any warnings that
              # loadHTML might throw because of invalid HTML in the page.
              @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' .$html);
              $f=0; //flag for error output
              # Iterate over all the <a> tags
              foreach($dom->getElementsByTagName('title') as $link) {
                      # Show the <a href>
                      $test["title"] = $link->nodeValue;
                      $f=1;
              }

              foreach($dom->getElementsByTagName('meta') as $link) {
                      # Show the <a href>
                      if( strtolower($link->getAttribute('name')) == 'description'){
              		 $test["description"] = $link->getAttribute('content');
              	}

                      if( strtolower($link->getAttribute('name')) == 'keywords'){
                   $test["keywords"] = $link->getAttribute('content');
                }

              }
              if($f==0){
                $test["title"] = "error";
                $test["description"] = "error";
                $test["keywords"] = "error";
              }
              return $test;

        }
}
