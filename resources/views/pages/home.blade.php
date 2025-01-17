@extends ('layouts.app')

@section( 'hero' )
    <section class="hero is-dark has-text-centered home-hero">
        <div class="hero-body">
            <div class="container">
                <h1 class="title is-spaced">
                    Team fEMR
                </h1>
                <p>
                    We created a fast, open-source Electronic Medical Records system for transient medical teams in order to promote data driven communication in low resource settings.
                </p>

                <div class="columns button-columns">

                    {{--<div class="column">--}}
                        {{--<div class="publications">--}}

                            {{--<a href="#publications" v-scroll-to="'#publications'"  class="button femr-button publications-button" >--}}
                                {{--<span>Publications</span>--}}
                            {{--</a>--}}

                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="column">
                        <div class="demo">

                            <a href="http://demo.teamfemr.org" class="button femr-button demo-button" target="_blank">
                                <span>Legacy fEMR Demo</span>
                            </a>
                            <ul class="credentials">
                                <li><span id="username">username: visitor</span></li>
                                <li><span id="password">password: Teamfemr1</span></li>
                            </ul>

                        </div>
                    </div>

                    <div class="column">
                        <div class="demo">

                            <a href="http://femr-onchain-training-beta.eba-umphej7e.us-west-2.elasticbeanstalk.com/" class="button femr-button demo-button" target="_blank">
                                <span>fEMR OnChain Demo</span>
                            </a>
                            <ul class="credentials">
                                <li><span id="username">username: visitor</span></li>
                                <li><span id="password">password: Teamfemr1</span></li>
                            </ul>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection


@section('below-content')
    <div id="map">
        <femr-map></femr-map>
    </div>
@endsection


@section('content')

    @include( 'sections.about' )

    @include( 'sections.open-source' )

    @include( 'sections.publications' )

    @include( 'sections.news' )

@endsection