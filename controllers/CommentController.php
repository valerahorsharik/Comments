<?php

namespace controllers;

use core\Controller as Controller;

class CommentController extends Controller{
    
    public function index() {
        $this->view->display('test', ['a' => 7]);
    }
}
