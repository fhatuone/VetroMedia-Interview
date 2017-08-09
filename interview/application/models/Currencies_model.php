<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Currencies_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function insertCurrency($data) {
        $data['curr_time'] = date('Y-m-d H:i:s');

        unset($data['curr_id']);
        unset($data['cntry_code']);

        $this->db->insert('currencies', $data);

        $insertid = $this->db->insert_id();

        return true;
    }

    function updateCurrency($data) {
        $data['curr_time'] = date('Y-m-d H:i:s');
        $curr_id = $data['curr_id'];

        unset($data['curr_id']);
        unset($data['cntry_code']);

        $this->db->where('curr_id', $curr_id);
        $this->db->update('currencies', array('curr_time' => $data['curr_time']));

        return true;
    }

    function getCurrencyById($data) {
//        unset($data['curr_id']);
//        unset($data['cntry_code']);
        $sql = "SELECT * FROM currencies WHERE cur_id = ?";
        $values = array($data['cur_id']);
        $q = $this->db->query($sql, $values);

        $results['currencies'] = array();

        if ($q->num_rows() > 0) {
            $x = 0;
            foreach ($q->result() as $row) {
                $results['currencies'][$x] = $row;
                $x++;
            }
        }

        return $results;
    }

    public function get_currency() {
        $sql = "SELECT curr_id, curr_amount, curr_time, cntry_id 
                FROM currencies 
                WHERE curr_time IN (SELECT MAX(curr_time) FROM currencies ) 
                ORDER BY curr_time DESC";
        $q = $this->db->query($sql);

        $results['currencies'] = array();

        if ($q->num_rows() > 0) {
            $x = 0;
            foreach ($q->result() as $row) {
                $results['currencies'][$x] = $row;
                $x++;
            }
        }

        return $results;
    }

    function getCurrencyByCountryId($data) {


        $this->db->select('*');
        $this->db->from('currencies');
        $this->db->where('cntry_id', $data['cntry_id']);
        $this->db->order_by("curr_time", "desc");
        $query = $this->db->get();
        $results['currencies'] = $query->result();

        return $results;
    }

    function getCurrencies($data) {
        $sql = "SELECT * FROM currencies ORDER BY curr_time DESC";
        $q = $this->db->query($sql);

        $results['countries'] = array();

        if ($q->num_rows() > 0) {
            $x = 0;
            foreach ($q->result() as $row) {
                $results['countries'][$x] = $row;
                $x++;
            }
        }

        return $results;
    }

    public function get_acc_bal() {

        $this->db->select('*');
        $this->db->from('account');
        $this->db->where('acc_type','USD');
        $this->db->order_by("acc_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        return $result = $query->result();
    }
    
    public function get_transactions() {

        $this->db->select('*');
        $this->db->from('account');
        $this->db->where('acc_type','USD');
        $this->db->order_by("acc_id", "desc");        
        $query = $this->db->get();
        return $result = $query->result();
    }

    public function update_account($input_data) {        
        $this->db->insert('account', $input_data);
        $insertid = $this->db->insert_id();
        return $insertid;
    }

    public function set_order($input_data) {

        $this->db->insert('orders', $input_data);

        $insertid = $this->db->insert_id();
        return $insertid;
    }

    public function get_orders() {

        $this->db->select('*');
        $this->db->from('orders');
        $this->db->where('acc_id', $acc_id);
        $query = $this->db->get();
        return $query->row_array();
    }

}
