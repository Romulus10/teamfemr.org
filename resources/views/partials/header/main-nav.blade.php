<nav class="nav main-nav">

    <div class="container">

        <div class="nav-left">

            @if( Route::currentRouteName() == 'pages.home' )
            <a href="#top-bar" v-scroll-to="'#top-bar'" class="nav-item logo">
            @else
            <a href="/" class="nav-item logo">
            @endif
                <img src="{{ asset('images/logo/logo_no_tagline.png') }}" alt="Team fEMR Logo">
            </a>

            <!-- This "nav-toggle" hamburger menu is only visible on mobile -->
            <!-- You need JavaScript to toggle the "is-active" class on "nav-menu" -->
            <span class="nav-item">
                <span class="nav-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </span>

        </div>

        <div class="nav-right nav-menu">

            {{--@if( Route::currentRouteName() == 'pages.home' )--}}
            {{--<a href="#top-bar" v-scroll-to="'#top-bar'" class="nav-item">--}}
            {{--@else--}}
            {{--<a href="/" class="nav-item">--}}
            {{--@endif--}}
                {{--Home--}}
            {{--</a>--}}

            <a href="{{ Route::currentRouteName() == 'pages.home' ? '#about' : '/#about' }}" v-scroll-to="'#about'" class="nav-item">
                About
            </a>

            <a href="#open-source" v-scroll-to="'#open-source'" class="nav-item">
                Open Source
            </a>

            <a href="#publications" v-scroll-to="'#publications'"  class="nav-item">
                Publications
            </a>

            <a href="#news" v-scroll-to="'#news'"  class="nav-item">
                News
            </a>

            <a class="nav-item is-hidden-tablet" href="{{ asset('/documents/Annual_Report_2014_2015.pdf') }}" target="_blank">
                Annual Report
            </a>

            <span class="nav-item">

                @include( 'components.donate.paypal' )

            </span>

        </div>

    </div>

</nav>