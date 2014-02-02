@extends("admin/layout")
@section("navigation/layout/class")
  active
@stop
@section("content")
  @include("admin/include/layout/navigation")
  <form role="form" method="post">
    <div class="form-group">
      <label for="name">Name</label>
      <span class="help-text text-danger">
        {{ $errors->first("name") }}
      </span>
      <input
        type="text"
        class="form-control"
        id="name"
        name="name"
        placeholder="new-layout"
        value="{{ Input::old("name") }}"
      />
    </div>
    <div class="form-group">
      <label for="title">Default Meta Title</label>
      <input
        type="text"
        class="form-control"
        id="title"
        name="title"
        value="{{ Input::old("title") }}"
      />
    </div>
    <div class="form-group">
      <label for="description">Default Meta Description</label>
      <input
        type="text"
        class="form-control"
        id="description"
        name="description"
        value="{{ Input::old("description") }}"
      />
    </div>
    <div class="form-group">
      <label for="code">Code</label>
      <span class="help-text text-danger">
        {{ $errors->first("code") }}
      </span>
      <textarea
        class="form-control"
        id="code"
        name="code"
        rows="5"
        placeholder="&lt;div&gt;Hello world&lt;/div&gt;"
      >{{ Input::old("code") }}</textarea>
    </div>
    <input type="submit" name="save" class="btn btn-default" value="Save" />
  </form>
@stop