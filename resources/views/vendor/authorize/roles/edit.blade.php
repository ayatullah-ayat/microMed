@extends('vendor.authorize.layouts.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Edit Role {{ $role->id }}</div>
        <div class="panel-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            {!! Form::model($role, [
                'method' => 'PATCH',
                'url' => ['/' . Config("authorization.route-prefix") . '/roles', $role->id],
                'class' => 'form-horizontal',
                'files' => true
            ]) !!}

            @include ('vendor.authorize.roles.form', ['submitButtonText' => 'Update'])

            {!! Form::close() !!}

        </div>
    </div>
@endsection