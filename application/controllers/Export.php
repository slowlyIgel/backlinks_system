<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends MY_Controller {

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
    public function backlink_data(){
      if ($_POST["ContentIndex"]) {
        foreach ($_POST["ContentIndex"] as $key => $eachGroupinCase) {
                $this->db->start_cache()
                         ->flush_cache()
                         ->select("backlink_content")
                         ->from("backlink_content_table")
                         ->where($eachGroupinCase)
                         ->stop_cache();
                $data["eachGroupContent"] = $this->db->get()->result_array()[0]["backlink_content"];
                $finaldata[$key]["eachGroupContent"] = explode("Seperate%%EachLink%%Here",$data["eachGroupContent"]);
                $finaldata[$key]["CaseName"] = $_POST["CaseName"][$key];
        }
        $this->finaldata["allLinkinFile"] = $finaldata;
        $this->output->set_header("Content-type: application/octetstream");
    		$this->output->set_header("Content-Disposition: attachment; filename=test.txt");
         echo $this->twig->render("export_txt",$this->finaldata);
    }
  }

}
