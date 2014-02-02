<?php

namespace Formativ\Cms;

interface FilesystemInterface
{
  public function has($file);
  public function listContents($folder, $detail = false);
  public function write($file, $contents);
  public function read($file);
  public function put($file, $content);
  public function delete($file);
}