<!-- resources/views/reports/friends.blade.php -->

@extends('layouts.app')

@section('content')

<!-- Bootstrap Boilerplate... -->

<div class="panel-body">
    <!-- Display Validation Errors -->
    @include('common.errors')

    <div class="container">
        @if ($profiletype == 'friend')
            @each('reports.friend', [$profile], 'friend')
        @else if ($profiletype == 'celeb')
            @each('reports.celeb', [$profile], 'celeb')
        @endif
    </div>

    {{ $friends->links() }}

</div>

@endsection