<?php

namespace App\HTML;

class Form
{

    private $data;

    private $errors;

    public function __construct($data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    /**
     * Méthode permettant de générer un input
     *
     * @param string $name
     * @param string $label
     * @param string $type
     * @return string
     */
    public function input(string $name, string $label, string $type = "text"): string
    {
        $value = $this->getValue($name);
        $inputClass = $this->getInputClass($name);
        $invalidFeedBack = $this->getErrorFeedBack($name);
        return <<<HTML
    <div class="form-group">
        <label for="field{$name}">{$label}</label>
        <input type="$type" id="field{$name}" class="{$inputClass}" name="{$name}" value="$value">
        {$invalidFeedBack}
    </div>
HTML;

    }

    /**
     * Permet de générer un textarea
     *
     * @param string $name
     * @param string $label
     * @return string
     */
    public function textarea(string $name, string $label)
    {
        $value = $this->getValue($name);
        $inputClass = $this->getInputClass($name);
        $invalidFeedBack = $this->getErrorFeedBack($name);
        return <<<HTML
    <div class="form-group">
        <label for="field{$name}">{$label}</label>
        <textarea id="field{$name}" class="{$inputClass}" name="{$name}">{$value}</textarea>
        {$invalidFeedBack}
    </div>
HTML;

    }

    /**
     * Permet de générer un champ select
     * @param string $name
     * @param string $label
     * @param array $options
     * @return string
     */
    public function select(string $name, string $label, array $options = []): string
    {
        $optionsHTML = [];
        $value = $this->getValue($name);
        foreach ($options as $k => $v) {
            //$selected = in_array($k, $value) ? " selected" : "";
            $selected = null;
            if (in_array($k, $value)) {
                $selected .= " selected";
            } else {
                $selected .= "";
            }
            $optionsHTML[] = "<option value=\"$k\" $selected>{$v}</option>";
        }
        $inputClass = $this->getInputClass($name);
        $invalidFeedBack = $this->getErrorFeedBack($name);
        $optionsHTML = implode("", $optionsHTML);
        return <<<HTML
    <div class="form-group">
        <label for="field{$name}">{$label}</label>
        <select id="field{$name}" class="{$inputClass}" name="{$name}[]" multiple>{$optionsHTML}</select>
        {$invalidFeedBack}
    </div>
HTML;
    }

    /**
     * Méthode qui permet de récupérer la valeur a afficher dans le formulaire en cas d'édition
     *
     * @param string $name
     * @return string
     */
    private function getValue(string $name)
    {
        if (is_array($this->data)) {
            if (!isset($this->data[$name])) {
                return null;
            }
            return $this->data[$name];
        }
        $method = "get" . str_replace(" ", "", ucwords(str_replace("_", " ", $name)));
        $value = $this->data->$method();
        if ($value instanceof \DateTimeInterface) { // si la valeur est un DateTime
            return $value->format("Y-m-d H:i:s");
        }
        return $value;
    }

    /**
     * Permet de générer une class pour un input
     *
     * @param string $key
     * @return string
     */
    private function getInputClass(string $key): string
    {
        $inputClass = "form-control";
        if (isset($this->errors[$key])) {
            $inputClass .= " is-invalid";
        }
        return $inputClass;
    }

    /**
     * Permet de générer le champ qui affiche l'erreur en dessous de l'input
     *
     * @param string $key
     * @return string
     */
    private function getErrorFeedBack(string $key): string
    {
        $invalidFeedBack = "";
        if (isset($this->errors[$key])) {
            if (is_array($this->errors[$key])) {
                $error = implode("<br>", $this->errors[$key]);
            } else {
                $error = $this->errors[$key];
            }
            $invalidFeedBack = '<div class="invalid-feedback">'. $error .'</div>';
        }
        return $invalidFeedBack;
    }

}