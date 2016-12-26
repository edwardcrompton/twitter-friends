<!-- resources/views/reports/followers.blade.php -->

@extends('layouts.app')

@section('content')

        <!-- Bootstrap Boilerplate... -->

<div class="panel-body">
    <!-- Display Validation Errors -->
    @include('common.errors')

    Here are your friends, {{ $handle }}.

    <div class="container">
        @foreach ($friends as $friend)
            {{ $friend }}
        @endforeach
    </div>

    {{ $friends->links() }}

</div>

<!-- TODO: Current Tasks -->
@endsection