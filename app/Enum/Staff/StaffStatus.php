<?php

namespace App\Enum\Staff;

use App\Enum\Enum;

abstract class StaffStatus extends Enum
{
    const Active = "Active";
    const Intern = "Intern";
    const Resign = "Resign";
}
