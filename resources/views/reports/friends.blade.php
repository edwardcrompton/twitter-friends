<!-- resources/views/reports/friends.blade.php -->

@extends('layouts.app')

@section('content')

<!-- Bootstrap Boilerplate... -->

<div class="panel-body">
    <!-- Display Validation Errors -->
    @include('common.errors')

    <div class="container">

        <h3>Friends of {{ $handle }}</h3>

        @each('reports.friend', $friends, 'friend')
    </div>

    {{ $friends->links() }}

</div>

@endsection