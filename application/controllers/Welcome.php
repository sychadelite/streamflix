<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

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
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 * 
	 * 
	 * References on Project: 
	 * - https://adminlte.io/themes/v3/index.html
	 * - https://petanikode.com/codeigniter-template/
	 */

	public $security;
	public $auth_model;

	private $_context = 'welcome';
	private $_path_prefix = '/';

	public function __construct()
	{
		parent::__construct();

		$this->load->model('client/auth_model');
	}

	public function index()
	{
		$data = $this->page_meta_data();

		return $this->load->view($data["layout"], $data);
	}

	// -------------------------------------------- PRIVATE ---------------------------------------------

	private function page_meta_data()
	{
		$data["layout"] = 'client/_blank';
		$data["page"] = $this->_context;
		$data["parts"] = [];
		$data["context"] = $this->_context;
		$data["path_prefix"] = $this->_path_prefix;

		$data["auth"]["current_user"] = $this->auth_model->current_user();

		$data["content"]["csrf"] = array(
			"name" => $this->security->get_csrf_token_name(),
			"hash" => $this->security->get_csrf_hash()
		);

		return $data;
	}
}
