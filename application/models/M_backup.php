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

    function insertFile($data=array()) {
        if(!$this->db->insert('t_backup_files', $data)){
            return false;
        }else{
            return true;
        }
        
    }

    function getAllBackupFiles() {
        $this->db->select("*");
        return $this->db->get("t_backup_files")->result();
    }

    function getFile($file_id) {
        $this->db->select("*");
        $this->db->where('id', $file_id);
        return $this->db->get("t_backup_files")->row();
    }
}