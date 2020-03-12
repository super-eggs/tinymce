<?php

namespace Encore\TinyMCE;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\ServiceProvider;

class TinyMCEServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(TinyMCE $extension)
    {
        if (! TinyMCE::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-tinymce');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/tinymce')],
                'laravel-admin-tinymce'
            );
        }

        Admin::booting(function () {
            $name = TinyMCE::config('field_name', 'tinymce');
            Form::extend($name, Editor::class);
        });

    }
}
