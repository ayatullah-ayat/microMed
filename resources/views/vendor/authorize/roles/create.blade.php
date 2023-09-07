@extends('vendor.authorize.layouts.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Create New Role</div>
        <div class="panel-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            {!! Form::open(['url' => '/' . Config("authorization.route-prefix") . '/roles', 'class' => 'form-horizontal', 'files' => true]) !!}

            @include ('vendor.authorize.roles.form')

            {!! Form::close() !!}

        </div>
    </div>
@endsection