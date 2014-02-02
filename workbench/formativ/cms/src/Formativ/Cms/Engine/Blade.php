<?php

namespace Formativ\Cms\Engine;

use Formativ\Cms\CompilerInterface;
use Formativ\Cms\EngineInterface;

class Blade
implements EngineInterface
{
  protected $compiler;

  public function __construct(CompilerInterface $compiler)
  {
    $this->compiler = $compiler;
  }

  public function render($template, $data)
  {
    $extracted = $this->extractMeta($template)["template"];
    $compiled = $this->compiler->compileString($extracted);

    ob_start();
    extract($data, EXTR_SKIP);

    try
    {
      eval("?>" . $compiled);
    }
    catch (Exception $e)
    {
      ob_end_clean();
      throw $e;
    }

    $result = ob_get_contents();
    ob_end_clean();

    return $result;
  }

  public function minify($template)
  {
    $search = array(
        "/\>[^\S ]+/s",  // strip whitespaces after tags, except space
        "/[^\S ]+\</s",  // strip whitespaces before tags, except space
        "/(\s)+/s"       // shorten multiple whitespace sequences
    );

    $replace = array(
        ">",
        "<",
        "\\1"
    );

    $template = preg_replace($search, $replace, $template);

    return $template;
  }

  public function extractMeta($template)
  {
    $parts = explode("==", $template, 2);

    $meta = "";
    $template = $parts[0];

    if (count($parts) > 1)
    {
      $meta = $parts[0];
      $template = $parts[1];
    }

    return [
      "meta"     => $meta,
      "template" => $template
    ];
  }

  public function parseMeta($meta)
  {
    $meta  = trim($meta);
    $lines = explode("\n", $meta);
    $data  = [];

    foreach ($lines as $line)
    {
      $parts = explode("=", $line);
      $data[trim($parts[0])] = trim($parts[1]);
    }

    return $data;
  }
}