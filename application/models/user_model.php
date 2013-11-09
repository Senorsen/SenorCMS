<?php
class User_model extends CI_Model {
    
    var $user_session_key = 'userlogin';
    
    function __construct()
    {
        parent::__construct();
    }
    
    function saltPassword($username, $password)
    {
        $salt_h = 'Senorsen-';
        $salt_f = '-Freda';
        $salt = sha1(sha1($salt_h.$password.$salt_f).$username);
        return $salt;
    }
    
    function checkUser($required_priviledge = 0)
    {
        $this->load->library('session');
        $loginstate = $this->session->userdata($this->user_session_key);
        if ($loginstate === false)
        {
            $ret = false;
        }
        else
        {
            $user_session_arr = $this->session->userdata($this->user_session_key);
            if (($lret = validateLogin($user_session_arr->username, $user_session_arr->password, true)->no) == 0)
            {
                if ($lret->priviledge < $required_priviledge)
                {
                    $ret = false;
                }
                else
                {
                    $ret = (object)array('id' => $lret->id, 'username' => $lret->username, 'priviledge' => $lret->priviledge);
                }
            }
            else
            {
                $ret = false;
                $this->session->unset_userdata($this->user_session_key);
            }
        }
        return $ret;
    }
    
    function validateLogin($username, $password, $inner_call = false)
    {
        if (!$inner_call)
        {
            $this->load->library('session');
        }
        $this->load->database();
        $sql = "SELECT `priviledge`,`username`,`id`,`password` FROM `tb_user` WHERE `username`=? AND `password`=? AND `hidden`=0";
        $row = $this->db->query($sql, array($username, $this->saltPassword($username, $password)))->first_row();
        $ret = array();
        if ($row == false || count($row) == 0)
        {
            $ret = array('no' => 1, 'msg' => '用户验证失败');
        }
        else
        {
            if (!$inner_call)
            {
                $user_session_arr = array(
                    'id' => $row->id,
                    'username' => $row->username,
                    'password' => $row->password,
                    'priviledge' => $row->previledge
                );
                $this->session->set_userdata($this->user_session_key, $user_session_arr);
            }
            $ret = array(
                'no' => 0,
                'msg' => '成功登录',
                'id' => $row->id,
                'username' => $row->username,
                'previledge' => $row->previledge
            );
        }
        return (object)$ret;
    }
    
    function registerNewUser($username, $password, $ip)
    {
        $this->load->database();
        $sql = "SELECT `username` FROM `tb_user` WHERE `username`=?";
        $row = $this->db->query($sql, array($username))->first_row();
        $pwd = $this->saltPassword($username, $password);
        if ($row == false || count($row) == 0)
        {
            // no result
            $sql = "INSERT INTO `tb_user` (`username`,`password`,`regip`,`regdate`) VALUES (?,?,?,NOW())";
            $this->db->query($sql, array($username, $pwd, $ip));
            if ($this->db->affected_rows() > 0)
            {
                $id = $this->db->insert_id();
                $ret = array('no' => 0, 'info' => $id.':'.htmlspecialchars($username).' 注册成功');
            }
            else
            {
                $ret = array('no' => 1, 'info' => '插入失败 debug '.$this->db->last_query());
            }
        }
        else
        {
            // specific user exists
            $ret = array('no' => 1, 'info' => '用户已存在');
        }
        return (object)$ret;
    }
}
?>