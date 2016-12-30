<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_manage extends MY_Controller {

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

				$this->source_type_table = array(
					"type_source_topic",
					"type_source_status",
					"type_source_lang",
					"type_source_anchor"
				);

    }

    public function chinesegroup_change(){
      if (count($_POST) === 3) { //確定有且只有三個參數
        $this->db->where("group_id",$_POST["idFocus"])
                 ->update("backlink_content_group",array("chinese_groupname"=>$_POST["nameFocus"], "pie_chart_color"=>$_POST["colorFocus"]));
        $error = $this->db->error();
        if (!empty($error["message"])) {
          echo $error["message"];
        }
      }
    }

    public function chinesegroup_add(){
      if (count($_POST) == 2) { //有且只有名稱和色碼參數
        $this->db->insert("backlink_content_group",array("chinese_groupname"=>$_POST["nameFocus"],"pie_chart_color"=>$_POST["colorFocus"]));
        $error = $this->db->error();
        if (!empty($error["message"])) {
          echo $error["message"];
        }
      }
    }

		public function source_type_change($table){
			if (!in_array($table,$this->source_type_table)) {
				echo "分類表錯誤";
				exit;
			} elseif (count($_POST) != 3) {
				echo "資料錯誤";
				exit;
			}
			$this->db->where("auto_typeID",$_POST["idFocus"])
							 ->update($table,array("Type_name"=>$_POST["nameFocus"],"Type_color"=>$_POST["colorFocus"]));
			$error = $this->db->error();
			if (empty($error["message"])) {
				echo $error["message"];
			}

		}

		public function source_type_delete($table){
			$source_column = str_replace("type_","",$table);
			$this->db->select("COUNT(*)")
							 ->from("source_table")
							 ->where($source_column,$_POST["idFocus"]);
			$count = $this->db->get()->row_array();
			if ($count["COUNT(*)"] != 0) {
				echo "還有資源站屬於此分類不可刪除";
			} else{
				$this->db->delete($table,array("auto_typeID"=>$_POST["idFocus"]));
			}
		}

		public function source_type_add($table){
			if (!in_array($table,$this->source_type_table)) {
				echo "分類表錯誤";
				exit;
			} elseif (count($_POST) != 2) {
				echo "資料錯誤";
				exit;
			}
			$this->db->insert($table,array("Type_name"=>$_POST["nameFocus"],"Type_color"=>$_POST["colorFocus"]));
			$error = $this->db->error();
			if (empty($error["message"])) {
				echo $error["message"];
			}

		}

		public function source_sitetype_change(){
			if (count($_POST) != 4) {
				echo "資料錯誤";
				exit;
			}
			$this->db->where("auto_typeID",$_POST["idFocus"])
							 ->update("type_source_sitetype",array("Type_name"=>$_POST["nameFocus"],"Type_color"=>$_POST["colorFocus"],"Type_level"=>$_POST["levelFocus"]));
			$error = $this->db->error();
			if (empty($error["message"])) {
				echo $error["message"];
			}

		}

		public function source_sitetype_delete(){
			$this->db->select("COUNT(*)")
							 ->from("source_table")
							 ->where("source_sitetype",$_POST["idFocus"]);
			$count = $this->db->get()->row_array();
			if ($count["COUNT(*)"] != 0) {
				echo "還有資源站屬於此分類不可刪除";
			} else{
				$this->db->delete("type_source_sitetype",array("auto_typeID"=>$_POST["idFocus"]));
			}


		}

		public function source_sitetype_add(){
			if (count($_POST) != 3) {
				echo "資料錯誤";
				exit;
			}
			$this->db->insert("type_source_sitetype",array("Type_name"=>$_POST["nameFocus"],"Type_color"=>$_POST["colorFocus"],"Type_level"=>$_POST["levelFocus"]));
			$error = $this->db->error();
			if (empty($error["message"])) {
				echo $error["message"];
			}

		}
}
