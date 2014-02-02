<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
    <button type="button"
      class="navbar-toggle"
      data-toggle="collapse"
      data-target="#navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>
  <div class="collapse navbar-collapse" id="navbar-collapse">
    <ul class="nav navbar-nav">
      <li class="@yield("navigation/layout/class")">
        <a href="{{ URL::route("admin/layout/index") }}">Layouts</a>
      </li>
      <li class="@yield("navigation/page/class")">
        <a href="{{ URL::route("admin/page/index") }}">Pages</a>
      </li>
    </div>
  </div>
</nav>