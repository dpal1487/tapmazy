<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TermAndConditionController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function termAndCondition(Request $request)
    {
        $request->validate([
            'term_and_condition' => 'required',
        ]);

        $termAndCondition = Page::created([
            'emtity_type' => 'term_and_condition',
            'content' => $request->term_and_condition,
        ]);

        if ($termAndCondition){
            return redirect('pages')->with(['flash' , createMessage('Term and condition')]);
        }
        else{
            return redirect('pages')->with(['flash' , errorMessage()]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
