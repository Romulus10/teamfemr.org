<nav class="nav main-nav">

    <div class="container">

        <div class="nav-left">

            <a
                @if( Route::currentRouteName() == 'pages.home' )
                href="#top-bar"
                v-scroll-to="'#top-bar'"
                @else
                href="/"
                @endif

                class="nav-item logo"
            >
                <img src="{{ asset('images/logo/logo_no_tagline.png') }}" alt="Team fEMR Logo">
            </a>

            <!-- This "nav-toggle" hamburger menu is only visible on mobile -->
            <!-- Toggle the "is-active" class on "nav-menu" -->
            <span class="nav-item has-nav-toggle">
                <span class="nav-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </span>

        </div>

        <div class="nav-right nav-menu" style="overflow: visible /** TODO - move this **/">

            <a href="/" class="nav-item" >
                Home
            </a>

            <div class="nav-item nav-button">
                <div class="dropdown is-hoverable">
                    <div class="dropdown-trigger">
                        <button class="button" aria-haspopup="true" aria-controls="dropdown-menu">

                            <span>About Us</span>
                            <span class="icon is-small">
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </span>

                        </button>
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu" role="menu">

                        <div class="dropdown-content">

                            <a class="dropdown-item" href="/#publications" target="_blank">
                                Publications and News
                            </a>
                            <div class="dropdown-section">
                                <span class="title">Annual Reports</span>
                                <div class="content">
                                    @include('partials.annual-reports-list')
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <a href="http://demo.teamfemr.org" class="nav-item" target="_blank">
                EMR Demo
            </a>

            <a href="{{ route( 'chatter.home' ) }}" class="nav-item">
                Forum
            </a>

            <a href="{{ route( 'survey.create' ) }}" class="nav-item" >
                Survey
            </a>

            <a href="{{ route( 'programs.index' ) }}" class="nav-item">
                {{--International Med. <br > Outreach Programs--}}
                Program Directory
            </a>

            @if( Auth::check() )

                <span class="nav-item is-hidden-tablet logged-in-status">
                    Welcome {{ Auth::user()->name }}
                </span>

                @if( Auth::user()->is_admin )
                <a class="nav-item user-item is-hidden-tablet" href="{{ Nova::path() }}">
                  Admin
                </a>
                @endif

                <span class="nav-item user-item is-hidden-tablet">
                    {!! Form::open([ 'method' => 'POST', 'route' => 'logout' ]) !!}
                    <button class="logout-button" href="{{ url('/logout') }}">Logout</button>
                    {!! Form::close() !!}
                </span>

            @else

                <span class="nav-item is-hidden-tablet logged-in-status">
                    Account
                </span>

                <a class="nav-item user-item is-hidden-tablet" href="{{ route( 'register' ) }}">
                    Register
                </a>

                <a class="nav-item user-item is-hidden-tablet" href="{{ route( 'login' ) }}">
                    Login
                </a>

            @endif

            <span class="nav-item">

                @include( 'components.donate.paypal' )

            </span>

        </div>

    </div>

</nav>