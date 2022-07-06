<?php

namespace App\Http\Controllers;

use JsValidator;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Define your validation rules in a property in
     * the controller to reuse the rules.
     */
    protected $validationRules = [
        'title' => 'required|unique|max:255',
        'body' => 'required',
    ];

    /**
     * Show the edit form for blog post.
     * We create a JsValidator instance based on shared validation rules.
     *
     * @param  string|int  $post_id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $validator = JsValidator::make($this->validationRules);
        $post = [
            'tilte' => 'myTitle',
            'body' => 'The post body',
        ];
    
        return view('post.post')->with([
            'validator' => $validator,
            'post' => $post,
        ]);
    }

    /**
     * Store the incoming blog post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), $this->validationRules);
    
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }
    
        // do store stuff
    }
}