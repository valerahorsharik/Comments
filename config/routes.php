<?php
return array(
    //Registration routes

    'auth/social/vk/*' => 'registration/vk/$1',
    'auth/social/fb/*' => 'registration/fb/$1',
    
    //User's routes
    'logout' => 'user/logout',
    'login' => 'user/login',
    
    //Comment's routes
    'comment/save' => 'comment/save',
    'comment/delete' => 'comment/delete',
    'comment/edit' => 'comment/edit',
    '' => 'comment/index',
);
