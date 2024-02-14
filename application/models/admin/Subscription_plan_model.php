<?php defined('BASEPATH') or exit('No direct script access allowed');

class Subscription_plan_model extends CI_Model
{
  private $_table = "subscription_plan";

  // fields
  public $subscription_plan_id;
  public $name;
  public $price;
  public $concurrent_streams;
  public $content_resolution;
  public $description;

  // data table params
  private $_column_order = array(
    "sp.subscription_plan_id",
    "sp.name",
    "sp.price",
    "sp.concurrent_streams",
    "sp.content_resolution",
    "sp.description"
  );
  private $_column_search = array(
    "sp.subscription_plan_id",
    "sp.name",
    "sp.price",
    "sp.concurrent_streams",
    "sp.content_resolution",
    "sp.description"
  );
  private $_order = array('sp.subscription_plan_id' => 'asc');

  public function rules($context)
  {
    switch ($context) {
      case 'add':
        return [
          [
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'required|trim|is_unique[subscription_plan.name]',
            'errors' => [
              'is_unique' => 'The {field} is already taken.'
            ]
          ],
          [
            'field' => 'price',
            'label' => 'Price',
            'rules' => 'required|numeric|regex_match[/^\d{1,8}(\.\d{1,2})?$/]',
            'errors' => [
              'regex_match' => 'The {field} field must be a valid price format (up to 8 digits before the decimal point and up to 2 digits after).'
            ]
          ],
          [
            'field' => 'concurrent_streams',
            'label' => 'Concurrent Streams',
            'rules' => 'required|numeric'
          ],
          [
            'field' => 'content_resolution',
            'label' => 'Content Resolution',
            'rules' => 'required|in_list[HD,Full HD,4K]'
          ],
          [
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|max_length[255]'
          ]
        ];
        break;
      case 'edit':
        return [
          [
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'required|trim|callback_unique_name_ignore[' . $this->input->post('subscription_plan_id') . ']',
            'errors' => [
              'unique_name_ignore' => 'The {field} is already taken.'
            ]
          ],
          [
            'field' => 'price',
            'label' => 'Price',
            'rules' => 'required|numeric|regex_match[/^\d{1,8}(\.\d{1,2})?$/]',
            'errors' => [
              'regex_match' => 'The {field} field must be a valid price format (up to 8 digits before the decimal point and up to 2 digits after).'
            ]
          ],
          [
            'field' => 'concurrent_streams',
            'label' => 'Concurrent Streams',
            'rules' => 'required|numeric'
          ],
          [
            'field' => 'content_resolution',
            'label' => 'Content Resolution',
            'rules' => 'required|in_list[HD,Full HD,4K]'
          ],
          [
            'field' => 'description',
            'label' => 'Description',
            'rules' => 'trim|max_length[255]'
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
    return $this->db->get_where($this->_table, ["subscription_plan_id" => $id])->row();
  }

  public function save()
  {
    $post = $this->input->post();

    $this->name = $post["name"];
    $this->price = $post["price"];
    $this->concurrent_streams = $post["concurrent_streams"];
    $this->content_resolution = $post["content_resolution"];
    $this->description = $post["description"] ?? NULL;

    return $this->db->insert($this->_table, $this);
  }

  public function update()
  {
    $post = $this->input->post();
    $subscription_plan = $this->getById($post["subscription_plan_id"]);

    $this->subscription_plan_id = $post["subscription_plan_id"];
    $this->name = $post["name"] ?? $subscription_plan->name;
    $this->price = $post["price"] ?? $subscription_plan->price;
    $this->concurrent_streams = $post["concurrent_streams"] ?? $subscription_plan->concurrent_streams;
    $this->content_resolution = $post["content_resolution"] ?? $subscription_plan->content_resolution;
    $this->description = $post["description"] ?? $subscription_plan->description;

    return $this->db->update($this->_table, $this, array('subscription_plan_id' => $post['subscription_plan_id']));
  }

  public function delete($id)
  {
    return $this->db->delete($this->_table, array("subscription_plan_id" => $id));
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
      'sp.subscription_plan_id',
      'sp.name',
      'sp.price',
      'sp.concurrent_streams',
      'sp.content_resolution',
      'sp.description'
    ], FALSE);
    $this->db->from($this->_table . ' sp');
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
