<?php defined('BASEPATH') or exit('No direct script access allowed');

abstract class MY_Logintemplate extends MX_Controller{
    
    protected $_js = JS_LOGIN_CUSTOMER;
    protected $_background_login = "";
    protected $_form_image = "";
    protected $_logo_image = "";
    protected $_login = LOGIN;

    public function __construct()
    {
        parent::__construct();
        
        $this->template->set_layout($this->_login,THEME);
        $this->template->set('js', $this->_js);
        
        if($this->_background_login != ""){
            $this->template->set('background_login',$this->_background_login);
        }
        if($this->_form_image != ""){
            $this->template->set('form_image',$this->_form_image);
        }

        if($this->_logo_image != ""){
            $this->template->set("logo_image",$this->_logo_image);
        }

    }


}