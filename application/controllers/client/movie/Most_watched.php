<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Most_watched extends CI_Controller
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
  public $most_watched_model;

  private $_table = 'most_watched';
  private $_context = 'most_watched';
  private $_path_prefix = 'client/movie/';
  private $_file = [];

  public function __construct()
  {
    parent::__construct();

    $this->load->model('client/auth_model');
    if (!$this->auth_model->current_user()) :
      return redirect('client/auth/login');
    endif;

    $this->load->model($this->_path_prefix . $this->_context . '_model');

    $this->load->library(['form_validation', 'pagination']);
  }

  public function index()
  {
    $data = $this->page_meta_data();

    $config["base_url"] = base_url("client/movie/most_watched/page");
    $config["total_rows"] = $this->most_watched_model->get_count();
    $config["per_page"] = 5;

    $this->pagination->initialize($config);

    $page = 0;

    $data["content"]["most_watched"]["links"] = $this->pagination->create_links();

    $data["content"]["most_watched"]["data"] = $this->most_watched_model->getAll($config["per_page"], $page);

    return $this->load->view($data["layout"], $data);
  }

  public function page($slug = 0) {
    if (!is_numeric($slug) || !$slug) return redirect("client/movie/most_watched");

    $data = $this->page_meta_data();

    $config["base_url"] = base_url("client/movie/most_watched/page");
    $config["total_rows"] = $this->most_watched_model->get_count();
    $config["per_page"] = $slug;
    $config["uri_segment"] = 5;

    $this->pagination->initialize($config);

    $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;

    $data["content"]["most_watched"]["links"] = $this->pagination->create_links();

    $data["content"]["most_watched"]["data"] = $this->most_watched_model->getAll($config["per_page"], $page);

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
