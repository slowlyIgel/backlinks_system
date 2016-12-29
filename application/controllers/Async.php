<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Async extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
    }

    public function checkasync(){
      $this->db->select("case_name, case_address")
               ->from("case_table");
      $cases = $this->db->get()->result_array();
      $this->finaldata["async_case"] = $cases;
      $this->twig->display("async",$this->finaldata);
    }

    public function getasync(){

      function get_asynctest($url){
        // 借用
            // $this_header = array("charset=UTF-8");
            $ch = curl_init();
            $timeout = 5;
            // curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
            curl_setopt($ch, CURLOPT_URL, $url);
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

            foreach($dom->getElementsByTagName('script') as $link) {
                    $script = $link->getAttributeNode("async")->nodeName;
                    if (!empty($script)) {
                      return "async";
                    }
            }
            foreach ($dom->getElementsByTagName('link') as $link) {
                    $script = $link->getAttributeNode("async")->nodeName;
                    if (!empty($script)) {
                      return "async";
                    }
            }

            return 0;
      }

      $test = get_asynctest($_POST["url"]);
      echo $test;

    }
}
