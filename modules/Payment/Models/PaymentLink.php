<?php

namespace Modules\Payment\Models;

use Carbon\Carbon;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Tenant\{
    DocumentPayment,
    ModelTenant,
    SoapType,
    User,
};


class PaymentLink extends ModelTenant
{

    protected $fillable = [
        'soap_type_id',
        'uuid',
        'user_id',
        'payment_link_type_id',
        'payment_id',
        'payment_type',
        'total',
        'uploaded_filename',
    ];


    /**
     * @return BelongsTo
     */
    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }
 
    
    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @return BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(PaymentLinkType::class, 'payment_link_type_id');
    }


    /**
     * @return MorphTo
     */
    public function payment()
    {
        return $this->morphTo();
    }


    /**
     * @return mixed
     */
    public function doc_payments()
    {
        return $this->belongsTo(DocumentPayment::class, 'payment_id')->wherePaymentType(DocumentPayment::class);
    }


    /**
     * @return string
     */
    public function getInstanceTypeAttribute()
    {
        $instance_type = [
            DocumentPayment::class => 'document',
        ];

        return $instance_type[$this->payment_type];
    }


    public function getInstanceTypeDescriptionAttribute()
    {

        $description = null;

        switch ($this->instance_type) {
            case 'document':
                $description = 'CPE';
                break;
        }

        return $description;
    }


    public function getDataPersonAttribute()
    {

        $record = $this->payment->associated_record_payment;

        switch ($this->instance_type) {

            case 'document':
                $person['name'] = $record->customer->name;
                $person['number'] = $record->customer->number;
                break;

        }

        return (object)$person;
    }
 
    
    /**
     * @return string
     */
    public function getUserPaymentLinkAttribute()
    {
        return url("pagos/{$this->uuid}/{$this->payment_link_type_id}/{$this->total}");
    }


    /**
     * @return string
     */
    public function getImageUrlUploadedFilenameAttribute()
    {
        return $this->uploaded_filename ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'payment_links'.DIRECTORY_SEPARATOR.$this->uploaded_filename) : null;
    }

    public function getRowResource()
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'user_id' => $this->user_id,
            'payment_link_type_id' => $this->payment_link_type_id,
            'payment_id' => $this->payment_id,
            'payment_type' => $this->payment_type,
            'total' => $this->total,
            'uploaded_filename' => $this->uploaded_filename,
            'instance_type' => $this->instance_type,
            'user_payment_link' => $this->user_payment_link,
            'image_url_uploaded_filename' => $this->image_url_uploaded_filename,
        ];
    }


    public static function getModelByType($instance_type)
    {
        $model = null;

        switch ($instance_type) {
            case 'document':
                $model = DocumentPayment::class;
                break;
        }

        return $model;
    }


}
