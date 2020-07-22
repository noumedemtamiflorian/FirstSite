<?php


namespace App\Framework\Twig;

use DateTime;
use function DI\string;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'flied',
                [$this, 'flied'],
                ['is_safe' => ['html'], 'needs_context' => true]
            )
        ];
    }

    public function flied(array $context, string $key, $value, ?string $label = null, array $options = [])
    {
        $type = $options['type'] ?? 'input';
        $errors = $this->getErroHtml($context, $key);
        $class = 'form-group';
        $value = $this->convertValue($value);
        $attributes = [
            'class' => trim('form-control ' . ($options['class'] ?? '')),
            'id' => $key,
            'name' => $key
        ];
        if ($errors) {
            $class .= ' has-danger';
            $attributes['class'] .= ' is-invalid form-control-danger';
        }
        if ($type == 'textarea') {
            $input = $this->textarea($value, $attributes);
        } elseif ($type == 'file') {
            $input = $this->file($attributes);
        } elseif (array_key_exists('options', $options)) {
            $input = $this->select($value, $options['options'], $attributes);
        } else {
            $input = $this->input($value, $attributes);
        }
        return "
<div class=\"" . $class . "\">
    <label for=\"$key\">$label</label>
    $input
    $errors
</div>";
    }

    private function getErroHtml($context, $key)
    {
        $errors = $context['errors'][$key] ?? false;
        if ($errors) {
            return "<small class='form-text text-danger'>{$errors}</small>";
        }
        return "";
    }

    private function textarea(?string $value, array $attributes)
    {
        return "<textarea " . $this->getHtmlFromArray($attributes) . " cols=\"30\" rows=\"10\">$value</textarea>";
    }

    private function input(?string $value, array $attributes)
    {
        return "<input type=\"text\" " . $this->getHtmlFromArray($attributes) . " value=\"$value\">";
    }
    private function file(array $attributes)
    {
        return "<input type=\"file\" " . $this->getHtmlFromArray($attributes) . ">";
    }
    private function select($value, array $options, array $attributes)
    {
        $htmlOptions = array_reduce(array_keys($options), function (string $html, string $key) use ($options, $value) {
            $params = ['value' => $key, 'selected' => ($key == $value)];
            return $html . '<option ' . $this->getHtmlFromArray($params) . '>' . $options[$key] . '</option>';
        }, "");
        return "<select  " . $this->getHtmlFromArray($attributes) . ">$htmlOptions</select>";
    }

    private function getHtmlFromArray(array $attributes)
    {
        $htmlParts = [];
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $htmlParts[] = (string)$key;
            } elseif ($value !== false) {
                $htmlParts[] = "$key=\"$value\"";
            }
        }
        return implode(' ', $htmlParts);
    }

    private function convertValue($value)
    {
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return $value;
    }


}
