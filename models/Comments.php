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
        $comments = self::run("SELECT * FROM comments WHERE parent_id = ? GROUP BY id DESC",[0])->fetchAll();
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
        $comments = self::run("SELECT * FROM comments WHERE parent_id = ? ", [$parent_id])->fetchAll();
        return $comments;
    }
    
    /**
     * 
     * Save a new comment in the DB
     * 
     * @param ing $authorId Id of the user 
     * @param string $text Comment from Form
     * @param int $parent_id 
     * 
     * @return int 
     */
    public static function save($authorId, $text , $parent_id = 0){
        self::run("INSERT INTO comments VALUES(NULL,?,?,NULL,?)",[$authorId,
                                                        $parent_id,
                                                        $text]);
        return self::lastInsertId();
    }
}
