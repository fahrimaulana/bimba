<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Product;
use App\Models\Preference;
use DB, Exception, Config;
use App\Models\Staff\Staff;
use App\Models\Master\Grade;
use App\Enum\Staff\StaffStatus;
use App\Jobs\RegisterNewClient;
use Illuminate\Console\Command;
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
use App\Models\Student\EducationSertificate;
use App\Models\UserManagement\Client as UserClient;

class RegisterNewClientCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:client
                                {--clientCode= : Client Code}
                                {--clientName= : Client name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup new client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Config::set('platform', 'Client');


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

        // $faker = Faker\Factory::create('id_ID');
            $data = new \stdClass;
            $data->clientCode = $this->option('clientCode');
            $data->clientName = $this->option('clientName');

            $clientGroupExists = Client::where('code', $data->clientCode)->first();
            if ($clientGroupExists) {
                $this->error('Client with code ' . $data->clientCode . ' already exists!');
                return;
            }



        $client = new Client;
        $client->code =  $this->option('clientCode');
        $client->name = $this->option('clientName');
        $client->type = 'Default';
        $client->staff_name = 'Fandy Kurniawan';
        $client->phone = '082112335231';
        $client->email = 'fkurniawan64@gmail.com';
        $client->address = [
            'address' => 'Jalan Boulevard ' . $this->option('clientName') . 'Blok A no 99.',
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
        $client->account_name = 'fandy kurniawan';
        $client->save();

        $admin = new Role;
        $admin->name = $this->option('clientName') . ' Admin';
        $admin->display_name = 'Administrator';
        $admin->platform = 'Client';
        $admin->client_id = $client->id;
        $admin->save();
        $admin->attachPermissions(Permission::all());

        $user = new UserClient;
        $user->name = $this->option('clientName') . ' Admin';
        $user->email = kebab_case($this->option('clientName')) . '@client.com';
        $user->username = kebab_case($this->option('clientName'));
        $user->password = bcrypt('123123');
        $user->platform = 'Client';
        $user->client_id = $client->id;
        $user->active = 1;
        $user->save();
        $user->attachRole($admin);

        Department::insert([
            ['client_id' => $client->id, 'code' => 'bA', 'name' => 'biMBA-AIUEO', 'price' => 350000, 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'Eb', 'name' => 'English biMBA', 'price' => 100000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $positions = ['Kepala Unit', 'Asisten KU', 'Guru', 'Asisten Guru', 'Staff Mobile', 'Admin', 'Bendahara', 'Satpam', 'Office Boy', 'Office Girl'];
        $positionWithActiveStatus = ['Admin', 'Bendahara', 'Satpam', 'Office Boy', 'Office Girl'];
        $positionWithExtraFunctional = ['Kepala Unit', 'Admin', 'Bendahara', 'Satpam', 'Office Boy', 'Office Girl'];
        foreach ($positions as $positionName) {
            $staffPosition = new StaffPosition;
            $staffPosition->client_id = $client->id;
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
            ['client_id' => $client->id, 'reason' => 'Sakit Dengan Keterangan Dokter', 'status' => 'Sakit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Sakit Tanpa Keterangan Dokter', 'status' => 'Alpa', 'created_at' => now(), 'updated_at' => now()]
        ]);

        PublicRelationStatus::insert([
            ['client_id' => $client->id, 'name' => 'Komunitas', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Non Komunitas', 'created_at' => now(), 'updated_at' => now()],
        ]);

        ClassGroup::insert([
            ['client_id' => $client->id, 'name' => 'Kelas Standar', 'total_teacher' => '1', 'total_student' => '4', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Kelas Khusus', 'total_teacher' => '1', 'total_student' => '2', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Kelas Beasiswa', 'total_teacher' => '1', 'total_student' => '4', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Kelas English', 'total_teacher' => '1', 'total_student' => '6 - 10', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Grade::insert([
            ['client_id' => $client->id, 'name' => 'A', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'B', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'C', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'D', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $standardClass = ClassGroup::withoutGlobalScopes()->whereClientId($client->id)->whereName('Kelas Standar')->first()->id;
        $specialClass = ClassGroup::withoutGlobalScopes()->whereClientId($client->id)->whereName('Kelas Khusus')->first()->id;
        $scholarshipClass = ClassGroup::withoutGlobalScopes()->whereClientId($client->id)->whereName('Kelas Beasiswa')->first()->id;
        $englishClass = ClassGroup::withoutGlobalScopes()->whereClientId($client->id)->whereName('Kelas English')->first()->id;
        MasterClass::insert([
            ['client_id' => $client->id, 'code' => 'S2', 'name' => 'Standar 2 : Seminggu 2x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'S3', 'name' => 'Standar 3 : Seminggu 3x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'S4', 'name' => 'Standar 4 : Seminggu 4x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'S5', 'name' => 'Standar 5 : Seminggu 5x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'S6', 'name' => 'Standar 6 : Seminggu 6x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'P72', 'name' => 'Paket 72 : Seminggu 3x', 'group_id' => $standardClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],

            ['client_id' => $client->id, 'code' => 'K2', 'name' => 'Khusus 2 : Seminggu 2x', 'group_id' => $specialClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'K3', 'name' => 'Khusus 3 : Seminggu 3x', 'group_id' => $specialClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'K4', 'name' => 'Khusus 4 : Seminggu 4x', 'group_id' => $specialClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'K5', 'name' => 'Khusus 5 : Seminggu 5x', 'group_id' => $specialClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],

            ['client_id' => $client->id, 'code' => 'S3B1', 'name' => 'Bea Rp 100.000 : Seminggu 3x', 'group_id' => $scholarshipClass, 'scholarship' => 'BNF', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'S3B2', 'name' => 'Bea Rp 200.000 : Seminggu 3x', 'group_id' => $scholarshipClass, 'scholarship' => 'BNF', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'S3B3', 'name' => 'Bea Rp 50.000 : Seminggu 3x', 'group_id' => $scholarshipClass, 'scholarship' => 'BNF', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'D', 'name' => 'Beasiswa Full : Seminggu 3x', 'group_id' => $scholarshipClass, 'scholarship' => 'Dhuafa', 'created_at' => now(), 'updated_at' => now()],

            ['client_id' => $client->id, 'code' => 'S1_Mb', 'name' => 'Murid biMBA : Seminggu 1x', 'group_id' => $englishClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'S1_Mu', 'name' => 'Murid umum : Seminggu 1x', 'group_id' => $englishClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'S3_Mb', 'name' => 'Murid biMBA : Seminggu 3x', 'group_id' => $englishClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'S3_Mu', 'name' => 'Murid umum : Seminggu 3x', 'group_id' => $englishClass, 'scholarship' => 'None', 'created_at' => now(), 'updated_at' => now()],
        ]);

        MediaSource::insert([
            ['client_id' => $client->id, 'name' => 'Brosur', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Event', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Humas', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Internet', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Spanduk', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Lainnya', 'created_at' => now(), 'updated_at' => now()],
        ]);

        StudentPhase::insert([
            ['client_id' => $client->id, 'name' => 'Persiapan', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Lanjutan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        StudentOutReason::insert([
            ['client_id' => $client->id, 'reason' => 'Banyak Kegiatan', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Belum Bayar SPP', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Belum Kondusif', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Masuk TK/SD', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Perpanjang Bea', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Pindah biMBA', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Pindah Rumah', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Sakit/Rehat', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Sudah Bisa Baca', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Sudah Lulus', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Tidak Ada Yang Antar', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'reason' => 'Tidak Ada Kabar', 'created_at' => now(), 'updated_at' => now()],
        ]);

        StudentNote::insert([
            ['client_id' => $client->id, 'name' => 'Aktif Kembali', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Cuti', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Ganti Gol', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Garansi', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Pindahan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        PettyCashCategory::insert([
            ['client_id' => $client->id, 'name' => 'Petty Cash', 'code' => '500', 'type' => 'Debit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Modul', 'code' => '501', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Modul Mewarnai', 'code' => '502', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Upah', 'code' => '503', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Humas', 'code' => '504', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Bagi Hasil', 'code' => '505', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Sewa Tempat', 'code' => '506', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Listrik', 'code' => '507', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Air', 'code' => '508', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Telepon', 'code' => '509', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'ATK, ABM, FC & Fax', 'code' => '510', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Rumah Tangga', 'code' => '511', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Iuran & Sumbangan', 'code' => '512', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Transportasi', 'code' => '513', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Kegiatan', 'code' => '514', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Perawatan ', 'code' => '515', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Kaos', 'code' => '516', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Sertifikat', 'code' => '517', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Lain-Lain', 'code' => '518', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Gaji', 'code' => '519', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Progresif', 'code' => '520', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'THR', 'code' => '521', 'type' => 'Kredit', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Product::insert([
            ['client_id' => $client->id, 'code' => 'KA', 'name' => 'Kaos Anak', 'price' => 40000, 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'ME', 'name' => 'Modul Eksklusif', 'price' => 35000, 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'STF', 'name' => 'Sertifikat', 'price' => 10000, 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'STPB', 'name' => 'Surat Tanda Peserta biMBA', 'price' => 40000, 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'code' => 'TAS', 'name' => 'Tas biMBA', 'price' => 60000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $englishDepartmentId = Department::withoutGlobalScopes()->whereClientId($client->id)->whereCode('Eb')->first()->id;
        SpecialAllowanceGroup::insert([
            ['client_id' => $client->id, 'name' => 'Kerajinan', 'department_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'English', 'department_id' => $englishDepartmentId, 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Mentor', 'department_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $artGroupId = SpecialAllowanceGroup::withoutGlobalScopes()->whereClientId($client->id)->whereName('Kerajinan')->first()->id;
        $englishGroupId = SpecialAllowanceGroup::withoutGlobalScopes()->whereClientId($client->id)->whereName('English')->first()->id;
        $mentorGroupId = SpecialAllowanceGroup::withoutGlobalScopes()->whereClientId($client->id)->whereName('Mentor')->first()->id;
        SpecialAllowance::insert([
            ['client_id' => $client->id, 'name' => 'Kerajinan', 'group_id' => $artGroupId, 'price' => 100000, 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'English biMBA', 'group_id' => $englishGroupId, 'price' => 500000, 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'Mentor Magang', 'group_id' => $mentorGroupId, 'price' => 100000, 'created_at' => now(), 'updated_at' => now()],
            ['client_id' => $client->id, 'name' => 'PJ Training', 'group_id' => $mentorGroupId, 'price' => 750000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        foreach (['John Doe', 'Fandy Kurniawan'] as $j => $staffName) {
            $staff = new Staff;
            $staff->client_id = $client->id;
            $staff->nik = '475' . $client->id . '010000000' . $client->id + $j + 1;
            $staff->name = $staffName;
            $staff->department_id = Department::withoutGlobalScopes()->whereClientId($client->id)->whereName('biMBA-AIUEO')->first()->id;
            $staff->position_id = StaffPosition::withoutGlobalScopes()->whereClientId($client->id)->whereName('Guru')->first()->id;
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
                'value' => 'client' . $this->option('clientName') . '@client.com',
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

        EducationSertificate::insert([
            ['client_id' => $client->id, 'amount' => '1350000', 'amount_written' => '(Satu Juta Tiga Ratus Lima Puluh Ribu Rupiah)', 'change_date' => '2014-08-07', 'person_in_charge' => 'Iriana Nur Rahman', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $classes = MasterClass::withoutGlobalScopes()->where('client_id', $client->id)->get();
        foreach ($classes as $key => $class) {
            $grades = Grade::withoutGlobalScopes()->where('client_id', $class->client_id)->get();
            foreach ($grades as $key => $grade) {
                ClassPrice::insert([
                    ['client_id' => $client->id, 'class_id' => $class->id, 'grade_id' => $grade->id, 'price' => 0, 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        }

        $this->line($data->clientCode . ' has been registered!');
        return true;
    }
}
