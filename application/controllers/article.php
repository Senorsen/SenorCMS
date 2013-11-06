<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller {
    var $article_count = 10;
    var $view = '', $title = 'cmstest';
    var $footer, $header, $static;
    
    function __construct()
    {
        parent::__construct();
        $this->footer = file_get_contents('footer.html');
        $this->header = file_get_contents('header.html');
        $this->static = (object)array('header' => $this->header, 'footer' => $this->footer);
    }
    
	public function displist($start = 0)
	{
		$this->load->model('article_model');
        $list = $this->article_model->getList(intval($start), $this->article_count);
        $this->load->view('article_list', array(
            'title' => $this->title,
            'list' => $list,
            'static' => $this->static
        ));
	}
    
    public function category($category, $start = 0)
    {
        if (!isset($category)) return;
        $this->load->model('article_model');
        $list = $this->article_model->getCategoryList(intval($start), $this->article_count, $category);
        $this->load->view('article_list', array(
            'title' => $this->title,
            'list' => $list,
            'static' => $this->static
        ));
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
        $article->static = $this->static;
        $this->load->view('article_disp', $article);
    }
    
    public function cat_cache()
    {
        $this->load->model('article_model');
        echo $this->article_model->cacheCategory();
    }
}
