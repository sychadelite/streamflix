<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Director extends CI_Controller
{
  public $security;
  public $db;
  public $session;
  public $input;
  public $output;
  public $upload;
  public $form_validation;
  public $auth_model;
  public $director_model;

  private $_table = 'director';
  private $_context = 'director';
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
    $director = $this->director_model;
    $validation = $this->form_validation;
    $validation->set_rules($director->rules('add'));

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

    $director->save();

    $this->session->set_flashdata('message_add_' . $this->_context . '_success', 'Successfully added !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function edit($id = null)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    if (!$this->director_model->getById($id)) return show_404();

    $data = $this->page_meta_data();

    $auth = $this->auth_model;
    $director = $this->director_model;
    $validation = $this->form_validation;
    $validation->set_rules($director->rules('edit'));

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

    $director->update();

    $this->session->set_flashdata('message_edit_' . $this->_context . '_success', 'Successfully updated !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function delete($id)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    $director_data = $this->director_model->getById($id);

    if (!$director_data) return show_404();

    if ($this->input->post('nickname_confirm_delete') == $director_data->nickname) :

      if ($this->director_model->delete($id)) :
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

    $list = $this->director_model->get_datatables();
    $result = array();
    $row_start = $this->input->post('start');


    //looping data mahasiswa
    foreach ($list as $director) {
      $row_start++;

      $row = array();

      // path to set ajax hit
      $endpoint = $this->_path_prefix . $this->_context . '/ajax_get_by_id/' . $director->director_id;

      $row[] = $director->director_id;
      $row[] = $director->nickname;
      $row[] = $director->first_name;
      $row[] = $director->last_name;
      $row[] = '
        <a id="' . $this->_context . '-edit-' . $director->director_id . '" type="button" class="btn btn-success btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-edit`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `edit`, ' . $director->director_id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-edit"><i class="fa fa-edit"></i> Edit</a>
        <a id="' . $this->_context . '-delete-' . $director->director_id . '" type="button" class="btn btn-danger btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-delete`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `delete`, ' . $director->director_id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-delete"><i class="fa fa-trash"></i> Delete</a>
      ';

      $result[] = $row;
    }

    $output = array(
      "draw" => $this->input->post('draw'),
      "recordsTotal" => $this->director_model->count_all(),
      "recordsFiltered" => $this->director_model->count_filtered(),
      "data" => $result,
    );

    $this->output->set_output(json_encode($output));
  }

  public function ajax_get_by_id($id)
  {
    header('Content-Type: application/json');

    $row = $this->director_model->getById($id);

    $this->output->set_output(json_encode($row));
  }

  // -------------------------------------------- CALLBACK ---------------------------------------------

  public function unique_nickname_ignore($nickname, $excludeId)
  {
    $this->db->where('nickname', $nickname);
    $this->db->where('director_id !=', $excludeId);
    $query = $this->db->get($this->_table);

    return $query->num_rows() === 0;
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

    $data["content"]["table"][$this->_context]["columns"] = $this->director_model->columns();

    $data["content"]["director"]["data"] = $this->director_model->getAll();

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
                      <input type="hidden" class="form-control" id="add_director_id" name="director_id">
                      <div class="form-group">
                        <label for="nickname">Nickname *</label>
                        <input type="text" class="form-control" id="add_nickname" name="nickname" value="' . set_value("nickname") . '" placeholder="Enter nickname" autocomplete="off">
                        ' . form_error("nickname", "<small id='add_nickname-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" class="form-control" id="add_first_name" name="first_name" value="' . set_value("first_name") . '" placeholder="Enter first name" autocomplete="off">
                            ' . form_error("first_name", "<small id='add_first_name-error' class='text-danger'>", "</small>") . '
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" class="form-control" id="add_last_name" name="last_name" value="' . set_value("last_name") . '" placeholder="Enter last name" autocomplete="off">
                            ' . form_error("last_name", "<small id='add_last_name-error' class='text-danger'>", "</small>") . '
                          </div>
                        </div>
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
                      <input type="hidden" class="form-control" id="edit_director_id" name="director_id">
                      <div class="form-group">
                        <label for="nickname">Nickname *</label>
                        <input type="text" class="form-control" id="edit_nickname" name="nickname" value="' . set_value("nickname") . '" placeholder="Enter nickname" autocomplete="off">
                        ' . form_error("nickname", "<small id='edit_nickname-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" value="' . set_value("first_name") . '" placeholder="Enter first name" autocomplete="off">
                            ' . form_error("first_name", "<small id='edit_first_name-error' class='text-danger'>", "</small>") . '
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" value="' . set_value("last_name") . '" placeholder="Enter last name" autocomplete="off">
                            ' . form_error("last_name", "<small id='edit_last_name-error' class='text-danger'>", "</small>") . '
                          </div>
                        </div>
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
        $field_match = 'nickname';
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
                        <input type="text" class="form-control" id="delete_' . $field_match . '" name="' . $field_match . '_confirm_delete" value="' . set_value($field_match . '_confirm_delete') . '" placeholder="Enter ' . $field_match . '" autocomplete="off">
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
