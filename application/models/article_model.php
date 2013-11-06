<?php
class Article_model extends CI_Model {

    var $cat_tree, $cat_res;
    
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
        $sql = "SELECT `cat_descendants` FROM `tb_category_cache` WHERE `cat_self` in "
            ."(SELECT `id` FROM `tb_category_def` WHERE `short_name`=?)";
        $r_cache = $this->db->query($sql, array($category))->first_row();
        $org_ids = $r_cache->cat_descendants;
        $sql = "SELECT `id`,`title`,`author`,`pubdate`,`sort` FROM `tb_article` WHERE `hidden`=0 AND `id` in "
            ."(SELECT `article_id` FROM `tb_category_link` WHERE `category_id` in "
            ."( $org_ids ) )"
            ." LIMIT ?,?";
        $query = $this->db->query($sql, array(intval($start), intval($count)));
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
    function cacheCategory()
    {
        global $cat_tree;
        global $cat_res;
        $this->load->database();
        $this->db->query('TRUNCATE TABLE `tb_category_cache`');
        $query = $this->db->query('SELECT `category_id`,`child_id` FROM `tb_category_map` WHERE 1');
        $cat_tree = array();
        $cat_res = array();
        $cat_par = array();
        foreach ($query->result() as $row)
        {
            if (!isset($cat_tree[$row->category_id])) $cat_tree[$row->category_id] = array();
            if (!isset($cat_tree[$row->child_id])) $cat_tree[$row->child_id] = array();
            array_push($cat_tree[$row->category_id], $row->child_id);
        }
        var_dump($cat_tree);
        $sql = '';
        foreach ($cat_tree as $key => $value)
        {
            echo $key;
            $this_res = $this->_work_cache_category($key);
            $this_val = implode(",", $this_res);
            $sql = "INSERT INTO `tb_category_cache` (`cat_self`,`cat_descendants`) VALUES ($key,'$this_val');\n";
            $this->db->query($sql);
        }
        return $sql;
    }
    /*
        @Usage: 来简单的深搜记忆化吧~返回一个节点所有的后代节点
        
        @param: 节点ID
        
        @return: 后代节点集合 [array]
    */
    private function _work_cache_category($step)
    {
        global $cat_res, $cat_tree;
        if (isset($cat_res[$step]))
        {
            // keep in mind.
            return $cat_res[$step];
        }
        else
        {
            // never met before.
            $cat_res[$step] = array($step);
            foreach ($cat_tree[$step] as $key => $value)
            {
                $ret_descendants = $this->_work_cache_category($value);
                $cat_res[$step] = array_merge($cat_res[$step], $ret_descendants);
                $cat_res[$value] = $ret_descendants;
            }
            return $cat_res[$step];
        }
    }
}
?>