<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends CI_Controller {
    
    var $title = 'cmstest - manage', $static;
    var $publish_priviledge = 1;
    
    function __construct()
    {
        parent::__construct();
        $this->static = (object)array();
    }
    
    public function publish($id, $is_update_event, $ajax = 0)
    {
        $this->load->model('user_model');
        $this->load->model('manage_model');
        $this->load->model('article_model');
        if (false === $this->user_model->checkUser($this->$publish_priviledge))
        {
            if ($ajax)
            {
                echo json_decode(array('no' => 1, 'msg' => '越权请求'));
            }
            else
            {
                $static = $this->static;
                $static->title = '错误：操作被拒绝';
                $static->msg = '您不能发表文章：这是一个越权请求';
                $this->load->view('dochead', $static);
                $this->load->view('error', $static);
                $this->load->view('docfoot', $static);
            }
        }
        else
        {
            // 特权足够
            $post = $this->input->post();
            if ($ajax)
            {
                $categories = json_decode($post['categores']);
                if (!is_array($categories))
                {
                    echo json_decode(array('no' => 1, 'msg' => '数据解析失败：categories非数组.'));
                    exit;
                }
                
            }
        }
    }     
}
?>