<?php
class Manage_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function publishArticle($uid, $id, $is_update_event, $category_id, $title, $content, $hidden)
    {
        $this->load->database();
        if ($is_update_event)
        {
            $sql = "SELECT `id` FROM `tb_article` WHERE `id`=?";
            $r_id = $this->db->query($sql, array($id))->first_row();
            if (false == $r_id || count($r_id) == 0)
            {
                return (object)array('no' => 1, 'msg' => '无法更新不存在的文章。');
            }
            $id = $r_id->id;
            $sql = "UPDATE `tb_article` SET `title`=?,`content`=?,`hidden`=? WHERE `id`=?";
            $this->db->query($sql, array($title, $content, $hidden , $id));
            if ($this->db->affected_rows() != 1)
            {
                return (object)array('no' => 1, 'msg' => '更新文章失败');
            }
            $sql = "UPDATE `tb_category_link` SET `category_id`=? WHERE `article_id`=?";
            $this->db->query($sql, array($category_id, $id));
            if ($this->db->affected_rows() != 1)
            {
                return (object)array('no' => 1, 'msg' => '更新分类信息失败');
            }
            return (object)array('no' => 0, 'msg' => '更新成功', 'id' => $id);
        }
        else
        {
            $sql = " INSERT INTO `tb_article` (`uid`,`title`,`content`,`hidden`,`pubdate`,`click_count`) "
                  ." VALUES(?,?,?,?,NOW(),0) ";
            $this->db->query($sql, array($uid, $title, $content, $hidden));
            if ($this->db->affected_rows() != 1)
            {
                return (object)array('no' => 1, 'msg' => '新建文章失败');
            }
            $id = $this->db->insert_id();
            $sql = "INSERT INTO `tb_category_link` (`article_id`,`category_id`) VALUES (?,?)";
            $this->db->query($sql, array($id, $category_id));
            if ($this->db->affected_rows() != 1)
            {
                return (object)array('no' => 1, 'msg' => '为文章新建分类记录失败');
            }
            return (object)array('no' => 0,'msg' => '添加文章成功' , 'id' => $id);
        }
    }
    
}
?>