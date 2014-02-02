<?php

namespace Formativ\Cms;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Adapter\Local;

class CmsServiceProvider
extends ServiceProvider
{
  protected $defer = true;

  public function register()
  {
    $this->app->bind("Formativ\Cms\CompilerInterface", function() {
      return new Compiler\Blade(
        $this->app->make("files"),
        $this->app->make("path.storage") . "/views"
      );
    });

    $this->app->bind("Formativ\Cms\EngineInterface", "Formativ\Cms\Engine\Blade");

    $this->app->bind("Formativ\Cms\FilesystemInterface", function() {
      return new Filesystem(new Local($this->app->make("path.base") . "/app/views"));
    });
  }

  public function provides()
  {
    return [
      "Formativ\Cms\CompilerInterface",
      "Formativ\Cms\EngineInterface",
      "Formativ\Cms\FilesystemInterface"
    ];
  }
}