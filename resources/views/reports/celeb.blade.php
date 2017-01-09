<div class="media">
    <a class="media-left" href="{{ $linkToTwitter }}/{{ $friend->screen_name }}" target="_blank">
        <img class="media-object" src="{{ $friend->profile_image_url_https }}" alt="Profile image">
    </a>
    <div class="media-body">
        <a class="media-left" href="{{ $linkToTwitter }}/{{ $friend->screen_name }}" target="_blank">
            <h4 class="media-heading">{{ $friend->name }}</h4>
        </a>
        <p>{{ '@'.$friend->screen_name }}</p>
        <p>{{ $friend->friends_count }} friends</p>
        <p>{{HTML::linkAction('Twitter\FriendsController@showFollowersByCelebStatus', $friend->followers_count . ' followers', array($friend->screen_name))}}</p>
    </div>
</div>