<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Case_model extends CI_Model {

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

    // 取得像index的那張table一樣的資訊
    public function case_table_list($case_name = NULL, $group_filter = NULL){
      $this->db->flush_cache()
               ->select("auto_id,case_name, case_industry, case_program, case_level")
							 ->from("case_table")
							 ->order_by("auto_id","DESC")
               ->stop_cache();
      if(!empty($case_name)){
        $this->db->like("case_name",$case_name)
                 ->stop_cache();
      }
      if(!empty($group_filter)){
        $this->db->where($group_filter)
                 ->stop_cache();
      }
      $data = $this->db->get()->result_array();
      return $data;
    }

}
