var PaperService = (function(){

    /**
     *
     * @param programId
     * @returns {*}
     */
    var index = function( programId ){

        return axios.get( '/admin/programs/' + programId + '/papers' )
    };

    /**
     * Creates a new Paper entity
     *
     * @param programId
     * @param title
     * @param url
     * @param description
     * @returns {*}
     */
    var create = function( programId, title, url, description ){

        return axios.post('/admin/programs/' + programId + '/papers', {

            title: title,
            url: url,
            description: description
        });

    };

    /**
     * Update an existing Paper entity
     *
     * @param programId
     * @param paperId
     * @param title
     * @param url
     * @param description
     * @returns {*}
     */
    var update = function( programId, paperId, title, url, description ){

        return axios.put('/admin/programs/' + programId + '/papers/' + paperId, {

            title: title,
            url: url,
            description: description
        })
    };

    /**
     * Delete a Paper entity
     *
     * @param programId
     * @param paperId
     * @returns {boolean|*}
     */
    var destroy = function( programId, paperId ){

        return axios.delete( '/admin/programs/' + programId + '/papers/' + paperId );
    };

    return {

        index: index,
        create: create,
        update: update,
        destroy: destroy
    }
})();

export default PaperService;