<?php

namespace Encore\TinyMCE;

use Encore\Admin\Form\Field;

class Editor extends Field
{
    protected $view = 'laravel-admin-tinymce::editor';

    protected static $js = [
        'vendor/laravel-admin-ext/tinymce/tinymce/tinymce.min.js',
    ];

    public function render()
    {
        $name = $this->formatName($this->column);

        $config = (array) TinyMCE::config('config');

        $config = json_encode($config);
        $config = rtrim($config, "}");
        $config = ltrim($config, "{");

        $this->script = <<<EOT
        
tinymce.init({
selector: '#{$this->id}',
language: 'zh_CN',
$config
});

EOT;
        return parent::render();
    }
}


//
//tinymce.init({
//selector: '#{$this->id}',
//$config,
//language:'zh_CN',
//height: 400,
//resize: false,
//plugins: 'advlist autolink link image lists preview code help fullscreen table autoresize ',
//toolbar: 'undo redo | styleselect | fontsizeselect bold italic | link image blockquote removeformat | indent outdent bullist numlist code',
//images_upload_url: '/api/v1/images',
//});
