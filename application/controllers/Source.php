<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Source extends MY_Controller {

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
   var $data = array();
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
      $this->finaldata["page_name"] = "資源站管理";
      $this->twig->display("source_index",$this->finaldata);
    }

    public function source_dataedit($id){
      $this->finaldata["page_name"] = "資源站編輯";
      $this->twig->display("source_dataedit",$this->finaldata);
    }
}
