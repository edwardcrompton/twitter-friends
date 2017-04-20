<nav class="navbar navbar-default">
    <div class="navbar-header"><a class="navbar-brand" href="/">Twitter Friends</a></div><!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">View <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ action('Twitter\FriendsController@showFriends', [$mainhandle, 'celebs']) }}">
                            Friends: Celebs
                        </a>
                    </li>
                    <li>
                        <a href="{{ action('Twitter\FriendsController@showFriends', [$mainhandle, 'lastupdated']) }}">
                            Friends: Old profiles
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="{{ action('Twitter\MainFollowersController@showFollowers', [$mainhandle, 'celebs']) }}">
                            Followers: Celebs
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="{{ action('Twitter\MainFollowersController@showUnfollowers', [$mainhandle]) }}">
                            Followers: Unfollowed
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    <div>  
</nav>
    