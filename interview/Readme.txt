This application was developed and tested on a Linux Mint 18.1 Serena and all the thoughts in its environment setup was based on Linux Mint
However the same setup should run without any hastle on Ubuntu and Debian distributions

How to setup and run this application

1. Extract or unzip the project

# project name "interview"

=== Project Setup ==

1. Project Location

Go to /var/www/html/ and extract your project there.

2. Project rights and ownership

* Assign apache web server the right to own this project
#  sudo chown -R www-data:www-data /var/www/html/interview

*Allow this progect to the writerble
# sudo chmod -R 777 /var/www/html/interview

=== Database Setup ==

3. Database Setup
Run setup script from the top level of the projects directory

* Go inside the "interview" directory 
# cd interview

* Run the sql script to setup database (NB: Please note that my database user account was "root" in my case)
# mysql -u root -p < currency_converter1.sql

=== PHP Setup ===
NB: Please note that this application was developed with CodeIgniter Framework version 3

4. Go to config directory so that you can start setting up your envinronment cd /interview/application/config

# cd /interview/application/config
* Should you want to change the database name, please edit this file database.php and modify this part ('database' => 'Cur_Converter'), to be what you would like it to be according to your preferances.
* Should you want to change the base URL, please edit this file config.php and modify this part ('$config['base_url'] = 'http://localhost/'), to be what you would like it to be according to your preferances. 


=== Email setup ===

* Go to this path /interview/application/helpers/auth
# cd /interview/application/helpers/auth

NB: 

Please note that I used the email addresses called 'yourgmailaccout@gmail.com', 'noreply-yourgmailaccout@gmail.com' 
and password called 'yourpassword' as a place holder of where you have to replace this email with your own working gmail account.

* Edit this file email_helper.php and setup your email details there.

    function send_email($email_data)
    {
        $CI = & get_instance();

        $CI->load->library('email');


        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.googlemail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'yourgmailaccout@gmail.com';
        $config['smtp_pass']    = 'yourpassword';
        $config['charset']    = 'iso-8859-1';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; 
        $CI->email->initialize($config);

        $CI->email->from('noreply-yourgmailaccout@gmail.com', 'Fhatuwani Manyane Assesstest Test');
        $CI->email->to($email_data['to']);
        $CI->email->subject($email_data['subject']);

        $body = $CI->load->view('auth/email_template',$email_data['message'],TRUE); 
        $CI->email->message($body); 

        if($CI->email->send())
            return "email sent!";
        else 
            return "failed";
    }

=== Installing/updating needed vendor packages

6. Please note that composer is used for package management system on this application

* To install or update packages run the command below

# composer install
# composer update









