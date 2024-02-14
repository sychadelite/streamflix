<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Genre extends CI_Controller
{
  public $security;
  public $db;
  public $session;
  public $input;
  public $output;
  public $upload;
  public $form_validation;
  public $auth_model;
  public $genre_model;

  private $_table = 'genre';
  private $_context = 'genre';
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
    $genre = $this->genre_model;
    $validation = $this->form_validation;
    $validation->set_rules($genre->rules('add'));

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

    $genre->save();

    $this->session->set_flashdata('message_add_' . $this->_context . '_success', 'Successfully added !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function edit($id = null)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    if (!$this->genre_model->getById($id)) return show_404();

    $data = $this->page_meta_data();

    $auth = $this->auth_model;
    $genre = $this->genre_model;
    $validation = $this->form_validation;
    $validation->set_rules($genre->rules('edit'));

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

    $genre->update();

    $this->session->set_flashdata('message_edit_' . $this->_context . '_success', 'Successfully updated !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function delete($id)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    $genre_data = $this->genre_model->getById($id);

    if (!$genre_data) return show_404();

    if ($this->input->post('genre_name_confirm_delete') == $genre_data->genre_name) :

      if ($this->genre_model->delete($id)) :
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

    $list = $this->genre_model->get_datatables();
    $result = array();
    $row_start = $this->input->post('start');


    //looping data mahasiswa
    foreach ($list as $genre) {
      $row_start++;

      $row = array();

      // path to set ajax hit
      $endpoint = $this->_path_prefix . $this->_context . '/ajax_get_by_id/' . $genre->genre_id;

      $row[] = $genre->genre_id;
      $row[] = $genre->genre_name;
      $row[] = '
        <a id="' . $this->_context . '-edit-' . $genre->genre_id . '" type="button" class="btn btn-success btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-edit`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `edit`, ' . $genre->genre_id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-edit"><i class="fa fa-edit"></i> Edit</a>
        <a id="' . $this->_context . '-delete-' . $genre->genre_id . '" type="button" class="btn btn-danger btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-delete`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `delete`, ' . $genre->genre_id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-delete"><i class="fa fa-trash"></i> Delete</a>
      ';

      $result[] = $row;
    }

    $output = array(
      "draw" => $this->input->post('draw'),
      "recordsTotal" => $this->genre_model->count_all(),
      "recordsFiltered" => $this->genre_model->count_filtered(),
      "data" => $result,
    );

    $this->output->set_output(json_encode($output));
  }

  public function ajax_get_by_id($id)
  {
    header('Content-Type: application/json');

    $row = $this->genre_model->getById($id);

    $this->output->set_output(json_encode($row));
  }

  // -------------------------------------------- CALLBACK ---------------------------------------------

  public function unique_genre_name_ignore($genre_name, $excludeId)
  {
    $this->db->where('genre_name', $genre_name);
    $this->db->where('genre_id !=', $excludeId);
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

    $data["content"]["table"][$this->_context]["columns"] = $this->genre_model->columns();

    $data["content"]["genre"]["data"] = $this->genre_model->getAll();

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
                      <input type="hidden" class="form-control" id="add_genre_id" name="genre_id">
                      <div class="form-group">
                        <label for="genre_name">Genre Name *</label>
                        <input type="text" class="form-control" id="add_genre_name" name="genre_name" value="' . set_value("genre_name") . '" placeholder="Enter genre name" autocomplete="off">
                        ' . form_error("genre_name", "<small id='add_genre_name-error' class='text-danger'>", "</small>") . '
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
                      <input type="hidden" class="form-control" id="edit_genre_id" name="genre_id">
                      <div class="form-group">
                        <label for="genre_name">Genre Name *</label>
                        <input type="text" class="form-control" id="edit_genre_name" name="genre_name" value="' . set_value("genre_name") . '" placeholder="Enter genre name" autocomplete="off">
                        ' . form_error("genre_name", "<small id='edit_genre_name-error' class='text-danger'>", "</small>") . '
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
        $field_match = 'genre_name';
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
