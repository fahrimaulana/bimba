<?php

namespace App\Http\Controllers\Client\PublicRelation;

use Datatables, DB, Exception, CSV, Excel, File;
use Carbon\Carbon;
use App\Models\PublicRelation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\PublicRelationStatus;

class PublicRelationController extends Controller
{
    private $delimiter = ',';

    public function index()
    {
        checkPermissionTo('view-public-relation-list');

        $status = PublicRelationStatus::all();

        return view('client.public-relation.index', compact('status'));
    }

    public function insertTo($csv, array $rows, $uncleanError = null)
    {
        if ($uncleanError) {
            fputs($csv, implode($rows, $this->delimiter) . $this->delimiter . $uncleanError . "\n");
        } else {
            fputs($csv, implode($rows, $this->delimiter) . "\n");
        }

        return true;
    }

    public function importCsv(Request $request)
    {
        $masterPath = storage_path('app/uploads/public-relation/');
        $uncleanCsv = fopen($masterPath . 'fail/import-public-relation-fail.csv','w+');
        $pathToCsv = $request->file('csv_file')->getPathName();
        Excel::load($pathToCsv, function($results) use($uncleanCsv) {
            $results->sheet(0, function($sheet) use($uncleanCsv) {
                   foreach ($sheet->toArray() as $row) {
                        $registered_date = $row[0];
                        $nih = $row[1];
                        $publicRelationNih = PublicRelation::where('nih', $nih)->first();
                        $name = $row[2];
                        $publicRelationStatus = PublicRelationStatus::find($row[3]);
                        $phone = $row[4];
                        $email = $row[5];
                        if (empty($registered_date)) {
                            $this->insertTo($uncleanCsv, $row, 'TGL REG tidak boleh kosong');
                            continue;
                        }

                        if (empty($nih)) {
                            $this->insertTo($uncleanCsv, $row, 'Nih tidak boleh kosong');
                            continue;
                        }

                        if ($publicRelationNih) {
                            $this->insertTo($uncleanCsv, $row, 'Nih tidak boleh ada yang sama');
                            continue;
                        }

                        if (empty($name)) {
                            $this->insertTo($uncleanCsv, $row, 'Nama tidak boleh kosong');
                            continue;
                        }

                        if(empty($publicRelationStatus)) {
                            $this->insertTo($uncleanCsv, $row, 'Id Status tidak boleh kosong atau tidak di temukan di client '.Client()->name);
                            continue;
                        }

                        if (empty($phone)) {
                            $this->insertTo($uncleanCsv, $row, 'NO TELP/HP tidak boleh kosong');
                            continue;
                        }

                        if (empty($email)) {
                            $this->insertTo($uncleanCsv, $row, 'Email tidak boleh kosong');
                            continue;
                        }

                        $publicRelation                   = new PublicRelation;
                        $publicRelation->client_id        = clientId();
                        $publicRelation->registered_date  = Carbon::parse($row[0])->format('Y-m-d');
                        $publicRelation->nih              = $row[1];
                        $publicRelation->name             = $row[2];
                        $publicRelation->status_id        = $row[3];
                        $publicRelation->phone            = $row[4];
                        $publicRelation->email            = $row[5];
                        $publicRelation->job              = $row[6];
                        $publicRelation->address          = $row[7];
                        $publicRelation->save();
                    }

                });
            });

        return redirect()->route('client.public-relation.index')->with('notif_success', 'Humas baru telah berhasil import!');
    }

    public function show($id)
    {
        checkPermissionTo('show-public-relation-list');

        $publicRelation = PublicRelation::findOrFail($id);
        $status = PublicRelationStatus::all();

        return view('client.public-relation.show', compact('publicRelation', 'status'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-public-relation');

        $this->validate($request, [
            'registered_date' => 'required|date_format:d-m-Y',
            'nih'             => 'required|unique:public_relations,nih|min:9|max:9',
            'name'            => 'required',
            'status_id'       => 'required|integer|exists:master_public_relation_statuses,id,deleted_at,NULL',
            'phone'           => 'required|min:11|numeric',
            'email'           => 'required|email|unique:staff',
        ]);

        $publicRelation                  = new PublicRelation;
        $publicRelation->client_id       = clientId();
        $publicRelation->registered_date = Carbon::parse($request->registered_date);
        $publicRelation->nih             = $request->nih;
        $publicRelation->name            = $request->name;
        $publicRelation->status_id       = $request->status_id;
        $publicRelation->phone           = $request->phone;
        $publicRelation->email           = $request->email;
        $publicRelation->job             = $request->job;
        $publicRelation->address         = $request->address;
        $publicRelation->save();

        return redirect()->route('client.public-relation.index')->with('notif_success', 'Humas telah berhasil ditambahkan!');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-public-relation');

        $publicRelation = PublicRelation::findOrFail($id);
        $status = PublicRelationStatus::all();

        return view('client.public-relation.edit', compact('publicRelation', 'status'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-public-relation');

        $this->validate($request, [
            'registered_date' => 'required|date_format:d-m-Y',
            'name'            => 'required',
            'status_id'       => 'required|integer|exists:master_public_relation_statuses,id,deleted_at,NULL',
            'phone'           => 'required|min:11|numeric',
            'email'           => 'required|email|unique:staff',
        ]);

        $publicRelation                  = PublicRelation::findOrFail($id);
        $publicRelation->registered_date = Carbon::parse($request->registered_date);
        $publicRelation->nih             = $request->nih;
        $publicRelation->name            = $request->name;
        $publicRelation->status_id       = $request->status_id;
        $publicRelation->phone           = $request->phone;
        $publicRelation->email           = $request->email;
        $publicRelation->job             = $request->job;
        $publicRelation->address         = $request->address;
        $publicRelation->save();

        return redirect()->route('client.public-relation.index')->with('notif_success', ' Humas telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-public-relation');

        PublicRelation::findOrFail($id)->delete();

        return redirect()->route('client.public-relation.index')->with('notif_success', 'Humas telah berhasil dihapus!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-public-relation-list');

        $publicRelations = PublicRelation::with([
            'status' => function($qry) { $qry->withoutGlobalScopes(); }
        ])->select('public_relations.*')
        ->where(DB::raw('year(public_relations.registered_date)'), year())
        ->where(DB::raw('month(public_relations.registered_date)'), month());

        return Datatables::of($publicRelations)
                    ->editColumn('registered_date', function($publicRelation) {
                        return optional($publicRelation->registered_date)->format('d M Y');
                    })
                    ->addColumn('action', function($publicRelation) {
                        $show = '<a href="' . route('client.public-relation.show', $publicRelation->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Lihat Humas"><i class="icon wb-eye" aria-hidden="true"></i></a>';

                        $edit = '<a href="' . route('client.public-relation.edit', $publicRelation->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Humas"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.public-relation.destroy', $publicRelation->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus Humas"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('show-public-relation-list') ? $show : '') . (userCan('edit-public-relation') ? $edit : '') . (userCan('delete-public-relation') ? $delete : '');
                    })
                    ->rawColumns(['registered_date', 'action'])
                    ->make(true);
    }
}
