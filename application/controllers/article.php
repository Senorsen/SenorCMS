<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller {
    var $article_count = 10, $article_cat_count = 10;
    var $title = 'SenorCMS';
    var $static;
    
    function __construct()
    {
        parent::__construct();
        $this->static = (object)array();
        $this->load->helper('url');
        $this->load->library('session');
    }
    
    public function index()
    {
        $this->displist();
    }
    
	public function displist($page = 0, $count = -1, $ajax = 0)
	{
        if ($count == -1) $count = $this->article_count;
		$this->load->model('article_model');
        $list = $this->article_model->getList(intval($page), $this->article_count);
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
            $r = array('no' => 0, 'msg' => '', 'title' => $this->title, 'list' => $list);
            echo json_encode($r);
        }
	}
    
    public function category($category, $page = 0, $count = -1, $ajax = 0)
    {
        if ($category == 'all') return $this->displist($page, $count, $ajax);
        if ($count == -1) $count = $this->article_cat_count;
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
        $r = $this->article_model->getCategoryList(intval($page), $count, $category);
        $list = $r->list;
        if (!$ajax)
        {
            $static = $this->static;
            $static->title = $r->category_name . ' - ' . $this->title;
            $this->load->view('dochead', $static);
            $this->load->view('article_list', array(
                'category_name' => $r->category_name,
                'list' => $list
            ));
            $this->load->view('docfoot', $static);
        }
        else
        {
            $json = (object)array(
                'no' => 0,
                'msg' => '已读取',
                'title' =>  $r->category_name. ' - ' . $this->title,
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