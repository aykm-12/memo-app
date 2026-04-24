<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        return view('note.index');
    }

    public function store(Request $request)
    {
        $note = Note::create([
            'text' => $request->text
        ]);

        return response()->json($note);
    }
}
