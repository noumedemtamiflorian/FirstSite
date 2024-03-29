<?php


namespace App\Framework\Validator;

class ValidationError
{
    private $key;
    private $rule;
    private $messages = [
        'required' => 'Le champs %s est requis',
        'empty' => 'Le champs %s ne peut etre vide',
        'slug' => 'Le champs %s n\'est pas un slug valide',
        'betweenLength' => 'Le champs %s doit etre entre %d et %d caracteres',
        'minLength' => 'Le champs %s doit contenir plus de %d caracteres',
        'maxLength' => 'Le champs %s doit contenir moins de  %d caracteres',
        'datetime' => 'Le champs %s doit etre une date valide ( %s )',
        'exists' => 'Le champs %s n\'exists  pas dans la table( %s )',
        'unique' => 'Le champs %s doit etre unique',
        'filetype' => 'le champs %s n\'est pas au format valide ( %s )',
        'uploaded' => 'vous devez uploader un fichier',
        "email" => "Votre email est invalide",
        "password_confirm" => "Les deux mots de passe ne sont pas identique",
        "pricenull" => "Le prix ne peut etre null"
    ];
    /**
     * @var array
     */
    private $params;

    public function __construct(string $key, string $rule, array $params)
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->params = $params;
    }

    public function __toString()
    {
        $params = array_merge([$this->messages[$this->rule], $this->key], $this->params);
        return (string)call_user_func_array('sprintf', $params);
    }
}
