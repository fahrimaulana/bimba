<?php

namespace App\Http\Controllers\Client\UploadDownload;

use DB, File, Datatables;
use Illuminate\Http\Request;
use App\Models\UploadDownload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadDownloadController extends Controller
{
    public function index()
    {
        $uploadDownloads = UploadDownload::all();

        return view('backend.upload-download.index', compact('uploadDownloads'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'import_file' => 'required'
        ]);

        $importFile = $request->file('import_file')->getClientOriginalName();
        Storage::putFileAs('import-file', $request->file('import_file'), $importFile);

        $uploadDownload = new UploadDownload;
        $uploadDownload->name = $request->name;
        $uploadDownload->file = $importFile;
        $uploadDownload->save();

        return redirect()->route('client.upload-download.index')->with('notif_success', 'Import File Berhasil di simpan');
    }

    public function destroy($id)
    {
        $uploadDownload = UploadDownload::find($id);
        Storage::delete(url('../storage/app/public/uploads/import-file/'.$uploadDownload->file));
        $uploadDownload->delete();

        return redirect()->route('client.upload-download.index')->with('notif_success', 'Import File Berhasil di delete');
    }

    public function getData(Request $request)
    {
        $uploadDownloads = UploadDownload::query()
                            ->where(DB::raw('year(created_at)'), year())
                            ->where(DB::raw('month(created_at)'), month());


        return Datatables::of($uploadDownloads)
                    ->editColumn('created_at', function($userLoginHistory) {
                        return $userLoginHistory->created_at->format('d M Y H:i:s');
                    })
                    ->addColumn('action', function($uploadDownload) {
                        $download = '<a href="'. url('../storage/app/public/uploads/import-file/'.$uploadDownload->file) .'" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Download"><i class="icon wb-download" aria-hidden="true"></i></a>';

                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.upload-download.destroy', $uploadDownload->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return $download .''. $delete;
                    })
                    ->make(true);
    }
}
