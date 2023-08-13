<?php

class M_alat extends CI_Model {
    function update($data, $condition) {
        if( !$this->db->update('t_alat', $data, $condition) ){
            return false;
        }else{
            return true;
        }
    }

    function insert($data=array()) {
        if(!$this->db->insert('t_alat', $data)){
            return false;
        }else{
            return true;
        }
    }

    function lora_1() {
        return 1;
    }

    function lora_2() {
        return 2;
    }

    function getAlat($alat_id) {
        $this->db->select('*');
        $this->db->where('id', $alat_id);
        $alat = $this->db->get('t_alat')->row();

        return $alat;
    }
}