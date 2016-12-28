<div class="media">
    <a class="media-left">
        <img class="media-object" src="{{ $friend->profile_image_url_https }}" alt="Profile image">
    </a>
    <div class="media-body">
        <h4 class="media-heading">{{ $friend->name }}</h4>
        <p>{{ $friend->screen_name }}</p>
        <p>{{ $friend->status->created_at }}</p>
        <p>{{ $friend->status->text }}</p>
    </div>
</div>