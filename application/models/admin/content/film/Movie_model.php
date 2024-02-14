<?php defined('BASEPATH') or exit('No direct script access allowed');

class Movie_model extends CI_Model
{
  private $_table = "content";

  // fields
  public $content_id;
  public $title;
  public $release_year;
  public $duration;
  public $description;
  public $cast;
  public $cover_image;
  public $content_type;
  public $video_embed;

  // data table params
  private $_column_order = array(
    "m.content_id",
    "m.title",
    "m.release_year",
    "m.duration",
    "m.description",
    "m.cast",
    "m.cover_image",
    "m.content_type",
    "m.video_embed"
  );
  private $_column_search = array(
    "m.content_id",
    "m.title",
    "m.release_year",
    "m.duration",
    "m.description",
    "m.cast",
    "m.cover_image",
    "m.content_type",
    "m.video_embed"
  );
  private $_order = array('m.content_id' => 'asc');

  public function rules($context)
  {
    switch ($context) {
      case 'add':
        return [
          [
            'field' => 'title',
            'label' => 'Title',
            'rules' => 'required|trim|max_length[255]|is_unique[content.title]',
            'errors' => [
              'is_unique' => 'The {field} is already taken.'
            ]
          ],
          [
            'field' => 'release_year',
            'label' => 'Release Year',
            'rules' => 'required|callback_valid_date',
            'errors' => [
              'valid_date' => 'The {field} is not in a valid format.'
            ]
          ],
          [
            'field' => 'duration',
            'label' => 'Duration',
            'rules' => 'required|numeric|greater_than_equal_to[0]'
          ],
          [
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim'
          ],
          [
            'field' => 'cast[]',
            'label' => 'Cast',
            'rules' => 'trim|callback_validate_cast_json_array'
          ],
          [
            'field' => 'content_type',
            'label' => 'Content Type',
            'rules' => 'required|in_list[movie,series,tv_show]'
          ],
          [
            'field' => 'video_embed',
            'label' => 'Video Embed',
            'rules' => 'trim|regex_match[/<iframe.*src=["\'](.*?)["\'].*><\/iframe>/]'
          ]
        ];
        break;
      case 'edit':
        return [
          [
            'field' => 'title',
            'label' => 'Title',
            'rules' => 'required|trim|max_length[255]|callback_unique_title_ignore[' . $this->input->post('content_id') . ']',
            'errors' => [
              'unique_title_ignore' => 'The {field} is already taken.'
            ]
          ],
          [
            'field' => 'release_year',
            'label' => 'Release Year',
            'rules' => 'required|callback_valid_date',
            'errors' => [
              'valid_date' => 'The {field} is not in a valid format.'
            ]
          ],
          [
            'field' => 'duration',
            'label' => 'Duration',
            'rules' => 'required|numeric|greater_than_equal_to[0]'
          ],
          [
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim'
          ],
          [
            'field' => 'cast[]',
            'label' => 'Cast',
            'rules' => 'trim|callback_validate_cast_json_array',
            'errors' => [
              'validate_cast_json_array' => 'The {field} is not satisfied.'
            ]
          ],
          [
            'field' => 'content_type',
            'label' => 'Content Type',
            'rules' => 'required|in_list[movie,series,tv_show]'
          ],
          [
            'field' => 'video_embed',
            'label' => 'Video Embed',
            'rules' => 'trim|regex_match[/<iframe.*src=["\'](.*?)["\'].*><\/iframe>/]'
          ]
        ];
        break;

      default:
        return [];
        break;
    }
  }

  public function columns()
  {
    return $this->_column_order;
  }

  public function getAll()
  {
    return $this->db->get($this->_table)->result();
  }

  public function getById($id)
  {
    return $this->db->get_where($this->_table, ["content_id" => $id])->row();
  }

  public function save()
  {
    $post = $this->input->post();

    $this->title = $post["title"];
    $this->release_year = date('Y-m-d', strtotime(str_replace('/', '-', $post["release_year"])));
    $this->duration = $post["duration"];
    $this->description = $post["description"];
    $this->cast = $post['cast'] ? json_encode($post['cast']) : NULL;
    $this->cover_image = $this->cover_image ?? ($post["cover_image"] ?? NULL);
    $this->content_type = $post["content_type"];
    $this->video_embed = $post["video_embed"];

    return $this->db->insert($this->_table, $this);
  }

  public function update()
  {
    $post = $this->input->post();
    $content = $this->getById($post["content_id"]);

    $this->content_id = $post["content_id"];
    $this->title = $post["title"] ?? $content->title;
    $this->release_year = date('Y-m-d', strtotime(str_replace('/', '-', $post["release_year"]))) ?? $content->release_year;
    $this->duration = $post["duration"] ?? $content->duration;
    $this->description = $post["description"] ?? $content->description;
    $this->cast = $post['cast'] ? json_encode($post['cast'] ?? []) ?? $content->cast : NULL;
    $this->cover_image = $this->cover_image ?? ($post["cover_image"] ?? $content->cover_image);
    $this->content_type = $post["content_type"] ?? $content->content_type;
    $this->video_embed = $post["video_embed"] ?? $content->video_embed;

    return $this->db->update($this->_table, $this, array('content_id' => $post['content_id']));
  }

  public function delete($id)
  {
    return $this->db->delete($this->_table, array("content_id" => $id));
  }


  // ----------------------------------------- DATA TABLE ---------------------------------------------
  public function get_datatables()
  {
    $this->_get_datatables_query();
    if ($this->input->post('length') != -1)
      $this->db->limit($this->input->post('length'), $this->input->post('start'));
    $query = $this->db->get();
    return $query->result();
  }

  public function count_filtered()
  {
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
    return $this->db->count_all($this->_table);
  }

  // ------------------------------------------- PRIVATE ---------------------------------------------

  private function _get_datatables_query()
  {
    $this->db->select([
      'm.content_id',
      'm.title',
      'm.release_year',
      'm.duration',
      'm.description',
      'm.cast',
      'm.cover_image',
      'm.content_type',
      'm.video_embed'
    ], FALSE);
    $this->db->from($this->_table . ' m');
    $this->db->where("content_type", "movie");
    $i = 0;
    foreach ($this->_column_search as $item) {
      if ($this->input->post('search')['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $this->input->post('search')['value'], FALSE);
        } else {
          $this->db->or_like($item, $this->input->post('search')['value']);
        }

        if (count($this->_column_search) - 1 == $i)
          $this->db->group_end();
      }
      $i++;
    }

    if ($this->input->post('order')) {
      foreach ($this->input->post('order') as $key => $value) {
        $column_index = $this->input->post('order')[$key]['column'];
        $dir = $this->input->post('order')[$key]['dir'];
        $this->db->order_by($this->_column_order[$column_index], $dir);
      }
    } else if (isset($this->_order)) {
      foreach ($this->_order as $key => $value) {
        $this->db->order_by($key, $value);
      }
    }
  }
}
