<?php

namespace Modules\Person\Observers;

use App\Models\Tenant\Person;

class PersonObserver
{
    public function saving(Person $person)
    {
        $text = [];
        $text[] = $person->name;
        if($person->number) {
            $text[] = $person->number;
        }

        $person->text_filter = join('|', $text);
    }
}
