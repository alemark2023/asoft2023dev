<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Modules\DocumentaryProcedure\Models\DocumentaryProcess
     *
     * @property-read mixed $active
     * @method static Builder|DocumentaryProcess newModelQuery()
     * @method static Builder|DocumentaryProcess newQuery()
     * @method static Builder|DocumentaryProcess query()
     * @mixin \Eloquent
     */
    class DocumentaryProcess extends ModelTenant {
        use UsesTenantConnection;
        protected $table = 'documentary_processes';

        protected $fillable = [
            'description',
            'active',
            'price',
            'name',
        ];

        /**
         * @param string $description
         *
         * @return DocumentaryProcess
         */
        public function setDescription(string $description)
        : DocumentaryProcess {
            $this->description = $description;
            return $this;
        }

        /**
         * @param float $price
         *
         * @return DocumentaryProcess
         */
        public function setPrice(float $price)
        : DocumentaryProcess {
            $this->price = $price;
            return $this;
        }

        /**
         * @param string $name
         *
         * @return DocumentaryProcess
         */
        public function setName(string $name)
        : DocumentaryProcess {
            $this->name = $name;
            return $this;
        }

        /**
         * @param $value
         *
         * @return bool
         */
        public function getActiveAttribute($value) {
            return $value ? true : false;
        }

        /**
         * @return array
         */
        public function getCollectionData() {
            $data = [
                'id'          => $this->id,
                'description' => $this->getDescription(),
                'price'       => $this->getPrice(),
                'name'        => $this->getName(),
                'name_price'  => $this->getName().' - S/ '.$this->priceWithDecimal(),
                'active'      => (bool)$this->active,
            ];
            return $data;
        }

        /**
         * @return string
         */
        public function getDescription()
         {
            return $this->description;
        }

        /**
         * @return float
         */
        public function getPrice()
        {
            return $this->price;
        }

        /**
         * @return string
         */
        public function getName()
         {
            return $this->name;
        }

        public function priceWithDecimal($decimal = 2) {
            return number_format($this->price, $decimal, '.', '');
        }
    }
