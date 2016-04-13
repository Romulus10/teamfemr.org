<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class literature extends Model
{
    //Model portion of the Model-View-Controller system
    //Variables that can be changed in the Literature table
        protected $fillable = [

        'contributorName',
        'authorName',
        'addLink',
            
            //may delete later
        'title',
        'description',
        'url',
        'publishedDate',
        'image'
];

        //This makes it so that the date something was deleted is a new field on the literatures survey
        //Suggestion made by Ken, used to implement the moderator's delete option/feature
        use SoftDeletes;
        protected $dates = ['deleted_at'];
}
