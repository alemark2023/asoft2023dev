<?php

namespace App\Http\Controllers\Tenant\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Quotation;
use App\Http\Resources\Tenant\QuotationCollection;

class QuotationController extends Controller
{
    public function list()
    {
        $records = Quotation::orderBy('prefix', 'desc')->take(50)->get();
        $records = new QuotationCollection($record); // crear nuevo collection para apis

        return $records;
    }
}
