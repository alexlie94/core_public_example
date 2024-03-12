<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends MY_Model
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users;
        parent::__construct();
    }

    public function _validate()
    {

        $response = array('success' => false, 'validate' => true, 'messages' => array());

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[25]|xss_clean');
        $this->form_validation->set_error_delimiters('<div class="'.VALIDATION_MESSAGE_FORM.'">', '</div>');

        if ($this->form_validation->run() === false) {
            $response['validate'] = false;
            foreach ($this->input->post() as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        return $response;
    }

    public function _check_email($email,$password)
    {
        $get = $this->get(array('email' => $email));
        try {

            if(!$get){
                throw new Exception("Error Processing Request", 1);
                
            }

            if (!password_verify($password, $get->password)) {
                throw new Exception("Error Processing Request", 1);
                
            }

            return true;

        } catch (Exception $e) {
            return false;
        }

    }

    public function _check()
    {
        $email = clearInput($this->input->post('email'));
		$password = clearInput($this->input->post('password'));

        $text_message = 'Invalid Email or Password.';
        $text_suspended = 'This Account has been suspended';

        try {

            $response = $this->_validate();
            if($response['validate'] === false){
                throw new Exception();
            }

            $check = $this->_check_email($email,$password);
            if($check === false){
                $response['messages'] = $text_message;
                throw new Exception("Error Processing Request", 1);
                
            }

            $this->_ci->load->model('app/App_model','app_model');

            $check = $this->_ci->app_model->_check_data_user($email);
            if($check === false){
                $response['messages'] = $text_suspended;
                throw new Exception("Error Processing Request", 1);
                
            }

            $response = $this->_ci->app_model->createSession($email);
            return $response;
        } catch (Exception $e) {
            return $response;
        }
    }

}
