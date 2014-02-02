<?php

use Formativ\Cms\EngineInterface;
use Formativ\Cms\FilesystemInterface;

class PageController
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

    Validator::extend("add", function($attribute, $value, $parameters) {
      return !$this->filesystem->has("pages/" . $value);
    });

    Validator::extend("edit", function($attribute, $value, $parameters) {
      $new  = !$this->filesystem->has("pages/" . $value);
      $same = $this->filesystem->has("pages/" . $parameters[0]);

      return $new or $same;
    });
  }

  public function indexAction()
  {
    $pages  = $this->filesystem->listContents("pages");
    $edit   = URL::route("admin/page/edit") . "?page=";
    $delete = URL::route("admin/page/delete") . "?page=";
    
    return View::make("admin/page/index", compact(
      "pages",
      "edit",
      "delete"
    ));
  }

  public function addAction()
  {
    $files   = $this->filesystem->listContents("layouts");
    $layouts = [];

    foreach ($files as $file)
    {
      $name = $file["basename"];
      $layouts[$name] = $name;
    }

    if (Input::has("save"))
    {
      $validator = Validator::make(Input::all(), [
        "name"   => "required|add",
        "route"  => "required",
        "layout" => "required",
        "code"   => "required"
      ]);

      if ($validator->fails())
      {
        return Redirect::route("admin/page/add")
          ->withInput()
          ->withErrors($validator);
      }

      $meta = "
        title = " . Input::get("title") . "
        description = " . Input::get("description") . "
        layout = " . Input::get("layout") . "
        route = " . Input::get("route") . "
        ==
      ";

      $name = "pages/" . Input::get("name") . ".blade.php";

      $this->filesystem->write($name, $meta . Input::get("code"));

      return Redirect::route("admin/page/index");
    }

    return View::make("admin/page/add", compact(
      "layouts"
    ));
  }

  public function editAction()
  {
    $files   = $this->filesystem->listContents("layouts");
    $layouts = [];

    foreach ($files as $file)
    {
      $name = $file["basename"];
      $layouts[$name] = $name;
    }

    $page            = Input::get("page");
    $name            = str_ireplace(".blade.php", "", $page);
    $content         = $this->filesystem->read("pages/" . $page);
    $extracted       = $this->engine->extractMeta($content);
    $code            = trim($extracted["template"]);
    $parsed          = $this->engine->parseMeta($extracted["meta"]);
    $title       = $parsed["title"];
    $description = $parsed["description"];
    $route           = $parsed["route"];
    $layout          = $parsed["layout"];

    if (Input::has("save"))
    {
      $validator = Validator::make(Input::all(), [
        "name"  => "required|edit:" . Input::get("page"),
        "route" => "required",
        "layout"  => "required",
        "code"  => "required"
      ]);

      if ($validator->fails())
      {
        return Redirect::route("admin/page/edit")
          ->withInput()
          ->withErrors($validator);
      }

      $meta = "
        title = " . Input::get("title") . "
        description = " . Input::get("description") . "
        layout = " . Input::get("layout") . "
        route = " . Input::get("route") . "
        ==
      ";

      $name = "pages/" . Input::get("name") . ".blade.php";

      $this->filesystem->put($name, $meta . Input::get("code"));

      return Redirect::route("admin/page/index");
    }

    return View::make("admin/page/edit", compact(
      "name",
      "title",
      "description",
      "layout",
      "layouts",
      "route",
      "code"
    ));
  }

  public function deleteAction()
  {
    $name = "pages/" . Input::get("page");
    $this->filesystem->delete($name);

    return Redirect::route("admin/page/index");
  }
}