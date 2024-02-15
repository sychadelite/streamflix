<?php defined('BASEPATH') or exit('No direct script access allowed');

class Genre_model extends CI_Model
{
  private $_table = "genre";

  // fields
  public $genre_id;
  public $genre_name;

  // data table params
  private $_column_order = array(
    "s.genre_id",
    "s.genre_name"
  );
  private $_column_search = array(
    "s.genre_id",
    "s.genre_name"
  );
  private $_order = array('s.genre_id' => 'asc');

  public function rules($context)
  {
    switch ($context) {
      case 'add':
        return [
          [
            'field' => 'genre_name',
            'label' => 'Genre Name',
            'rules' => 'required|trim|max_length[255]|is_unique[genre.genre_name]',
            'errors' => [
              'is_unique' => 'The {field} is already taken.'
            ]
          ]
        ];
        break;
      case 'edit':
        return [
          [
            'field' => 'genre_name',
            'label' => 'Genre Name',
            'rules' => 'required|trim|max_length[255]|callback_unique_genre_name_ignore[' . $this->input->post('genre_id') . ']',
            'errors' => [
              'unique_genre_name_ignore' => 'The {field} is already taken.'
            ]
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

  public function get_count()
  {
    return $this->db->count_all($this->_table);
  }

  public function getAll()
  {
    return $this->db->get($this->_table)->result();
  }

  public function getById($id)
  {
    return $this->db->get_where($this->_table, ["genre_id" => $id])->row();
  }

  public function save()
  {
    $post = $this->input->post();

    $this->genre_name = $post["genre_name"];

    return $this->db->insert($this->_table, $this);
  }

  public function update()
  {
    $post = $this->input->post();
    $genre = $this->getById($post["genre_id"]);

    $this->genre_id = $post["genre_id"];
    $this->genre_name = $post["genre_name"] ?? $genre->genre_name;

    return $this->db->update($this->_table, $this, array('genre_id' => $post['genre_id']));
  }

  public function delete($id)
  {
    return $this->db->delete($this->_table, array("genre_id" => $id));
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
      's.genre_id',
      's.genre_name',
    ], FALSE);
    $this->db->from($this->_table . ' s');
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