<?php

Route::any("admin/layout/index", [
  "as"   => "admin/layout/index",
  "uses" => "LayoutController@indexAction"
]);

Route::any("admin/layout/add", [
  "as"   => "admin/layout/add",
  "uses" => "LayoutController@addAction"
]);

Route::any("admin/layout/edit", [
  "as"   => "admin/layout/edit",
  "uses" => "LayoutController@editAction"
]);

Route::any("admin/layout/delete", [
  "as"   => "admin/layout/delete",
  "uses" => "LayoutController@deleteAction"
]);

Route::any("admin/page/index", [
  "as"   => "admin/page/index",
  "uses" => "PageController@indexAction"
]);

Route::any("admin/page/add", [
  "as"   => "admin/page/add",
  "uses" => "PageController@addAction"
]);

Route::any("admin/page/edit", [
  "as"   => "admin/page/edit",
  "uses" => "PageController@editAction"
]);

Route::any("admin/page/delete", [
  "as"   => "admin/page/delete",
  "uses" => "PageController@deleteAction"
]);

Route::any("{all}", [
  "as" => "index/index",
  "uses" => "IndexController@indexAction"
])->where("all", ".*");