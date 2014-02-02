@extends("admin/layout")
@section("navigation/page/class")
  active
@stop
@section("content")
  @include("admin/include/page/navigation")
  @if (count($pages))
    <table class="table table-striped">
      <thead>
        <tr>
          <th class="wide">
            File
          </th>
          <th class="narrow">
            Actions
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pages as $page)
          @if ($page["type"] == "file")
            <tr>
              <td class="wide">
                <a href="{{ $edit . $page["basename"] }}">
                  {{ $page["basename"] }}
                </a>
              </td>
              <td class="narrow actions">
                <a href="{{ $edit . $page["basename"] }}">
                  <i class="glyphicon glyphicon-pencil"></i>
                </a>
                <a href="{{ $delete . $page["basename"] }}">
                  <i class="glyphicon glyphicon-trash"></i>
                </a>
              </td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
    @else
      No pages yet.
      <a href="{{ URL::route("admin/page/add") }}">create one now!</a>
    @endif
@stop