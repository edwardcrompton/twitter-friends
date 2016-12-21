<!-- resources/views/reports/followers.blade.php -->

@extends('layouts.app')

@section('content')

        <!-- Bootstrap Boilerplate... -->

<div class="panel-body">
    <!-- Display Validation Errors -->
    @include('common.errors')

    Here are your followers, {{ $handle }}.

</div>

<!-- TODO: Current Tasks -->
@endsection