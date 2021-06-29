<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
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
        protected $table = 'documentary_processes';

        protected $fillable = [
            'description',
            'active',
            'price',
            'name',
        ];

        /**
         * @return string
         */
        public function getDescription()
        : string {
            return $this->description;
        }

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
         * @return float
         */
        public function getPrice()
        : float {
            return $this->price;
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
         * @return string
         */
        public function getName()
        : string {
            return $this->name;
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

public function priceWithDecimal($decimal = 2){ return number_format($this->price,$decimal,'.','');}
        /**
         * @param false $extended
         *
         * @return array
         */
        public function getCollectionData() {

            $data = $this->toArray();
            $data['active'] = (bool)$data['active'];
            $data['name_price'] = $this->name." - S/ ".$this->priceWithDecimal();
            return $data;
        }
    }
