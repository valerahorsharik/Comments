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
        $comments = self::run("SELECT * FROM comments WHERE parent_id = ? AND deleted = ? GROUP BY id DESC",[0,0])->fetchAll();
        return $comments;
    }
    
    /**
     * 
     * Get all comments which belongs to the $parent_id
     * 
     * @param int $parentId
     * @return array
     */
    public static function getCommentsByParentId($parentId){
        $comments = self::run("SELECT * FROM comments WHERE parent_id = ? AND deleted = ? ", [$parentId, 0])->fetchAll();
        return $comments;
    }
    
    /**
     * 
     * Get User Id of the comment with $commentId
     * 
     * @param int $commentId
     * 
     * @return int
     */
    public static function getUserId($commentId){
        $userId = self::run("SELECT user_id FROM comments where id = ?",[$commentId])->fetchColumn();
        return $userId;
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
        $text = trim($text);
        self::run("INSERT INTO comments VALUES(NULL,?,?,NULL,?,?)",[$authorId,
                                                        $parent_id,
                                                        $text,
                                                        0]);
        return self::lastInsertId();
    }
    
    /**
     * 
     * Delete comment from DB by $id
     * 
     * @param int $id 
     * 
     * @return void
     */
    public static function deleteComment($id){
        self::run("UPDATE comments SET deleted = ? WHERE id = ?",[1,$id]);
    }
    
    /**
     * 
     * Edit a comment in DB with $id 
     * 
     * @param int $id
     * @param string $text
     */
    public static function editComment($id,$text){
        $text = trim($text);
        self::run("UPDATE comments SET text = ? where id = ?",[$text,$id]);
    }
}
