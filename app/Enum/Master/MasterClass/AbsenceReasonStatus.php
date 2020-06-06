<?php

namespace App\Enum\Master\MasterClass;

use App\Enum\Enum;

abstract class AbsenceReasonStatus extends Enum
{
    const Sakit = 'Sakit';
    const Izin = 'Izin';
    const TidakAktif = 'Tidak Aktif';
    const Alpa = 'Alpa';
    const C = 'C';
    const DT = 'DT';
    const PC = 'PC';
}
