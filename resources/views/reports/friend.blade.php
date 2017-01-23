<!-- resources/views/reports/friend.blade.php -->

<div class="media">
    <a class="media-left" href="{{ $linkToTwitter }}/{{ $profile->screen_name }}" target="_blank">
        <img class="media-object" src="{{ $profile->profile_image_url_https }}" alt="Profile image">
    </a>
    <div class="media-body">
        <a class="media-left" href="{{ $linkToTwitter }}/{{ $profile->screen_name }}" target="_blank">
            <h4 class="media-heading">{{ $profile->name }}</h4>
        </a>
        <p>{{ '@'.$profile->screen_name }}</p>
        <p>{{ $profile->status->created_at or "" }}</p>
        <p>{{ $profile->status->text or "" }}</p>
    </div>
</div>