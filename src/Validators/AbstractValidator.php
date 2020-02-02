<?php

namespace App\Validators;

use App\Validator;

abstract class AbstractValidator
{
    protected $data;

    protected $validator;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->validator = new Validator($data);
    }

    /**
     * Méthode permettant de vérifier si les données entrées sont correctes aux règles de validation
     *
     */
    public function validate(): bool
    {
        return $this->validator->validate();
    }

    /**
     * Permet de renvoyer les erreurs de validation
     *
     */
    public function errors(): array
    {
        return $this->validator->errors();
    }
}
