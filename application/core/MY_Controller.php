<?
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
		var $test;
		var $groupname;
		var $finaldata;
        public function __construct($config = array())
        {
                parent::__construct($config);
								if (empty($this->session->admin_name)) {
										header("location: /login/login_page");
								} else{
									$this->finaldata["adminID"] = $this->session->admin_name;
									$this->finaldata["total_privilege"] = intval($this->session->total_privilege);
								 }
								 $this->db->select("group_id, chinese_groupname")
								 				  ->from("backlink_content_group");
									$groupname = $this->db->get()->result_array();
									foreach ($groupname as $key => $value) {
										$this->groupname[$value["group_id"]] = $value["chinese_groupname"];
									}
									$this->finaldata["groupChinese"] = $this->groupname;

									// 共用所有下外鏈種類
									$this->db->from("type_backlink");
									$backlink = $this->db->get()->result_array();
									foreach ($backlink as $key => $value) {
										$this->finaldata["backlink_typeName"][ $value["auto_typeID"] ] = $value["Type_name"];
										$this->finaldata["backlink_typeName_color"][ $value["auto_typeID"] ] = $value["Type_color"];
									}
									// 共用所有產業分類
									$this->db->from("type_industry");
									$industry = $this->db->get()->result_array();
									foreach ($industry as $key => $value) {
										$this->finaldata["industry_tpyeName"][ $value["auto_typeID"] ] = $value["Type_name"];
									}
									// 共用所有操作等級分類
									$this->db->from("type_level");
									$level = $this->db->get()->result_array();
									foreach ($level as $key => $value) {
										$this->finaldata["level_tpyeName"][ $value["auto_typeID"] ] = $value["Type_name"];
									}
									// 共用所有方案分類
									$this->db->from("type_program");
									$program = $this->db->get()->result_array();
									foreach ($program as $key => $value) {
										$this->finaldata["program_tpyeName"][ $value["auto_typeID"] ] = $value["Type_name"];
									}

									// 共用權限內容
									$this->db->from("page_privilege");
									$page_privilege = $this->db->get()->result_array();
									foreach ($page_privilege as $key => $value) {
										$this->finaldata["page_privilege"][ $value["page_part_description"] ] = intval($value["privilege_id"]);
									}
        }


}
?>
