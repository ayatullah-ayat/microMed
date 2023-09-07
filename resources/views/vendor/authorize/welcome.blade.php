@extends('vendor.authorize.layouts.auth')

@section('content')
    <div class="jumbotron">
        <h1>Authorization</h1>
        <p>In addition to providing authentication services out of the box, Laravel also provides a simple way to authorize user actions against a given resource. Like authentication, Laravel's approach to authorization is simple, and there are two primary ways of authorizing actions: gates and policies.</p>
        <p>Think of gates and policies like routes and controllers. Gates provide a simple, Closure based approach to authorization while policies, like controllers, group their logic around a particular model or resource. We'll explore gates first and then examine policies.</p>
    </div>
@endsection