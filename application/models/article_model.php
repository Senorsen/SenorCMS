<?php
class Article_model extends CI_Model {

    var $cat_tree, $cat_res;
    
    function __construct()
    {
        parent::__construct();
    }

    function getList($page, $count, $show_hidden = 0)
    {
        $start = $page * $count;
        $this->load->database();
        $sql = "SELECT `a`.`id`,`a`.`title`,`a`.`pubdate`,`a`.`sort`,`u`.`username` AS `author`, `a`.`author` AS `author_id`,
                       `l`.`category_id`, `d`.`short_name`, `d`.`full_name`
                FROM `tb_article` AS `a`
                  LEFT JOIN `tb_user` AS `u` ON `a`.`author`=`u`.`id`
                  LEFT JOIN `tb_category_link` AS `l` ON `l`.`article_id`=`a`.`id`
                  LEFT JOIN `tb_category_def` AS `d` ON `l`.`category_id`=`d`.`id`
                WHERE (`a`.`hidden`<=?)
                ORDER BY `a`.`id` DESC LIMIT ?,? ";
                
        $query = $this->db->select('article.id, article.title, article.pubdate, article.sort,
                                    user.username AS author_name,
                                    category_link.category_id, category_def.short_name, category_def.full_name')
                            ->join('user', 'article.author=user.id', 'left')
                            ->join('category_link', 'category_link.article_id=article.id', 'left')
                            ->join('category_def', 'category_link.category_id=category_def.id', 'left')
                           ->where('article.hidden <=', $show_hidden?1:0)
                           ->order_by('article.id', 'desc')
                           ->limit(intval($count), intval($start))
                           ->get('article');
        //$query = $this->db->query($sql, array($show_hidden?1:0, intval($start), intval($count)));
        $ret_arr = $query->result();
        return $ret_arr;
    }
    
    function getCategoryList($page, $count, $category, $show_hidden = 0)
    {
        $start = $page * $count;
        $this->load->database();
        $sql = " SELECT `c`.`cat_descendants`,`d`.`full_name` AS `category_name` FROM `tb_category_cache` AS `c` "
              ." LEFT JOIN `tb_category_def` AS `d` ON `c`.`cat_self`=`d`.`id` "
              ." WHERE `c`.`cat_self` in "
              ." (SELECT `id` FROM `tb_category_def` WHERE `short_name`=?)";
        try {
            $r_cache = $this->db->query($sql, array($category))->first_row();
            if ($r_cache != false)
            {
                $org_ids = $r_cache->cat_descendants;
                $category_name = $r_cache->category_name;
            }
            else
            {
                $org_ids = "";
            }
        } catch(Exception $e) {
            $org_ids = "";
        }
        if ($org_ids == "") return array();
        $sql = " SELECT `a`.`id`,`a`.`title`,`a`.`pubdate`,`a`.`sort`,`u`.`username` AS `author`, "
              ." `a`.`author` AS `author_id`, `l`.`category_id`,`d`.`short_name`,`d`.`full_name` "
              ." FROM `tb_article` AS `a` LEFT JOIN `tb_user` AS `u` ON `a`.`author`=`u`.`id` "
              ." LEFT JOIN `tb_category_link` AS `l` ON `l`.`article_id`=`a`.`id` "
              ." LEFT JOIN `tb_category_def` AS `d` ON `l`.`category_id`=`d`.`id` "
              ." WHERE (`a`.`hidden`=0 or `a`.`hidden`<=?) AND `a`.`id` in /* A MUST GO AHEAD */ "
              ." (SELECT `article_id` FROM `tb_category_link` WHERE `category_id` in "
              ." ( $org_ids ) ) GROUP BY `a`.`id` ORDER BY `a`.`id` DESC LIMIT ?,? ";
        //$sql = "SELECT a.id, a.title, a.pubdate, a.sort, u.username AS author, a.author AS author_id, l.category_id, d.short_name, d.full_name FROM tb_article AS a, tb_user AS u, tb_category_link AS l, tb_category_def AS d WHERE a.hidden<=? AND a.id=";
        $query = $this->db->query($sql, array($show_hidden?1:0, intval($start), intval($count)));
        $ret_arr = array();
        foreach ($query->result() as $row)
        {
            array_push($ret_arr, $row);
        }
        return (object)array('category_name' => $category_name, 'list' =>$ret_arr);
    }
    
    function isCategoryExists($category_short_name, $category_id = 0)
    {
        if (is_null($category_short_name))
        {
            $category_short_name = '';
        }
        $this->load->database();
        $sql = "SELECT `id`,`short_name` FROM `tb_category_def` WHERE (`id`=? OR `short_name`=?)";
        $row = $this->db->query($sql, array(intval($category_id), $category_short_name))->first_row();
        if ($row == false || count($row) == 0)
        {
            return false;
        }
        else
        {
            return $row;
        }
    }
    
    function getArticleObj($id, $hiddenmask = 0)
    {
        $this->load->database();
        if ($hiddenmask == 0)
        {
            $q_arr = array($id, 0);
        }
        else
        {
            $q_arr = array($id, 1);
        };
        $sql = " SELECT `a`.`id`,`a`.`title`,`a`.`content`,`a`.`pubdate`,`a`.`author` AS `author_id`, "
              ." `a`.`sort`,`a`.`hidden`,`a`.`click_count`,`u`.`username` AS `author`, "
              ." `d`.`short_name` AS `cat_short`,`d`.`full_name` AS `category`,`d`.`id` AS `category_id` "
              ." FROM `tb_article` AS `a` "
              ." LEFT OUTER JOIN `tb_user` AS `u` ON `u`.`id`=`a`.`author` "
              ." LEFT OUTER JOIN `tb_category_link` AS `l` ON `a`.`id`=`l`.`article_id` "
              ." LEFT OUTER JOIN `tb_category_def` AS `d` ON `d`.`id`=`l`.`category_id` "
              ." WHERE `a`.`id`=? AND `a`.`hidden`<=? ";
        $query = $this->db->query($sql, $q_arr);
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
            return $ret;
        }
    }
    function cacheCategory()
    {
        // 以后可以考虑使用MEMORY类型的临时表……
        // 但现在为了便于调试，暂时使用了MYISAM
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