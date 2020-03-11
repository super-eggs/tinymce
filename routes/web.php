<?php

use Encore\TinyMCE\Http\Controllers\TinyMCEController;

Route::get('tinymce', TinyMCEController::class.'@index');
