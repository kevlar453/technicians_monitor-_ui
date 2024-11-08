<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_tasks($start, $end) {
      $this->db->where('tasks.set_com',$this->session->userdata('com_id'));
        $this->db->where('start_date >=', $start);
        $this->db->where('end_date <=', $end);
        $this->db->join('tbl_client','tbl_client.kode_client=tasks.description','both');
        $this->db->join('tbl_user','tbl_user.clidentitas=tasks.title','both');
        return $this->db->get('tasks')->result_array();
    }

    public function insert_task($data) {
        return $this->db->insert('tasks', $data);
    }

    public function update_task($id, $data) {
      $this->db->where('set_com',$this->session->userdata('com_id'));
        $this->db->where('id', $id);
        return $this->db->update('tasks', $data);
    }

    public function delete_task($id) {
      $this->db->where('set_com',$this->session->userdata('com_id'));
        $this->db->where('id', $id);
        return $this->db->delete('tasks');
    }
}
?>
