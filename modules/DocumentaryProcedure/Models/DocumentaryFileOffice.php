<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
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

        protected $fillable = [
            'documentary_file_id',
            'documentary_office_id',
            'documentary_action_id',
            'observation',
            'status',
        ];


        /** @var int */
        protected $documentary_file_id;
        /** @var int */
        protected $documentary_office_id;
        /** @var int */
        protected $documentary_action_id;
        /** @var string */
        protected $observation;

        /**
         * @return int
         */
        public function getDocumentaryFileId()
        : int {
            return $this->documentary_file_id;
        }

        /**
         * @param int $documentary_file_id
         *
         * @return DocumentaryFileOffice
         */
        public function setDocumentaryFileId(int $documentary_file_id)
        : DocumentaryFileOffice {
            $this->documentary_file_id = $documentary_file_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getDocumentaryOfficeId()
        : int {
            return $this->documentary_office_id;
        }

        /**
         * @param int $documentary_office_id
         *
         * @return DocumentaryFileOffice
         */
        public function setDocumentaryOfficeId(int $documentary_office_id)
        : DocumentaryFileOffice {
            $this->documentary_office_id = $documentary_office_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getDocumentaryActionId()
        : int {
            return $this->documentary_action_id;
        }

        /**
         * @param int $documentary_action_id
         *
         * @return DocumentaryFileOffice
         */
        public function setDocumentaryActionId(int $documentary_action_id)
        : DocumentaryFileOffice {
            $this->documentary_action_id = $documentary_action_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getObservation()
        : string {
            return $this->observation;
        }

        /**
         * @param string $observation
         *
         * @return DocumentaryFileOffice
         */
        public function setObservation(string $observation)
        : DocumentaryFileOffice {
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




