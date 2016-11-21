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
									$this->finaldata["total_privilege"] = $this->session->total_privilege;

								 }
									$this->groupname = array("群組一","群組二","群組三","群組四","群組五",
																					"群組六","群組七","群組八","群組九","群組十",
																					"群組十一","群組十二","群組十三","群組十四","群組十五",
																					"群組十六","群組十七","群組十八","群組十九","群組二十",
																					"群組二一","群組二二","群組二三","群組二四","群組二五",
																					"群組二六","群組二七","群組二八","群組二九","群組三十");
									$this->finaldata["groupChinese"] = $this->groupname;


									// 共用所有下外鏈種類
									$this->db->from("type_backlink");
									$backlink = $this->db->get()->result_array();
									foreach ($backlink as $key => $value) {
										$this->finaldata["backlink_typeName"][ $value["auto_typeID"] ] = $value["Type_name"];
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
										$this->finaldata["program_tpyeName"][ $value["auto_programID"] ] = $value["program_name"];
									}

									// 共用權限內容
									$this->db->from("page_privilege");
									$page_privilege = $this->db->get()->result_array();
									foreach ($page_privilege as $key => $value) {
										$this->finaldata["page_privilege"][ $value["page_part_description"] ] = $value["privilege_id"];
									}

        }


}
?>
