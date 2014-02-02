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

  protected function parseFile($file)
  {
    return $this->parseContent(
      $this->filesystem->read($file["path"]),
      $file
    );
  }

  protected function parseContent($content, $file = null)
  {
    $extracted = $this->engine->extractMeta($content);
    $parsed    = $this->engine->parseMeta($extracted["meta"]);

    return compact("file", "content", "extracted", "parsed");
  }

  protected function stripExtension($name)
  {
    return str_ireplace(".blade.php", "", $name);
  }

  protected function cleanArray($array)
  {
    return array_filter($array, function($item) {
      return !empty($item);
    });
  }

  public function indexAction($route = "/")
  {
    $pages = $this->filesystem->listContents("pages");

    foreach ($pages as $page)
    {
      if ($page["type"] == "file")
      {
        $page = $this->parseFile($page);

        if ($page["parsed"]["route"] == $route)
        {
          $name       = "pages/extracted/" . $page["file"]["basename"];
          $layout     = $page["parsed"]["layout"];
          $layoutName = "layouts/extracted/" . $layout;

          $template = "
            @extends('" . $this->stripExtension($layoutName) . "')
            @section('page')
              " . $page["extracted"]["template"] . "
            @stop
          ";

          $this->filesystem->put($name, trim($template));

          $layout = "layouts/" . $layout;
          $layout = $this->parseContent($this->filesystem->read($layout));

          $this->filesystem->put(
            $layoutName,
            $layout["extracted"]["template"]
          );

          $data = array_merge(
            $this->cleanArray($layout["parsed"]),
            $this->cleanArray($page["parsed"])
          );

          return View::make($this->stripExtension($name), $data);
        }
      }
    }
  }
}