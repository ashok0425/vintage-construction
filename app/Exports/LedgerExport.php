<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class LedgerExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct($data)
    {
        $this->data=$data;
    }

    public function view():view
    {
        return view('exports.ledger', [
            'ledgers' => $this->data
        ]);
    }
}
