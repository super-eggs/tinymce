TinyMCE extension for laravel-admin
======

这是一个`laravel-admin`扩展，用来将`tinyMCE`集成进`laravel-admin`的表单中

## 截图

![截图演示](https://tva1.sinaimg.cn/large/00831rSTly1gcqectsfz1j31km0ckgmx.jpg)

## 安装

首先

```composer require super-eggs/laravel-admin-tinymce```

然后

```php artisan vendor:publish --tag=laravel-admin-tinymce```

## 配置

```
'tinymce' => [
            // Set to false if you want to disable this extension
            'enable' => true,
            // Editor configuration
            'config' => [
                'resize'=> false,
                'plugins'=> 'advlist autolink link image lists preview code help fullscreen table autoresize ',   // 插件
                'toolbar'=> 'undo redo | styleselect | fontsizeselect bold italic | link image blockquote removeformat | indent outdent bullist numlist code',   // 配置工具栏
                'images_upload_url'=> '/api/v1/images',  //图片上传接口  返回格式：{ location : "/demo/image/1.jpg" }
            ]
        ]
```
更多配置: http://tinymce.ax-z.cn/  文档写的非常棒,清晰明了!! (感谢 莫若卿 写的中文文档.)



## 使用

在`form`中使用

```
$form->tinymce('content');
```

## 上传图片

```php
<?php
/***************************************************
 * 数据来源白名单 *
 ***************************************************/
$accepted_origins = array("http://localhost", "http://192.168.1.1", "http://example.com");

/*********************************************
 * 设置图片保存的文件夹 *
 *********************************************/
$imageFolder = "images/";

reset ($_FILES);
$temp = current($_FILES);
if (!is_uploaded_file($temp['tmp_name'])){
  // 通知编辑器上传失败
  header("HTTP/1.1 500 Server Error");
  exit;
}

if (isset($_SERVER['HTTP_ORIGIN'])) {
  // 验证来源是否在白名单内
  if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
  } else {
    header("HTTP/1.1 403 Origin Denied");
    exit;
  }
}

/*
  如果脚本需要接收cookie，在init中加入参数 images_upload_credentials : true
  并加入下面两个被注释掉的header内容
*/
// header('Access-Control-Allow-Credentials: true');
// header('P3P: CP="There is no P3P policy."');

// 简单的过滤一下文件名是否合格
if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
    header("HTTP/1.1 400 Invalid file name.");
    exit;
}

// 验证扩展名
if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
    header("HTTP/1.1 400 Invalid extension.");
    exit;
}

// 都没问题，就将上传数据移动到目标文件夹，此处直接使用原文件名，建议重命名
$filetowrite = $imageFolder . $temp['name'];
move_uploaded_file($temp['tmp_name'], $filetowrite);

// 返回JSON格式的数据
// 形如下一行所示，使用location存放图片URL
// { location : '/your/uploaded/image/file.jpg'}
echo json_encode(array('location' => $filetowrite));
```
> 引用自 http://tinymce.ax-z.cn/advanced/php-upload-handler.php

## License

Licensed under The [MIT License (MIT).](https://github.com/super-eggs/tinymce/blob/master/LICENSE)