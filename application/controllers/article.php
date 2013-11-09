<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller {
    var $article_count = 10;
    var $title = 'cmstest';
    var $static;
    
    function __construct()
    {
        parent::__construct();
        $this->static = (object)array();
    }
    
	public function displist($start = 0)
	{
		$this->load->model('article_model');
        $list = $this->article_model->getList(intval($start), $this->article_count);
        $static = $this->static;
        $static->title = $this->title;
        $this->load->view('dochead', $static);
        $this->load->view('article_list', array(
            'title' => $this->title,
            'list' => $list
        ));
        $this->load->view('docfoot', $static);
	}
    
    public function category($category, $ajax = 0, $start = 0)
    {
        if (!isset($category))
        {
            if (!$ajax)
            {
                $static = $this->static;
                $static->title = '错误';
                $static->msg = 'category未指定';
                $this->load->view('dochead', $static);
                $this->load->view('error', $static);
                $this->load->view('docfoot', $static);
            }
            else
            {
                echo json_encode(array('no' => 1, 'msg' => 'category未指定'));
            }
            return;
        }
        $this->load->model('article_model');
        $list = $this->article_model->getCategoryList(intval($start), $this->article_count, $category);
        if (!$ajax)
        {
            $static = $this->static;
            $static->title = $this->title;
            $this->load->view('dochead', $static);
            $this->load->view('article_list', array(
                'title' => $this->title,
                'list' => $list
            ));
            $this->load->view('docfoot', $static);
        }
        else
        {
            $json = (object)array(
                'no' => 0,
                'msg' => '已读取',
                'title' => $this->title,
                'list' => $list
            );
            echo json_encode($json);
        }
    }
    
    public function disp($id, $ajax = 0)
    {
        $id = intval($id);
        if ($id <= 0)
        {
            if (!$ajax)
            {
                $static = $this->static;
                $static->title = '错误';
                $static->msg = '无效的文章id';
                $this->load->view('dochead', $static);
                $this->load->view('error', $static);
                $this->load->view('docfoot', $static);
            }
            else
            {
                echo json_encode(array('no' => 1, 'msg' => '无效的文章id'));
            }
            return;
        }
        $this->load->model('article_model');
        $article = $this->article_model->getArticleObj($id);
        if ($article->no != 0)
        {
            if (!$ajax)
            {
                $static = $this->static;
                $static->title = '错误';
                $static->msg = $article->msg;
                $this->load->view('dochead', $static);
                $this->load->view('error', $static);
                $this->load->view('docfoot', $static);
            }
            else
            {
                echo json_encode(array('no' => $article->no, 'msg' => $article->msg));
            }
            return;
        }
        // succeed & display article
        if (!$ajax)
        {
            $static = $this->static;
            $static->title = $article->title;
            $this->load->view('dochead', $static);
            $this->load->view('article_disp', $article);
            $this->load->view('docfoot', $static);
        }
        else
        {
            $article->msg = '已读取';
            echo json_encode($article);
        }
    }
    
    public function cat_cache()
    {
        $this->load->model('article_model');
        echo "开始缓存……\n";
        echo $this->article_model->cacheCategory()."\n";
        echo "缓存结束，请检查状态。\n";
    }
}
?>