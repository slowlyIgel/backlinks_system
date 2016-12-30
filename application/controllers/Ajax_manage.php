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

    }

    public function chinesegroup_change(){
      if (count($_POST) === 3) { //確定有且只有三個參數
        $this->db->where("group_id",$_POST["idFocus"])
                 ->update("backlink_content_group",array("chinese_groupname"=>$_POST["nameFocus"], "pie_chart_color"=>$_POST["colorFocus"]));
        $error = $this->db->error();
        if (!empty($error["messege"])) {
          echo $error["messege"];
        }
      }
    }

    public function chinesegroup_add(){
      if (count($_POST) == 2) { //有且只有名稱和色碼參數
        $this->db->insert("backlink_content_group",array("chinese_groupname"=>$_POST["nameFocus"],"pie_chart_color"=>$_POST["colorFocus"]));
        $error = $this->db->error();
        if (!empty($error["messege"])) {
          echo $error["messege"];
        }
      }
    }


}
