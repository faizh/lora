<?php

class M_backup_messages extends CI_Model {
    function tableName() {
        return "t_backup_messages";
    }

    function update($data, $condition) {
        if( !$this->db->update($this->tableName(), $data, $condition) ){
            return false;
        }else{
            return true;
        }
    }

    function insert($data=array()) {
        if(!$this->db->insert($this->tableName(), $data)){
            return false;
        }else{
            return true;
        }
    }

    function getBackupMessages($date, $start_time, $end_time) {
        $query = "SELECT *
        FROM `t_backup_messages` bm
        WHERE DATE(bm.`created_dtm`) = '" . $date . "'
        AND TIME(bm.`created_dtm`) > '" . $start_time . "' 
        AND TIME(bm.`created_dtm`) < '" . $end_time . "'";

        return $this->db->query($query)->result();
    }
}