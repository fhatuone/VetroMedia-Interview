<?php
    function message_template($template_config)
    {
        $result = array();

        $msg_config = $template_config;
        
        if($msg_config['type']=='send_activation_link')
        {
            $result['message'] = '<p>Hello &nbsp;&nbsp;'.$msg_config['name'].'</p>';
            $result['message'] .= '<p>Your activation link as below. Please click this link to activate your account.</p>';
            $result['message'] .= '<p><a href="'.base_url('user/active_user').'/'.$msg_config['user_activation_link'].'" target="_blank">Activation Link</a></p>';
            $result['message'] .= '<p>Alternatively, copy link below and paste into your web browser`s address bar and press enter.</p>';
            $result['message'] .= '<p>'.base_url('user/active_user').'/'.$msg_config['user_activation_link'].'</p>';
        }
        else if($msg_config['type']=='forget_password')
        {
            $result['message'] = '<p>Hello &nbsp;&nbsp;'.$msg_config['first_name'].' '.$msg_config['last_name'].'</p>';
            $result['message'] .= '<p>Your password reset link as below. Please click this link to reset your account password.</p>';
            $result['message'] .= '<p><a href="'.base_url('user/reset_password').'/'.$msg_config['reset_password_link'].'" target="_blank">Reset Password Link</a></p>';
            $result['message'] .= '<p>Alternatively, copy link below and paste into your web browser`s address bar and press enter.</p>';
            $result['message'] .= '<p>'.base_url('user/reset_password').'/'.$msg_config['reset_password_link'].'</p>';
            
        }
        
        else if($msg_config['type']=='confirm_order')
        {
            $result['message'] = '<p>Hello &nbsp;&nbsp;'.$msg_config['first_name'].'</p>';           
            $result['message'] .= '<p>Your Order Confirmation as bellow. Please verify that everything is in order.</p>';
            $result['message'] .= '<p>'.'You spent &nbsp;&nbsp;'.$msg_config['spent'].'From your account'.'</p>';
                      
        }
        
        return $result;
    }
    

    function send_email($email_data)
    {
        $CI = & get_instance();

        $CI->load->library('email');
        

        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.googlemail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'algpads@gmail.com';
        $config['smtp_pass']    = 'Pass1230';
        $config['charset']    = 'iso-8859-1';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; 
        $CI->email->initialize($config);

        $CI->email->from('noreply@gpads.co.za', 'vetro Media');
        $CI->email->to($email_data['to']);
        $CI->email->subject($email_data['subject']);

        $body = $CI->load->view('auth/email_template',$email_data['message'],TRUE); 
        $CI->email->message($body); 

        if($CI->email->send())
            return "email sent!";
        else 
            return "failed";
    }
?>
