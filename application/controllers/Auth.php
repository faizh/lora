<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'm_users'
        ));
    }

    public function login() {
        $this->load->view('auth/v_login');
    }

    public function act_login() {
        $input = (object) $this->input->post();

        $username   = $input->username;
        $password   = $input->password;

        $data_user  = $this->m_users->getByUsername($username);
        $logged_in  = FALSE;
        if (isset($data_user->id)) {
            if (password_verify($password, $data_user->password)){
                $logged_in = TRUE;
            }
        }else {
            $data_user  = $this->m_users->getByEmail($username);

            if (isset($data_user->id)) {
                if (password_verify($password, $data_user->password)){
                    $logged_in = TRUE;
                }
            }
        }

        if ($logged_in) {
            $sess_data = array(
                'logged_in' => $logged_in,
                'user_id'   => $data_user->id,
                'username'  => $data_user->username
            );
            redirect('backup');
        }else {
            redirect('auth/login');
        }

    }

    public function logout() {
        redirect('');
    }
}