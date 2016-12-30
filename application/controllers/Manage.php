<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends MY_Controller {

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
    public function index(){
			$this->finaldata["page_name"] = "分類管理";
      $this->twig->display("manage_index",$this->finaldata);
    }
		public function backlink_type(){
			$this->finaldata["page_name"] = "外鏈種類管理";
			$this->twig->display("manage_backlink_type",$this->finaldata);
		}
		public function industry(){
			$this->finaldata["page_name"] = "產業分類管理";
			$this->twig->display("manage_industry",$this->finaldata);
		}
		public function program(){
			$this->finaldata["page_name"] = "方案分類管理";
			$this->twig->display("manage_program",$this->finaldata);
		}
		public function level(){
			$this->finaldata["page_name"] = "操作等級管理";
			$this->twig->display("manage_level",$this->finaldata);
		}

		public function grouplimit(){
			$this->finaldata["page_name"] = "外鏈群組上限管理";
			$this->db->select()
							 ->from("backlink_content_group");
			$this->finaldata["chinese_group"] = $this->db->get()->result_array();
			$this->twig->display("manage_grouplimit",$this->finaldata);
		}

		public function source_topic(){
			$this->finaldata["page_name"] = "資源站類型管理";
			$this->finaldata["table_name"] = "type_source_topic";
			$this->db->select()
							 ->from("type_source_topic");
			$this->finaldata["source_type_manage"] = $this->db->get()->result_array();
			$this->twig->display("manage_source",$this->finaldata);

		}

		public function source_status(){
			$this->finaldata["page_name"] = "資源站狀態管理";
			$this->finaldata["table_name"] = "type_source_status";
			$this->db->select()
							 ->from("type_source_status");
			$this->finaldata["source_type_manage"] = $this->db->get()->result_array();
			$this->twig->display("manage_source",$this->finaldata);

		}

		public function source_indexstatus(){
			$this->finaldata["page_name"] = "資源站收錄狀態管理";
			$this->finaldata["table_name"] = "type_source_indexstatus";
			$this->db->select()
							 ->from("type_source_indexstatus");
			$this->finaldata["source_type_manage"] = $this->db->get()->result_array();
			$this->twig->display("manage_source",$this->finaldata);

		}

		public function source_language(){
			$this->finaldata["page_name"] = "資源站語言管理";
			$this->finaldata["table_name"] = "type_source_lang";
			$this->db->select()
							 ->from("type_source_lang");
			$this->finaldata["source_type_manage"] = $this->db->get()->result_array();
			$this->twig->display("manage_source",$this->finaldata);

		}

		public function source_anchor(){
			$this->finaldata["page_name"] = "資源站錨文本管理";
			$this->finaldata["table_name"] = "type_source_anchor";
			$this->db->select()
							 ->from("type_source_anchor");
			$this->finaldata["source_type_manage"] = $this->db->get()->result_array();
			$this->twig->display("manage_source",$this->finaldata);

		}

		public function source_sitetype(){
			$this->finaldata["page_name"] = "資源站網站類型管理";
			$this->db->select()
							 ->from("type_source_sitetype");
			$this->finaldata["sitetype"] = $this->db->get()->result_array();

			$this->db->select()
							 ->from("type_source_level");
			$data = $this->db->get()->result_array();
			foreach ($data as $key => $value) {
				$this->finaldata["site_level"][ $value["auto_typeID"] ] = $value["Type_name"];
			}

			$this->twig->display("manage_source_sitetype",$this->finaldata);

		}
}
