<!-- resources/views/reports/celeb.blade.php -->

<div class="media">
    <a class="media-left" href="{{ $linkToTwitter }}/{{ $profile->screen_name }}" target="_blank">
        <img class="media-object" src="{{ $profile->profile_image_url_https }}" alt="Profile image">
    </a>
    <div class="media-body">
        <a class="media-left" href="{{ $linkToTwitter }}/{{ $profile->screen_name }}" target="_blank">
            <h4 class="media-heading">{{ $profile->name }}</h4>
        </a>
        <p>{{ '@'.$profile->screen_name }}</p>
        <p>{{ $profile->friends_count }} friends</p>
        <p>{{HTML::linkAction('Twitter\FollowersController@showFollowers', $profile->followers_count . ' followers', array($profile->screen_name, 'celebs'))}}</p>
    </div>
</div>