<?php

namespace controllers;

use core\Controller as Controller;
use models\Comments as Comments;
use controllers\UserController as User;

class CommentController extends Controller{
    
    /**
     * 
     * Display main comments
     * 
     * @return void
     */
    public function index() {
        $guest = User::isGuest();
        $comments = Comments::getMainComments();
        $this->view->display('comments/index', ['comments' => $comments,
                                                'guest' => $guest]);
    }
    
    /**
     * 
     * Save a new comment
     * 
     * @return void
     */
    public function save(){
        $this->notAuthorized();
        $comment = getPost('comment');
        $authorId = $_SESSION['user']['id'];
        $date = date("Y-m-d H:i:s");
        $commentId = Comments::save($authorId, $comment);
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['date' => $date,'commentId' => $commentId]);

    }
    
    /**
     * 
     * Delete a comment
     * 
     * @return void
     */
    public function delete(){
        $this->notAuthorized();
        $commentId = getPost('commentId');
        $this->isOwner($commentId);
        Comments::deleteComment($commentId);
        header('HTTP/1.1 200 OK');
    }
    
    /**
     * 
     * Edit a comment
     * 
     * @return void
     */
    public function edit(){
        $this->notAuthorized();
        $commentId = getPost('commentId');
        $text = getPost('text');
        $this->isOwner($commentId);
        Comments::editComment($commentId,$text);
        header('HTTP/1.1 200 OK');
    }
    
    /**
     * Commenting an existing comment
     * 
     * @return void
     */
    public function commentAnExistingComment(){
        $this->notAuthorized();
        $text = getPost('text');
        $parentId = getPost('parentId');
        $authorId = $_SESSION['user']['id'];
        $date = date("Y-m-d H:i:s");
        $commentId = Comments::save($authorId, $text, $parentId);
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['date' => $date,'commentId' => $commentId]);
    }
    
    /**
     * Download all comments which belongs to parent
     */
    public function downloadCommentsByParentId(){
        $parentId = getPost('parentId');
        $guest = User::isGuest();
        $comments = Comments::getCommentsByParentId($parentId);
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['comments' => $comments,'guest' => $guest]);
    }
    
    /**
     * 
     * Checking, if user not authorized
     * send an error with 403 status
     * 
     * @return string
     */
    private function notAuthorized(){
        if (User::isGuest()){
            header('HTTP/1.1 403 Forbidden');
            die("You need <a href=\"/login\">login</a> first.");
        }
    }
    
    /**
     * 
     * Checking if the user is author of the comment
     * 
     * @param int $commentId ID of the comment,which we wanna delete
     * 
     * @return boolean
     */
    private function isOwner($commentId){
        $userId = Comments::getUserId($commentId);
        if ($userId != $_SESSION['user']['id']){
            header('HTTP/1.1 403 Forbidden');
            die("Sorry, but you are not owner of the comment");
        }
        return true;
    }
}
