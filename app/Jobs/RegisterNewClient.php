<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Config, DB, Exception;
use App\Models\Client;
use App\Models\Product;
use App\Models\Preference;
use App\Models\Staff\Staff;
use App\Models\Master\Grade;
use App\Enum\Staff\StaffStatus;
use Illuminate\Database\Seeder;
use App\Models\Master\ClassGroup;
use App\Models\Master\ClassPrice;
use App\Models\Master\Department;
use App\Models\Master\MasterClass;
use App\Models\Master\MediaSource;
use App\Models\Master\StudentNote;
use App\Models\Master\StudentPhase;
use App\Models\UserManagement\Role;
use App\Models\Master\AbsenceReason;
use App\Models\Master\StaffPosition;
use App\Models\Master\PositionSalary;
use App\Models\Master\SpecialAllowance;
use App\Models\Master\StudentOutReason;
use App\Models\Master\PettyCashCategory;
use App\Models\UserManagement\Permission;
use App\Models\Master\PublicRelationStatus;
use App\Models\Master\SpecialAllowanceGroup;
use App\Models\UserManagement\Client as UserClient;

class RegisterNewClient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $passwordEncrypted;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $passwordEncrypted = false)
    {
        $this->data = $data;
        $this->passwordEncrypted = $passwordEncrypted;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        DB::beginTransaction();
        try {
        // Config::set('platform', 'Client');
        // DB::select('SET FOREIGN_KEY_CHECKS=0;');
        // Client::truncate();
        // UserClient::wherePlatform(platform())->forceDelete();
        // Role::wherePlatform(platform())->forceDelete();
        // Department::truncate();
        // StaffPosition::truncate();
        // AbsenceReason::truncate();
        // PublicRelationStatus::truncate();
        // ClassPrice::truncate();
        // ClassGroup::truncate();
        // Grade::truncate();
        // MasterClass::truncate();
        // MediaSource::truncate();
        // StudentPhase::truncate();
        // StudentOutReason::truncate();
        // StudentNote::truncate();
        // PettyCashCategory::truncate();
        // Product::truncate();
        // SpecialAllowance::truncate();
        // SpecialAllowanceGroup::truncate();
        // Staff::truncate();
        // PositionSalary::truncate();
        // Preference::withoutGlobalScope('platform')->wherePlatform(platform())->forceDelete();
        // DB::select('SET FOREIGN_KEY_CHECKS=1;');

        foreach (['Pulomas2'] as $i => $clientName) {
            // dd($clientName, $data);
            $client = new Client;
            $client->code = '00' . $i + 1;
            $client->name = $clientName;
            $client->type = 'Default';
            $client->staff_name = 'Jimmy Setiawan';
            $client->phone = '08111111111';
            $client->email = 'jimmysetiawan.js@gmail.com';
            $client->address = [
                'address' => 'Jalan Boulevard ' . $clientName . 'Blok A no 99.',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Utara',
                'district' => 'Kelapa Gading',
                'subdistrict' => 'Kelapa Gading Timur',
                'rt' => '004',
                'rw' => '003',
                'pos_code' => '14242'
            ];
            $client->account_bank = 'BCA';
            $client->account_number = '0650412222';
            $client->account_name = 'Jimmy Setiawan';
            $client->save();

            $admin = new Role;
            $admin->name = $clientName . ' Admin';
            $admin->display_name = 'Administrator';
            $admin->platform = 'Client';
            $admin->client_id = $client->id;
            $admin->save();
            $admin->attachPermissions(Permission::all());

            $user = new UserClient;
            $user->name = $clientName . ' Admin';
            $user->email = kebab_case($clientName) . '@client.com';
            $user->username = kebab_case($clientName);
            $user->password = bcrypt('123123');
            $user->platform = 'Client';
            $user->client_id = $client->id;
            $user->active = 1;
            $user->save();
            $user->attachRole($admin);

            Department::insert([
                ['client_id' => $i + 1, 'code' => 'bA', 'name' => 'biMBA-AIUEO', 'price' => 350000, 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'Eb', 'name' => 'English biMBA', 'price' => 100000, 'created_at' => now(), 'updated_at' => now()],
            ]);

            $positions = ['Kepala Unit', 'Asisten KU', 'Guru', 'Asisten Guru', 'Staff Mobile', 'Admin', 'Bendahara', 'Satpam', 'Office Boy', 'Office Girl'];
            $positionWithActiveStatus = ['Admin', 'Bendahara', 'Satpam', 'Office Boy', 'Office Girl'];
            $positionWithExtraFunctional = ['Kepala Unit', 'Admin', 'Bendahara', 'Satpam', 'Office Boy', 'Office Girl'];
            foreach ($positions as $positionName) {
                $staffPosition = new StaffPosition;
                $staffPosition->client_id = $i + 1;
                $staffPosition->name = $positionName;
                $staffPosition->save();

                if ($positionName === 'Staff Mobile') continue;

                foreach (['0,23', '24,999'] as $workLength) {
                    list($minWorkLength, $maxWorkLength) = explode(',', $workLength);

                    foreach (StaffStatus::keys() as $staffStatus) {
                        if (in_array($positionName, $positionWithActiveStatus) && $staffStatus !== StaffStatus::Active) continue;
                        if ($staffStatus === StaffStatus::Intern && $minWorkLength == 24) continue;

                        $positionSalary = new PositionSalary;
                        $positionSalary->position_id = $staffPosition->id;
                        $positionSalary->min_work_length = $minWorkLength;
                        $positionSalary->max_work_length = $maxWorkLength;
                        $positionSalary->status = $staffStatus;
                        $positionSalary->basic_salary = $staffStatus !== StaffStatus::Intern
                            ? ($minWorkLength == 24 ? 350000 : 300000)
                            : 0;
                        $positionSalary->daily = 450000;
                        $positionSalary->functional = (in_array($positionName, $positionWithExtraFunctional) && $minWorkLength == 24) ? 150000 : 100000;
                        $positionSalary->health = $minWorkLength == 24 ? 100000 : 50000;
                        $positionSalary->save();
                    }
                }
            }

            AbsenceReason::insert([
                ['client_id' => $i + 1, 'reason' => 'Sakit Dengan Keterangan Dokter', 'status' => 'Sakit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Sakit Tanpa Keterangan Dokter', 'status' => 'Alpa', 'created_at' => now(), 'updated_at' => now()]
            ]);

            PublicRelationStatus::insert([
                ['client_id' => $i + 1, 'name' => 'Komunitas', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Non Komunitas', 'created_at' => now(), 'updated_at' => now()],
            ]);

            ClassGroup::insert([
                ['client_id' => $i + 1, 'name' => 'Kelas Standar', 'total_teacher' => '1', 'total_student' => '4', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Kelas Khusus', 'total_teacher' => '1', 'total_student' => '2', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Kelas Beasiswa', 'total_teacher' => '1', 'total_student' => '4', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Kelas English', 'total_teacher' => '1', 'total_student' => '6 - 10', 'created_at' => now(), 'updated_at' => now()],
            ]);

            Grade::insert([
                ['client_id' => $i + 1, 'name' => 'A', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'B', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'C', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'D', 'created_at' => now(), 'updated_at' => now()],
            ]);

            $standardClass = ClassGroup::withoutGlobalScopes()->whereClientId($i + 1)->whereName('Kelas Standar')->first()->id;
            $specialClass = ClassGroup::withoutGlobalScopes()->whereClientId($i + 1)->whereName('Kelas Khusus')->first()->id;
            $scholarshipClass = ClassGroup::withoutGlobalScopes()->whereClientId($i + 1)->whereName('Kelas Beasiswa')->first()->id;
            $englishClass = ClassGroup::withoutGlobalScopes()->whereClientId($i + 1)->whereName('Kelas English')->first()->id;
            MasterClass::insert([
                ['client_id' => $i + 1, 'code' => 'S2', 'name' => 'Standar 2 : Seminggu 2x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'S3', 'name' => 'Standar 3 : Seminggu 3x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'S4', 'name' => 'Standar 4 : Seminggu 4x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'S5', 'name' => 'Standar 5 : Seminggu 5x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'S6', 'name' => 'Standar 6 : Seminggu 6x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'P72', 'name' => 'Paket 72 : Seminggu 3x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],

                ['client_id' => $i + 1, 'code' => 'K2', 'name' => 'Khusus 2 : Seminggu 2x', 'group_id' => $specialClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'K3', 'name' => 'Khusus 3 : Seminggu 3x', 'group_id' => $specialClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'K4', 'name' => 'Khusus 4 : Seminggu 4x', 'group_id' => $specialClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'K5', 'name' => 'Khusus 5 : Seminggu 5x', 'group_id' => $specialClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],

                ['client_id' => $i + 1, 'code' => 'S3B1', 'name' => 'Bea Rp 100.000 : Seminggu 3x', 'group_id' => $scholarshipClass, 'scholarship' => 'BNF', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'S3B2', 'name' => 'Bea Rp 200.000 : Seminggu 3x', 'group_id' => $scholarshipClass, 'scholarship' => 'BNF', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'S3B3', 'name' => 'Bea Rp 50.000 : Seminggu 3x', 'group_id' => $scholarshipClass, 'scholarship' => 'BNF', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'D', 'name' => 'Beasiswa Full : Seminggu 3x', 'group_id' => $scholarshipClass, 'scholarship' => 'Dhuafa', 'created_at' => now(), 'updated_at' => now()],

                ['client_id' => $i + 1, 'code' => 'S1_Mb', 'name' => 'Murid biMBA : Seminggu 1x', 'group_id' => $englishClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'S1_Mu', 'name' => 'Murid umum : Seminggu 1x', 'group_id' => $englishClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'S3_Mb', 'name' => 'Murid biMBA : Seminggu 3x', 'group_id' => $englishClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'S3_Mu', 'name' => 'Murid umum : Seminggu 3x', 'group_id' => $englishClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ]);

            MediaSource::insert([
                ['client_id' => $i + 1, 'name' => 'Brosur', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Event', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Humas', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Internet', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Spanduk', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Lainnya', 'created_at' => now(), 'updated_at' => now()],
            ]);

            StudentPhase::insert([
                ['client_id' => $i + 1, 'name' => 'Persiapan', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Lanjutan', 'created_at' => now(), 'updated_at' => now()],
            ]);

            StudentOutReason::insert([
                ['client_id' => $i + 1, 'reason' => 'Banyak Kegiatan', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Belum Bayar SPP', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Belum Kondusif', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Masuk TK/SD', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Perpanjang Bea', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Pindah biMBA', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Pindah Rumah', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Sakit/Rehat', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Sudah Bisa Baca', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Sudah Lulus', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Tidak Ada Yang Antar', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'reason' => 'Tidak Ada Kabar', 'created_at' => now(), 'updated_at' => now()],
            ]);

            StudentNote::insert([
                ['client_id' => $i + 1, 'name' => 'Aktif Kembali', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Cuti', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Ganti Gol', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Garansi', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Pindahan', 'created_at' => now(), 'updated_at' => now()],
            ]);

            PettyCashCategory::insert([
                ['client_id' => $i + 1, 'name' => 'Petty Cash', 'code' => '500', 'type' => 'Debit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Modul', 'code' => '501', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Modul Mewarnai', 'code' => '502', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Upah', 'code' => '503', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Humas', 'code' => '504', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Bagi Hasil', 'code' => '505', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Sewa Tempat', 'code' => '506', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Listrik', 'code' => '507', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Air', 'code' => '508', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Telepon', 'code' => '509', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'ATK, ABM, FC & Fax', 'code' => '510', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Rumah Tangga', 'code' => '511', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Iuran & Sumbangan', 'code' => '512', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Transportasi', 'code' => '513', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Kegiatan', 'code' => '514', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Perawatan ', 'code' => '515', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Kaos', 'code' => '516', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Sertifikat', 'code' => '517', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Lain-Lain', 'code' => '518', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Gaji', 'code' => '519', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Progresif', 'code' => '520', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'THR', 'code' => '521', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ]);

            Product::insert([
                ['client_id' => $i + 1, 'code' => 'KA', 'name' => 'Kaos Anak', 'price' => 40000, 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'ME', 'name' => 'Modul Eksklusif', 'price' => 35000, 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'STF', 'name' => 'Sertifikat', 'price' => 10000, 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'STPB', 'name' => 'Surat Tanda Peserta biMBA', 'price' => 40000, 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'code' => 'TAS', 'name' => 'Tas biMBA', 'price' => 60000, 'created_at' => now(), 'updated_at' => now()],
            ]);

            $englishDepartmentId = Department::withoutGlobalScopes()->whereClientId($i + 1)->whereCode('Eb')->first()->id;
            SpecialAllowanceGroup::insert([
                ['client_id' => $i + 1, 'name' => 'Kerajinan', 'department_id' => null, 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'English', 'department_id' => $englishDepartmentId, 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Mentor', 'department_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ]);

            $artGroupId = SpecialAllowanceGroup::withoutGlobalScopes()->whereClientId($i + 1)->whereName('Kerajinan')->first()->id;
            $englishGroupId = SpecialAllowanceGroup::withoutGlobalScopes()->whereClientId($i + 1)->whereName('English')->first()->id;
            $mentorGroupId = SpecialAllowanceGroup::withoutGlobalScopes()->whereClientId($i + 1)->whereName('Mentor')->first()->id;
            SpecialAllowance::insert([
                ['client_id' => $i + 1, 'name' => 'Kerajinan', 'group_id' => $artGroupId, 'price' => 100000, 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'English biMBA', 'group_id' => $englishGroupId, 'price' => 500000, 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'Mentor Magang', 'group_id' => $mentorGroupId, 'price' => 100000, 'created_at' => now(), 'updated_at' => now()],
                ['client_id' => $i + 1, 'name' => 'PJ Training', 'group_id' => $mentorGroupId, 'price' => 750000, 'created_at' => now(), 'updated_at' => now()],
            ]);

            foreach (['John Doe', 'Jimmy Setiawan'] as $j => $staffName) {
                $staff = new Staff;
                $staff->client_id = $i + 1;
                $staff->nik = '475' . $i + 1 . '010000000' . $i + $j + 1;
                $staff->name = $staffName;
                $staff->department_id = Department::withoutGlobalScopes()->whereClientId($i + 1)->whereName('biMBA-AIUEO')->first()->id;
                $staff->position_id = StaffPosition::withoutGlobalScopes()->whereClientId($i + 1)->whereName('Guru')->first()->id;
                $staff->birth_date = '1990-01-01';
                $staff->phone = '08111111111';
                $staff->email = kebab_case($staffName) . '@acme.com';
                $staff->account_bank = 'BCA';
                $staff->account_number = '111111111';
                $staff->account_name = $staffName;
                $staff->joined_date = now();
                $staff->save();
            }

            Preference::insert([
                [
                    'key' => 'logo',
                    'value' => url('assets/images/logo.png'),
                    'platform' => 'Client',
                    'client_id' => $client->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], [
                    'key' => 'phone',
                    'value' => '+ 62 21 7388 1188',
                    'platform' => 'Client',
                    'client_id' => $client->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], [
                    'key' => 'email',
                    'value' => 'client' . $clientName . '@client.com',
                    'platform' => 'Client',
                    'client_id' => $client->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], [
                    'key' => 'profit_sharing_percentage',
                    'value' => '5',
                    'platform' => 'Client',
                    'client_id' => $client->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        $classes = MasterClass::withoutGlobalScopes()->get();
        foreach ($classes as $key => $class) {
            $grades = Grade::withoutGlobalScopes()->where('client_id', $class->client_id)->get();
            foreach ($grades as $key => $grade) {
                ClassPrice::insert([
                    ['class_id' => $class->id, 'grade_id' => $grade->id, 'price' => 0, 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            report($e);

            throw $e;
        }

        return true;
    }
}
