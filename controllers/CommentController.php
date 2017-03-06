<?php

namespace controllers;

use core\Controller as Controller;
use models\Comments as Comments;

class CommentController extends Controller{
    
    /**
     * 
     * Display main comments
     * 
     */
    public function index() {
        $comments = Comments::getMainComments();
        $this->view->display('comments/index', ['comments' => $comments]);
    }
    
    /**
     * 
     * Save a new comment
     * 
     */
    public function save(){
        $comment = getPost('comment');
        $authorId = $_SESSION['user']['id'];
        Comments::save($authorId, $comment);
    }
}
