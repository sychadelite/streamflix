<?php defined('BASEPATH') or exit('No direct script access allowed');

class Recommendations_model extends CI_Model
{
  private $_table = "recommendations";

  // fields
  public $recommendation_id;
  public $user_id;
  public $content_id;
  public $score;
  public $created_at;

  // data table params
  private $_column_order = array(
    "r.recommendation_id",
    "r.user_id",
    "r.content_id",
    "r.score",
    "r.created_at"
  );
  private $_column_search = array(
    "r.recommendation_id",
    "r.user_id",
    "r.content_id",
    "r.score",
    "r.created_at"
  );
  private $_order = array('r.recommendation_id' => 'asc');

  public function rules($context)
  {
    switch ($context) {
      case 'add':
        return [
          [
            'field' => 'user_id',
            'label' => 'User',
            'rules' => 'required|numeric|callback_check_available_user[' . $this->input->post('content_id') . ']',
            'errors' => [
              'check_available_user' => 'The {field} is already available.'
            ]
          ],
          [
            'field' => 'content_id',
            'label' => 'Content',
            'rules' => 'required|numeric|callback_check_available_content[' . $this->input->post('user_id') . ']',
            'errors' => [
              'check_available_content' => 'The {field} is already available.'
            ]
          ],
          [
            'field' => 'score',
            'label' => 'Score',
            'rules' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[10]|regex_match[/^\d{1,8}(\.\d{1,2})?$/]',
            'errors' => [
              'regex_match' => 'The {field} field must be a valid number format (up to 8 digits before the decimal point and up to 2 digits after).'
            ]
          ],
        ];
        break;
      case 'edit':
        $params_check_available_user_ignore = serialize([$this->input->post('content_id'), $this->input->post('recommendation_id')]);
        $params_check_available_content_ignore = serialize([$this->input->post('user_id'), $this->input->post('recommendation_id')]);

        return [
          [
            'field' => 'user_id',
            'label' => 'User',
            'rules' => 'required|numeric|callback_check_available_user_ignore[' . $params_check_available_user_ignore . ']'
          ],
          [
            'field' => 'content_id',
            'label' => 'Content',
            'rules' => 'required|numeric|callback_check_available_content_ignore[' . $params_check_available_content_ignore . ']'
          ],
          [
            'field' => 'score',
            'label' => 'Score',
            'rules' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[10]|regex_match[/^\d{1,8}(\.\d{1,2})?$/]',
            'errors' => [
              'regex_match' => 'The {field} field must be a valid number format (up to 8 digits before the decimal point and up to 2 digits after).'
            ]
          ],
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
    return $this->db->get_where($this->_table, ["recommendation_id" => $id])->row();
  }

  public function save()
  {
    $post = $this->input->post();

    $this->user_id = $post["user_id"];
    $this->content_id = $post["content_id"];
    $this->score = $post["score"];
    $this->created_at = date('Y-m-d H:i:s');

    return $this->db->insert($this->_table, $this);
  }

  public function update()
  {
    $post = $this->input->post();
    $recommendations = $this->getById($post["recommendation_id"]);

    $this->recommendation_id = $post["recommendation_id"];
    $this->user_id = $post["user_id"] ?? $recommendations->user_id;
    $this->content_id = $post["content_id"] ?? $recommendations->content_id;
    $this->score = $post["score"] ?? $recommendations->score;
    $this->created_at = $recommendations->created_at;

    return $this->db->update($this->_table, $this, array('recommendation_id' => $post['recommendation_id']));
  }

  public function delete($id)
  {
    return $this->db->delete($this->_table, array("recommendation_id" => $id));
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
      'r.recommendation_id',
      'r.user_id',
      'r.content_id',
      'r.score',
      'r.created_at',
      'u.username',
      'c.title'
    ], FALSE);
    $this->db->from($this->_table . ' r');
    $this->db->join('users u', 'u.id = r.user_id');
    $this->db->join('content c', 'c.content_id = r.content_id');
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
