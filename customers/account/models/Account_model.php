<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account_model extends MY_ModelCustomer
{
    use MY_Tables;

    public function __construct()
    {
        $this->_tabel = $this->_table_users;
        parent::__construct();
    }

    private function _validate()
    {
        $response = ['success' => false, 'validate' => true, 'messages' => []];
        $response['type'] = 'update';
        $role_validate = ['trim', 'required', 'xss_clean'];
        $role_email = array('trim', 'required', 'valid_email', 'xss_clean');
        $role_old_password = array('trim', 'required', 'xss_clean', 'min_length[8]', 'max_length[15]');
        $role_new_password = array('trim', 'required', 'xss_clean', 'min_length[8]', 'max_length[15]');
        $role_con_password = array('trim', 'required', 'xss_clean', 'min_length[8]', 'max_length[15]');
        $email_check = array(
            'email_check', function ($value) {
                $id = $_POST['id'];
                if (!empty($value) || $value != '') {
                    try {
                        $cek = $this->db->where(array('email' => $value, 'id <>' => $id))->get('users')->num_rows();
                        if ($cek) {
                            throw new Exception();
                        }
                        return true;
                    } catch (Exception $e) {
                        $this->form_validation->set_message('email_check', 'The {field} already used');
                        return false;
                    }
                }
            }
        );
        array_push($role_email, $email_check);

        $this->form_validation->set_rules('fullname', 'Fullname', $role_validate);
        $this->form_validation->set_rules('email', 'Email', $role_email);

        if ($response['type'] == 'update') {
            $oldPassword_check = array(
                'oldPassword_check', function ($value) {
                    $id = $_POST['id'];
                    if (!empty($value) || $value != '') {
                        try {
                            $get = $this->get(array('id' => $id));
                            $length = strlen($value);
                            if ($length < 8 || ($length > 8  && $length < 15)) {
                                throw new Exception;
                            }
                            if (!password_verify($value, $get->password)) {
                                $this->form_validation->set_message('oldPassword_check', 'The password you entered is incorect');
                                return false;
                            }

                            return true;
                        } catch (Exception $e) {
                            $this->form_validation->set_message('oldPassword_check', 'The {field} min length 8 character & max length 15 character');
                            return false;
                        }
                    }
                }
            );
            array_push($role_old_password, $oldPassword_check);

            $newPassword_check = array(
                'newPassword_check', function ($value) {
                    $conPass = $_POST['conPass'];
                    if (!empty($value) || $value != '') {
                        try {
                            $length = strlen($value);
                            if ($length < 8 || ($length > 8  && $length < 15)) {
                                throw new Exception;
                            }
                            if ($value != $conPass) {
                                $this->form_validation->set_message('newPassword_check', 'New Password And Confirmation Password Not Similar');
                                return false;
                            }

                            return true;
                        } catch (Exception $e) {
                            $this->form_validation->set_message('newPassword_check', 'The {field} min length 8 character & max length 15 character');
                            return false;
                        }
                    }
                }
            );
            array_push($role_new_password, $newPassword_check);

            $conPassword_check = array(
                'conPassword_check', function ($value) {
                    $newPass = $_POST['newPass'];
                    if (!empty($value) || $value != '') {
                        try {
                            $length = strlen($value);
                            if ($length < 8 || ($length > 8  && $length < 15)) {
                                throw new Exception;
                            }
                            if ($value != $newPass) {
                                $this->form_validation->set_message('conPassword_check', 'New Password And Confirmation Password Not Similar');
                                return false;
                            }

                            return true;
                        } catch (Exception $e) {
                            $this->form_validation->set_message('conPassword_check', 'The {field} min length 8 character & max length 15 character');
                            return false;
                        }
                    }
                }
            );
            array_push($role_con_password, $conPassword_check);
        }

        if ($this->input->post('myCheck') == "1") {
            $this->form_validation->set_rules('oldPass', 'Old Password', $role_old_password);
            $this->form_validation->set_rules('newPass', 'New Password', $role_new_password);
            $this->form_validation->set_rules('conPass', 'Confirm Password', $role_con_password);
        }

        $this->form_validation->set_error_delimiters('<div class="' . VALIDATION_MESSAGE_FORM . '">', '</div>');

        if ($this->form_validation->run() === false) {
            $response['validate'] = false;
            foreach ($this->input->post() as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        return $response;
    }

    public function save()
    {
        $this->db->trans_begin();
        try {
            $response = self::_validate();

            if (!$response['validate']) {
                throw new Exception('Error Processing Request', 1);
            }

            $fullname = clearInput($this->input->post('fullname'));
            $email = clearInput($this->input->post('email'));
            $oldPass = clearInput($this->input->post('oldPass'));
            $newPass = clearInput($this->input->post('newPass'));
            $conPass = clearInput($this->input->post('conPass'));

            $data = $this->get(['id' => $_POST['id']]);
            if (!$data) {
                $response['messages'] = 'Data update invalid';
                throw new Exception();
            }

            if ($newPass == "" && $oldPass == "" && $conPass == "") {
                $data_array = [
                    'fullname' => $fullname,
                    'email' => $email,
                ];
            } elseif ($oldPass != "" && $newPass != "" && $conPass != "") {
                $data_array = [
                    'fullname' => $fullname,
                    'email' => $email,
                    'password' => $newPass,
                ];
            } else {
                $response['messages'] = 'Data update invalid';
                throw new Exception();
            }

            if (strlen($newPass) < 8) {
                unset($data_array['password']);
            } else {
                $data_array['password'] = password_hash($newPass, PASSWORD_DEFAULT);
            }

            $process = $this->update(['id' => $_POST['id']], $data_array);

            if (!$process) {
                $response['messages'] = 'Failed Update Data Account';
                throw new Exception();
            }

            $this->db->trans_commit();
            $response = $this->_ci->app_model->createSession($email);
            $response['messages'] = 'Successfully Update Data Account';
            $response['success'] = true;
            return $response;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $response;
        }
    }
}
