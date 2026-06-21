<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {

    $markdownContent = file_get_contents(base_path('README.md'));

    $parsedown      = new Parsedown();
    $markdownTOHtml = $parsedown->text($markdownContent);

    return view('welcome', compact('markdownTOHtml'));
});
