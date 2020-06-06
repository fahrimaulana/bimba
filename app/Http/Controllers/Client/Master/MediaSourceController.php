<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\MediaSource;
use App\Http\Controllers\Controller;

class MediaSourceController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-media-source-list');

        return view('client.master.media-source.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-media-source');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $mediaSource = new MediaSource;
        $mediaSource->client_id = clientId();
        $mediaSource->name = $request->name;
        $mediaSource->save();

        return redirect()->route('client.master.media-source.index')->with('notif_success', 'New media source has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-media-source');

        $mediaSource = MediaSource::findOrFail($id);
        return view('client.master.media-source.edit', compact('mediaSource'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-media-source');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $mediaSource = MediaSource::findOrFail($id);
        $mediaSource->name = $request->name;
        $mediaSource->save();

        return redirect()->route('client.master.media-source.index')->with('notif_success', 'Media source has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-media-source');

        $mediaSource = MediaSource::findOrFail($id);

        $mediaSource->delete();

        return redirect()->route('client.master.media-source.index')->with('notif_success', 'Media source has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-media-source-list');

        $mediaSources = MediaSource::query();

        return Datatables::of($mediaSources)
                    ->addColumn('action', function($mediaSource) {
                        $edit = '<a href="' . route('client.master.media-source.edit', $mediaSource->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit media source"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.media-source.destroy', $mediaSource->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete media source"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-media-source') ? $edit : '') . (userCan('delete-media-source') ? $delete : '');
                    })
                    ->make(true);
    }
}