<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('encrypt');
       
    }
        public function authenticate($email, $password)
    {
        $this->db->select('*');
        $this->db->from('users');        
        $this->db->where('LOWER(usr_email)', $email);
        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
           $user = $query->row();
           if($user->usr_status == 1)
           {
                if($this->encrypt->decode($user->usr_password) === $password)
                {
                    
                    $sess_array = array(
                        'user_id' => $user->usr_id,
                        'fname' => $user->usr_fname, 
                        'lname' => $user->usr_lname,                        
                        'email' => $user->usr_email
                    );
                    $this->session->set_userdata('logged_in',$sess_array);              
                    return true;                    
                }
                else
                {
                    $this->session->set_flashdata('failure','Incorrect password.');
                    return false;
                }
            }
            else
            {
                $this->session->set_flashdata('failure','Inactive user login');
                return false;
            }
        }
        else
        {
            $this->session->set_flashdata('failure','Invalid Email.');
            return false;
        } 
    }

    public function update_user($userdata)
    {

        $this->db->insert('users', $userdata); 
        $insertid = $this->db->insert_id();
        return $insertid;
    }

    public function update_active_user($random_string)
    {
        $this->db->set('usr_status', 1);
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->set('usr_actv_link', '');
        $this->db->where('usr_actv_link', $random_string);
        return $this->db->update('users'); 
    }

    public function get_user_details_by_randomstring($random_string)
    {
        $this->db->select('*');
        $this->db->from('users');        
        $this->db->where('usr_actv_link', $random_string);
        $query = $this->db->get();
        return $query->row_array();
    }

    
    public function check_email_exist($email)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('usr_email', $email);
        $query = $this->db->get();
        return $query->num_rows();
    }

    
    public function get_user_details($email)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('usr_email', $email);
        $query = $this->db->get();
        return $query->row_array();
    }

    
    public function update_forget_password_random_string($data)
    {
        $this->db->set('frgt_psswd_rndm_str', $data['frgt_psswd_rndm_str']);
        $this->db->where('usr_email', $data['usr_email']);
        return $this->db->update('users'); 
    }

    
    public function get_user_details_reset_password($random_string)
    {
        $this->db->select('*');
        $this->db->from('users');        
        $this->db->where('frgt_psswd_rndm_str', $random_string);
        $query = $this->db->get();
        return $query->row_array();
    }

   
    public function update_password($data)
    {
        $this->db->set('usr_password', $data['password']);
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('frgt_psswd_rndm_str', $data['reset_password_link']);
        $this->db->where('usr_email', $data['email']);
        return $this->db->update('users'); 
    }

   
    public function update_reset_link($email)
    {
        $this->db->set('frgt_psswd_rndm_str', '');
        $this->db->where('usr_email', $email);
        return $this->db->update('users'); 
    }

    
     public function update_change_password($data)
    {
        $this->db->set('usr_password', $data['password']);
        $this->db->where('usr_email', $data['email']);
        return $this->db->update('users'); 
    }
    }



