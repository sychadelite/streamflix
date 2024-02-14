<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Movie extends CI_Controller
{
  public $security;
  public $db;
  public $session;
  public $input;
  public $output;
  public $upload;
  public $form_validation;
  public $auth_model;
  public $actor_model;
  public $movie_model;

  private $_table = 'content';
  private $_context = 'movie';
  private $_path_prefix = 'admin/content/film/';
  private $_file = ['cover_image'];

  public function __construct()
  {
    parent::__construct();

    $this->load->model('admin/auth_model');
    if (!$this->auth_model->current_user()) :
      return redirect('admin/auth/login');
    endif;

    $this->load->model($this->_path_prefix . $this->_context . '_model');
    $this->load->model('admin/actor_model');

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
    $movie = $this->movie_model;
    $validation = $this->form_validation;
    $validation->set_rules($movie->rules('add'));

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
        $movie->cover_image = $uploadFile['data']['relative_path'];
      } else {
        $this->session->set_flashdata('message_upload_file_error', $uploadFile['message'] . '<br>' . '<code class="text-white">' . $uploadFile['data'] . '</code>');
      }
    endif;

    $movie->save();

    $this->session->set_flashdata('message_add_' . $this->_context . '_success', 'Successfully added !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function edit($id = null)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    if (!$this->movie_model->getById($id)) return show_404();

    $data = $this->page_meta_data();

    $auth = $this->auth_model;
    $movie = $this->movie_model;
    $validation = $this->form_validation;
    $validation->set_rules($movie->rules('edit'));

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
        $old_path = $movie->getById($id)->cover_image;

        if ($old_path) :
          $old_file_path = FCPATH . $old_path;
          // Perform remove of old path
          $removeFile = $this->removeFile($old_file_path);
          if (!$removeFile['status']) :
            $this->session->set_flashdata('message_remove_file_error', $removeFile['message']);
          endif;
        endif;

        // Bind path to table field
        $movie->cover_image = $uploadFile['data']['relative_path'];
      } else {
        $this->session->set_flashdata('message_upload_file_error', $uploadFile['message'] . '<br>' . '<code class="text-white">' . $uploadFile['data'] . '</code>');
      }
    endif;

    $movie->update();

    $this->session->set_flashdata('message_edit_' . $this->_context . '_success', 'Successfully updated !');

    return redirect($this->_path_prefix . $this->_context);
  }

  public function delete($id)
  {
    if (!isset($id)) return redirect($this->_path_prefix . $this->_context);

    $movie_data = $this->movie_model->getById($id);

    if (!$movie_data) return show_404();

    if ($this->input->post('name_confirm_delete') == $movie_data->name) :

      if ($this->movie_model->delete($id)) :
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

    $list = $this->movie_model->get_datatables();
    $result = array();
    $row_start = $this->input->post('start');


    //looping data mahasiswa
    foreach ($list as $movie) {
      $row_start++;

      $row = array();

      // path to set ajax hit
      $endpoint = $this->_path_prefix . $this->_context . '/ajax_get_by_id/' . $movie->content_id;

      $row[] = $movie->content_id;
      $row[] = $movie->title;
      $row[] = $movie->release_year;
      $row[] = $movie->duration;
      $row[] = $movie->description;
      $row[] = $movie->cast;
      $row[] = '
        <div style="width: 7rem; height: 7rem; border-radius: 10px; overflow: hidden;">
          <img src="' . ($movie->cover_image ?? base_url('assets/vendor/AdminLTE/dist/img/avatar.png')) . '" alt="" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;" />
        </div>
      ';
      $row[] = $movie->content_type;
      $row[] = $movie->video_embed;
      $row[] = '
        <a id="' . $this->_context . '-edit-' . $movie->content_id . '" type="button" class="btn btn-success btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-edit`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `edit`, ' . $movie->content_id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-edit"><i class="fa fa-edit"></i> Edit</a>
        <a id="' . $this->_context . '-delete-' . $movie->content_id . '" type="button" class="btn btn-danger btn-sm text-white m-1" onclick="openModal(`#modal-' . $this->_context . '-delete`, [`' . $this->_path_prefix . '`, `' . $this->_context . '`, `delete`, ' . $movie->content_id . '], `' . $endpoint . '`);" data-toggle="modal" data-target="#modal-' . $this->_context . '-delete"><i class="fa fa-trash"></i> Delete</a>
      ';

      $result[] = $row;
    }

    $output = array(
      "draw" => $this->input->post('draw'),
      "recordsTotal" => $this->movie_model->count_all(),
      "recordsFiltered" => $this->movie_model->count_filtered(),
      "data" => $result,
    );

    $this->output->set_output(json_encode($output));
  }

  public function ajax_get_by_id($id)
  {
    header('Content-Type: application/json');

    $row = $this->movie_model->getById($id);

    $this->output->set_output(json_encode($row));
  }

  // -------------------------------------------- CALLBACK ---------------------------------------------

  public function unique_title_ignore($title, $excludeId)
  {
    $this->db->where('title', $title);
    $this->db->where('content_id !=', $excludeId);
    $query = $this->db->get($this->_table);

    return $query->num_rows() === 0;
  }

  function valid_date($date)
  {
    if (!preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $date)) {
      return false; // Invalid format
    }

    $date_parts = explode('/', $date);
    $year = (int) $date_parts[0];
    $month = (int) $date_parts[1];
    $day = (int) $date_parts[2];

    return checkdate($month, $day, $year); // Check if it's a valid date
  }

  public function validate_cast_json_array($cast)
  {
    if (empty($cast)) {
      // If no cast members are selected, return false
      return true;
    } else {
      return true;
    }
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

    $data["content"]["table"][$this->_context]["columns"] = $this->movie_model->columns();

    $data["content"]["movie"]["data"] = $this->movie_model->getAll();

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
    $list_actor = $this->actor_model->getAll();

    $option = [
      "cast" => NULL
    ];

    for ($i = 0; $i < count($list_actor); $i++) {
      $row = $list_actor[$i];
      $selected = set_select("cast[]", $row->nickname);
      $option["cast"] .= '<option value="' . $row->nickname . '" ' . $selected . '>' . $row->nickname . '</option>';
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
                      <input type="hidden" class="form-control" id="add_content_id" name="content_id">
                      <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" class="form-control" id="add_title" name="title" value="' . set_value("title") . '" placeholder="Enter title" autocomplete="off">
                        ' . form_error("title", "<small id='add_title-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="release_year">Release Year *</label>
                        <div class="input-group date" id="date_input_add_release_year" data-target-input="nearest">
                          <input type="text" id="add_release_year" name="release_year" value="' . set_value("release_year") . '" placeholder="Enter release year" class="form-control datetimepicker-input" onclick="$(`#add_release_year_date_input`).click()" data-target="#date_input_add_release_year" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask />
                          <div id="add_release_year_date_input" class="input-group-append" data-target="#date_input_add_release_year" data-toggle="datetimepicker">
                            <div class="input-group-text">
                              <i class="fa fa-calendar"></i>
                            </div>
                          </div>
                        </div>
                        ' . form_error("release_year", "<small id='add_release_year-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label>Video Embed <small>( iframe: 360 x 215 )</small> *</label>
                        <textarea class="form-control" id="add_video_embed" name="video_embed" rows="3" placeholder="Enter video embed">' . set_value("video_embed") . '</textarea>
                        ' . form_error("video_embed", "<small id='add_video_embed-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="cast">Cast</label>
                        <div class="select2-purple">
                          <select id="add_cast" name="cast[]" class="select2" multiple="multiple" data-placeholder="Select some cast" data-dropdown-css-class="select2-purple" style="width: 100%;">
                            ' . $option["cast"] . '
                          </select>
                       </div>
                       ' . form_error("cast[]", "<small id='add_cast[]-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="duration">Duration <small>( in minutes )</small> *</label>
                        <input type="number" class="form-control" id="add_duration" name="duration" value="' . set_value("duration") . '" min="0" placeholder="Enter duration" autocomplete="off">
                        ' . form_error("duration", "<small id='add_duration-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" id="add_description" name="description" rows="3" placeholder="Enter description">' . set_value("description") . '</textarea>
                        ' . form_error("description", "<small id='add_description-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="' . $this->_file[0] . '">' . formatColumnName($this->_file[0]) . '</label>
                        <div class="d-flex flex-wrap align-items-end" style="gap: 6px;">
                          <div class="preview ' . $this->_file[0] . ' mb-2" style="display: none;  width: 7rem; height: 7rem; border-radius: 10px; overflow: hidden;">
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
                      <div class="form-group">
                        <label>Content Type *</label>
                        <select class="form-control" id="add_content_type" name="content_type">
                          <option value="movie" ' . set_select("content_type", "movie", TRUE) . '>Movie</option>
                          <option value="series" ' . set_select("content_type", "series") . '>Series</option>
                          <option value="tv_show" ' . set_select("content_type", "tv_show") . '>Tv Show</option>
                        </select>
                        ' . form_error("content_type", "<small id='add_content_type-error' class='text-danger'>", "</small>") . '
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

          <script>
            $("#add_release_year").inputmask("yyyy/mm/dd", { "placeholder": "yyyy/mm/dd" })

            $("#date_input_add_release_year").datetimepicker({
              format: "YYYY/MM/DD" //moment js
            });
          </script>
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
                      <input type="hidden" class="form-control" id="edit_content_id" name="content_id">
                      <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" class="form-control" id="edit_title" name="title" value="' . set_value("title") . '" placeholder="Enter title" autocomplete="off">
                        ' . form_error("title", "<small id='edit_title-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="release_year">Release Year *</label>
                        <div class="input-group date" id="date_input_edit_release_year" data-target-input="nearest">
                          <input type="text" id="edit_release_year" name="release_year" value="' . set_value("release_year") . '" placeholder="Enter release year" class="form-control datetimepicker-input" onclick="$(`#edit_release_year_date_input`).click()" data-target="#date_input_edit_release_year" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask />
                          <div id="edit_release_year_date_input" class="input-group-append" data-target="#date_input_edit_release_year" data-toggle="datetimepicker">
                            <div class="input-group-text">
                              <i class="fa fa-calendar"></i>
                            </div>
                          </div>
                        </div>
                        ' . form_error("release_year", "<small id='edit_release_year-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label>Video Embed <small>( iframe: 360 x 215 )</small> *</label>
                        <textarea class="form-control" id="edit_video_embed" name="video_embed" rows="3" placeholder="Enter video embed">' . set_value("video_embed") . '</textarea>
                        ' . form_error("video_embed", "<small id='edit_video_embed-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="cast">Cast</label>
                        <div class="select2-purple">
                          <select id="edit_cast" name="cast[]" class="select2" multiple="multiple" data-placeholder="Select some cast" data-dropdown-css-class="select2-purple" style="width: 100%;">
                            ' . $option["cast"] . '
                          </select>
                       </div>
                       ' . form_error("cast[]", "<small id='edit_cast[]-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="duration">Duration <small>( in minutes )</small> *</label>
                        <input type="number" class="form-control" id="edit_duration" name="duration" value="' . set_value("duration") . '" min="0" placeholder="Enter duration" autocomplete="off">
                        ' . form_error("duration", "<small id='edit_duration-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" placeholder="Enter description">' . set_value("description") . '</textarea>
                        ' . form_error("description", "<small id='edit_description-error' class='text-danger'>", "</small>") . '
                      </div>
                      <div class="form-group">
                        <label for="' . $this->_file[0] . '">' . formatColumnName($this->_file[0]) . '</label>
                        <div class="d-flex flex-wrap align-items-end" style="gap: 6px;">
                          <div class="preview ' . $this->_file[0] . ' mb-2" style="display: none;  width: 7rem; height: 7rem; border-radius: 10px; overflow: hidden;">
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
                      <div class="form-group">
                        <label>Content Type *</label>
                        <select class="form-control" id="edit_content_type" name="content_type">
                          <option value="movie" ' . set_select("content_type", "movie", TRUE) . '>Movie</option>
                          <option value="series" ' . set_select("content_type", "series") . '>Series</option>
                          <option value="tv_show" ' . set_select("content_type", "tv_show") . '>Tv Show</option>
                        </select>
                        ' . form_error("content_type", "<small id='edit_content_type-error' class='text-danger'>", "</small>") . '
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

          <script>
            $("#edit_release_year").inputmask("yyyy/mm/dd", { "placeholder": "yyyy/mm/dd" })

            $("#date_input_edit_release_year").datetimepicker({
              format: "YYYY/MM/DD" //moment js
            });
          </script>
        ';
        break;

      case 'delete':
        $field_match = 'title';
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
