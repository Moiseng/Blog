<?php

namespace App\Validators;

use App\Table\CategoryTable;

final class CategoryValidator extends AbstractValidator
{

    public function __construct(array $data, CategoryTable $table, ?int $categoryID = null)
    {
        parent::__construct($data);
        $this->validator->rule("required", ["name", "slug"]);
        $this->validator->rule("lengthBetween", ["name", "slug"], 3, 200);
        $this->validator->rule("slug", "slug");
        $this->validator->rule(function ($field, $value) use ($table, $categoryID) {
            return !$table->exist($field, $value, $categoryID);
        }, "slug", "Cette valeur est déjà utilisée");
    }

}
