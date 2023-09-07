@extends('vendor.authorize.layouts.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Users</div>
        <div class="panel-body">
            <br/>
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Login Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name ?? 'N/A' }}</td>
                            <td>{{ $item->email ?? 'N/A' }}</td>
                            <td>{{ $item->role['name'] ?? 'N/A' }}</td>
                            <td class="text-center">{!! $item->status?? '' !!}</td>
                            <td>
                                <a href="{{ url('/' . Config("authorization.route-prefix") . '/admins/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs"
                                   title="Edit User"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>

                                @if($item->id != Auth::user()->id)
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['/' . Config("authorization.route-prefix") . '/admins', $item->id],
                                    'style' => 'display:inline'
                                ]) !!}
                                {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete Admin" />', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-xs',
                                        'title' => 'Delete User',
                                        'onclick'=>'return confirm("Confirm delete?")'
                                )) !!}
                                {!! Form::close() !!}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper"> {!! $users->render() !!} </div>
            </div>

        </div>
    </div>
@endsection