@extends('vendor.authorize.layouts.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Update Permission</div>
        <div class="panel-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            {!! Form::open(['url' => '/' . Config("authorization.route-prefix") . '/permissions', 'class' => 'form-horizontal']) !!}
            <div class="form-group {{ $errors->has('role_id') ? 'has-error' : ''}}">
                {!! Form::label('role_id', 'Role', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::select('role_id', $roles, null, ['placeholder' => 'Please select ...', 'class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('role_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('controller') ? 'has-error' : ''}}">
                {!! Form::label('controller', 'Actions', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    <ul id="tree">
                        @foreach($actions as $namespace => $controllers)
                        <li>
                            {{-- @dd(explode("App\Http\Controllers\\",$namespace), $namespace) --}}
                            @php
                                $arr      = explode("App\Http\Controllers\\",$namespace);
                                $location = count($arr) > 1 ? $arr[1] : $namespace;
                            @endphp
                            {{-- {{ $namespace }} --}}
                            {{ $location ?? '' }}
                            <ul>
                                @foreach($controllers as $controller => $methods)
                                {{-- @dump( preg_replace('/(Controller)/', '',$controller)) --}}
                                <li>{{ preg_replace('/(Controller)/', '',$controller) ?? $controller }}
                                    <ul>
                                        @foreach($methods as $method => $actions)
                                        <li>{{ $method }}
                                            <ul>
                                                @foreach($actions as $action)
                                                    <li>
                                                        {{ Form::checkbox('actions[]', $namespace . '-' . $controller . '-' . $method . '-' . $action, null, ['class' => 'field']) }}
                                                        {{ $action }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-4 col-md-4">
                    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection

@push('styles')
<link href="/vendor/authorize/css/tree.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="/vendor/authorize/js/tree.js"></script>
<script>
    $(function () {
        $('#role_id').on('change', function () {
            var role_id = $(this).val();
            $('#tree').find('input[type=checkbox]:checked').prop('checked', '');
            if (role_id > 0) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': Laravel.csrfToken
                    },
                    type: "POST",
                    url: '{{ url('/' . Config("authorization.route-prefix") . '/permissions/getSelectedRoutes') }}',
                    data: {role_id: role_id},
                    cache: false,
                    success: function (res) {
                        $.each(res.selectedActions, function (key, val) {
                            var value = val.replace(/\\/g, '\\\\');
                            $('input[type="checkbox"][value="' + value + '"]').prop('checked', 'checked');
                        });
                    },
                    error: function (xhr, status, error) {
                        alert("An AJAX error occured: " + status + "\nError: " + error);
                    }
                });
            }
        });
    });
</script>
@endpush