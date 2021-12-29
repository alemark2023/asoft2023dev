<?php


    namespace Modules\LogisticOperator\Models;


    use App\Models\Tenant\ModelTenant;
    use Hyn\Tenancy\Traits\UsesTenantConnection;

    /**
     * Class YobelConfiguration
     *
     * @property string|null $compania
     * @property string|null $usuario
     * @property string|null $password
     * @property bool $is_active
     * @mixin ModelTenant
     * @package Modules\LogisticOperator\Modelss
     */
    class YobelConfiguration extends ModelTenant
    {
        use UsesTenantConnection;

        public $incrementing = false;
        public $timestamps = false;
        protected $table = 'yobel_configuration';
        protected $perPage = 25;
        protected $hidden = [
            'password'
        ];
        protected $casts = [
            'is_active'=>'bool'
        ];
        protected $fillable = [
            'compania',
            'usuario',
            'password'
        ];

        /**
         * @return string|null
         */
        public function getCompania(): ?string
        {
            return self::cutString($this->compania,0,3);
        }

        /**
         * @param string|null $compania
         *
         * @return YobelConfiguration
         */
        public function setCompania(?string $compania): YobelConfiguration
        {
            $this->compania = self::cutString($compania, 0, 3);
            return $this;
        }

        /**
         * @return string|null
         */
        public function getUsuario(): ?string
        {
            return self::cutString($this->usuario,0,10);
        }

        /**
         * @param string|null $usuario
         *
         * @return YobelConfiguration
         */
        public function setUsuario(?string $usuario): YobelConfiguration
        {
            $this->usuario = self::cutString($usuario,0,10);
            return $this;
        }

        /**
         * @return string|null
         */
        public function getPassword(): ?string
        {
            return self::cutString($this->password,0,10);
        }

        /**
         * @param string|null $password
         *
         * @return YobelConfiguration
         */
        public function setPassword(?string $password): YobelConfiguration
        {
            $this->password = self::cutString($password,0,10);
            return $this;
        }


        /**
         * @return array
         */
        public function setSecurity(): array
        {
            $array = [];
            $array['Seguridad']['compania'] = $this->getCompania();
            $array['Seguridad']['usuario'] = $this->getUsuario();
            $array['Seguridad']['password'] = $this->getPassword();
            return $array;
        }


        public static function cutString($string, $start = 0, $end = 0)
        {
            return substr($string, $start, $end);
        }

        /**
         * @return bool
         */
        public function isIsActive(): bool
        {
            return (bool) $this->is_active;
        }

        /**
         * @param bool $is_active
         *
         * @return YobelConfiguration
         */
        public function setIsActive(bool $is_active): YobelConfiguration
        {
            $this->is_active = (bool) $is_active;
            return $this;
        }

    }
