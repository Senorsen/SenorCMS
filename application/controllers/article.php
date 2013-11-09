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
    
    public function category($category, $start = 0)
    {
        if (!isset($category)) return;
        $this->load->model('article_model');
        $list = $this->article_model->getCategoryList(intval($start), $this->article_count, $category);
        $static = $this->static;
        $static->title = $this->title;
        $this->load->view('dochead', $static);
        $this->load->view('article_list', array(
            'title' => $this->title,
            'list' => $list
        ));
        $this->load->view('docfoot', $static);
    }
    
    public function disp($id)
    {
        $id = intval($id);
        if ($id <= 0)
        {
            $this->load->view('error', array('msg' => '无效的文章id'));
            return;
        }
        $this->load->model('article_model');
        $article = $this->article_model->getArticleObj($id);
        if ($article->no != 0)
        {
            $this->load->view('error', array('msg' => $article->msg));
            return;
        }
        $static = $this->static;
        $static->title = $article->title;
        $this->load->view('dochead', $static);
        $this->load->view('article_disp', $article);
        $this->load->view('docfoot', $static);
    }
    
    public function cat_cache()
    {
        $this->load->model('article_model');
        echo $this->article_model->cacheCategory();
    }
}
?>