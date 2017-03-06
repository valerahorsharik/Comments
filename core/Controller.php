<?php

namespace core;

use core\View;

class Controller {
   
    /**
     *
     * Contains a page if the controller has a view.
     * 
     * @var core\View
     */
    protected $view;
    
    public function __construct() {
        $this->view = new View();
    }
    
}
