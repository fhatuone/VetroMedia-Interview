<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('auth/calc_helper');
    }

    public function index() {
        $data['results'] = $this->Countries_model->getCountries();
        $data['result2'] = $this->Currencies_model->get_currency();

        if (count($data['result2']) > 0) {
            foreach ($data['result2']['currencies'] as $currencyKey => $currencyValue) {
                if (count($data['results']['countries']) > 0) {
                    foreach ($data['results']['countries'] as $countryKey => $countryValue) {
                        if ($currencyValue->cntry_id == $countryValue->cntry_id) {
                            $data['result2']['currencies'][$currencyKey]->cntry_code = $countryValue->cntry_code;
                            $data['result2']['currencies'][$currencyKey]->cntry_name = $countryValue->cntry_name;
                        }
                    }
                }
            }
        }

        $data['result'] = $this->Currencies_model->get_acc_bal();

        $this->load->view('static/header');
        $this->load->view('currency/index', $data);
        $this->load->view('static/footer');
    }

    public function update_balance() {
        $this->form_validation->set_rules('new_value', 'New Amount', 'trim|required');
        $this->form_validation->set_rules('Trans_type', 'Transaction Type', 'trim|required');
        $this->form_validation->set_rules('user_id', 'Login', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $data['result'] = $this->Currencies_model->get_acc_bal();
            $this->load->view('static/header');
            $this->load->view('currency/update_balance', $data);
            $this->load->view('static/footer');
        } else {
            if ($this->input->post('Trans_type') === 'Deposit') {
                $balance = $this->input->post('old_balance');
                $input_data['amnt_deposit'] = $this->input->post('new_value');
                $input_data['acc_trans'] = $this->input->post('Trans_type');
                $input_data['acc_balance'] = $input_data['amnt_deposit'] + $balance;
                $input_data['date_created'] = date('Y-m-d H:i:s');
                $input_data['usr_id'] = $this->input->post('user_id');
                $input_data['acc_type'] = 'USD';


                if ($this->Currencies_model->update_account($input_data)) {
                    redirect();
                }
            } elseif ($this->input->post('Trans_type') === 'Withdraw') {

                $balance = $this->input->post('old_balance');
                $input_data['amnt_withdraw'] = $this->input->post('new_value');
                $input_data['acc_trans'] = $this->input->post('Trans_type');
                $input_data['acc_balance'] = $balance - $input_data['amnt_withdraw'];
                $input_data['date_created'] = date('Y-m-d H:i:s');
                $input_data['usr_id'] = $this->input->post('user_id');
                $input_data['acc_type'] = 'USD';

                $this->Currencies_model->update_account($input_data);
                redirect();
            }
        }
    }

    public function transactions() {

        $data['result'] = $this->Currencies_model->get_transactions();

        $this->load->view('static/header');
        $this->load->view('currency/transaction', $data);
        $this->load->view('static/footer');
    }

    public function purchase() {

        $id = $this->uri->segment(3);

        if (empty($id)) {
            show_404();
        }

        $exchange_rate = 0;
        $country_code = '';

        $data['results'] = $this->Countries_model->getCountries();
        $data['result2'] = $this->Currencies_model->get_currency();

        if (count($data['result2']) > 0) {
            foreach ($data['result2']['currencies'] as $currencyKey => $currencyValue) {
                if (count($data['results']['countries']) > 0) {
                    foreach ($data['results']['countries'] as $countryKey => $countryValue) {
                        if ($currencyValue->cntry_id == $countryValue->cntry_id && $id == $data['result2']['currencies'][$currencyKey]->curr_id) {
                            $country_code = $countryValue->cntry_code;
                            $exchange_rate = $data['result2']['currencies'][$currencyKey]->curr_amount;
                            $data['result2']['currencies'][$currencyKey]->cntry_code = $countryValue->cntry_code;
                            $data['result2']['currencies'][$currencyKey]->cntry_name = $countryValue->cntry_name;
                        }
                    }
                }
            }
        }

        $data['result'] = $this->Currencies_model->get_acc_bal();

        $this->form_validation->set_rules('amount_paid', 'Enter value to inverst', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('static/header');
            $this->load->view('currency/purchase', $data);
            $this->load->view('static/footer');
        } else {
            $input_order['fr_curr_pchsd'] = $country_code;
            $input_order['exch_rate'] = $exchange_rate;

            $input_order['surch_perc'] = get_surch($country_code);
            $input_order['fr_amnt_pchsd'] = amnt_pchsd($exchange_rate, $this->input->post('amount_paid'));

            $input_order['usd_amnt_paid'] = $this->input->post('amount_paid');
            $input_order['surch_amnt'] = $this->input->post('amount_paid') * get_surch($country_code);
            $input_order['order_time'] = date('Y-m-d H:i:s');

            $input_order['acc_id'] = $data['result'][0]->acc_id;

            $balance = $data['result'][0]->acc_balance - $this->input->post('amount_paid');

            $input_data['acc_type'] = 'USD';
            $input_data['acc_balance'] = $balance;
            $input_data['acc_trans'] = 'Puchase';
            $input_data['amnt_withdraw'] = $this->input->post('amount_paid');
            $input_data['usr_id'] = $this->input->post('user_id');
            $input_data['date_created'] = date('Y-m-d H:i:s');



            $input_data1['acc_type'] = $country_code;
            $input_data1['acc_balance'] = amnt_pchsd($exchange_rate, $this->input->post('amount_paid'));
            $input_data1['acc_trans'] = 'Puchased';
            $input_data1['amnt_deposit'] = amnt_pchsd($exchange_rate, $this->input->post('amount_paid'));
            $input_data1['usr_id'] = $this->input->post('user_id');
            $input_data1['date_created'] = date('Y-m-d H:i:s');

            $data['user_email'] = $this->input->post('user_email');

            $this->Currencies_model->set_order($input_order);
            $this->Currencies_model->update_account($input_data);
            $this->Currencies_model->update_account($input_data1);

            $code = get_surch($country_code);

            if ($country_code === 'GBP') {
                $this->send_confirm_email($input_data);
            }


            redirect();
        }
    }

    public function send_confirm_email($input_order) {
        $this->load->helper('auth/email_helper');
        $template_config = array(
            'type' => 'confirm_order',
            'name' => ucwords($this->session->userdata('logged_in')['fname']),
            'email' => $this->session->userdata('logged_in')['email'],
            'spent' => $this->input->post('amount_paid'),
        );
        $message_details = message_template($template_config);
        $headers = "From: Accounts (noreply@gpads.co.za)";
        $mail_config = array('to' => $this->session->userdata('logged_in')['email'],
            'subject' => 'Order Confirmation',
            'message' => $message_details,
            'headers' => $headers
        );
        send_email($mail_config);
    }

}
