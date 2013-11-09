<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends CI_Controller {
    
    var $title = 'cmstest - manage', $static;
    var $publish_priviledge = 1;
    
    function __construct()
    {
        parent::__construct();
        $this->static = (object)array();
    }
    
    public function publish($id = 0, $is_update_event = 0, $ajax = 0)
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
            // 经过思考，还是先做单分类。
            $category_id = intval($post['category']);
            if (false === $this->article_model->isCategoryExists(null, $category_id))
            {
                if ($ajax)
                {
                    echo json_decode(array('no' => 1, 'msg' => '分类不存在'));
                }
                else
                {
                    $static = $this->static;
                    $static->title = '错误';
                    $static->msg = '发表文章失败，因为指定的分类不存在。您可能必须选择一个分类。';
                    $this->load->view('dochead', $static);
                    $this->load->view('error', $static);
                    $this->load->view('docfoot', $static);
                }
                return;
            }
            $r = $this->manage_model->publishArticle($id, $is_update_event, $category_id, $post['title'], $post['content']);
            if ($ajax)
            {
                echo json_encode($r);
            }
            else
            {
                if ($r->no != 0)
                {
                    $static = $this->static;
                    $static->title = '错误';
                    $static->msg = $r->msg;
                    $this->load->view('dochead', $static);
                    $this->load->view('error', $static);
                    $this->load->view('docfoot', $static);
                }
                else
                {
                    $static = $this->static;
                    $static->title = $r->title;
                    
        }
    }     
}
?>