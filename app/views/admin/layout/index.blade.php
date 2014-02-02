@extends("admin/layout")
@section("navigation/layout/class")
  active
@stop
@section("content")
  @include("admin/include/layout/navigation")
  @if (count($layouts))
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
        @foreach ($layouts as $layout)
          @if ($layout["type"] == "file")
            <tr>
              <td class="wide">
                <a href="{{ $edit . $layout["basename"] }}">
                  {{ $layout["basename"] }}
                </a>
              </td>
              <td class="narrow actions">
                <a href="{{ $edit . $layout["basename"] }}">
                  <i class="glyphicon glyphicon-pencil"></i>
                </a>
                <a href="{{ $delete . $layout["basename"] }}">
                  <i class="glyphicon glyphicon-trash"></i>
                </a>
              </td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
    @else
      No layouts yet.
      <a href="{{ URL::route("admin/layout/add") }}">create one now!</a>
    @endif
@stop