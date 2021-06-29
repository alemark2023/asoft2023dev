<?php

namespace Modules\DocumentaryProcedure\Models;

use App\Models\Tenant\ModelTenant;

/**
 * Modules\DocumentaryProcedure\Models\DocumentaryDocument
 *
 * @property-read mixed $active
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentaryDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentaryDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentaryDocument query()
 * @mixin \Eloquent
 */
class DocumentaryDocument extends ModelTenant
{
	protected $table = 'documentary_documents';

	protected $fillable = ['description', 'active', 'name'];

	public function getActiveAttribute($value)
	{
		return $value ? true : false;
	}

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
     * @return DocumentaryDocument
     */
    public function setDescription(string $description)
    : DocumentaryDocument {
        $this->description = $description;
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
     * @return DocumentaryDocument
     */
    public function setName(string $name)
    : DocumentaryDocument {
        $this->name = $name;
        return $this;
    }

    public function getCollectionData() {
        $data = $this->toArray();
        return $data
            ;
    }
    }
