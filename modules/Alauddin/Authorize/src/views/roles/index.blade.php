@extends('vendor.authorize.layouts.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Roles</div>
        <div class="panel-body">

            <a href="{{ url('/' . Config("authorization.route-prefix") . '/roles/create') }}" class="btn btn-primary btn-xs" title="Add New Role"><span
                        class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
            <br/>
            <br/>
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th> Name</th>
                        <th> Alias</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->alias }}</td>
                            <td>
                                <a href="{{ url('/' . Config("authorization.route-prefix") . '/roles/' . $item->id) }}" class="btn btn-success btn-xs"
                                   title="View Role"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                                <a href="{{ url('/' . Config("authorization.route-prefix") . '/roles/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs"
                                   title="Edit Role"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

                                @if($item->id > 2)
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['/' . Config("authorization.route-prefix") . '/roles', $item->id],
                                    'style' => 'display:inline'
                                ]) !!}
                                {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete Role" />', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-xs',
                                        'title' => 'Delete Role',
                                        'onclick'=>'return confirm("Confirm delete?")'
                                )) !!}
                                {!! Form::close() !!}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper"> {!! $roles->render() !!} </div>
            </div>

        </div>
    </div>
@endsection