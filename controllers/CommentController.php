<?php

namespace controllers;

use core\Controller as Controller;
use models\Comments as Comments;

class CommentController extends Controller{
    
    public function index() {
        $comments = Comments::getMainComments();
        $this->view->display('comments/index', ['comments' => $comments]);
    }
}
