<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('auth/user_helper');
        
    }

    public function index() {
        if (!empty($this->session->userdata('logged_in'))) {
            redirect('user/home');
        }
        if (!empty($this->input->post())) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                
                $data['result'] = $this->Countries_model->getCountries();

                $this->load->view('static/header');
                $this->load->view('auth/login',$data);
                $this->load->view('static/footer');
            } else {
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $check_auth = $this->user_model->authenticate($email, $password);
                if ($check_auth) {
                    redirect('user/home');
                } else {
                    redirect('user');
                }
            }
        } else {
            
            $data['result'] = $this->Countries_model->getCountries();
            



            $this->load->view('static/header');
            $this->load->view('auth/login',$data);
            $this->load->view('static/footer');
        }
    }

    

    public function signup() {
        if (!empty($this->session->userdata('logged_in')))
            redirect('user/home');
        if (!empty($this->input->post())) {
            $this->form_validation->set_rules('fname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lname', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.usr_email]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
            $this->form_validation->set_rules('conf_password', 'Confirm Password', 'trim|required|matches[password]');
            $this->form_validation->set_rules('Country_id', 'Country', 'trim|required');
            if ($this->form_validation->run() == FALSE) {

                
               $data['results'] = $this->Countries_model->getCountries();

                $this->load->view('static/header');
                $this->load->view('auth/signup',$data);
                $this->load->view('static/footer');
            } else {
                $input_data['usr_fname'] = $this->input->post('fname');
                $input_data['usr_lname'] = $this->input->post('lname');
                $input_data['usr_email'] = $this->input->post('email');
                $input_data['usr_password'] = $this->input->post('password');
                $input_data['usr_password'] = $this->encrypt->encode($input_data['usr_password']);
                $input_data['cntry_id'] = $this->input->post('Country_id');
                $input_data['usr_actv_link'] = generate_random() . time();
                


                $user_id = 0;
                $user_id = $this->user_model->update_user($input_data);

                if (!empty($user_id)) {

                    $this->user_create_activation_sendmail($input_data);
                    $this->session->set_flashdata('success', 'Activation link sent to your email. Please active.');
                    redirect('user/signup');
                } else {
                    $this->session->set_flashdata('failure', 'Thre was a problem please try again later.');
                    redirect('user/signup');
                }
            }
        } else {

 $data['results'] = $this->Countries_model->getCountries();

                $this->load->view('static/header');
                $this->load->view('auth/signup',$data);
                $this->load->view('static/footer');
        }
    }

    public function user_create_activation_sendmail($input_data) {
        $this->load->helper('auth/email_helper');
        $template_config = array(
            'type' => 'send_activation_link',
            'name' => ucwords($input_data['usr_fname']),
            'email' => $input_data['usr_email'],
            'user_activation_link' => $input_data['usr_actv_link']
        );
        $message_details = message_template($template_config);
        $headers = "From: Accounts (noreply@gpads.co.za)";
        $mail_config = array('to' => $input_data['usr_email'],
            'subject' => 'User Activation Link',
            'message' => $message_details,
            'headers' => $headers
        );
        send_email($mail_config);
    }

    public function active_user() {
        $random_string = $this->uri->segment(3);

        $user_details = $this->user_model->get_user_details_by_randomstring($random_string);
        if (!empty($user_details)) {
            $status = $this->user_model->update_active_user($random_string);
            if ($status == 1) {
                $this->session->set_flashdata('success', 'Your account has been activated. Please login..');
                redirect('user');
            } else {
                $this->session->set_flashdata('failure', 'There was a problem to activate your account. Try again later.');
                redirect('user');
            }
        } else {
            $this->session->set_flashdata('failure', 'Acount already activated. Please login..');
            redirect('user');
        }
    }


    public function home() {

        check_user_sess();

        redirect('/Welcome');
    }


    public function forget_password() {
        if (!empty($this->input->post())) {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            if ($this->form_validation->run() == FALSE) {
                $page['ptitle'] = 'Welcome';

                $this->load->view('static/header', $page);
                $this->load->view('auth/forget_password');
                $this->load->view('static/footer');
            } else {
                $email = $this->input->post('email');
                $status = $this->user_model->check_email_exist($email);
                if ($status == 1) {
                    $user_details = $this->user_model->get_user_details($email);

                    $data['frgt_psswd_rndm_str'] = generate_random() . time();
                    $data['usr_email'] = $email;

                    $forget_password_status = $this->user_model->update_forget_password_random_string($data);
                    if ($forget_password_status) {
                        $email_data = array();
                        $email_data['email'] = $user_details['usr_email'];
                        $email_data['first_name'] = $user_details['usr_fname'];
                        $email_data['last_name'] = $user_details['usr_lname'];
                        $email_data['reset_password_link'] = $data['frgt_psswd_rndm_str'];
                        $this->user_forget_sendmail($email_data);
                        $this->session->set_flashdata('success', 'Please check your email. The password reset link has been sent your email.');
                        redirect('user/forget_password');
                    } else {
                        $this->session->set_flashdata('failure', 'Thre was a problem please try again later.');
                        redirect('user/forget_password');
                    }
                } else {
                    $this->session->set_flashdata('failure', 'Email does not exist.');
                    redirect('user/forget_password');
                }
            }
        } else {
            if (!empty($this->session->userdata('logged_in')))
                redirect('user/home');



            $this->load->view('static/header');
            $this->load->view('auth/forget_password');
            $this->load->view('static/footer');
        }
    }

    

    public function user_forget_sendmail($email_data) {
        $this->load->helper('auth/email_helper');
        $template_config = array(
            'type' => 'forget_password',
            'email' => $email_data['email'],
            'first_name' => $email_data['first_name'],
            'last_name' => $email_data['last_name'],
            'reset_password_link' => $email_data['reset_password_link'],
        );
        $message_details = message_template($template_config);

        $headers = "From: Accounts (noreply@gpads.co.za)";
        $mail_config = array('to' => $email_data['email'],
            'subject' => 'Password Request',
            'message' => $message_details,
            'headers' => $headers
        );
        send_email($mail_config);
    }

   

    public function reset_password() {
        $random_string = $this->uri->segment(3);
        $user_details = $this->user_model->get_user_details_reset_password($random_string);
        if (!empty($user_details)) {
            if ($random_string == $user_details['frgt_psswd_rndm_str']) {
                if ($this->input->post()) {
                    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
                    $this->form_validation->set_rules('conf_password', 'Confirm Password', 'trim|required|matches[password]');
                    if ($this->form_validation->run() == FALSE) {
                        $data = array();
                        $data['random_string'] = $random_string;

                        $this->load->view('static/header');
                        $this->load->view('auth/password_reset', $data);
                        $this->load->view('static/footer');
                    } else {
                        $password = $this->input->post('password');
                        $input_data['password'] = $this->encrypt->encode($password);
                        $input_data['email'] = $user_details['usr_email'];
                        $input_data['reset_password_link'] = $random_string;
                        $status = $this->user_model->update_password($input_data);
                        if ($status) {
                            $this->user_model->update_reset_link($input_data['email']);
                            $this->session->set_flashdata('success', 'Password reset was successfully complete. Please login with new password.');
                            redirect('user');
                        } else {
                            $this->session->set_flashdata('failure', 'There was a problem. Please try again later..');
                            redirect('user/forget_password');
                        }
                    }
                } else {
                    $data = array();
                    $data['random_string'] = $random_string;

                    $page['ptitle'] = 'Welcome';

                    $this->load->view('static/header', $page);
                    $this->load->view('auth/password_reset', $data);
                    $this->load->view('static/footer');
                }
            } else {
                $this->session->set_flashdata('failure', 'Invalid request.');
                redirect('user/forget_password');
            }
        } else {
            $this->session->set_flashdata('failure', 'Invalid request.');
            redirect('user/forget_password');
        }
    }

   

    public function change_password() {
        check_user_sess();
        if ($this->input->post()) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
            $this->form_validation->set_rules('conf_password', 'Confirm Password', 'trim|required|matches[password]');
            if ($this->form_validation->run() == FALSE) {
                $page['ptitle'] = 'Welcome';

                $this->load->view('static/header', $page);
                $this->load->view('auth/change_password');
                $this->load->view('static/footer');
            } else {
                $password = $this->input->post('password');
                $input_data['password'] = $this->encrypt->encode($password);
                $input_data['email'] = $this->session->userdata('logged_in')['email'];
                $status = $this->user_model->update_change_password($input_data);
                if ($status) {
                    $this->session->set_flashdata('success', 'Password reset was successfully complete.');
                    redirect('user/change_password');
                } else {
                    $this->session->set_flashdata('failure', 'There was a problem. Please try again later..');
                    redirect('user/change_password');
                }
            }
        } else {


            $this->load->view('static/header');
            $this->load->view('auth/change_password');
            $this->load->view('static/footer');
        }
    }

   

    public function logout() {
        check_user_sess();
        if ($this->session->userdata('logged_in')) {
            $this->session->unset_userdata('logged_in');
            $this->session->sess_destroy();
            redirect('welcome');
        }
    }

}
