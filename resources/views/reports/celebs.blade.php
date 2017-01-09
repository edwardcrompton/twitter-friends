<!-- resources/views/reports/friends.blade.php -->

@extends('layouts.app')

@section('content')

<!-- Bootstrap Boilerplate... -->

<div class="panel-body">
    <!-- Display Validation Errors -->
    @include('common.errors')

    <div class="container">
        @each('reports.celeb', $friends, 'friend'); 
    </div>

    {{ $friends->links() }}

</div>

@endsection