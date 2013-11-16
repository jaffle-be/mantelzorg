<?php

View::composer('layout.global.footer', function($view){
   $view->with('user', Auth::user());
});

View::composer('layout.admin.master', function($view){
    $view->with('user', Auth::user());
});

Validator::extend('passcheck', function($attribute, $value, $parameters){

    $user = Auth::user();

    return Hash::check($value, $user->password);

});