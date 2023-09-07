@extends('vendor.authorize.layouts.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Edit User {{ $user->id }}</div>
        <div class="panel-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            {!! Form::model($user, [
                'method' => 'PATCH',
                'url' => ['admin/authorize/admins', $user->id],
                'class' => 'form-horizontal',
                'files' => true
            ]) !!}

            <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name', 'Name', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('role_id') ? 'has-error' : ''}}">
                {!! Form::label('role_id', 'Role', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::select('role_id', $roles, null, ['placeholder' => 'please select ...', 'class' => 'form-control']) !!}
                    {!! $errors->first('role_id', '<p class="help-block">:message</p>') !!}
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