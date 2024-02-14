<?php defined('BASEPATH') or exit('No direct script access allowed');
// https://www.petanikode.com/codeigniter-login/

class Auth_model extends CI_Model
{
  private $_table = "users";
  const SESSION_KEY = "user_id";

  public function rules($context)
  {
    switch ($context) {
      case 'login':
        return [
          [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim|valid_email|callback_email_exists',
            'errors' => [
              'required' => 'The {field} field is required.',
              'valid_email' => 'Please enter a valid email address.',
              'email_exists' => 'The provided email does not exist in our records.',
            ],
          ],
          [
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[6]|max_length[255]',
            'errors' => [
              'required' => 'The {field} field is required.',
              'min_length' => 'Password must be at least {param} characters long.',
              'max_length' => 'Password cannot exceed {param} characters.',
            ],
          ]
        ];

        break;

      case 'register':
        return [
          [
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required|trim|is_unique[users.username]',
            'errors' => [
              'required' => 'The {field} field is required.',
              'is_unique' => 'The {field} is already taken. Please choose a different one.',
            ],
          ],
          [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim|valid_email|is_unique[users.email]',
            'errors' => [
              'required' => 'The {field} field is required.',
              'valid_email' => 'Please enter a valid email address.',
              'is_unique' => 'The {field} is already registered. Please use a different email.',
            ],
          ],
          [
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[6]|max_length[255]',
            'errors' => [
              'required' => 'The {field} field is required.',
              'min_length' => 'Password must be at least {param} characters long.',
              'max_length' => 'Password cannot exceed {param} characters.',
            ],
          ],
          [
            'field' => 'password_confirmation',
            'label' => 'Password Confirmation',
            'rules' => 'required|matches[password]',
            'errors' => [
              'required' => 'The {field} field is required.',
              'matches' => 'The {field} does not match the password.',
            ],
          ],
          [
            'field' => 'terms',
            'label' => 'Terms',
            'rules' => 'required',
            'errors' => [
              'required' => 'You must agree to the {field}.',
            ],
          ]
        ];

        break;

      default:
        return [];
        break;
    }
  }

  public function login($email, $password)
  {
    $this->db->where(['email' => $email, 'role' => '1']);
    $query = $this->db->get($this->_table);
    $user = $query->row();

    // check if user has already registered
    if (!$user) {
      return FALSE;
    }

    // check if the password is correct
    if (!password_verify($password, $user->password)) {
      return FALSE;
    }

    // create session
    $this->session->set_userdata([self::SESSION_KEY => $user->id]);
    $this->_update_last_login($user->id);

    return $this->session->has_userdata(self::SESSION_KEY);
  }

  public function register($username, $email, $password)
  {
    $data = array(
      'username' => $username,
      'email' => $email,
      'password' => password_hash($password, PASSWORD_BCRYPT)
    );

    return $this->db->insert('users', $data);
  }

  public function current_user()
  {
    if (!$this->session->has_userdata(self::SESSION_KEY)) {
      return null;
    }

    $user_id = $this->session->userdata(self::SESSION_KEY);
    $query = $this->db->get_where($this->_table, ['id' => $user_id, 'role' => '1']);
    return $query->row();
  }

  public function logout()
  {
    $this->session->unset_userdata(self::SESSION_KEY);
    return !$this->session->has_userdata(self::SESSION_KEY);
  }

  // --------------------------------------------- PRIVATE ---------------------------------------------

  private function _update_last_login($id)
  {
    $data = [
      'last_login' => date("Y-m-d H:i:s"),
    ];

    return $this->db->update($this->_table, $data, ['id' => $id]);
  }
}
