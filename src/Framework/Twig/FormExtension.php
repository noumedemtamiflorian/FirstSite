<?php


namespace App\Framework\Twig;

use DateTime;
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
            'class' => trim('form-control '.($options['class'] ?? '')),
            'id' => $key,
            'name' => $key
        ];
        if ($errors) {
            $class .= ' has-danger';
            $attributes['class'] .= ' is-invalid form-control-danger';
        }
        if ($type == 'textarea') {
            $input = $this->textarea($value, $attributes);
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

    private function getHtmlFromArray(array $attributes)
    {
        return implode(' ', array_map(function ($key, $value) {
            return "$key=\"$value\"";
        }, array_keys($attributes), $attributes));
    }

    private function convertValue($value)
    {
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return $value;
    }
}
