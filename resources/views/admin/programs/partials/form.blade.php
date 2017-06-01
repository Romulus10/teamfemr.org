<div class="columns">

    <div class="column is-half">

        <div class="notification ">

            @include( 'admin.programs.partials.form.base-fields' )

            @include( 'admin.programs.partials.form.other-fields' )

            <div class="field is-grouped">
                <p class="control">
                    <button class="button is-primary">Submit</button>
                </p>
            </div>

        </div>

    </div>

    <div class="column is-half">

        {{-- Only show the additional fields when there is an existing program --}}
        @if( isset( $program ) )

            @include( 'admin.programs.partials.form.papers.table' )

            @include( 'admin.programs.partials.form.partner-organization' )

            @include( 'admin.programs.partials.form.visited-locations' )

            @include( 'admin.programs.partials.form.contacts' )

        @endif

    </div>

</div>
