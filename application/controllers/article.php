<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller {
    var $article_count = 10;
    var $view = '', $title = 'cmstest';
    
	public function displist($start = 0)
	{
		$this->load->model('article_model');
        $list = $this->article_model->getList(intval($start), $this->article_count);
        $this->load->view('article_list', array('title' => $this->title, 'list' => $list));
	}
    
    public function category_list($category, $start = 0)
    {
        if (!isset($category)) return;
        $this->load->model('article_model');
        $list = $this->article_model->getCategoryList(intval($start), $this->article_count, $category);
        $this->load->view('article_list', array('title' => $this->title, 'list' => $list));
    }
}
