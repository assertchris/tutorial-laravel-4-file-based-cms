<?php

use Formativ\Cms\EngineInterface;
use Formativ\Cms\FilesystemInterface;

class LayoutController
extends BaseController
{
  protected $filesystem;

  public function __construct(
    EngineInterface $engine,
    FilesystemInterface $filesystem
  )
  {
    $this->engine     = $engine;
    $this->filesystem = $filesystem;

    Validator::extend("add", function($attribute, $value, $parameters) {
      return !$this->filesystem->has("layouts/" . $value);
    });

    Validator::extend("edit", function($attribute, $value, $parameters) {
      $new  = !$this->filesystem->has("layouts/" . $value);
      $same = $this->filesystem->has("layouts/" . $parameters[0]);

      return $new or $same;
    });
  }

  public function indexAction()
  {
    $layouts = $this->filesystem->listContents("layouts");
    $edit    = URL::route("admin/layout/edit") . "?layout=";
    $delete  = URL::route("admin/layout/delete") . "?layout=";
    
    return View::make("admin/layout/index", compact(
      "layouts",
      "edit",
      "delete"
    ));
  }

  public function addAction()
  {
    if (Input::has("save"))
    {
      $validator = Validator::make(Input::all(), [
        "name" => "required|add",
        "code" => "required"
      ]);

      if ($validator->fails())
      {
        return Redirect::route("admin/layout/add")
          ->withInput()
          ->withErrors($validator);
      }

      $meta = "
        title = " . Input::get("title") . "
        description = " . Input::get("description") . "
        ==
      ";

      $name = "layouts/" . Input::get("name") . ".blade.php";

      $this->filesystem->write($name, $meta . Input::get("code"));

      return Redirect::route("admin/layout/index");
    }

    return View::make("admin/layout/add");
  }

  public function editAction()
  {
    $layout          = Input::get("layout");
    $name            = str_ireplace(".blade.php", "", $layout);
    $content         = $this->filesystem->read("layouts/" . $layout);
    $extracted       = $this->engine->extractMeta($content);
    $code            = trim($extracted["template"]);
    $parsed          = $this->engine->parseMeta($extracted["meta"]);
    $title       = $parsed["title"];
    $description = $parsed["description"];

    if (Input::has("save"))
    {
      $validator = Validator::make(Input::all(), [
        "name" => "required|edit:" . Input::get("layout"),
        "code" => "required"
      ]);

      if ($validator->fails())
      {
        return Redirect::route("admin/layout/edit")
          ->withInput()
          ->withErrors($validator);
      }

      $meta = "
        title = " . Input::get("title") . "
        description = " . Input::get("description") . "
        ==
      ";

      $name = "layouts/" . Input::get("name") . ".blade.php";

      $this->filesystem->put($name, $meta . Input::get("code"));

      return Redirect::route("admin/layout/index");
    }

    return View::make("admin/layout/edit", compact(
      "name",
      "title",
      "description",
      "code"
    ));
  }

  public function deleteAction()
  {
    $name = "layouts/" . Input::get("layout");
    $this->filesystem->delete($name);

    return Redirect::route("admin/layout/index");
  }
}