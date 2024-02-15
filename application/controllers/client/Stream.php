<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stream extends CI_Controller
{
  public $security;
  public $db;
  public $session;
  public $uri;
  public $input;
  public $output;
  public $upload;
  public $form_validation;
  public $pagination;
  public $auth_model;
  public $stream_model;

  private $_table = 'content';
  private $_context = 'stream';
  private $_path_prefix = 'client/';
  private $_file = [];

  public function __construct()
  {
    parent::__construct();

    $this->load->model('client/auth_model');

    $this->load->model($this->_path_prefix . $this->_context . '_model');

    $this->load->library(['form_validation', 'pagination']);
  }

  public function index($slug = "")
  {
    if (!$slug) return redirect("client/home");

    $data = $this->page_meta_data();

    $data["content"]["stream"]["data"] = $this->stream_model->getById($slug);

    return $this->load->view($data["layout"], $data);
  }

  // -------------------------------------------- PRIVATE ---------------------------------------------

  private function page_meta_data()
  {
    $data["layout"] = 'client/_index';
    $data["page"] = $this->_path_prefix . $this->_context;
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
