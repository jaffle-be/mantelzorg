<?php

View::composer('layout.global.footer', function($view){
   $view->with('user', Auth::user());
});

View::composer('layout.admin.master', function($view){
    $view->with('user', Auth::user());
});