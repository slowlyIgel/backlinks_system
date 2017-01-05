<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Source_case_interact extends MY_Controller {

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

    public function record(){
      $this->twig->display("source_record",$this->finaldata);
    }

		public function test(){
			$this->db->select("case_table.case_name, backlink_add_history.linkpage, source_table.source_address")
							 ->from("backlink_add_history")
							 ->join("case_table","backlink_add_history.case_id = case_table.auto_id")
							 ->join("source_table","backlink_add_history.source_id = source_table.source_id")
							 ->order_by("backlink_add_history.case_id");

			$test = $this->db->get()->result_array();
			print_r($test);

		}
}
