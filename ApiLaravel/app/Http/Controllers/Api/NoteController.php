<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Note;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Note::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['note', 'category_id']);

        $this->validate($request, [
            'note'  => 'required',
            'category_id' => 'exists:categories,id'
        ]);

        $note = Note::create($data);

        return [
            'success' => true,
            'note' => $note->toArray()
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
          $data = $request->only(['note', 'category_id']);
          

        $this->validate($request, [
            'note'  => 'required',
            'category_id' => 'exists:categories,id'
        ]);


        $note = Note::findOrFail($id);

        $note->fill($data);

        $note->save();

        return [
            'success' => true,
            'note' => $note
        ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // abort(404, 'Something is wrong!');

        $note = Note::findOrFail($id);

        $note->delete();

        return [
            'success' => true
        ];
    }
}
