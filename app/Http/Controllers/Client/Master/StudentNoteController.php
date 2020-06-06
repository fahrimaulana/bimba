<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\StudentNote;
use App\Http\Controllers\Controller;

class StudentNoteController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-student-note-list');

        return view('client.master.student-note.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-student-note');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $studentNote = new StudentNote;
        $studentNote->client_id = clientId();
        $studentNote->name = $request->name;
        $studentNote->save();

        return redirect()->route('client.master.student-note.index')->with('notif_success', 'New student note has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-student-note');

        $studentNote = StudentNote::findOrFail($id);
        return view('client.master.student-note.edit', compact('studentNote'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-student-note');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $studentNote = StudentNote::findOrFail($id);
        $studentNote->name = $request->name;
        $studentNote->save();

        return redirect()->route('client.master.student-note.index')->with('notif_success', 'Student note has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-student-note');

        $studentNote = StudentNote::findOrFail($id);

        $studentNote->delete();

        return redirect()->route('client.master.student-note.index')->with('notif_success', 'Student note has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-student-note-list');

        $studentNotes = StudentNote::query();

        return Datatables::of($studentNotes)
                    ->addColumn('action', function($studentNote) {
                        $edit = '<a href="' . route('client.master.student-note.edit', $studentNote->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit student note"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.student-note.destroy', $studentNote->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete student note"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-student-note') ? $edit : '') . (userCan('delete-student-note') ? $delete : '');
                    })
                    ->make(true);
    }
}