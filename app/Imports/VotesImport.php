<?php

namespace App\Imports;

use App\Models\Control;
use App\Models\Vote;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VotesImport implements ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        if ($row['voto']) {
            Control::where('id', $row['control'])->update(['vote' => $row['voto']]);
        }else{
            return;
        }
    }
}
