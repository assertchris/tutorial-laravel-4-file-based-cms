<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Laravel 4 File-Based CMS</title>
    <link rel="stylesheet" href="{{ asset("css/bootstrap.min.css"); }}" />
    <link rel="stylesheet" href="{{ asset("css/shared.css"); }}" />
  </head>
  <body>
    @include("admin/include/navigation")
    <div class="container">
      <div class="row">
        <div class="column md-12">
          @yield("content")
        </div>
      </div>
    </div>
    <script src="{{ asset("js/jquery.min.js"); }}"></script>
    <script src="{{ asset("js/bootstrap.min.js"); }}"></script>
  </body>
</html>