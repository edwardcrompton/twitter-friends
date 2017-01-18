<!-- resources/views/reports/friends.blade.php -->

@extends('layouts.app')

@section('content')

<!-- Bootstrap Boilerplate... -->

<div class="panel-body">
    <!-- Display Validation Errors -->
    @include('common.errors')

    <div class="container">
        @if (count($profiles))
            @if ($profiletype == 'friend')
                @foreach ($profiles as $profile)
                    @include ('reports.friend', [$profile])
                @endforeach
            @elseif ($profiletype == 'celeb')
                @foreach ($profiles as $profile)
                    @include ('reports.celeb', [$profile])
                @endforeach
            @endif
        @endif
    </div>

    {{ $profiles->links() }}

</div>

@endsection