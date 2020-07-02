<?php


namespace App\Framework;


use App\Framework\Validator\ValidationError;
use DateTime;

class Validator
{
    /**
     * @var array
     */
    private $params;
    /**
     * @var string[]
     */
    private $errors = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     *
     * verifie que les champs sont presents dans les tableau
     *
     * @param string ...$keys
     * @return Validator
     */
    public function required(string  ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value)) {
                $this->addError($key, 'required');
            }
        }
        return $this;
    }

    /**
     *
     * Verifie que le champ n'est pas vide
     *
     * @param string ...$keys
     * @return Validator
     */
    public function notEmpty(string  ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty');
            }
        }
        return $this;
    }

    public function length(string $key, ?int $min, $max = null)
    {
        $value = $this->getValue($key);
        $length = strlen($value);
        if (!is_null($min) && !is_null($max) && ($length < $min || $length > $max)) {
            $this->addError($key, 'betweenLength', [$min, $max]);
            return $this;
        }
        if (!is_null($min) && ($length < $min)) {
            $this->addError($key, 'minLength', [$min]);
            return $this;
        }
        if (!is_null($max) && ($length > $max)) {
            $this->addError($key, 'maxLength', [$max]);
        }
        return $this;
    }

    /**
     *
     * Verifie qu'un element est un slug
     *
     * @param string $key
     * @return $this
     */
    public function slug(string $key)
    {
        $value = $this->getValue($key);
        $pattern = '#^([a-z0-9]+-?)+$#';
        if (!is_null($value) && !preg_match($pattern, $value)) {
            $this->addError($key, 'slug');
        }
        return $this;
    }

    public function dateTime(string $key,string  $format = 'Y-m-d H:i:s')
    {
        $value = $this->getValue($key);
        $dateTime = DateTime::createFromFormat($format, $value);
        $errors = DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $dateTime == false) {
            $this->addError($key, 'datetime',[$format]);
        }
        return $this;
    }

    public function  isValid(){
        return empty($this->errors);
    }
    /**
     *
     * Recupere les erreurs
     *
     * @return ValidationError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     *
     * Ajoute une erreur
     * @param string $key
     * @param string $rule
     * @param array $params
     */
    private function addError(string $key, string $rule, array $params = []): void
    {
        $this->errors[$key] = new  ValidationError($key, $rule, $params);

    }

    private function getValue(string $key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        return null;
    }
}