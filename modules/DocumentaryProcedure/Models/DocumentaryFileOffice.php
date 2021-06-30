<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Modules\DocumentaryProcedure\Models\DocumentaryFileOffice
     *
     * @method static Builder|DocumentaryFileOffice newModelQuery()
     * @method static Builder|DocumentaryFileOffice newQuery()
     * @method static Builder|DocumentaryFileOffice query()
     * @mixin \Eloquent
     */
    class DocumentaryFileOffice extends ModelTenant {
        protected $table = 'documentary_file_offices';
        use UsesTenantConnection;

        protected $fillable = [
            'documentary_file_id',
            'documentary_office_id',
            'documentary_action_id',
            'observation',
            'status',
        ];


        /**
         * @return int
         */
        public function getDocumentaryFileId()
         {
            return $this->documentary_file_id;
        }

        /**
         * @param int $documentary_file_id
         *
         * @return DocumentaryFileOffice
         */
        public function setDocumentaryFileId(int $documentary_file_id = 0)
        : DocumentaryFileOffice {
            $this->documentary_file_id = (int) $documentary_file_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getDocumentaryOfficeId()
         {
            return $this->documentary_office_id;
        }

        /**
         * @param int $documentary_office_id
         *
         * @return DocumentaryFileOffice
         */
        public function setDocumentaryOfficeId(int $documentary_office_id = 0)
        : DocumentaryFileOffice {
            $this->documentary_office_id =  (int)$documentary_office_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getDocumentaryActionId()
         {
            return $this->documentary_action_id;
        }

        /**
         * @param int $documentary_action_id
         *
         * @return DocumentaryFileOffice
         */
        public function setDocumentaryActionId(int $documentary_action_id = 0)
        : DocumentaryFileOffice {
            $this->documentary_action_id = (int) $documentary_action_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getObservation()
         {
            return $this->observation;
        }

        /**
         * @param string $observation
         *
         * @return DocumentaryFileOffice
         */
        public function setObservation( $observation = '')
        : DocumentaryFileOffice {
            if(empty($observation)) $observation = '';
            $this->observation = $observation;
            return $this;
        }

        /**
         * @return string
         */
        public function getStatus() {
            return $this->status;
        }

        /**
         * @return $this
         */
        public function setPorDerivar() { $this->status = 'POR DERIVAR'; return $this;}

        /**
         * @return $this
         */
        public function setPorRecibir() { $this->status = 'POR RECIBIR'; return $this;}

        /**
         * @return $this
         */
        public function setEnProceso() { $this->status = 'EN PROCESO'; return $this;}

        /**
         * @return $this
         */
        public function setFinalizado() { $this->status = 'FINALIZADO'; return $this;}

        /**
         * @return $this
         */
        public function setArchivado() { $this->status = 'ARCHIVADO'; return $this;}

        /**
         * $status = [
         * 'POR DERIVAR',
         * 'POR RECIBIR',
         * 'EN PROCESO',
         * 'FINALIZADO',
         * 'ARCHIVADO'
         * ]
         *
         * @param string $status
         *
         * @return DocumentaryFileOffice
         */
        public function setStatus($status)
        : DocumentaryFileOffice {
            $this->status = $status;
            return $this;
        }

        public function getCollectionData() {
            $data = $this->toArray();
            $data['documentary_office'] = DocumentaryOffice::find($this->documentary_office_id);
            $data['documentary_file'] = DocumentaryFile::find($this->documentary_file_id);
            $data['documentary_action'] = DocumentaryAction::find($this->documentary_action_id);
            return $data;
        }
    }




