<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Year extends CI_Controller
{
  public $security;
  public $auth_model;

  private $_context = 'year';
  private $_path_prefix = 'client/';

  public function __construct()
  {
    parent::__construct();

    $this->load->model($this->_path_prefix . $this->_context . '_model');

    $this->load->model('client/auth_model');
  }

  public function index()
  {
    $data = $this->page_meta_data();

    $data["content"]["year"]["data"] = $this->year_model->getAll();

    return $this->load->view($data["layout"], $data);
  }

  // -------------------------------------------- PRIVATE ---------------------------------------------

  private function page_meta_data()
  {
    $data["layout"] = 'client/_index';
    $data["page"] = $this->_path_prefix . '/' . $this->_context;
    $data["parts"] = [
      "navbar",
      "footer"
    ];
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
