<?php

namespace App\Imports;

use App\Models\Userinfo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Userinfo([
            'firstname'     => $row['firstname'],
            'lastname'      => $row['lastname'],
            'fullname'      => $row['fullname'],
            'emailid'       => $row['emailid'],
            'mobileno'      => $row['mobileno'],
            'pan_no'        => $row['pan_no'],
        ]);
    }
}