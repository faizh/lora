<?php

class M_backup extends CI_Model {
    function update($data, $condition) {
        if( !$this->db->update('t_backup', $data, $condition) ){
            return false;
        }else{
            return true;
        }
    }

    function getBackupInterval(){
        $this->db->select("value");
        $this->db->where("name", "interval");

        return $this->db->get("t_backup")->row()->value;
    }
}