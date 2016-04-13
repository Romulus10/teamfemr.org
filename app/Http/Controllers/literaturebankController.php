<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Literature;
use Input;
use Validator;
use Redirect;
use Session;
use Embed\Embed;
use Auth;
class LiteratureBankController extends Controller
{
//Controls the view of the Literature Bank, by calling the view file (literaturebank.plade.php)
    public function index()
    {

        $literatures = Literature::where( 'approved', '=', 1 )->get();
        $info = [];

        foreach ($literatures as $id => $literature)
        {
            $info[$id] = Embed::create($literature->addLink);

            //try this
            $info[$id] = Embed::create($info[$id]->title);
            $info[$id] = Embed::create($info[$id]->description);
            $info[$id] = Embed::create($literature->url);
            $info[$id] = Embed::create($info[$id]->publishedDate);
            $info[$id] = Embed::create($info[$id]->image);
        }

        return view('literaturebank', compact ('literatures', 'info'));

    }

    //Controls upload functionality on the Literature Bank Survey
    public function upload()
    {
        //Get data
        $file = array('image' => Input::file('image'));
        //Set up rules
        $rules = array('image' => 'required',);
        //messages
        //Performing validation per the rules, passing the data
        $validator = Validator::make($file, $rules);

        //Error checking
        if ($validator->fails()) {
            return Redirect::to('upload')->withInput()->withErrors($validator);
        }
        else {
            //Checking if the file is valid
            if (Input::file('image')->isValid()) {
                //Upload the destination path
                $destinationPath = 'uploads';

                //Get extension
                $extension = Input::file('image')->getClientOriginalExtension();

                //Rename file
                $fileName = rand(11111,99999).'.'.$extension;

                //Upload the file to the destination path
                Input::file('image')->move($destinationPath, $fileName);

                //Message
                Session::flash('success', 'Upload successfully');
                return Redirect::to('upload');
            }
            else {
//Error message
                Session::flash('error', 'uploaded file is not valid');
                return Redirect::to('upload');
            }
        }
    }
}
