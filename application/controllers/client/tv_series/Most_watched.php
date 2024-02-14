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

  private $_table = 'content';
  private $_context = 'most_watched';
  private $_path_prefix = 'client/tv_series/';
  private $_file = [];

  public function __construct()
  {
    parent::__construct();

    $this->load->model('client/auth_model');

    $this->load->model($this->_path_prefix . $this->_context . '_model');

    $this->load->library(['form_validation', 'pagination']);
  }

  public function index()
  {
    $data = $this->page_meta_data();

    $config['base_url'] = base_url("client/tv_series/most_watched");
    $config['total_rows'] = $this->most_watched_model->get_count();
    $config['per_page'] = 12;
    $config['use_page_numbers'] = TRUE;
    $config['page_query_string'] = TRUE;

    $config['cur_tag_open'] = '<li class="active"><a href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    $page = html_escape($this->input->get('per_page')) ?? "1";

    $limit = $config['per_page'];
    $offset = ($page - 1) * $config['per_page'];

    $data["content"]["most_watched"]["links"] = '<ul class="pagination">' . $this->pagination->create_links() . '</ul>';

    $data["content"]["most_watched"]["data"] = $this->most_watched_model->getAll($limit, $offset);

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
