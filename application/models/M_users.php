<?php 

class M_users extends CI_Model {

    public function getByUsername($username) {
        $this->db->select('*');
        $this->db->where('username', $username);

        return $this->db->get('t_users')->row();
    }

    public function getByEmail($email) {
        $this->db->select('*');
        $this->db->where('email', $email);
        
        return $this->db->get('t_users')->row();
    }

}