<?php if (!defined('BASEPATH'))    exit('No direct script access allowed');


class Countries_model extends CI_Model {

    function insertCountry($data) {
        $sql = "INSERT INTO countries (cntry_name,cntry_code) VALUES (?,?)";
        $values = array($data['cntry_name'], $data['cntry_code']);

        $prep = prep($sql, $values);
        $this->db->query($sql, $values);

        return true;
    }

    function getCountryById($data) {
        $sql = "SELECT * FROM countries WHERE cntry_id = ?";
        $values = array($data['id']);
        $q = $this->db->query($sql, $values);

        $results['users'] = array();

        if ($q->num_rows() > 0) {
            $x = 0;
            foreach ($q->result() as $row) {
                $results['users'][$x] = $row;
                $x++;
            }
        }

        return $results;
    }

    function getCountryByCode($data) {
      
        
        $this->db->select('*');
        $this->db->from('countries');        
        $this->db->where('cntry_code', $data['cntry_code']);
        
        $query = $this->db->get();
        $results['countries'] = $query->result(); 

        return $results;
    }

    function getCountries() {
        $sql = "SELECT * FROM countries";
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

}
