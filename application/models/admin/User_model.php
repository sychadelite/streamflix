<?php defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
  private $_table = "users";

  // fields
  public $id;
  public $subscription_plan_id = NULL;
  public $username;
  public $email;
  public $password;
  public $avatar;
  public $role = 0;
  public $active = 1;
  public $created_at;
  public $last_login;

  // data table params
  private $_column_order = array(
    "u.id",
    "u.username",
    "u.email",
    "u.role",
    "u.active",
    "sp.subscription_plan_id",
    "u.created_at",
    "u.last_login"
  );
  private $_column_search = array(
    "u.id",
    "u.username",
    "u.email",
    "u.password",
    "u.avatar",
    "CASE WHEN u.role = '0' THEN 'user' WHEN u.role = '1' THEN 'admin' END",
    "CASE WHEN u.active = '0' THEN 'disabled' WHEN u.active = '1' THEN 'verified' END",
    "sp.subscription_plan_id",
    "sp.name",
    "u.created_at",
    "u.last_login"
  );
  private $_order = array('u.active' => 'desc', 'u.role' => 'desc', 'u.id' => 'asc');

  public function rules($context)
  {
    switch ($context) {
      case 'add':
        return [
          [
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required|trim|is_unique[users.username]',
            'errors' => [
              'is_unique' => 'The {field} is already taken.'
            ]
          ],
          [
            'field' => 'subscription_plan_id',
            'label' => 'Subscription Plan ID',
            'rules' => 'numeric'
          ],
          [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim|valid_email|is_unique[users.email]',
            'errors' => [
              'is_unique' => 'The {field} is already taken.'
            ]
          ],
          [
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[6]|max_length[255]'
          ],
          [
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'required|numeric|in_list[0,1]'
          ],
          [
            'field' => 'active',
            'label' => 'Active',
            'rules' => 'numeric|in_list[0,1]'
          ]
        ];
        break;
      case 'edit':
        return [
          [
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required|trim|callback_unique_username_ignore[' . $this->input->post('id') . ']',
            'errors' => [
              'unique_username_ignore' => 'The {field} is already taken.'
            ]
          ],
          [
            'field' => 'subscription_plan_id',
            'label' => 'Subscription Plan ID',
            'rules' => 'numeric'
          ],
          [
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'min_length[6]|max_length[255]'
          ],
          [
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'required|numeric|in_list[0,1]'
          ],
          [
            'field' => 'active',
            'label' => 'Active',
            'rules' => 'numeric|in_list[0,1]'
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
    return $this->db->get_where($this->_table, ["id" => $id])->row();
  }

  public function save()
  {
    $post = $this->input->post();

    $this->subscription_plan_id = !empty($post["subscription_plan_id"]) ? $post["subscription_plan_id"] : NULL;
    $this->username = $post["username"];
    $this->email = $post["email"];
    $this->password = password_hash($post["password"], PASSWORD_BCRYPT);
    $this->avatar = $this->avatar ?? ($post["avatar"] ?? NULL);
    $this->role = $post["role"] ?? 0;
    $this->active = $post['active'] ? '1' : '0';
    $this->created_at = date('Y-m-d H:i:s');

    return $this->db->insert($this->_table, $this);
  }

  public function update()
  {
    $post = $this->input->post();
    $user = $this->getById($post["id"]);

    $this->id = $post["id"];
    $this->subscription_plan_id = !empty($post["subscription_plan_id"]) ? $post["subscription_plan_id"] : NULL;
    $this->username = $post["username"] ?? $user->username;
    $this->email = $post["email"] ?? $user->email;
    $this->password = $post["password"] ? password_hash($post["password"], PASSWORD_BCRYPT) : $user->password;
    $this->avatar = $this->avatar ?? ($post["avatar"] ?? $user->avatar);
    $this->role = $post["role"] ?? $user->role;
    $this->active = $post['active'] ? '1' : '0';
    $this->created_at = $user->created_at;
    $this->last_login = $user->last_login;

    return $this->db->update($this->_table, $this, array('id' => $post['id']));
  }

  public function delete($id)
  {
    return $this->db->delete($this->_table, array("id" => $id));
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
      'u.id',
      'u.username',
      'u.email',
      'u.password',
      'u.avatar',
      'CASE WHEN u.role = "0" THEN "user" WHEN u.role = "1" THEN "admin" END AS role',
      'CASE WHEN u.active = "0" THEN "disabled" WHEN u.active = "1" THEN "verified" END AS active',
      'sp.subscription_plan_id',
      'sp.name AS subscription_plan_name',
      'u.created_at',
      'u.last_login'
    ], FALSE);
    $this->db->from($this->_table . ' u');
    $this->db->join('subscription_plan sp', 'sp.subscription_plan_id = u.subscription_plan_id', 'left');
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
