    <!-- Header Area Start -->
    <div id="header-area" class="header-area section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <!-- Logo -->
                    <a class="logo float-left" href="{{route('index')}}"><img src="{{asset('img/logo.png')}}" alt=""></a>
                    <!-- Mini Cart -->
                    {{-- <div class="mini-cart float-right">
                        <a href={{route('cart')}}><i class="zmdi zmdi-shopping-basket"></i></a>
                    </div> --}}
                    <!---- Menu ---->
                    <div id="main-menu" class="main-menu float-right">
                        <nav>
                            <ul>
                                {{-- <li @if(Route::is('index'))class="active"@endif><a href={{route('index')}}>home</a></li> --}}
                                {{-- <li><a href={{route('about')}}>about</a></li>
                                <li><a href={{route('team')}}>team</a></li> --}}
                                @if(Auth::user())
                                    @if(Auth::user()->isadmin == 1)
                                        <li @if(Route::is('users'))class="active"@endif><a href={{route('users')}}>{{__('layout.users')}}</a></li>
                                        <li @if(Route::is('games') || Route::is('rounds') || Route::is('rounds.new') || Route::is('rounds.edit'))class="active"@endif><a href={{route('games')}}>{{__('layout.games')}}</a></li>
                                        {{-- <li @if(Route::is('rounds') || Route::is('rounds.new') || Route::is('rounds.edit'))class="active"@endif><a href={{route('rounds')}}>rounds</a></li> --}}
                                        <li @if(Route::is('teams') || Route::is('teams.new') || Route::is('teams.edit'))class="active"@endif><a href={{route('teams')}}>{{__('layout.teams')}}</a></li>
                                        {{-- <li @if(Route::is('fixtures') || Route::is('fixtures.new') || Route::is('fixtures.edit'))class="active"@endif><a href={{route('fixtures')}}>fixtures</a></li> --}}
                                        <li @if(Route::is('players') || Route::is('players.new') || Route::is('players.edit'))class="active"@endif><a href={{route('players')}}>{{__('layout.players')}}</a></li>
                                        <li @if(Route::is('questions') || Route::is('questions.new') || Route::is('questions.edit') || Route::is('questions.answers') || Route::is('questions.round.edit') || Route::is('qinputs.new') || Route::is('qinputs.edit'))class="active"@endif><a href={{route('questions')}}>{{__('layout.questions')}}</a></li>
                                        <li @if(Route::is('userteams') || Route::is('standing') || Route::is('groupstanding'))class="active"@endif>
                                            @if(!Route::is('standing') && !Route::is('groupstanding'))
                                                <a href={{route('userteams')}}>{{__('layout.userteams')}}</a>
                                                <ul>
                                                    <li><a href={{route('standing')}}>{{__('layout.standing')}}</a></li>
                                                    <li><a href={{route('groupstanding')}}>{{__('layout.groupstanding')}}</a></li>
                                                </ul>
                                            @endif
                                            @if(Route::is('standing'))
                                                <a href={{route('standing')}}>{{__('layout.standing')}}</a>
                                                <ul>
                                                    <li><a href={{route('userteams')}}>{{__('layout.userteams')}}</a></li>
                                                    <li><a href={{route('groupstanding')}}>{{__('layout.groupstanding')}}</a></li>
                                                </ul>
                                            @endif
                                            @if(Route::is('groupstanding'))
                                                <a href={{route('groupstanding')}}>{{__('layout.groupstanding')}}</a>
                                                <ul>
                                                    <li><a href={{route('userteams')}}>{{__('layout.userteams')}}</a></li>
                                                    <li><a href={{route('standing')}}>{{__('layout.standing')}}</a></li>
                                                </ul>
                                            @endif
                                        </li>
                                    @else
                                        {{-- <li @if(Route::is('submit'))class="active"@endif><a href={{route('submit')}}>game</a></li>
                                        <li @if(Route::is('rule'))class="active"@endif><a href={{route('rule')}}>rules</a></li> --}}

                                        @if(Route::is('submit') || Route::is('rule') || Route::is('userteams') || Route::is('standing') || Route::is('groupstanding'))
                                            <li><a href={{route('games.joined')}}>{{__('layout.home')}}</a></li>
                                            {{-- <li @if(Route::is('submit'))class="active"@endif><a href={{route('games.joined')}}>{{__('layout.game')}}</a></li> --}}
                                            <li @if(Route::is('rule'))class="active"@endif><a href={{route('rule')}}>{{__('layout.rule')}}</a></li>
                                            <li @if(Route::is('userteams'))class="active"@endif><a href={{route('userteams')}}>{{__('layout.myteam')}}</a></li>
                                            @if(Auth::user()->ispaid == 1)
                                                <li @if(Route::is('standing') || Route::is('groupstanding'))class="active"@endif>
                                                    @if(!Route::is('standing') && !Route::is('groupstanding'))
                                                        <a href={{route('standing')}}>{{__('layout.standing')}}</a>
                                                        <ul>
                                                            <li><a href={{route('groupstanding')}}>{{__('layout.groupstanding')}}</a></li>
                                                        </ul>
                                                    @endif
                                                    @if(Route::is('standing'))
                                                        <a href={{route('standing')}}>{{__('layout.standing')}}</a>
                                                        <ul>
                                                            <li><a href={{route('groupstanding')}}>{{__('layout.groupstanding')}}</a></li>
                                                        </ul>
                                                    @endif
                                                    @if(Route::is('groupstanding'))
                                                        <a href={{route('groupstanding')}}>{{__('layout.groupstanding')}}</a>
                                                        <ul>
                                                            <li><a href={{route('standing')}}>{{__('layout.standing')}}</a></li>
                                                        </ul>
                                                    @endif
                                                </li>
                                                {{-- <li @if(Route::is('groupstanding'))class="active"@endif><a href={{route('groupstanding')}}>group standing</a></li> --}}
                                            @else
                                                <li @if(Route::is('standing'))class="active"@endif><a href={{route('standing')}}>{{__('layout.standing')}}</a></li>
                                            @endif
                                        @else
                                            <li @if(Route::is('games.joined'))class="active"@endif><a href={{route('games.joined')}}>{{__('layout.mygames')}}</a></li>
                                            <li @if(Route::is('games.calendar'))class="active"@endif><a href={{route('games.calendar')}}>{{__('layout.gamecalendar')}}</a></li>
                                            <li @if(Route::is('games.ended') || Route::is('finalstanding'))class="active"@endif><a href={{route('games.ended')}}>{{__('layout.endedgames')}}</a></li>

                                            
                                            <li @if(Route::is('contact'))class="active"@endif><a href={{route('contact')}}>{{__('layout.contact')}}</a></li>
                                        @endif
                                    @endif
                                @else
                                    {{-- <li @if(Route::is('submit'))class="active"@endif><a href={{route('submit')}}>game</a></li>
                                    <li @if(Route::is('rule'))class="active"@endif><a href={{route('rule')}}>rules</a></li>
                                    <li @if(Route::is('standing'))class="active"@endif><a href={{route('standing')}}>standing</a></li> --}}

                                    @if(Route::is('submit') || Route::is('rule') || Route::is('standing'))
                                        <li><a href={{route('games.open')}}>{{__('layout.home')}}</a></li>
                                        <li @if(Route::is('rule'))class="active"@endif><a href={{route('rule')}}>{{__('layout.rule')}}</a></li>
                                        <li @if(Route::is('standing'))class="active"@endif><a href={{route('standing')}}>{{__('layout.standing')}}</a></li>
                                    @else
                                        <li @if(Route::is('games.open'))class="active"@endif><a href={{route('games.open')}}>{{__('layout.opengames')}}</a></li>
                                        <li @if(Route::is('games.calendar'))class="active"@endif><a href={{route('games.calendar')}}>{{__('layout.gamecalendar')}}</a></li>
                                        <li @if(Route::is('games.ended') || Route::is('finalstanding'))class="active"@endif><a href={{route('games.ended')}}>{{__('layout.endedgames')}}</a></li>
                                        <li @if(Route::is('contact'))class="active"@endif><a href={{route('contact')}}>Contact</a></li>
                                    @endif
                      
                                @endif
                                {{-- <li><a href={{route('pointtable')}}>point table</a></li> --}}
                                {{-- <li><a href={{route('blog')}}>blog</a>
                                    <ul>
                                        <li><a href={{route('blog')}}>blog</a></li>
                                        <li><a href={{route('blogdetails')}}>blog details</a></li>
                                    </ul>
                                </li> --}}
                                {{-- <li><a href={{route('shop')}}>shop</a>
                                    <ul>
                                        <li><a href={{route('shop')}}>shop</a></li>
                                        <li><a href={{route('productdetails')}}>product details</a></li>
                                        <li><a href={{route('cart')}}>cart</a></li>
                                        <li><a href={{route('wishlist')}}>wishlist</a></li>
                                        <li><a href={{route('checkout')}}>checkout</a></li>
                                    </ul>
                                </li> --}}
                                {{-- <li><a href={{route('contact')}}>contact</a></li> --}}
                                @if(Auth::user())

                                    @if(Route::is('submit') || Route::is('rule') || Route::is('standing') || Route::is('userteams'))
                                        <li><a href="#">{{App\Model\Game::find(Session::get('game'))->name}}</a></li>
                                    @else
                                        <li @if(Route::is('profile'))class="active"@endif><a href={{route('profile')}}>{{Auth::user()->fullname}}</a>
                                            <ul>
                                                @if(Auth::user()->isadmin != 1)
                                                    <li><a href={{route('profile')}}>{{__('layout.profile')}}</a></li>
                                                @endif
                                                <li>
                                                    <a href="#" onclick="logout()">{{__('layout.logout')}}</a>
                                                    <form id="logoutform" class="w-full" method="post" action="{{ route('logout') }}">
                                                        @csrf
                                                    </form>
                                                </li>
                                            </ul>
                                        </li>
                                    @endif
                                    
                                @else
                                    <li @if(Route::is('login'))class="active"@endif><a href="{{route('login')}}">{{__('layout.login')}}</a></li>
                                @endif
                                {{-- <li>
                                    @if(App::getLocale() == "en")
                                        <a href={{route('lang', 'en')}}>English</a>
                                        <ul>
                                            <li><a href={{route('lang', 'da')}}>Dansk</a></li>
                                        </ul>
                                    @else
                                        <a href={{route('lang', 'da')}}>Dansk</a>
                                        <ul>
                                            <li><a href={{route('lang', 'en')}}>English</a></li>
                                        </ul>
                                    @endif
                                </li> --}}
                            </ul>
                        </nav>
                    </div>
                    <!---- Mobile Menu ---->
                    <div class="mobile-menu"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Area End -->
