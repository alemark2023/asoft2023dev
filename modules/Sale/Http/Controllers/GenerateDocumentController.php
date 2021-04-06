<?php

namespace Modules\Sale\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\Person;
use App\Models\Tenant\Series;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Sale\Http\Resources\TechnicalServiceResource;
use Modules\Sale\Models\TechnicalService;

class GenerateDocumentController extends Controller
{
    use FinanceTrait;

    public function tables()
    {
        $establishment = Establishment::query()->where('id', auth()->user()->establishment_id)->first();
        $series = Series::query()->where('establishment_id',$establishment->id)->get();
        $document_types = [
            ['id' => '01', 'name' => 'Factura'],
            ['id' => '03', 'name' => 'Boleta'],
            ['id' => 'nv', 'name' => 'Nota de venta'],
        ];
        $payment_method_types = PaymentMethodType::all();
        $payment_destinations = $this->getPaymentDestinations();

        return compact('series', 'document_types', 'payment_method_types', 'payment_destinations');
    }

    public function record($table, $id)
    {
        if($table === 'technical-services') {
            return new TechnicalServiceResource(TechnicalService::query()->findOrFail($id));
        }
    }

    public function customers(Request $request)
    {
        $customers = Person::query()->where('number','like', "%{$request->input}%")
            ->orWhere('name','like', "%{$request->input}%")
            ->whereType('customers')
            ->whereIsEnabled()
            ->orderBy('name')
            ->get()->transform(function($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number.' - '.$row->name,
                    'name' => $row->name,
                    'number' => $row->number,
                    'identity_document_type_id' => $row->identity_document_type_id,
                    'identity_document_type_code' => $row->identity_document_type->code,
                    'addresses' => $row->addresses,
                    'address' =>  $row->address
                ];
            });

        return compact('customers');
    }

//    private function searchCustomers($input)
//    {
//        return  Person::query()->where('number','like', "%{$input}%")
//        ->orWhere('name','like', "%{$input}%")
//        ->whereType('customers')
//        ->whereIsEnabled()
//        ->orderBy('name')
//        ->get()->transform(function($row) {
//            return [
//                'id' => $row->id,
//                'description' => $row->number.' - '.$row->name,
//                'name' => $row->name,
//                'number' => $row->number,
//                'identity_document_type_id' => $row->identity_document_type_id,
//                'identity_document_type_code' => $row->identity_document_type->code,
//                'addresses' => $row->addresses,
//                'address' =>  $row->address
//            ];
//        });
//    }
}
