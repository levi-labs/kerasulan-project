<?php

namespace App\Imports;

use App\Models\DataTraining;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataTrainingImport implements ToModel, WithHeadingRow
{
    /**
     * @param Model $model
     */
    public function model(array $row)
    {
        return new DataTraining([
            'nama' => $row['nama'],
            'membaca_not_angka' => $row['membaca_not_angka'],
            'mengoperasikan_software' => $row['mengoperasikan_software'],
            'mengoperasikan_audio' => $row['mengoperasikan_audio'],
            'bidang' => $row['bidang'],
        ]);
    }
}
