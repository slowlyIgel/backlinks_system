<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backlink extends MY_Controller {

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

	public function index()
	{
		echo "backlink side";
	}

	public function testtime(){
		$monday = strtotime("Monday last Week",time());
		echo $monday."<br>";
		$lastmonday = date("Y-n-d H:i:s",strtotime("Monday last Week",time()));
		echo $lastmonday."<br>";
		$sunday = strtotime("Sunday last Week",time());
		echo $sunday."<br>";
		$lastSunday = date("Y-n-d H:i:s",strtotime("Sunday last Week",time()));
		echo $lastSunday;

	}
}
