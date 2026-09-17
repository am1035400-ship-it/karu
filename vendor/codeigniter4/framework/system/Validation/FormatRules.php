<?php

namespace CodeIgniter\Validation;

use DateTime;

class FormatRules
{
    public function alpha(?string $str = null): bool
    {
        return ctype_alpha($str);
    }

    public function alpha_space(?string $value = null): bool
    {
        if ($value === null) {
            return true;
        }

        return (bool) preg_match('/\A[A-Z ]+\z/i', $value);
    }

    public function alpha_dash(?string $str = null): bool
    {
        return (bool) preg_match('/\A[a-z0-9_-]+\z/i', $str);
    }

    public function alpha_numeric_punct($str)
    {
        return (bool) preg_match('/\A[A-Z0-9 ~!#$%\&\*\-_+=|:.]+\z/i', $str);
    }

    public function alpha_numeric(?string $str = null): bool
    {
        return ctype_alnum($str);
    }

    public function alpha_numeric_space(?string $str = null): bool
    {
        return (bool) preg_match('/\A[A-Z0-9 ]+\z/i', $str);
    }

    public function string($str = null): bool
    {
        return is_string($str);
    }

    public function decimal(?string $str = null): bool
    {
        return (bool) preg_match('/\A[-+]?\d{0,}\.?\d+\z/', $str);
    }

    public function hex(?string $str = null): bool
    {
        return ctype_xdigit($str);
    }

    public function integer(?string $str = null): bool
    {
        return (bool) preg_match('/\A[\-+]?\d+\z/', $str);
    }

    public function is_natural(?string $str = null): bool
    {
        return ctype_digit($str);
    }

    public function is_natural_no_zero(?string $str = null): bool
    {
        return $str !== '0' && ctype_digit($str);
    }

    public function numeric(?string $str = null): bool
    {
        return (bool) preg_match('/\A[\-+]?\d*\.?\d+\z/', $str);
    }

    public function regex_match(?string $str, string $pattern): bool
    {
        if (strpos($pattern, '/') !== 0) {
            $pattern = "/{$pattern}/";
        }

        return (bool) preg_match($pattern, $str);
    }

    public function timezone(?string $str = null): bool
    {
        return in_array($str, timezone_identifiers_list(), true);
    }

    public function valid_base64(?string $str = null): bool
    {
        return base64_encode(base64_decode($str, true)) === $str;
    }

    public function valid_json(?string $str = null): bool
    {
        json_decode($str);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function valid_email(?string $str = null): bool
    {
        if (function_exists('idn_to_ascii') && defined('INTL_IDNA_VARIANT_UTS46') && preg_match('#\A([^@]+)@(.+)\z#', $str, $matches)) {
            $str = $matches[1] . '@' . idn_to_ascii($matches[2], 0, INTL_IDNA_VARIANT_UTS46);
        }

        return (bool) filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    public function valid_emails(?string $str = null): bool
    {
        foreach (explode(',', $str) as $email) {
            $email = trim($email);
            if ($email === '') {
                return false;
            }

            if ($this->valid_email($email) === false) {
                return false;
            }
        }

        return true;
    }

    public function valid_ip(?string $ip = null, ?string $which = null): bool
    {
        if (empty($ip)) {
            return false;
        }

        $flag = null;
        $whichLower = strtolower($which ?? '');

        switch ($whichLower) {
            case 'ipv4':
                $flag = FILTER_FLAG_IPV4;
                break;
            case 'ipv6':
                $flag = FILTER_FLAG_IPV6;
                break;
        }

        if ($flag !== null) {
            return (bool) filter_var($ip, FILTER_VALIDATE_IP, ['flags' => $flag])
                || (!ctype_print($ip) && (bool) filter_var(inet_ntop($ip), FILTER_VALIDATE_IP, ['flags' => $flag]));
        }

        return (bool) filter_var($ip, FILTER_VALIDATE_IP)
            || (!ctype_print($ip) && (bool) filter_var(inet_ntop($ip), FILTER_VALIDATE_IP));
    }

    public function valid_url(?string $str = null): bool
    {
        if (empty($str)) {
            return false;
        }

        if (preg_match('/^(?:([^:]*)\:)?\/\/(.+)$/', $str, $matches)) {
            if (!in_array($matches[1], ['http', 'https'], true)) {
                return false;
            }

            $str = $matches[2];
        }

        $str = 'http://' . $str;

        return filter_var($str, FILTER_VALIDATE_URL) !== false;
    }

    public function valid_url_strict(?string $str = null, ?string $validSchemes = null): bool
    {
        if (empty($str)) {
            return false;
        }

        $scheme = strtolower(parse_url($str, PHP_URL_SCHEME));
        $validSchemes = explode(',', strtolower($validSchemes ?? 'http,https'));

        return in_array($scheme, $validSchemes, true)
            && filter_var($str, FILTER_VALIDATE_URL) !== false;
    }

    public function valid_date(?string $str = null, ?string $format = null): bool
    {
        if (empty($format)) {
            return (bool) strtotime($str);
        }

        $date = DateTime::createFromFormat($format, $str);

        return (bool) $date
            && DateTime::getLastErrors()['warning_count'] === 0
            && DateTime::getLastErrors()['error_count'] === 0;
    }
}