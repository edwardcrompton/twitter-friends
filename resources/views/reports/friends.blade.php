<!-- resources/views/reports/friends.blade.php -->

@extends('layouts.app')

@section('content')

<!-- Bootstrap Boilerplate... -->

<div class="panel-body">
    <!-- Display Validation Errors -->
    @include('common.errors')

    <div class="container">
        @if (count($friends))
            <h3>Friends of {{ $handle }}</h3>
            @foreach ($friends as $friend)
                @include('reports.friend', [$friend])
            @endforeach
        @else
            <h4>We can't find any friends of {{ $handle }}</h4>
        @endif
    </div>

    {{ $friends->links() }}

</div>

@endsection