<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::query();

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('note_code', 'like', '%' . $searchTerm . '%')
                      ->orWhere('department', 'like', '%' . $searchTerm . '%')
                      ->orWhere('comment', 'like', '%' . $searchTerm . '%');

            });
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified
            $query->orderBy($request->input('sort_by'), $sortOrder);
        }

        $notes = $query->paginate(10);
        // $notes = Note::all();
        // $practices = Practice::paginate(10);
        return view('setup.notes',compact('notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'note_code' => 'required',
            'department' => 'required',
            'comment' => 'required',
       ]);

        $note = new Note();
        $note->note_code  = $request->input('note_code');
        $note->department  = $request->input('department');
        $note->comment  = $request->input('comment');
        $note->save();

        Session::flash('message', 'Created successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $note = Note::find($id);
        return response()->json([
            'note' => $note,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'note_code' => 'required',
            'department' => 'required',
            'comment' => 'required',
       ]);


        $note = Note::find($id);
        $note->note_code  = $request->input('note_code');
        $note->department  = $request->input('department');
        $note->comment  = $request->input('comment');
        $note->update();


        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();

    }

    public function destroy($id)
    {
        $note = Note::find($id);
        $note->delete();
        Session::flash('message', 'Deleted successfully!');
        Session::flash('alert-class', 'alert-success');
    }
}
