<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Recommendations extends CI_Controller
{
  public $security;
  public $db;
  public $session;
  public $input;
  public $output;
  public $upload;
  public $form_validation;
  public $auth_model;
  public $recommendations_model;
  public $user_model;
  public $content_model;

  private $_table = 'recommendations';
  private $_context = 'recommendations';
  private $_path_prefix = 'admin/';
  private $_file = [];

  public function __construct()
  {
    parent::__construct();

    $this->load->model('admin/auth_model');
    if (!$this->auth_model->current_user()) :
      return redirect('admin/auth/login');
    endif;

    $this->load->model($this->_path_prefix . $this->_context . '_model');
    $this->load->model('admin/user_model');
    $this->load->model('admin/content/content_model');

    $this->load->library(['form_validation']);
  }

  public function index()
  {
    $data = $this->page_meta_data();

    return $this->load->view($data["layout"], $data);
  }

  public function add()
  {
    $data = $this->page_meta_data();

    $auth = $this->auth_model;
    $recommendations = $this->recommendations_model;
    $validation = $this->form_validation;
    $validation->set_rules($recommendations->rules('add'));

    if ($validation->run() == FALSE) {
      // Modal to display on first render
      $data['content']['modal'] = [
        "container" => '#modal-' . $this->_context . '-add'
      ];

      if (count($this->input->post()) > 0 && hasNonEmptyPostValues($this->input->post())) :
        $data['validation_errors'] = validation_errors();
        $data["content"]["component"]["modal"]["add"] = $this->renderModal('add', $data);
        $this->session->set_flashdata('message_validation_error', 'Validation Failed, make sure the forms are correct!');
      endif;

      return $this->load->view($data["layout"], $data);
    }

    $recommendations->save();

    $this->session->set_flashdata('message_add_' . $this->_context . '_success', 'Successfully added !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function edit($id = null)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    if (!$this->recommendations_model->getById($id)) return show_404();

    $data = $this->page_meta_data();

    $auth = $this->auth_model;
    $recommendations = $this->recommendations_model;
    $validation = $this->form_validation;
    $validation->set_rules($recommendations->rules('edit'));

    if ($validation->run() == FALSE) {
      // Modal to display on first render
      $data['content']['modal'] = [
        "container" => '#modal-' . $this->_context . '-edit',
        "endpoint" => '/' . $this->_path_prefix . $this->_context . '/ajax_get_by_id/' . $id
      ];

      if (count($this->input->post()) > 0 && hasNonEmptyPostValues($this->input->post())) :
        $data['validation_errors'] = validation_errors();
        $data["content"]["component"]["modal"]["edit"] = $this->renderModal('edit', $data);
        $this->session->set_flashdata('message_validation_error', 'Validation Failed, make sure the forms are correct!');
      endif;

      return $this->load->view($data["layout"], $data);
    }

    $recommendations->update();

    $this->session->set_flashdata('message_edit_' . $this->_context . '_success', 'Successfully updated !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function delete($id)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    $recommendations_data = $this->recommendations_model->getById($id);

    if (!$recommendations_data) return show_404();

    if ($this->input->post('nickname_confirm_delete') == $recommendations_data->nickname) :

      if ($this->recommendations_model->delete($id)) :
        $this->session->set_flashdata('message_delete_' . $this->_context . '_success', 'Successfully deleted !');

        return redirect(site_url($this->_path_prefix . $this->_context));
      endif;

      $this->session->set_flashdata('message_delete_' . $this->_context . '_error', 'Failed to delete data !');

      return redirect($this->_path_prefix . $this->_context);
    endif;

    $this->session->set_flashdata('message_delete_' . $this->_context . '_error', 'Failed to match the confirmation type');

    $data = $this->page_meta_data();

    // Modal to display on first render
    $data['content']['modal'] = [
      "container" => '#modal-' . $this->_context . '-delete',
      "endpoint" => '/' . $this->_path_prefix . $this->_context . '/ajax_get_by_id/' . $id
    ];

    return $this->load->view($data["layout"], $data);
  }

  // ---------------------------------------------- AJAX ---------------------------------------------

  public function ajax_list()
  {
    header('Content-Type: application/json');

    $list = $this->recommendations_model->get_datatables();
    $result = array();
    $row_start = $this->input->post('start');


    //looping data mahasiswa
    foreach ($list as $recommendations) {
      $row_start++;

      $row = array();

      // path to set ajax hit
      $endpoint = $this->_path_prefix . $this->_context . '/ajax_get_by_id/' . $recommendations->recommendation_id;

      $row[] = $recommendations->recommendation_id;
      $row[] = $recommendations->username;
      $row[] = $recommendations->title;
      $row[] = $recommendations->score;
      $row[] = $recommendations->created_at;
      $row[] = '
        <a id="' . $this->_context . '-edit-' . $recommendations->recommendation_id . '" type="button" class="btn btn-success btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-edit`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `edit`, ' . $recommendations->recommendation_id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-edit"><i class="fa fa-edit"></i> Edit</a>
        <a id="' . $this->_context . '-delete-' . $recommendations->recommendation_id . '" type="button" class="btn btn-danger btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-delete`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `delete`, ' . $recommendations->recommendation_id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-delete"><i class="fa fa-trash"></i> Delete</a>
      ';

      $result[] = $row;
    }

    $output = array(
      "draw" => $this->input->post('draw'),
      "recordsTotal" => $this->recommendations_model->count_all(),
      "recordsFiltered" => $this->recommendations_model->count_filtered(),
      "data" => $result,
    );

    $this->output->set_output(json_encode($output));
  }

  public function ajax_get_by_id($id)
  {
    header('Content-Type: application/json');

    $row = $this->recommendations_model->getById($id);

    $this->output->set_output(json_encode($row));
  }

  // -------------------------------------------- CALLBACK ---------------------------------------------

  public function check_available_user($user_id, $content_id)
  {
    $this->db->where('user_id', $user_id);
    $this->db->where('content_id', $content_id);
    $query = $this->db->get($this->_table);

    return $query->num_rows() === 0;
  }

  public function check_available_content($content_id, $user_id)
  {
    $this->db->where('content_id', $content_id);
    $this->db->where('user_id', $user_id);
    $query = $this->db->get($this->_table);

    return $query->num_rows() === 0;
  }

  public function check_available_user_ignore($user_id, $params)
  {
    // Unserialize the parameters into an array
    $params = unserialize($params);
    [$content_id, $recommendation_id] = $params;

    $this->db->where('recommendation_id !=', $recommendation_id);
    $this->db->where('user_id', $user_id);
    $this->db->where('content_id', $content_id);
    $query = $this->db->get($this->_table);

    if ($query->num_rows() === 1) {
      $this->form_validation->set_message('check_available_user_ignore', 'The {field} is already available.');
      return false;
    } else {
      return true;
    }

    return $query->num_rows() === 1;
  }

  public function check_available_content_ignore($content_id, $params)
  {
    // Unserialize the parameters into an array
    $params = unserialize($params);
    [$user_id, $recommendation_id] = $params;

    $this->db->where('recommendation_id !=', $recommendation_id);
    $this->db->where('user_id', $user_id);
    $this->db->where('content_id', $content_id);
    $query = $this->db->get($this->_table);

    if ($query->num_rows() === 1) {
      $this->form_validation->set_message('check_available_content_ignore', 'The {field} is already available.');
      return false;
    } else {
      return true;
    }

    return $query->num_rows() === 1;
  }

  // -------------------------------------------- PRIVATE ---------------------------------------------

  private function page_meta_data()
  {
    $data["layout"] = 'admin/_index';
    $data["page"] = $this->_path_prefix . $this->_context;
    $data["context"] = $this->_context;
    $data["path_prefix"] = $this->_path_prefix;

    $data["auth"]["current_user"] = $this->auth_model->current_user();

    $data["content"]["csrf"] = array(
      "name" => $this->security->get_csrf_token_name(),
      "hash" => $this->security->get_csrf_hash()
    );

    $data["content"]["table"][$this->_context]["columns"] = $this->recommendations_model->columns();

    $data["content"]["recommendations"]["data"] = $this->recommendations_model->getAll();

    $data["content"]["component"]["modal"] = [
      "add" => $this->renderModal('add', $data),
      "edit" => $this->renderModal('edit', $data),
      "delete" => $this->renderModal('delete', $data)
    ];

    $data["content"]["files"] = $this->_file;

    return $data;
  }

  private function renderModal($name, $data)
  {
    $list_content = $this->content_model->getAll();
    $list_user = $this->user_model->getAll();

    $option = [
      "content" => null,
      "user" => null
    ];

    for ($i = 0; $i < count($list_user); $i++) {
      $row = $list_user[$i];
      $selected = set_select("user_id", $row->id);
      $option["user"] .= '<option value="' . $row->id . '" ' . $selected . '>' . $row->username . '</option>';
    }

    for ($i = 0; $i < count($list_content); $i++) {
      $row = $list_content[$i];
      $selected = set_select("content_id", $row->content_id);
      $option["content"] .= '<option value="' . $row->content_id . '" ' . $selected . '>' . $row->title . '</option>';
    }

    switch ($name) {
      case 'add':
        return '
          <div class="modal fade" id="modal-' . $this->_context . '-add">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title text-capitalize">add ' . formatColumnName($this->_context) . '</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="modal-' . $this->_context . '-add-form" action="' . base_url($this->_path_prefix . $this->_context . "/add") . '" method="post" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="' . $data["content"]["csrf"]["name"] . '" value="' . $data["content"]["csrf"]["hash"] . '">
                    <div class="card-body">
                      <input type="hidden" class="form-control" id="add_recommendation_id" name="recommendation_id">
                      <div class="form-group">
                        <label>User *</label>
                        <select class="form-control" id="add_user_id" name="user_id" placeholder="asd">
                          <option value="" disabled selected>Select user</option>
                          ' . $option["user"] . '
                        </select>
                        ' . form_error('user_id', '<small id="add_user_id-error" class="text-danger">', '</small>') . '
                      </div>
                      <div class="form-group">
                        <label>Content *</label>
                        <select class="form-control" id="add_content_id" name="content_id">
                          <option value="" disabled selected>Select content</option>
                          ' . $option["content"] . '
                        </select>
                        ' . form_error('content_id', '<small id="add_content_id-error" class="text-danger">', '</small>') . '
                      </div>
                      <div class="form-group">
                        <label for="score">Score <small>( 1 - 10 )</small> *</label>
                        <input type="number" class="form-control" id="add_score" name="score" value="' . set_value("score") . '" placeholder="Enter score" min="0" max="10" step=".01" autocomplete="off">
                        ' . form_error("score", "<small id='add_score-error' class='text-danger'>", "</small>") . '
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" form="modal-' . $this->_context . '-add-form">Save changes</button>
                </div>
              </div>
            </div>
          </div>
        ';
        break;

      case 'edit':
        return '
          <div class="modal fade" id="modal-' . $this->_context . '-edit">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Edit ' . formatColumnName($this->_context) . '</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="modal-' . $this->_context . '-edit-form" action="' . base_url($this->_path_prefix . $this->_context . "/edit") . '" method="post" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="' . $data["content"]["csrf"]["name"] . '" value="' . $data["content"]["csrf"]["hash"] . '">
                    <div class="card-body">
                      <input type="hidden" class="form-control" id="edit_recommendation_id" name="recommendation_id">
                      <div class="form-group">
                        <label>User *</label>
                        <select class="form-control" id="add_user_id" name="user_id">
                          <option value="" disabled selected>Select user</option>
                          ' . $option["user"] . '
                        </select>
                        ' . form_error('user_id', '<small id="add_user_id-error" class="text-danger">', '</small>') . '
                      </div>
                      <div class="form-group">
                        <label>Content *</label>
                        <select class="form-control" id="edit_content_id" name="content_id">
                          <option value="" disabled selected>Select content</option>
                          ' . $option["content"] . '
                        </select>
                        ' . form_error('content_id', '<small id="edit_content_id-error" class="text-danger">', '</small>') . '
                      </div>
                      <div class="form-group">
                        <label for="score">Score <small>( 1 - 10 )</small> *</label>
                        <input type="number" class="form-control" id="edit_score" name="score" value="' . set_value("score") . '" placeholder="Enter score" min="0" max="10" step=".01" autocomplete="off">
                        ' . form_error("score", "<small id='edit_score-error' class='text-danger'>", "</small>") . '
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" form="modal-' . $this->_context . '-edit-form">Save changes</button>
                </div>
              </div>
            </div>
          </div>
        ';
        break;

      case 'delete':
        $field_match = 'recommendation_id';
        return '
          <div class="modal fade" id="modal-' . $this->_context . '-delete">
            <div class="modal-dialog">
              <div class="modal-content bg-danger">
                <div class="modal-header">
                  <h4 class="modal-title">Delete ' . formatColumnName($this->_context) . '</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="modal-' . $this->_context . '-delete-form" action="' . base_url($this->_path_prefix . $this->_context . "/delete/{uid}") . '" method="post" autocomplete="off">
                    <input type="hidden" name="' . $data['content']['csrf']['name'] . '" value="' . $data['content']['csrf']['hash'] . '">
                    <div class="card-body">
                      <h5>Are you sure want to delete this ' . formatColumnName($this->_context) . ' ?</h5>
                      <p>' . formatColumnName($this->_context) . ' <span class="' . $field_match . '-label font-weight-bold">...</span> will be deleted and this action cannot be undo !</p>
                      <div class="form-group">
                        <label for="' . $field_match . '_confirm_delete">To confirm, type "<strong class="' . $field_match . '-label">...</strong>" in the box below</label>
                        <input type="text" class="form-control" id="delete_' . $field_match . '" name="' . $field_match . '_confirm_delete" value="' . set_value($field_match . '_confirm_delete') . '" placeholder="Enter ' . strtolower(formatColumnName($field_match)) . '" autocomplete="off">
                        ' . form_error('' . $field_match . '_confirm_delete', '<small id="delete_' . $field_match . '-error" class="text-danger">', '</small>') . '
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-outline-light" form="modal-' . $this->_context . '-delete-form">Delete</button>
                </div>
              </div>
            </div>
          </div>
        ';
        break;

      default:
        return '';
        break;
    }
  }
}
