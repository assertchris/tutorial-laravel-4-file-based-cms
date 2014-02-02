<?php

use Formativ\Cms\EngineInterface;
use Formativ\Cms\FilesystemInterface;

class IndexController
extends BaseController
{
  protected $engine;
  protected $filesystem;

  public function __construct(
    EngineInterface $engine,
    FilesystemInterface $filesystem
  )
  {
    $this->engine     = $engine;
    $this->filesystem = $filesystem;
  }

  public function indexAction($route = "/")
  {
    $pages = $this->filesystem->listContents("pages");

    foreach ($pages as $page)
    {
      if ($page["type"] == "file")
      {
        $pageContent   = $this->filesystem->read($page["path"]);
        $pageExtracted = $this->engine->extractMeta($pageContent);
        $pageParsed    = $this->engine->parseMeta($pageExtracted["meta"]);

        if ($pageParsed["route"] == $route)
        {
          $pageName   = "pages/extracted/" . $page["basename"];
          $pageLayout = $pageParsed["layout"];

          $layoutName = "layouts/extracted/" . $pageLayout;

          $layoutViewName = str_ireplace(".blade.php", "", $layoutName);

          $template = "
            @extends('" . $layoutViewName . "')
            @section('page')
              " . $pageExtracted["template"] . "
            @stop
          ";

          $this->filesystem->put($pageName, trim($template));

          $layout          = "layouts/" . $pageLayout;
          $layoutContent   = $this->filesystem->read($layout);
          $layoutExtracted = $this->engine->extractMeta($layoutContent);
          $layoutParsed    = $this->engine->parseMeta($layoutExtracted["meta"]);

          $this->filesystem->put($layoutName, $layoutExtracted["template"]);

          $layoutParsed = array_filter($layoutParsed, function($item) {
            return !empty($item);
          });

          $pageParsed = array_filter($pageParsed, function($item) {
            return !empty($item);
          });

          $viewName = str_ireplace(".blade.php", "", $pageName);
          return View::make($viewName, array_merge($layoutParsed, $pageParsed));
        }
      }
    }
  }
}