<?php

namespace App\Http\Controllers\Client\Master;

use App\Models\Master\Grade;
use Illuminate\Http\Request;
use DB, Exception;
use App\Models\Master\ClassGroup;
use App\Models\Master\ClassPrice;
use App\Models\Master\MasterClass;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class ClassController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-class-list');

        $classes = MasterClass::with(['classPrices', 'group' => function ($qry) {
            $qry->orderBy('created_at', 'desc');
        }])->get();

        $grades = Grade::all();
        $groups = ClassGroup::all();

        return view('client.master.class.index', compact('groups', 'classes', 'grades'));
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-class');

        $class = MasterClass::findOrFail($id);

        return view('client.master.class.edit', compact('class'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-class');

        $this->validate($request, [
            'group_id' => 'required|integer|exists:master_class_groups,id,deleted_at,NULL',
            'code'     => 'required',
            'name'     => 'required',
            'grade.*'   => 'nullable|integer',
        ]);

        $code = MasterClass::where('code', $request->code)
            ->whereNotNull('code')
            ->first();

        if ($code) return validationError('Kode telah digunakan oleh Kelas lain');

        DB::beginTransaction();
        try {
            $class            = new MasterClass;
            $class->client_id = clientId();
            $class->group_id  = $request->group_id;
            $class->code      = $request->code;
            $class->name      = $request->name;
            $class->scholarship = $request->scholarship ?: 'None';
            $class->save();

            foreach ($request->grade as $key => $grade) {
                $classPrice = new ClassPrice;
                $classPrice->class_id = $class->id;
                $classPrice->grade_id = $key ?: 0;
                $classPrice->price =  $grade ?: 0;
                $classPrice->client_id = clientId();
                $classPrice->save();
            }
        } catch (ValidationException $e) {
            DB::rollBack();

            throw new ValidationException($e->validator, $e->getResponse());
        } catch (Exception $e) {
            DB::rollBack();

            return unknownError($e, 'Gagal memperbaruhi kelas. Silakan coba lagi.');
        }
        DB::commit();
        return redirect()->route('client.master.class.index')->with('notif_success', 'Kelas Baru telah berhasil disimpan!');
    }

    public function update(Request $request)
    {
        checkPermissionTo('edit-class');
        $this->validate($request, [
            'prices.*' => 'nullable|numeric'
        ]);
        foreach ($request->prices as $key => $price) {
            $classPrice = ClassPrice::findOrFail($key);
            $classPrice->price = $price ? $price : 0;
            $classPrice->save();
        }

        return redirect()->route('client.master.class.index')->with('notif_success', 'Kelas telah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-class');

        $class = MasterClass::findOrFail($id);
        $classPrices = ClassPrice::where('class_id', $class->id)->get();

        foreach ($classPrices as $key => $classPrice) {
            $classPrice->delete();
        }

        $class->delete();

        return redirect()->route('client.master.class.index')->with('notif_success', 'Class has been deleted successfully!');
    }
}
