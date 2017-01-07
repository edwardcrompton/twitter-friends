<div class="media">
    <a class="media-left" href="{{ $linkToTwitter }}/{{ $friend->screen_name }}" target="_blank">
        <img class="media-object" src="{{ $friend->profile_image_url_https }}" alt="Profile image">
    </a>
    <div class="media-body">
        <a class="media-left" href="{{ $linkToTwitter }}/{{ $friend->screen_name }}" target="_blank">
            <h4 class="media-heading">{{ $friend->name }}</h4>
        </a>
        <p>{{ '@'.$friend->screen_name }}</p>
        <p>{{ $friend->status->created_at or "" }}</p>
        <p>{{ $friend->status->text or "" }}</p>
    </div>
</div>