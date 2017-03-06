<?php

namespace models;

use core\Database as DB;

class Comments extends DB {
    
    /**
     * 
     * Get all comments which does not has a parent.
     * 
     * @return array
     */
    public static function getMainComments(){
        $comments = self::run("SELECT * FROM comments WHERE parent_id = ?",[0])->fetchAll();
        return $comments;
    }
    
    /**
     * 
     * Get all comments which belongs to the $parent_id
     * 
     * @param int $parent_id
     * @return array
     */
    public static function getCommentsByParentId($parent_id){
        $comments = self::run("SELECT * FROM comments WHERE parent_id = ?", [$parent_id])->fetchAll();
        return $comments;
    }
}
