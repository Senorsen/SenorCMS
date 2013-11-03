<?php
class Article_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function getList($start, $count = 10)
    {
        $this->load->database();
        $sql = "SELECT `id`,`title`,`author`,`pubdate`,`sort` FROM `tb_article` WHERE `hidden`=0 LIMIT ?,?";
        $query = $this->db->query($sql, array(intval($start), intval($count)));
        $ret_arr = array();
        foreach ($query->result() as $row)
        {
            array_push($ret_arr, $row);
        }
        return $ret_arr;
    }
    
    function getCategoryList($start, $count, $category)
    {
        $this->load->database();
        $sql = "SELECT `id`,`title`,`author`,`pubdate`,`sort` FROM `tb_article` WHERE `hidden`=0 AND `id` in "
            ."(SELECT `article_id` FROM `tb_category_link` WHERE `category_id` in "
            ."(SELECT `id` FROM `tb_category_def` WHERE `short_name`=? ) )"
            ." LIMIT ?,?";
        $query = $this->db->query($sql, array($category, intval($start), intval($count)));
        $ret_arr = array();
        foreach ($query->result() as $row)
        {
            array_push($ret_arr, $row);
        }
        return $ret_arr;
    }
    
    function getArticleObj($id)
    {
        $this->load->database();
        $query = $this->db->get_where('tb_article', array('id' => $id));
        $row = $query->result();
        if (!$row)
        {
            return (object)array('no' => 1, 'msg' => '查询结果为空');
        }
        else
        {
            $ret = $row[0];
            $ret->no = 0;
            $ret->timestamp = strtotime($ret->pubdate);
            //var_dump($ret);
            return $ret;
        }
    }
}
?>