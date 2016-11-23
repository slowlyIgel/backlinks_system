<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Textworks {

        public function __construct()
        {
                // Do something with $params
        }
        public function wirte_txtfile($to_txt_array,$filename){
          $content = "";
          foreach ($to_txt_array as $key => $eachgroup) {
            $content .= $eachgroup["CaseName"]."\r\n\r\n";
            $content .= "描述:"."\r\n";
            $content .= $eachgroup["description"]."\r\n";
            $content .= "連結原始碼:"."\r\n";
            foreach ($eachgroup["linkContent"] as $key2 => $eachlink) {
              $content .= $eachlink."\r\n";
            }
            $content .= "------------------------------------------------------"."\r\n";
          }
          file_put_contents("upload/".$filename.".txt",$content);

        }
}
