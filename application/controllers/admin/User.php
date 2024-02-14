<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
  public $security;
  public $db;
  public $session;
  public $input;
  public $output;
  public $upload;
  public $form_validation;
  public $auth_model;
  public $user_model;
  public $subscription_plan_model;

  private $_table = 'users';
  private $_context = 'user';
  private $_path_prefix = 'admin/';
  private $_file = ['avatar'];

  public function __construct()
  {
    parent::__construct();

    $this->load->model('admin/auth_model');
    if (!$this->auth_model->current_user()) :
      return redirect('admin/auth/login');
    endif;

    $this->load->model($this->_path_prefix . $this->_context . '_model');
    $this->load->model('admin/subscription_plan_model');

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
    $user = $this->user_model;
    $validation = $this->form_validation;
    $validation->set_rules($user->rules('add'));

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

    if (!empty($_FILES[$this->_file[0]]['name'])) :
      $file_field = $this->_file[0];

      $config['upload_path']          = FCPATH . '/assets/img/' . $file_field . '/';
      $config['allowed_types']        = 'gif|jpg|jpeg|png';
      $config['overwrite']            = true;
      $config['max_size']             = 1024; // 1MB
      $config['file_name']            = md5($file_field . time() . $auth->current_user()->id);

      $uploadFile = $this->file_upload($config, $file_field);

      if ($uploadFile['status']) {
        // Bind path to table field
        $user->avatar = $uploadFile['data']['relative_path'];
      } else {
        $this->session->set_flashdata('message_upload_file_error', $uploadFile['message'] . '<br>' . '<code class="text-white">' . $uploadFile['data'] . '</code>');
      }
    endif;

    $user->save();

    $this->session->set_flashdata('message_add_' . $this->_context . '_success', 'Successfully added !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function edit($id = null)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    if (!$this->user_model->getById($id)) return show_404();

    $data = $this->page_meta_data();

    $auth = $this->auth_model;
    $user = $this->user_model;
    $validation = $this->form_validation;
    $validation->set_rules($user->rules('edit'));

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

    if (!empty($_FILES[$this->_file[0]]['name'])) :
      $file_field = $this->_file[0];

      $config['upload_path']          = FCPATH . '/assets/img/' . $file_field . '/';
      $config['allowed_types']        = 'gif|jpg|jpeg|png';
      $config['overwrite']            = true;
      $config['max_size']             = 1024; // 1MB
      $config['file_name']            = md5($file_field . time() . $auth->current_user()->id);

      $uploadFile = $this->file_upload($config, $file_field);

      if ($uploadFile['status']) {
        $old_path = $user->getById($id)->avatar;

        if ($old_path) :
          $old_file_path = FCPATH . $old_path;
          // Perform remove of old path
          $removeFile = $this->removeFile($old_file_path);
          if (!$removeFile['status']) :
            $this->session->set_flashdata('message_remove_file_error', $removeFile['message']);
          endif;
        endif;

        // Bind path to table field
        $user->avatar = $uploadFile['data']['relative_path'];
      } else {
        $this->session->set_flashdata('message_upload_file_error', $uploadFile['message'] . '<br>' . '<code class="text-white">' . $uploadFile['data'] . '</code>');
      }
    endif;

    $user->update();

    $this->session->set_flashdata('message_edit_' . $this->_context . '_success', 'Successfully updated !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function delete($id)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    $user_data = $this->user_model->getById($id);

    if (!$user_data) return show_404();

    if ($this->input->post('username_confirm_delete') == $user_data->username) :

      $old_file_path = FCPATH . $user_data->avatar;
      // Perform remove of old path
      $removeFile = $this->removeFile($old_file_path);
      if (!$removeFile['status']) :
        $this->session->set_flashdata('message_remove_file_error', $removeFile['message']);
      endif;

      if ($this->user_model->delete($id)) :
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

    $list = $this->user_model->get_datatables();
    $result = array();
    $row_start = $this->input->post('start');

    //looping data mahasiswa
    foreach ($list as $user) {
      $row_start++;

      $row = array();

      // path to set ajax hit
      $endpoint = $this->_path_prefix . $this->_context . '/ajax_get_by_id/' . $user->id;

      $row[] = $user->id;
      $row[] = '
        <div class="d-flex flex-nowrap align-items-center" style="gap: 8px;">
          <div style="width: 3rem; height: 3rem; border-radius: 9999px; overflow: hidden;">
            <img src="' . ($user->avatar ?? base_url('assets/vendor/AdminLTE/dist/img/avatar.png')) . '" alt="" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" />
          </div>
          <p class="mb-0">' . $user->username . '</p>
        </div>
      ';
      $row[] = $user->email;
      $row[] = $user->role == 'admin' ? '<span class="badge badge-pill badge-info">' . $user->role . '</span>' : '<span class="badge badge-pill badge-secondary">' . $user->role . '</span>';
      $row[] = $user->active;
      $row[] = $user->subscription_plan_name;
      $row[] = $user->created_at;
      $row[] = $user->last_login;
      $row[] = '
        <a id="' . $this->_context . '-edit-' . $user->id . '" type="button" class="btn btn-success btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-edit`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `edit`, ' . $user->id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-edit"><i class="fa fa-edit"></i> Edit</a>
        <a id="' . $this->_context . '-delete-' . $user->id . '" type="button" class="btn btn-danger btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-delete`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `delete`, ' . $user->id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-delete"><i class="fa fa-trash"></i> Delete</a>
      ';

      $result[] = $row;
    }

    $output = array(
      "draw" => $this->input->post('draw'),
      "recordsTotal" => $this->user_model->count_all(),
      "recordsFiltered" => $this->user_model->count_filtered(),
      "data" => $result
    );

    $this->output->set_output(json_encode($output));
  }

  public function ajax_get_by_id($id)
  {
    header('Content-Type: application/json');

    $row = $this->user_model->getById($id);

    $this->output->set_output(json_encode($row));
  }

  // -------------------------------------------- CALLBACK ---------------------------------------------

  public function unique_username_ignore($username, $excludeId)
  {
    $this->db->where('username', $username);
    $this->db->where('id !=', $excludeId); // Exclude the specified ID
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

    $data["content"]["table"][$this->_context]["columns"] = $this->user_model->columns();

    $data["content"]["subscription_plan"]["data"] = $this->subscription_plan_model->getAll();

    $data["content"]["component"]["modal"] = [
      "add" => $this->renderModal('add', $data),
      "edit" => $this->renderModal('edit', $data),
      "delete" => $this->renderModal('delete', $data)
    ];

    $data["content"]["files"] = $this->_file;

    return $data;
  }

  private function file_upload($config = array(), $file_field = 'upload')
  {
    $default_upload_path = FCPATH . '/assets/img/upload/';

    $upload_path = isset($config['upload_path']) ? $config['upload_path'] : $default_upload_path;

    // Ensure upload directory exists or created
    if (!$this->ensure_upload_directory_exists($upload_path)) :
      return [
        "status" => FALSE,
        "message" => 'Failed to create upload directory !'
      ];
    endif;

    $this->load->library('upload', $config);

    // Perform file upload to server
    if (!$this->upload->do_upload($file_field)) :
      return [
        "status" => FALSE,
        "message" => 'Failed on perform file upload to server !',
        "data" => $this->upload->display_errors()
      ];
    endif;

    $uploaded_data = $this->upload->data();

    $fcpath = str_replace('\\', '/', FCPATH);

    $uploaded_data['relative_path'] = '/' . str_replace($fcpath, '', $uploaded_data['full_path']);

    return [
      "status" => TRUE,
      "message" => 'File uploaded',
      "data" => $uploaded_data
    ];
  }

  private function removeFile($file_path)
  {
    if (file_exists($file_path)) {
      if (unlink($file_path)) {
        return [
          "status" => TRUE,
          "message" => "Success remove the file"
        ];
      } else {
        return [
          "status" => FALSE,
          "message" => "Failed to unlink the existed file"
        ];
      }
    } else {
      return [
        "status" => TRUE,
        "message" => "File to remove didn't exists"
      ];
    }
  }

  private function ensure_upload_directory_exists($upload_path)
  {
    // Check if the upload directory exists, if not, create it
    if (!is_dir($upload_path)) {
      // Create the directory with 0777 permissions (you may adjust this as needed)
      mkdir($upload_path, 0777, true);

      // Check if directory creation failed
      if (!is_dir($upload_path)) {
        return false;
      }
    }

    return true;
  }

  private function renderModal($name, $data)
  {
    $option = [
      "subscription_plan" => null
    ];

    for ($i = 0; $i < count($data["content"]["subscription_plan"]["data"]); $i++) {
      $row = $data["content"]["subscription_plan"]["data"][$i];
      $selected = set_select("subscription_plan_id", $row->subscription_plan_id);
      $option["subscription_plan"] .= '<option value="' . $row->subscription_plan_id . '" ' . $selected . '>' . $row->name . '</option>';
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
                      <input type="hidden" class="form-control" id="add_id" name="id">
                      <div class="form-group">
                        <label for="email">Email address *</label>
                        <input type="email" class="form-control" id="add_email" name="email" value="' . set_value("email") . '" placeholder="Enter email" autocomplete="off">
                        ' . form_error("email", "<small id='add_email-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" class="form-control" id="add_username" name="username" value="' . set_value("username") . '" placeholder="Enter username" autocomplete="off">
                        ' . form_error("username", "<small id='add_username-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" class="form-control" id="add_password" name="password" value="' . set_value("password") . '" placeholder="Password" autocomplete="off">
                        <small>leave blank if you don"t want to change<br></small>
                        ' . form_error("password", "<small id='add_password-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="' . $this->_file[0] . '">' . formatColumnName($this->_file[0]) . '</label>
                        <div class="d-flex flex-wrap align-items-end" style="gap: 6px;">
                          <div class="preview ' . $this->_file[0] . ' mb-2" style="display: none;  width: 7rem; height: 7rem; border-radius: 9999px; overflow: hidden;">
                            <img src="#" alt="picture" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" />
                          </div>
                          <div class="preview ' . $this->_file[0] . ' mb-2" style="display: none;  width: 5rem; height: 5rem; border-radius: 9999px; overflow: hidden;">
                            <img src="#" alt="picture" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" />
                          </div>
                          <div class="preview ' . $this->_file[0] . ' mb-2" style="display: none;  width: 3rem; height: 3rem; border-radius: 9999px; overflow: hidden;">
                            <img src="#" alt="picture" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" />
                          </div>
                          <div class="preview ' . $this->_file[0] . ' mb-2" style="display: none;  width: 2rem; height: 2rem; border-radius: 9999px; overflow: hidden;">
                            <img src="#" alt="picture" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" />
                          </div>
                        </div>
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" accept="image/gif,image/jpg,image/jpeg,image/png" class="custom-file-input" id="add_' . $this->_file[0] . '" name="' . $this->_file[0] . '" value="' . set_value($this->_file[0]) . '">
                            <label class="custom-file-label" for="' . $this->_file[0] . '">Choose ' . strtolower(formatColumnName($this->_file[0])) . '</label>
                          </div>
                        </div>
                        <small>allowed type: gif, jpg, jpeg, png | max 1mb<br></small>
                        ' . form_error($this->_file[0], '<small id="add_' . $this->_file[0] . '-error" class="text-danger">', '</small>') . '
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Subscription</label>
                            <select class="form-control" id="add_subscription_plan_id" name="subscription_plan_id">
                              <option value="" ' . set_select("subscription_plan_id", "", TRUE) . '>N/A</option>
                              ' . $option["subscription_plan"] . '
                            </select>
                            ' . form_error("subscription_plan_id", "<small id='add_subscription_plan_id-error' class='text-danger'>", "</small>") . '
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Role *</label>
                            <select class="form-control" id="add_role" name="role">
                              <option value="0" ' . set_select("role", 0, TRUE) . '>User</option>
                              <option value="1" ' . set_select("role", 1) . '>Admin</option>
                            </select>
                            ' . form_error("role", "<small id='add_role-error' class='text-danger'>", "</small>") . '
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="add_active" name="active" value="1" ' . set_checkbox("active", "1", FALSE) . '>
                          <label for="add_active" class="custom-control-label">Active</label>
                        </div>
                        ' . form_error("active", "<small id='add_active-error' class='text-danger'>", "</small>") . '
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
                  <form id="modal-' . $this->_context . '-edit-form" action="' . base_url($this->_path_prefix . $this->_context . "/edit/{uid}") . '" method="post" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="' . $data['content']['csrf']['name'] . '" value="' . $data['content']['csrf']['hash'] . '">
                    <div class="card-body">
                      <input type="hidden" class="form-control" id="edit_id" name="id">
                      <div class="form-group">
                        <label for="email">Email address *</label>
                        <input type="email" class="form-control" id="edit_email" name="email" value="' . set_value('email') . '" placeholder="Enter email" autocomplete="off" disabled>
                      </div>
                      <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" class="form-control" id="edit_username" name="username" value="' . set_value('username') . '" placeholder="Enter username" autocomplete="off">
                        ' . form_error('username', '<small id="edit_username-error" class="text-danger">', '</small>') . '
                      </div>
                      <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password" value="' . set_value('password') . '" placeholder="Password" autocomplete="off">
                        <small>leave blank if you don\'t want to change<br></small>
                        ' . form_error('password', '<small id="edit_password-error" class="text-danger">', '</small>') . '
                      </div>
                      <div class="form-group">
                        <label for="' . $this->_file[0] . '">' . formatColumnName($this->_file[0]) . '</label>
                        <div class="d-flex flex-wrap align-items-end" style="gap: 6px;">
                          <div class="preview ' . $this->_file[0] . ' mb-2" style="display: none;  width: 7rem; height: 7rem; border-radius: 9999px; overflow: hidden;">
                            <img src="#" alt="picture" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" />
                          </div>
                          <div class="preview ' . $this->_file[0] . ' mb-2" style="display: none;  width: 5rem; height: 5rem; border-radius: 9999px; overflow: hidden;">
                            <img src="#" alt="picture" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" />
                          </div>
                          <div class="preview ' . $this->_file[0] . ' mb-2" style="display: none;  width: 3rem; height: 3rem; border-radius: 9999px; overflow: hidden;">
                            <img src="#" alt="picture" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" />
                          </div>
                          <div class="preview ' . $this->_file[0] . ' mb-2" style="display: none;  width: 2rem; height: 2rem; border-radius: 9999px; overflow: hidden;">
                            <img src="#" alt="picture" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" />
                          </div>
                        </div>
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" accept="image/gif,image/jpg,image/jpeg,image/png" class="custom-file-input" id="edit_' . $this->_file[0] . '" name="' . $this->_file[0] . '" value="' . set_value($this->_file[0]) . '">
                            <label class="custom-file-label" for="' . $this->_file[0] . '">Choose ' . strtolower(formatColumnName($this->_file[0])) . '</label>
                          </div>
                        </div>
                        <small>allowed type: gif, jpg, jpeg, png | max 1mb<br></small>
                        ' . form_error($this->_file[0], '<small id="edit_' . $this->_file[0] . '-error" class="text-danger">', '</small>') . '
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Subscription</label>
                            <select class="form-control" id="edit_subscription_plan_id" name="subscription_plan_id">
                              <option value="" ' . set_select('subscription_plan_id', '', TRUE) . '>N/A</option>
                              ' . $option["subscription_plan"] . '
                            </select>
                            ' . form_error('subscription_plan_id', '<small id="edit_subscription_plan_id-error" class="text-danger">', '</small>') . '
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Role *</label>
                            <select class="form-control" id="edit_role" name="role">
                              <option value="0" ' . set_select('role', 0, TRUE) . '>User</option>
                              <option value="1" ' . set_select('role', 1) . '>Admin</option>
                            </select>
                            ' . form_error('role', '<small id="edit_role-error" class="text-danger">', '</small>') . '
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="edit_active" name="active" value="1" ' . set_checkbox('active', '1', FALSE) . '>
                          <label for="edit_active" class="custom-control-label">Active</label>
                        </div>
                        ' . form_error('active', '<small id="edit_active-error" class="text-danger">', '</small>') . '
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
        $field_match = 'username';
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
