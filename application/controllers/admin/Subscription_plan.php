<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subscription_plan extends CI_Controller
{
  public $security;
  public $db;
  public $session;
  public $input;
  public $output;
  public $upload;
  public $form_validation;
  public $auth_model;
  public $subscription_plan_model;

  private $_table = 'subscription_plan';
  private $_context = 'subscription_plan';
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
    $subscription_plan = $this->subscription_plan_model;
    $validation = $this->form_validation;
    $validation->set_rules($subscription_plan->rules('add'));

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

    $subscription_plan->save();

    $this->session->set_flashdata('message_add_' . $this->_context . '_success', 'Successfully added !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function edit($id = null)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    if (!$this->subscription_plan_model->getById($id)) return show_404();

    $data = $this->page_meta_data();

    $auth = $this->auth_model;
    $subscription_plan = $this->subscription_plan_model;
    $validation = $this->form_validation;
    $validation->set_rules($subscription_plan->rules('edit'));

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

    $subscription_plan->update();

    $this->session->set_flashdata('message_edit_' . $this->_context . '_success', 'Successfully updated !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function delete($id)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    $subscription_plan_data = $this->subscription_plan_model->getById($id);

    if (!$subscription_plan_data) return show_404();

    if ($this->input->post('name_confirm_delete') == $subscription_plan_data->name) :

      if ($this->subscription_plan_model->delete($id)) :
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

    $list = $this->subscription_plan_model->get_datatables();
    $result = array();
    $row_start = $this->input->post('start');


    //looping data mahasiswa
    foreach ($list as $subscription_plan) {
      $row_start++;

      $row = array();

      // path to set ajax hit
      $endpoint = $this->_path_prefix . $this->_context . '/ajax_get_by_id/' . $subscription_plan->subscription_plan_id;

      $row[] = $subscription_plan->subscription_plan_id;
      $row[] = $subscription_plan->name;
      $row[] = '<span class="badge badge-pill badge-warning">$' . $subscription_plan->price . '</span>';
      $row[] = '<span class="badge badge-pill badge-info">' . $subscription_plan->concurrent_streams . '</span>';
      $row[] = '<span class="badge badge-pill badge-dark">' . $subscription_plan->content_resolution . '</span>';
      $row[] = htmlspecialchars($subscription_plan->description, ENT_QUOTES, 'UTF-8');
      $row[] = '
        <a id="' . $this->_context . '-edit-' . $subscription_plan->subscription_plan_id . '" type="button" class="btn btn-success btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-edit`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `edit`, ' . $subscription_plan->subscription_plan_id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-edit"><i class="fa fa-edit"></i> Edit</a>
        <a id="' . $this->_context . '-delete-' . $subscription_plan->subscription_plan_id . '" type="button" class="btn btn-danger btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-delete`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `delete`, ' . $subscription_plan->subscription_plan_id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-delete"><i class="fa fa-trash"></i> Delete</a>
      ';

      $result[] = $row;
    }

    $output = array(
      "draw" => $this->input->post('draw'),
      "recordsTotal" => $this->subscription_plan_model->count_all(),
      "recordsFiltered" => $this->subscription_plan_model->count_filtered(),
      "data" => $result,
    );

    $this->output->set_output(json_encode($output));
  }

  public function ajax_get_by_id($id)
  {
    header('Content-Type: application/json');

    $row = $this->subscription_plan_model->getById($id);

    $this->output->set_output(json_encode($row));
  }

  // -------------------------------------------- CALLBACK ---------------------------------------------

  public function unique_name_ignore($name, $excludeId)
  {
    $this->db->where('name', $name);
    $this->db->where('subscription_plan_id !=', $excludeId);
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

    $data["content"]["table"][$this->_context]["columns"] = $this->subscription_plan_model->columns();

    $data["content"]["subscription_plan"]["data"] = $this->subscription_plan_model->getAll();

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
                      <input type="hidden" class="form-control" id="add_subscription_plan_id" name="subscription_plan_id">
                      <div class="form-group">
                        <label for="name">Name *</label>
                        <input type="text" class="form-control" id="add_name" name="name" value="' . set_value("name") . '" placeholder="Enter name" autocomplete="off">
                        ' . form_error("name", "<small id='add_name-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="price">Price *</label>
                        <input type="number" class="form-control" id="add_price" name="price" value="' . set_value("price") . '" placeholder="Enter price" min="0" step=".01" autocomplete="off">
                        ' . form_error("price", "<small id='add_price-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="concurrent_streams">Concurrent Streams *</label>
                        <input type="number" class="form-control" id="add_concurrent_streams" name="concurrent_streams" value="' . set_value("concurrent_streams") . '" min="1" placeholder="Enter Concurrent Streams" autocomplete="off">
                        ' . form_error("concurrent_streams", "<small id='add_concurrent_streams-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label>Content Resolution *</label>
                        <select class="form-control" id="add_content_resolution" name="content_resolution">
                          <option value="HD" ' . set_select("content_resolution", "HD", TRUE) . '>HD</option>
                          <option value="Full HD" ' . set_select("content_resolution", "Full HD") . '>Full HD</option>
                          <option value="4K" ' . set_select("content_resolution", "4K") . '>4K</option>
                        </select>
                        ' . form_error("content_resolution", "<small id='add_content_resolution-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" id="add_description" name="description" rows="3" placeholder="Enter description">' . set_value("description") . '</textarea>
                        ' . form_error("description", "<small id='add_description-error' class='text-danger'>", "</small>") . '
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
                      <input type="hidden" class="form-control" id="edit_subscription_plan_id" name="subscription_plan_id">
                      <div class="form-group">
                        <label for="name">Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" value="' . set_value("name") . '" placeholder="Enter name" autocomplete="off">
                        ' . form_error("name", "<small id='edit_name-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="price">Price *</label>
                        <input type="number" class="form-control" id="edit_price" name="price" value="' . set_value("price") . '" placeholder="Enter price" min="0" step=".01" autocomplete="off">
                        ' . form_error("price", "<small id='edit_price-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="concurrent_streams">Concurrent Streams *</label>
                        <input type="number" class="form-control" id="edit_concurrent_streams" name="concurrent_streams" value="' . set_value("concurrent_streams") . '" min="1" placeholder="Enter Concurrent Streams" autocomplete="off">
                        ' . form_error("concurrent_streams", "<small id='edit_concurrent_streams-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label>Content Resolution *</label>
                        <select class="form-control" id="edit_content_resolution" name="content_resolution">
                          <option value="HD" ' . set_select("content_resolution", "HD", TRUE) . '>HD</option>
                          <option value="Full HD" ' . set_select("content_resolution", "Full HD") . '>Full HD</option>
                          <option value="4K" ' . set_select("content_resolution", "4K") . '>4K</option>
                        </select>
                        ' . form_error("content_resolution", "<small id='edit_content_resolution-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" placeholder="Enter description">' . set_value("description") . '</textarea>
                        ' . form_error("description", "<small id='edit_description-error' class='text-danger'>", "</small>") . '
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
        $field_match = 'name';
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
