<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    
    var $static;
    function __construct()
    {
        parent::__construct();
        $this->static = (object)array();
        $this->load->helper('url');
    }
    
    public function index()
    {
        $static = $this->static;
        $static->title = "未定义";
        $this->load->view('dochead', $static);
        $this->load->view('error', array('msg' => '未定义的错误'));
        $this->load->view('docfoot', $static);
    }
    
    public function login_submit($ajax = 0)
    {
        $post = $this->input->post();
        $this->load->model('user_model');
        if (!isset($post['username']) || !isset($post['password']))
        {
            if (!$ajax)
            {
                $static = $this->static;
                $static->title = '错误 - 登录失败';
                $this->load->view('dochead', $static);
                $this->load->view('error', array('msg' => '登录失败：用户名或密码不能为空'));
                $this->load->view('docfoot', $static);
            }
            else
            {
                echo json_encode(array('no' => 1, 'msg' => '用户名或密码不能为空'));
            }
            return;
        }
        $ret = $this->user_model->validateLogin($post['username'], $post['password']);
        $ret->forwardURL = $post['fw'];
        if (!$ajax)
        {
            if ($ret->no != 0)
            {
                $static = $this->static;
                $static->title = '错误 - 登录失败';
                $this->load->view('dochead', $static);
                $this->load->view('error', array('msg' => '登录失败：'.$ret->msg));
                $this->load->view('docfoot', $static);
            }
            else
            {
                header('Location: '.$post['fw']);
            }
        }
        else
        {
            echo json_encode($ret);
        }
        return;
        
    }
    public function login()
    {
        
        $static = $this->static;
        $static->title = '登录页';
        $this->load->view('dochead', $static);
        $this->load->view('loginpage');
        $this->load->view('docfoot', $static);
    }
    
    public function register_submit($ajax = 0)
    {
        $post = $this->input->post();
        $this->load->model('user_model');
        $ip = $this->input->ip_address();
        if (!isset($post['username']) || !isset($post['password']) || $post['username'] == '' || $post['password'] == '')
        {
            $static = $this->static;
            $static->title = '错误';
            $this->load->view('dochead', $static);
            $this->load->view('error', array('msg' => '用户名或密码不能为空'));
            $this->load->view('docfoot', $static);
            return;
        }
        $ret = $this->user_model->registerNewUser($post['username'], $post['password'], $ip);
        $static = $this->static;
        if ($ret->no == 0)
        {
            $static->title = '注册成功';
            $this->load->view('dochead', $static);
            $this->load->view('error', array('msg' => $ret->info));
            $this->load->view('docfoot', $static);
        }
        else
        {
            $static->title = '错误';
            $this->load->view('dochead', $static);
            $this->load->view('error', array('msg' => $ret->info));
            $this->load->view('docfoot', $static);
        }
    }
        
    public function register()
    {
        $static = $this->static;
        $static->title = '用户注册';
        $this->load->view('dochead', $static);
        $this->load->view('registerpage');
        $this->load->view('docfoot', $static);
    }
}