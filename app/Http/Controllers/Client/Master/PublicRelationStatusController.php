<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\PublicRelationStatus;
use App\Http\Controllers\Controller;

class PublicRelationStatusController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-public-relation-status-list');

        return view('client.master.public-relation-status.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-public-relation-status');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $publicRelationStatus = new PublicRelationStatus;
        $publicRelationStatus->client_id = clientId();
        $publicRelationStatus->name = $request->name;
        $publicRelationStatus->save();

        return redirect()->route('client.master.public-relation-status.index')->with('notif_success', 'New public relation status has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-public-relation-status');

        $publicRelationStatus = PublicRelationStatus::findOrFail($id);
        return view('client.master.public-relation-status.edit', compact('publicRelationStatus'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-public-relation-status');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $publicRelationStatus = PublicRelationStatus::findOrFail($id);
        $publicRelationStatus->name = $request->name;
        $publicRelationStatus->save();

        return redirect()->route('client.master.public-relation-status.index')->with('notif_success', 'Public relation status has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-public-relation-status');

        $publicRelationStatus = PublicRelationStatus::findOrFail($id);

        $publicRelationStatus->delete();

        return redirect()->route('client.master.public-relation-status.index')->with('notif_success', 'Public relation status has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-public-relation-status-list');

        $publicRelationStatuses = PublicRelationStatus::query();

        return Datatables::of($publicRelationStatuses)
                    ->addColumn('action', function($publicRelationStatus) {
                        $edit = '<a href="' . route('client.master.public-relation-status.edit', $publicRelationStatus->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit public relation status"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.public-relation-status.destroy', $publicRelationStatus->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete public relation status"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-public-relation-status') ? $edit : '') . (userCan('delete-public-relation-status') ? $delete : '');
                    })
                    ->make(true);
    }
}