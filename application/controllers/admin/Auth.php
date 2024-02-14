<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public $security;
  public $config;
  public $session;
  public $db;
  public $auth_model;
  public $form_validation;
  public $input;

  private $_context = '';
  private $_path_prefix = 'auth/';

  public function __construct()
  {
    parent::__construct();

    $this->load->model('admin/auth_model');

    $this->load->library('form_validation');
  }

  public function login()
  {
    if ($this->auth_model->current_user()) :
      return redirect('admin/dashboard');
    endif;
    $data = $this->page_meta_data('login');

    // Clear previous flashdata
    $this->session->set_flashdata('message_validation_error', '');
    $this->session->set_flashdata('message_login_error', '');
    $this->session->set_flashdata('message_login_success', '');

    $rules = $this->auth_model->rules('login');
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == FALSE) {
      if (count($this->input->post()) > 0 && hasNonEmptyPostValues($this->input->post())) :
        $data['validation_errors'] = validation_errors();
        $this->session->set_flashdata('message_validation_error', 'Validation Failed, make sure the email and password are correct!');
      endif;

      return $this->load->view($data["layout"], $data);
    }

    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $remember = $this->input->post('remember');

    if ($this->auth_model->login($email, $password)) {
      // Check if "remember" is set and true
      if ($remember && $remember == 'true') {
        // Set session expiration to a longer duration (e.g., one week)
        $this->config->set_item('sess_expiration', 604800); // 1 week in seconds
      } else {
        // Use the default session expiration time
        $this->config->set_item('sess_expiration', $this->config->item('sess_expiration_default'));
      }

      $this->session->set_flashdata('message_login_success', 'Login Success !');

      return redirect('admin/dashboard');
    } else {
      $this->session->set_flashdata('message_login_error', 'Login Failed, make sure the email and password are correct!');
    }

    return $this->load->view($data["layout"], $data);
  }

  public function register()
  {
    if ($this->auth_model->current_user()) :
      return redirect('admin/dashboard');
    endif;

    $data = $this->page_meta_data('register');

    // Clear previous flashdata
    $this->session->set_flashdata('message_validation_error', '');
    $this->session->set_flashdata('message_register_error', '');
    $this->session->set_flashdata('message_register_success', '');

    $rules = $this->auth_model->rules('register');
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == FALSE) {
      if (count($this->input->post()) > 0 && hasNonEmptyPostValues($this->input->post())) :
        $data['validation_errors'] = validation_errors();
        $this->session->set_flashdata('message_validation_error', 'Validation Failed, make sure the forms are correct!');
      endif;

      return $this->load->view($data["layout"], $data);
    }

    $username = $this->input->post('username');
    $email = $this->input->post('email');
    $password = $this->input->post('password');

    if ($this->auth_model->register($username, $email, $password)) {
      $this->session->set_flashdata('message_register_success', 'Register Success !');

      return redirect('admin/auth/login');
    } else {
      $this->session->set_flashdata('message_register_error', 'Register Failed, make sure the email and password are correct!');
    }

    return $this->load->view($data["layout"], $data);
  }

  public function logout()
  {
    // Clear previous flashdata
    $this->session->set_flashdata('message_logout_success', '');
    
    // Log out call
    $this->auth_model->logout();

    // Set success feedback
    $this->session->set_flashdata('message_logout_success', 'You have logged out!');

    return redirect(site_url());
  }

  // -------------------------------------------- CALLBACK ---------------------------------------------

  public function email_exists($email)
  {
    $this->db->where('email', $email);
    $query = $this->db->get('users');

    return $query->num_rows() > 0;
  }

  // --------------------------------------------- PRIVATE ---------------------------------------------

  private function page_meta_data($context)
  {
    $data["layout"] = 'admin/_blank';
    $data["page"] = 'admin/' . $this->_path_prefix . $context;
    $data["parts"] = [
      "navbar",
      "footer"
    ];
    $data["context"] = $context;
    $data["path_prefix"] = $this->_path_prefix;

    $data["class"]["body"] = $context . '-page';

    $data["auth"]["current_user"] = $this->auth_model->current_user();

    $data["content"]["csrf"] = array(
      "name" => $this->security->get_csrf_token_name(),
      "hash" => $this->security->get_csrf_hash()
    );

    return $data;
  }
}
