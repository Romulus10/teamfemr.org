import FormField from '../models/FormField';
import OutreachProgram from '../models/OutreachProgram';

class Survey {

    static initialize( successCB, errorCB = ()=>{} ) {

        return axios.get( '/api/survey/form' )
            .then( ( response ) => {

                let def = {};
                _.forEach( response.data.data, ( fieldJson, key ) => {

                    if( _.isArray( fieldJson ) ) {

                        let subfield = {};
                        _.forEach( fieldJson, ( json ) => {

                            let field = new FormField( json );
                            subfield[ field.name ] = field;
                        });

                        def[ key ] = subfield;
                    }
                    else {

                        def[ key ] = new FormField( fieldJson );
                    }

                } );

                successCB( def );
            })
            .catch( ( error ) => {

                errorCB( error )
            });
    }

    static get( programId, successCB, errorCB = ()=>{} ) {

        axios.get( '/api/survey/' + programId )
            .then( function( result ) {

                let program = new OutreachProgram();
                program.populate( result.data.data );

                successCB( program );
            })
            .catch( ( error ) => {

                errorCB( error )
            });
    }

    static create( fields, successCB, errorCB = ()=>{} ) {

        if( FEMR.userToken  !== 'guest' ) {

            fields[ 'api_token' ] = FEMR.userToken;
        }

        axios.post( '/api/survey', fields )
            .then( function( result ) {

                let program = new OutreachProgram();
                program.populate( result.data.data );

                successCB( program );
            })
            .catch( ( error ) => {

                console.log( error );
                errorCB( error )
            });
    }

    static update( id, fields, successCB, errorCB = ()=>{} ) {

        if( FEMR.userToken  !== 'guest' ) {

            fields[ 'api_token' ] = FEMR.userToken;
        }

        axios.put( '/api/survey/' + id, fields )
            .then( function( result ) {

                let program = new OutreachProgram();
                program.populate( result.data.data );

                successCB( program );
            })
            .catch( ( error ) => {

                errorCB( error )
            });
    }
}

export default Survey;