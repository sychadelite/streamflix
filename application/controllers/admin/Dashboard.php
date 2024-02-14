<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
  public $security;
  public $auth_model;

  private $_context = 'dashboard';
  private $_path_prefix = 'admin/';

  public function __construct()
  {
    parent::__construct();

    $this->load->model('admin/auth_model');
    if (!$this->auth_model->current_user()) :
      return redirect('admin/auth/login');
    endif;
  }

  public function index()
  {
    $data = $this->page_meta_data();

    return $this->load->view($data["layout"], $data);
  }

  // -------------------------------------------- PRIVATE ---------------------------------------------

  private function page_meta_data()
  {
    $data["layout"] = 'admin/_index';
    $data["page"] = 'admin/' . $this->_context;
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
