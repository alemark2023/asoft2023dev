<?php

namespace Modules\Quotation\Observers;

use App\Models\Tenant\Company;
use App\Models\Tenant\Quotation;
use Illuminate\Support\Str;

class QuotationObserver
{
    public function creating(Quotation $data)
    {
        $company = Company::query()->first();

        $data->user_id = auth()->id();
        $data->external_id = Str::uuid()->toString();
        $data->soap_type_id = $company->soap_type_id;
        $data->state_type_id = '01';

        $number = $this->getNewNumber($data);
        $filename = join('-', [$data->series, $number, date('Ymd')]);
        $data->number = $number;
        $data->filename = $filename;
    }

    private function getNewNumber($data)
    {
        if ($data->number === '#') {
            $record = Quotation::query()
                ->select('number')
                ->where('soap_type_id', $data->soap_type_id)
                ->where('series', $data->series)
                ->orderBy('number', 'desc')
                ->first();

            if ($record) {
                return $record->number + 1;
            }
            return 1;
        }

        return $data->number;
    }
}
