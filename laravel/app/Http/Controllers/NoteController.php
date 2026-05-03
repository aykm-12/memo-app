<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $note = Note::create([
            'text' => $request->text,
            'category_id' => $request->category_id
        ]);

        return response()->json($note->load('category'));
    }

    public function index()
    {
        return Note::with('category')->get();
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();

        return response()->json(['message' => 'deleted']);
    }

    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);

        $note -> update([
            'text' => $request->text,
            'category_id' => $request->category_id
        ]);

        return response()->json($note->load('category'));
    }

}
