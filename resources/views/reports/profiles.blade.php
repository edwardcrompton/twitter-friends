<!-- resources/views/reports/profiles.blade.php -->

@extends('layouts.app')

@section('content')

<!-- Bootstrap Boilerplate... -->

<div class="panel-body">
    <!-- Display Validation Errors -->
    @include('common.errors')

    <div class="container">
        <h4>{{ $title }}</h4>
        
        @if (count($profiles))
            @if ($profiletype == 'friend')
                @foreach ($profiles as $profile)
                    @include ('reports.friend', [$profile])
                @endforeach
            @elseif ($profiletype == 'celeb')
                @foreach ($profiles as $profile)
                    @include ('reports.celeb', [$profile])
                @endforeach
            @elseif ($profiletype == 'unfollower')
                @foreach ($profiles as $profile)
                    @include ('reports.unfollower', [$profile])
                @endforeach
            @endif
        @endif
    </div>

    {{ $profiles->links() }}

</div>

@endsection