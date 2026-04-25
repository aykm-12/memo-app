<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $note = Note::create([
            'text' => $request->text
        ]);

        return response()->json($note);
    }

    public function index()
    {
        return Note::all();
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();

        return response()->json(['message' => 'deleted']);
    }

}
