<?php

namespace App\Imports;

use App\Models\Aduan;
use Maatwebsite\Excel\Concerns\ToModel;

class AduanImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Aduan([
            //
        ]);
    }
}
