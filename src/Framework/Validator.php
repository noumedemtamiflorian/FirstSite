<?php


namespace App\Framework;

use App\Framework\Validator\ValidationError;
use DateTime;
use Psr\Http\Message\UploadedFileInterface;

class Validator
{
    private const MINE_TYPES = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'pdf' => 'application/pdf'
    ];
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

    public function email(string $key)
    {
        $email = $this->getValue($key);
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $this->addError($key, "email", [$email]);
        }
        return $this;
    }

    /**
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
        $pattern = '#^[a-z0-9]+(-[a-z0-9]+)*$#';
        if (!is_null($value) && !preg_match($pattern, $value)) {
            $this->addError($key, 'slug');
        }
        return $this;
    }

    public function dateTime(string $key, string $format = 'Y-m-d H:i:s')
    {
        $value = $this->getValue($key);
        $dateTime = DateTime::createFromFormat($format, $value);
        $errors = DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $dateTime == false) {
            $this->addError($key, 'datetime', [$format]);
        }
        return $this;
    }

    public function exists($key, $table, \PDO $pdo)
    {
        $id = $this->getValue($key);
        $statement = $pdo->prepare("SELECT id FROM {$table} WHERE id = ? ");
        $statement->execute([$id]);
        if ($statement->fetchColumn() === false) {
            $this->addError($key, 'exists', [$table]);
        }
        return $this;
    }

    public function unique(string $key, string $table, \PDO $pdo, ?int $exclude = null)
    {
        $value = $this->getValue($key);
        $query = "SELECT id FROM $table WHERE $key = ? ";
        $params = [$value];
        if ($exclude !== null) {
            $query .= " AND id != ? ";
            $params[] = $exclude;
        }
        $statement = $pdo->prepare($query);
        $statement->execute($params);
        if ($statement->fetchColumn() !== false) {
            $this->addError($key, 'unique', [$value]);
        }
        return $this;
    }

    public function uploaded(string $key)
    {
        $file = $this->getValue($key);
        if ($file === null || $file->getError() !== UPLOAD_ERR_OK) {
            $this->addError($key, 'uploaded');
        }
        return $this;
    }

    public function extension(string $key, array $extensions)
    {
        /**
         * @var  UploadedFileInterface $file
         */
        $file = $this->getValue($key);
        if ($file !== null && $file->getError() === UPLOAD_ERR_OK) {
            $type = $file->getClientMediaType();
            $extension = strtolower(pathinfo($file->getClientFilename(), PATHINFO_EXTENSION));
            $expectedType = self::MINE_TYPES[$extension] ?? null;
            if (!in_array($extension, $extensions) || $expectedType !== $type) {
                $this->addError($key, 'filetype', [join(', ', $extensions)]);
            }
        }
        return $this;
    }

    public function isValid()
    {
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
