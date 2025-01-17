<template>
    <div class="map-section">
        <div class="container search-container">

            <div class="columns is-mobile wrap">

                <div class="column filters-container">

                    <template v-if="fieldsDefIsLoaded">

                        <div class="field">

                            <template v-if="isLocationSearch">
                                <gmap-autocomplete
                                    ref="autocomplete"
                                    v-model="autocompleteValue"
                                    :value="searchText"
                                    :selectFirstOnEnter="true"
                                    class="input"
                                    placeholder="Enter a city, state or country"
                                    @place_changed="locationChanged"
                                    @keyup.enter.prevent
                                ></gmap-autocomplete>
                            </template>
                            <template v-else>
                                <text-field
                                    v-model="searchText"
                                    :def="fieldsDef.searchText"
                                    :is-loading="isLoading"
                                ></text-field>
                            </template>
                        </div>


                        <div class="tabs is-toggle small search-type-tabs">
                            <ul>
                                <li :class="{ 'is-active': isLocationSearch }" @click="isLocationSearch = true">
                                    <a>
                                        <span>Search By Location</span>
                                    </a>
                                </li>
                                <li :class="{ 'is-active': ! isLocationSearch }" @click="isLocationSearch = false">
                                    <a>
                                        <span>Search By Name</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="results-container">

                            <div class="results-wrapper">
                                <p class="status has-text-centered" v-if="isLoading">
                                    <span class="icon is-large">
                                      <i class="fa fa-3x fa-circle-o-notch fa-spin"></i>
                                    </span>
                                </p>
                                <table class="table" v-else>

                                    <template v-if="programs.length > 0">
                                        <tr v-for="( program, index ) in programs">
                                            <td>{{ program.name }}</td>
                                            <td>
                                                <button
                                                    v-if="programs.length > 1"
                                                    @click="showProgramInfoWindow( program )"
                                                    :class="{ button: true, 'is-info': true, 'is-small': true }">View</button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr v-else>
                                        <td colspan="2">
                                            No results found for your search
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                    </template>

                </div>

                <div class="column map-container">
                    <gmap-map
                            ref="gmap"
                            :center="center"
                            :zoom="zoom"
                            :options="{ scrollwheel: false, minZoom: minZoom }"
                            class="map">

                        <femr-info-window ref="infoWindow"></femr-info-window>

                        <gmap-cluster :grid-size="50" v-if="useClusters">

                            <gmap-marker
                                :key="index"
                                v-for="( m, index ) in groupedLocations"
                                :position="m[0].position"
                                :clickable="true"
                                :draggable="false"
                                @click="toggleInfoWindow( m, index )"
                            >
                            </gmap-marker>

                        </gmap-cluster>

                        <template v-else>
                            <gmap-marker
                                    :key="index"
                                    v-for="( m, index ) in groupedLocations"
                                    :position="m[0].position"
                                    :clickable="true"
                                    :draggable="false"
                                    @click="toggleInfoWindow( m, index )"
                            >
                            </gmap-marker>
                        </template>

                    </gmap-map>

                </div>

            </div>

        </div>
    </div>
</template>


<script type="text/babel">

    import * as VueGoogleMaps from 'vue2-google-maps';
    import InfoWindow from './InfoWindow';
    import Switches from 'vue-switches';

    import VisitedLocation from '../models/VisitedLocation';

    import OutreachProgram from '../services/OutreachProgram';
    import Search from '../services/Search';

    export default {

        components: {

            'femr-info-window': InfoWindow,
            Switches
        },

        data() {

            return {

                fieldsDef: {},

                searchText: '',
                autocompleteValue: '',
                searchLocation: '',
                sortBy: '',
                isLocationSearch: true,

                isLoading: false,

                center: { lat: 0.0, lng: 0.0 },
                zoom: 2,
                minZoom: 0,
                programs: [],
                filteredPrograms: []
            }
        },

        watch: {

            groupedLocations: {

                handler: function( newLocations ) {

                    this.extendBounds();
                },
                deep: true
            },

            allFilters: function( newFilters ) {

                this.getLocations();
            }
        },

        computed: {

            isValidNameSearch: function() {

                return ( ! this.isLocationSearch && this.searchText.length > 1  )
            },

            isValidLocationSearch: function() {

                return  ( this.isLocationSearch && ! _.isEmpty( this.searchLocation ) )
            },

            allFilters: function() {

                if( ! this.isValidNameSearch && ! this.isValidLocationSearch ) {
                    return {};
                }

                return {
                    search_by: this.isLocationSearch ?'location' : 'name',
                    name: this.searchText,
                    latitude:  ! _.isEmpty( this.searchLocation ) ? this.searchLocation.latitude : '',
                    longitude: ! _.isEmpty( this.searchLocation ) ? this.searchLocation.longitude : ''
                }
            },

            fieldsDefIsLoaded: function() {

                return ! _.isEmpty( this.fieldsDef );
            },

            groupedLocations: function(){

                // get the locations from each program into 1 array
                let locations = []
                _.forEach( this.programs, ( program ) => {

                    _.forEach( program.visitedLocations, ( location ) => {

                        locations.push( location );
                    } );

                }, [] );

                // group by the lat/lng coords to handle overlapping pins
                return _.groupBy( locations, ( location ) => {

                    return location.latitude + ', ' + location.longitude;
                });
            },

            useClusters: function(){

                if( this.$refs.gmap && this.$refs.gmap.zoom < 6 ) return true;

                // TODO -- change this everywhere! move opened into FemrMap
                if( _.has( this, '$refs.infoWindow.opened' ) && this.$refs.infoWindow.opened ) return false;

                // use clusters whenever there are more than 20 results
                if( _.keys( this.groupedLocations ).length > 20 ) return true;

                // If less than 20, only use clusters if no filters are set
                return _.isEmpty( this.allFilters );
            }
        },

        methods: {

            locationChanged( place ){

                if( _.has( place, 'geometry.location' ) ) {

                    this.searchLocation = {
                        latitude: parseFloat(place.geometry.location.lat().toFixed(5)),
                        longitude: parseFloat(place.geometry.location.lng().toFixed(5))
                    };
                }
            },

            showProgramInfoWindow( program ) {

                // get first location in program
                let location = _.first( program.visitedLocations );

                // find groupedLocations with visitedLocation and get marker key
                let locationKey = _.findKey( this.groupedLocations, ( locations, key ) => {

                    return _.reduce( locations, ( result, l ) => result || l.id === location.id, false );
                });

                this.center = location.position;
                this.zoom = 6;

                this.$refs.infoWindow.toggle( [ program ], locationKey );
            },

            toggleInfoWindow( locations, idx ) {

                // get the clicked on locations
                const locationIds = _.map( locations, 'id' );

                // find the programs for the locations
                let programs = _.transform( this.programs, ( result, program ) => {

                    let newProgram = _.cloneDeep( program );

                    // filter the locations to those that were clicked on
                    const filteredLocations = _.filter( newProgram.visitedLocations, ( location ) => {

                        return _.includes( locationIds, location.id );
                    } );

                    // only keep programs with matching locations
                    if( filteredLocations.length > 0 ) {

                        newProgram.visitedLocations = filteredLocations;
                        result.push( newProgram );
                    }
                    return true;

                }, [] )

                // send the programs to show to the infoWindow
                this.$refs.infoWindow.toggle( programs, idx );
            },

            calculateMinZoom() {

                // calculate the allowed minZoom based on the map size - trying to prevent grey borders and repeating of the map
                this.minZoom = Math.ceil( Math.log( this.$refs.gmap.$el.offsetWidth / 256 ) / Math.log( 2 ) );
            },

            extendBounds() {

                this.calculateMinZoom();

                // Location search, so zoom in on searched location with radius
                if( this.isValidLocationSearch ) {

                    // default center
                    this.center = {

                        lat: this.searchLocation.latitude,
                        lng: this.searchLocation.longitude
                    };
                    this.zoom = 5;
                }

                // Zoom based on location results
                else if( _.keys( this.groupedLocations ).length > 1 ) {

                    const bounds = new google.maps.LatLngBounds();

                    _.forEach( this.groupedLocations, ( group, key ) => {

                        _.forEach( group, ( location ) => {

                            if( _.has( location, 'position' ) ) {

                                bounds.extend( location.position );
                            }
                        });
                    });

                    this.$refs.gmap.fitBounds( bounds );

                }
                else if( _.keys( this.groupedLocations ).length > 0 ) {

                    const locations = _.get( this.groupedLocations, _.first( _.keys( this.groupedLocations ) ) );

                    _.forEach( locations, ( location ) => {

                        if( _.has( location, 'position' )  ) {

                            this.center = location.position;
                            this.zoom = 7;
                        }
                        else {

                            // default center
                            this.center = { lat: 0.0, lng: 0.0 };
                            this.zoom = 2
                        }
                    })
                }
            },

            getLocations: _.debounce( function() {

                this.isLoading = true;
                OutreachProgram.search( this.allFilters, ( programs ) => {

                    this.programs = programs;
                    this.isLoading = false;

                } );

            }, 200 )
        },
        created() {

            this.fieldsDef = Search.form();

            VueGoogleMaps.loaded.then( () => {

                window.addEventListener('resize', _.debounce( this.extendBounds, 500, {}, false ) );

                this.getLocations();
                this.calculateMinZoom();
            });

        }
    }

</script>

<style lang="scss" scoped>

    .map-section{

    }

    .search-container{

        position: relative;
        width: 100%;
        padding-top: 35%;

        .wrap {

            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;

            margin: 0;
        }

        .filters-container{

            background-color: #efefef;
            padding: 1.5rem 0.5rem 0.5rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;

            .search-type-tabs{

                font-size: 0.8rem;

                ul{

                    display: flex;

                    li{
                        flex: 1;
                    }
                }
            }

            .results-container{

                overflow-y: scroll;
                flex: 1;

                .table{

                    width: 100%;
                    font-size: 0.8rem;
                }
            }
        }

        .map-container{

            flex: 3;
            position: relative;

            .map {

                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 1;
            }
        }


    }

    @media only screen and ( max-width: 768px ) {

        .search-container {

            padding-top: 0;

            .wrap {

                position: static;
                top: auto;
                left: auto;
                bottom: auto;
                right: auto;

                flex-direction: column;
            }

            .filters-container{

                flex: initial;
                order: 2;
            }

            .map-container{

                order: 1;
                flex: 1;
                min-height: 250px;
            }

            .results-container{

                flex: initial;
            }
        }
    }



</style>