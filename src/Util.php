<?php

namespace Common;
        return preg_replace('/<br\s*\/?>(?i)/', PHP_EOL, $text);
/**
 * @package Util Class in PHP8
 * @author Jovanni Lo
 * @link https://github.com/lodev09/php-util
 * @copyright 2017 Jovanni Lo, all rights reserved
 * @license
 * The MIT License (MIT)
 * Copyright (c) 2017 Jovanni Lo
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

class Util {

    private static $_http_codes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        429 => 'Too Many Requests',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    ];

    private static $_mime_extensions = [
        'text/plain' => 'txt',
        'text/html' => 'html',
        'text/css' => 'css',
        'application/javascript' => 'js',
        'application/json' => 'json',
        'application/xml' => 'xml',
        'application/x-shockwave-flash' => 'swf',
        'video/x-flv' => 'flv',

        // images
        'image/png' => 'png',
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/vnd.microsoft.icon' => 'ico',
        'image/tiff' => 'tiff',
        'image/svg+xml' => 'svg',

        // archives
        'application/zip' => 'zip',
        'application/x-rar-compressed' => 'rar',
        'application/x-msdownload' => 'exe',
        'application/vnd.ms-cab-compressed' => 'cab',

        // audio/video
        'audio/mpeg' => 'mp3',
        'video/quicktime' => 'mov',
        'video/x-ms-wmv' => 'wmv',
        'video/mp4' => 'mp4',
        'video/mpeg' => 'mpeg',

        // adobe
        'application/pdf' => 'pdf',
        'image/vnd.adobe.photoshop' => 'psd',
        'application/postscript' => 'eps',

        // ms office
        'application/msword' => 'doc',
        'application/rtf' => 'rtf',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-powerpoint' => 'ppt',

        // open office
        'application/vnd.oasis.opendocument.text' => 'odt',
        'application/vnd.oasis.opendocument.spreadsheet' => 'ods',
    ];

    /**
     * get HTTP code name
     * @param $code HTTP code
     *
     * @return string
     * code name
    */
    public static function getHttpStatus($code) {
        return self::get($code, self::$_http_codes, 'Unknown HTTP status code');
    }

    /**
     * get file extension by mime type
     * @param  string $mime_type Supported mime type
     * @return string            The extension
     */
    public static function getExtension($mime_type) {
        return self::get($mime_type, self::$_mime_extensions);
    }

    public static function get($field, $source = null, $default = null, $possible_values = []) {
        $source = is_null($source) ? $_GET : $source;
        if (is_array($source)) {
            $value = isset($source[$field]) ? $source[$field] : $default;
        } else if (is_object($source)) {
            $value = isset($source->{$field}) ? $source->{$field} : $default;
        } else {
            $value = $default;
        }

        if ($possible_values) {
            $possible_values = is_array($possible_values) ? $possible_values : [$possible_values];
            return in_array($value, $possible_values) ? $value : $default;
        }

        return $value;
    }

    /**
     * get cli options/switches. If run via http, gets data from $_GET instead
     * @param  array $config   options configuration (extended). see php's getopt
     * @param  string &$message exception messages
     * @return array | boolean  returns false if not valid, returns array otherwise
     * @example
     * getOptions([
     *     'myoption,m:' => 'Help text here',
     *     'foo,f::',
     *     'bar::'
     * ], $message)
     *
     * Usage: php script.php --myoption=somevalue --foo=fooo --bar=baaar
     */
    public static function getOptions($config, &$message = null) {
        if (!$config) return [];
        if (is_string($config)) $config = [$config];

        $config[] = 'help::';

        $short_opts = '';
        $long_opts = [];
        $required = [];
        $descriptions = [];

        $is_cli = self::isCli();

        $options_map = [];
        foreach ($config as $key => $value) {
            $description = null;
            if (is_int($key)) {
                $option_raw = $value;
            } else {
                $option_raw = $key;
                $description = $value;
            }

            if (!$option_raw) continue;

            $option_types = self::explodeClean($option_raw, ',');

            // base index to test if required, optional or no value
            $required_base_index = count($option_types) == 1 ? 0 : 1;

            $option = $option_types[$required_base_index];
            preg_match('/\:+/i', $option, $matches);

            $append = $matches ? $matches[0] : '';
            $is_required = strlen($append) == 1;

            $option_type_keys = [];
            foreach ($option_types as $index => $option_type) {
                $option_type = str_replace(':', '', $option_type);
                if (strlen($option_type) == 1) {
                    $short_opts .= $option_type.$append;
                } else {
                    $long_opts[] = $option_type.$append;
                }

                $option_type_keys[] = $option_type;
            }

            $option_key = $option_types[0];

            if ($is_required) {
                $required[] = $option_key;
            }

            $options_map[$option_key] = $option_type_keys;
            $descriptions[$option_key] = $description;
        }

        if ($is_cli) {
            $result = getopt($short_opts, $long_opts);
        } else {
            $result = $_GET;
        }

        // generate the final values
        $values = [];
        foreach ($options_map as $key => $option_type_keys) {
            $value = null;
            // check for each keys if value is provided -- use the first one
            foreach ($option_type_keys as $option_key) {
                if (isset($result[$option_key])) {
                    $value = $result[$option_key];
                    break;
                }
            }

           if (!is_null($value)) $values[$key] = $value;
        }

        if (isset($result['help'])) {
            $fields = array_map(function($option_key) use ($is_cli, $required, $descriptions) {
                if ($option_key == 'help::') return '';

                $description = $descriptions[$option_key] ?? null;

                $required_text = in_array($option_key, $required) ? '' : ' (optional)';
                if ($is_cli) {
                    $help_text = "\033[31m--$option_key\033[0m$required_text";
                } else {
                    $help_text = '<span style="color: #d9534f">'.$option_key.'</span> '.$required_text;
                }

                if ($description) $help_text .= "\t\t".$description;
                return $help_text;

            }, array_keys($options_map));;

            $message = 'Usage: php '.$_SERVER['SCRIPT_NAME'].' [options...]'.PHP_EOL;
            $message .= 'Options:'.PHP_EOL;
            $message .= "  ".trim(implode(PHP_EOL."  ", $fields));

            if (!$is_cli) $message = '<pre>'.$message.'</pre>';
            echo $message.PHP_EOL;

            // stop the script
            exit;
        }
      
        $validate = self::verifyFields($required, $values, $missing);
        if (!$validate) {
            $missing_fields = array_map(function($option_key) use ($is_cli) {
                return $is_cli ? "\033[31m$option_key\033[0m" : '<span class="text-danger">'.$option_key.'</span>';
            }, $missing);

            if ($missing_fields) {
                $plural = count($missing_fields) > 1;
                $message = self::implodeAnd($missing_fields).' field'.($plural ? 's' : '').' '.($plural ? 'are' : 'is').' required';
                if (!$is_cli) $message = '<pre>'.$message.'</pre>';
            }

            return false;
        } else return $values;
    }

    public static function isPjax() {
        return isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == true;
    }

    public static function inString($needle, $string) {
        if (is_array($needle)) {
            return preg_match('/\b'.implode('\b|\b', $needle).'\b/i', $string) == 1;
        } else return stripos($string, $needle) !== false;
    }

    public static function explodeIds($src, $separator = ';') {
        $text = is_array($src) ? implode(';', $src) : $src;
        $raw = preg_replace('/\s+/i', $separator, $text);
        return array_values(array_filter(explode($separator, $raw), function($id) {
            return is_numeric($id) && strlen($id);
        }));
    }

    public static function explodeClean($src, $separator = ';') {
        $text = is_array($src) ? implode($separator, $src) : $src;
        $raw = preg_replace('/\s+/i', $separator, $text);
        return array_values(array_filter(explode($separator, $raw), 'strlen'));
    }

    public static function implodeAnd($arr) {
        if (!is_array($arr)) return $arr;
        $first_key = key(array_slice($arr, 0, 1, TRUE));
        $last_key = key(array_slice($arr, -1, 1, TRUE));
        $result = '';
        foreach ($arr as $key => $item) {
            if ($first_key == $key) $separator = '';
            else $separator = $last_key == $key ? ' and' : ',';
            $result .= $separator.' '.$item;
        }

        return ltrim($result);
    }

    /**
     * encode and print the result to json (used for ajax routines)
     * @param  string $status  status
     * @param  string $message message
     * @param  mixed $data    data
     * @param bool $return should return json
     * @return string          json encoded string
     */
    public static function printStatus($status = 200, $data = [], $options = JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK, $return = false) {
        if (is_numeric($status)) $status_code = $status;
        else if (is_bool($status)) $status_code = $status ? 200 : 400;
        else $status_code = strtolower($status) === 'ok' ? 200 : 400;

        $status_name = self::$_http_codes[$status_code];
        self::setStatus($status_code);

        if (!is_array($data) && !$data) {
            $data = ['message' => $status_name];
        } else if (is_string($data)) {
            $data = ['message' => $data];
        }

        if ($status_code >= 400 || $status_code < 200) $data['error'] = $status_name;

        $json = json_encode($data, $options);
        if ($return) return $json;
        else echo $json;
    }

    /**
     * Check params of an array/object provided by the given required keys
     * @param  mixed $required array or object that are required
     * @param  mixed $fields  array or object that contains the currrent provided params
     * @return boolean         true if validated, otherwise false
     */
    public static function verifyFields($required, $fields = null, &$missing = []) {
        if (!$fields || is_string($fields)) {
            $missing = $required;
            return false;
        }

        foreach ($required as $field) {
            $value = is_array($fields) ? $fields[$field] ?? null : $fields->{$field} ?? null;
            if (!$value) $missing[] = $field;
        }

        return $missing ? false : true;
    }

    public static function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * check if the running script is in CLI mode
     * @return boolean [description]
     */
    public static function isCli() {
        return php_sapi_name() == 'cli' || !isset($_SERVER["REQUEST_METHOD"]);
    }

    /**
     * Convert a string to friendly SEO string
     * @param  string $text input
     * @return string       output
     */
    public static function slugify($text, $lowercase = true, $skip_chars = '', $replace = '-') {

        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d'.$skip_chars.']+~u', $replace, $text);
        // trim
        $text = trim($text, $replace);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // lowercase
        if ($lowercase) $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w'.$skip_chars.']+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
    /**
     * Set values from default properties of an array
     * @param array $defaults  The defualt array structure
     * @param array $values        The input array
     * @param string $default_key Default key if input is a string or something
     * @return array                    Returns the right array
     */
    public static function setValues($defaults, $values, $default_key = "") {
        if ($default_key != "") {
            if (!is_array($values)) {
                if (isset($defaults[$default_key])) $defaults[$default_key] = $values;
                return $defaults;
            }
        }

        if ($values) {
            foreach ($values as $key => $value) {
                if (array_key_exists($key, $defaults)) $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    /**
     * Read CSV from URL or File
     * @param  string $filename  Filename
     * @param  string $headers Delimiter
     * @return array            [description]
     */
    public static function readCsv($filename, $with_header = true, $headers = null, $delimiter = ',') {
        $data = [];
        $index = 0;
       // Ensure $headers is an array
        if ($headers === null) {
            $headers = [];
        }
        $header_count = count($headers);     
    
        $handle = @fopen($filename, "r") or false;
        if ($handle !== FALSE) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                if ($index == 0 && $with_header) {
                    if (!$headers) $headers = $row;
                    $header_count = is_countable($headers) ? count($headers) : 0;
                } else {
                    if ($headers) {
                        $column_count = count($row);
                        if ($header_count == $column_count) {
                            $data[] = array_combine($headers, $row);
                        } else {
                            // Puedes manejar esto de la forma que desees, por ejemplo, omitiendo la fila
                            // o agregando un registro de error.
                            // Omitir la fila:
                            // continue;
    
                            // Agregar un registro de error:
                            $error_message = 'readCsv: row ' . $index . ' column mismatch. headers: ' . $header_count . ', columns: ' . $column_count;
                            $data[] = ['error' => $error_message];
                        }
                    } else {
                        $data[] = $row;
                    }
                }
    
                $index++;
            }
    
            fclose($handle);
        }
    
        return $data;
    }    
    
    

    /**
     * Parse email address string
     * @param  string $str       string input
     * @param  string $separator separator, default ","
     * @return array             array
     */
    public static function parseEmail($str, $separator = ",") {

        $str = trim(preg_replace('/\s+/', ' ', $str));
        $all = [];
        $emails = preg_split('/(".*?"\s*<.+?>)\s*' . $separator . '*|' . $separator . '+/', $str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        foreach ($emails as $email) {
            $name = "";
            $email = trim($email);
            $email_info = new \stdClass;
            if (preg_match('/(.*?)<(.*)>/', $email, $regs)) {
                $email_info->name = trim(trim($regs[1]) , '"');
                $email_info->email = trim($regs[2]);
            } else {
                $email_info->name = $email;
                $email_info->email = $email;
            }

            if (strpos($email_info->email, $separator) !== false) {
                $addtl_emails = self::parseEmail($email_info->email, $separator);
                foreach ($addtl_emails as $addtl_email_info) {
                    if ($addtl_email_info->name == "" || $addtl_email_info->name == $addtl_email_info->email) $addtl_email_info->name = $email_info->name;

                    $all[] = $addtl_email_info;
                }
            } else {
                if (filter_var($email_info->email, FILTER_VALIDATE_EMAIL)) $all[] = $email_info;
            }
        }
        return $all;
    }

    /**
     * Store client session info to an object
     * @return stdClass returns the object containing details of the session
     */
    public static function getSessionInfo() {
        $browser_info = self::getBrowserInfo();
        $result = new \stdClass;
        $result->ip = self::getClientIp();
        $result->browser_info = (object)$browser_info;

        return $result;
    }


    public static function getBrowserInfo() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
        $browser_info = new \stdClass;
        $browser_info->user_agent = $user_agent;
    
        // Detectar el navegador utilizando expresiones regulares o alguna otra técnica
        if (preg_match('/MSIE/i', $user_agent)) {
            $browser_info->browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $user_agent)) {
            $browser_info->browser = 'Mozilla Firefox';
        } elseif (preg_match('/Chrome/i', $user_agent)) {
            $browser_info->browser = 'Google Chrome';
        } elseif (preg_match('/Safari/i', $user_agent)) {
            $browser_info->browser = 'Safari';
        } elseif (preg_match('/Opera/i', $user_agent)) {
            $browser_info->browser = 'Opera';
        } else {
            $browser_info->browser = 'Unknown';
        }
    
        // Puedes agregar más información del navegador según tus necesidades
    
        return $browser_info;
    }

    public static function truncate($string, $limit, $break = " ", $pad = "&hellip;") {
        // return with no change if string is shorter than $limit
        if (strlen($string) <= $limit) return $string;
        // is $break present between $limit and the end of the string?
        if (false !== ($breakpoint = strpos($string, $break, $limit))) {
            if ($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }

        return $string;
    }

    // calculate position distance (haversine formula) in miles
    public static function distance($origin, $dest, $radius = 3959) {

        if (!(isset($origin[0]) && isset($origin[1]))) return false;
        if (!(isset($dest[0]) && isset($dest[1]))) return false;

        $lat_orig = $origin[0];
        $lng_orig = $origin[1];

        $lat_dest = $dest[0];
        $lng_dest = $dest[1];

        $d_lat = deg2rad($lat_dest - $lat_orig);
        $d_lng = deg2rad($lng_dest - $lng_orig);

        $a = sin($d_lat/2) * sin($d_lat/2) + cos(deg2rad($lat_orig)) * cos(deg2rad($lat_dest)) * sin($d_lng/2) * sin($d_lng/2);
        $c = 2 * asin(sqrt($a));
        $d = $radius * $c;

        return $d;
    }

    /**
     * Get the client's IP Address
     * @return string IP address string
     */
    public static function getClientIp() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR')) $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED')) $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR')) $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED')) $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR')) $ipaddress = getenv('REMOTE_ADDR');
        else $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /**
     * Returns an base64 encoded encrypted string
     */
    public static function encrypt($data, $key, $iv) {
        $encrypt_method = 'AES-256-CBC';
        $iv = substr($iv, 0, 16);
        return openssl_encrypt($data, $encrypt_method, $key, 0, $iv);
    }

    /**
     * Returns decrypted original string
     */
    public static function decrypt($data, $key, $iv) {
        $encrypt_method = 'AES-256-CBC';
        $iv = substr($iv, 0, 16);
        return openssl_decrypt($data, $encrypt_method, $key, 0, $iv);
    }

    public static function setStatus($status) {
        http_response_code($status);
    }

    public static function getHeader($header, $headers = null) {
        if (!$headers) {
            if (function_exists('getallheaders')) {
                $headers = getallheaders();
            } else {
                $headers = [];
                foreach ($_SERVER as $key => $value) {
                    if (strpos($key, 'HTTP_') === 0) {
                        $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                        $headers[$name] = $value;
                    } elseif ($key === 'CONTENT_TYPE') {
                        $headers['Content-Type'] = $value;
                    } elseif ($key === 'CONTENT_LENGTH') {
                        $headers['Content-Length'] = $value;
                    }
                }
                if (!$headers) $headers = headers_list();
            }
        }
        foreach ($headers as $key => $value) {

            // if $headers is a non-associative array e.g. headers_list()
            if (is_int($key)) {
                $parts = explode(':', $value, 2);
                $key = $parts[0];
                $value = isset($parts[1]) ? trim($parts[1]) : '';
            }

            if (strtolower($key) === strtolower($header)) return $value;
        }

        return false;
    }

    public static function setContentType($type = 'application/json') {
        header('Content-Type: ' . $type);
    }

    public static function debug($var, $options = null, $return = false) {
        $is_cli = self::isCli();
        $is_ajax = self::isAjax();
        $is_pjax = self::isPjax();

        // if current header is json, return plain text
        $content_type = self::getHeader('Content-Type', headers_list());

        $is_html = !($is_cli || $is_ajax || $content_type === 'application/json') || $is_pjax;
        $new_line = self::get('newline', $options, true);

        $info = print_r($var, true);

        $info = preg_replace('/\s+\(/', ' (', $info);
        $info = preg_replace('/ {4}([)])/', '$1', $info);

        $result = $is_html ? '<pre>'.$info.'</pre>' : $info.($new_line ? PHP_EOL : '');

        if ($return) return $result;
        else echo $result;
    }

    public static function uuid() {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function randomInt($min, $max) {
        if (function_exists('random_int') === true)
            return random_int($min, $max);

        $range = $max - $min;
        if ($range < 1) return $min; // not so random...

        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);

        return $min + $rnd;
    }

    public static function token($length = 16) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::randomInt(0, $max-1)];
        }

        return $token;
    }

    public static function urlBase64Decode($str) {
        return base64_decode(strtr($str, [
            '-' => '+',
            '_' => '=',
            '~' => '/'
        ]));
    }

    public static function urlBase64Encode($str) {
        return strtr(base64_encode($str) , [
            '+' => '-',
            '=' => '_',
            '/' => '~'
        ]);
    }

    /**
     * redirect()
     *
     * @param mixed $location
     * @return
     */
    public static function redirect($location = null) {
        if (!is_null($location)) {
            header("Location: {$location}");
            exit;
        }
    }

    public static function formatAddress($data, $line_suffix = 'street') {
        if (is_string($data)) return $data;

        $components = [];
        $components[] = self::get($line_suffix.'_1', $data) ?: self::get($line_suffix, $data);
        $components[] = self::get($line_suffix.'_2', $data);
        $components[] = self::get('city', $data);
        $components[] = self::get('county', $data);
        $components[] = self::get('state', $data).' '.self::get('zip', $data);

        return implode(', ', array_filter(array_map(function($component) {
            return trim(self::br2nl($component));
        }, $components)));
    }

    /**
     * timeInWords()
     *
     * @param mixed $timestamp
     * @return
     */
    public static function timeInWords($date, $with_time = true) {
        if (!$date) return 'N/A';
        $timestamp = strtotime($date);
        $distance = (round(abs(time() - $timestamp) / 60));

        if ($distance <= 1) {
            $return = ($distance == 0) ? 'a few seconds ago' : '1 minute ago';
        } elseif ($distance < 60) {
            $return = $distance . ' minutes ago';
        } elseif ($distance < 119) {
            $return = 'an hour ago';
        } elseif ($distance < 1440) {
            $return = round(floatval($distance) / 60.0) . ' hours ago';
        } elseif ($distance < 2880) {
            $return = 'Yesterday' . ($with_time ? ' at ' . date('g:i A', $timestamp) : '');
        } elseif ($distance < 14568) {
            $return = date('l, F d, Y', $timestamp) . ($with_time ? ' at ' . date('g:i A', $timestamp) : '');
        } else {
            $return = date('F d ', $timestamp) . ((date('Y') != date('Y', $timestamp) ? ' ' . date('Y', $timestamp) : '')) . ($with_time ? ' at ' . date('g:i A', $timestamp) : '');
        }

        return $return;
    }

    /**
     * escapeHtml()
     *
     * @param mixed $str_value
     * @return
     */
    public static function escapeHtml($src, $nl2br = false) {
        if (is_array($src)) {
            return array_map([__CLASS__, 'escapeHtml'], $src);
        } else if (is_object($src)) {
            return (object)array_map([__CLASS__, 'escapeHtml'] , self::to_array($src));
        } else {
            if (is_null($src)) $src = "";
            $new_str = is_string($src) ? htmlentities(html_entity_decode($src, ENT_QUOTES)) : $src;
            return $nl2br ? nl2br($new_str) : $new_str;
        }
    }

    public static function descapeHtml($src) {
        if (is_array($src)) {
            return array_map([__CLASS__, 'descapeHtml'], $src);
        } else if (is_object($src)) {
            return (object)array_map([__CLASS__, 'descapeHtml'], self::to_array($src));
        } else {
            if (is_null($src)) $src = "";
            $new_str = is_string($src) ? html_entity_decode($src, ENT_QUOTES) : $src;
            return $new_str;
        }
    }

    public static function to_array($obj) {
        if (is_object($obj)) {
            return get_object_vars($obj);
        } else {
            return $obj;
        }
    }

    public static function br2nl($text) {
        return preg_replace('/<br\s*\/?>/i', PHP_EOL, $text);
    }

    /**
     * Convert an object to an array
     * @param object  $object The object to convert
     * @reeturn array
     */
    public static function toArray($object) {
        if (is_array($object)) return $object;
        if (!is_object($object) && !is_array($object)) return $object;
        if (is_object($object)) $object = get_object_vars($object);

        return array_map([
            __CLASS__,
            'toArray'
        ], $object);
    }

    /**
     * Convert an array to an object
     * @param array  $array The array to convert
     * @reeturn object
     */
    public static function toObject($array, $recursive = false) {
        if (!is_object($array) && !is_array($array)) return $array;

        if (!$recursive) return (object)$array;

        if (is_array($array)) return (object)array_map([
            __CLASS__,
            'toObject'
        ], $array);
        else return $array;
    }

    public static function unzip($zip_file, $extract_path = null) {
        $zip = new \ZipArchive;
        if ($zip->open($zip_file)) {
            if (!$extract_path) {
                $path_info = pathinfo($zip_file);
                $extract_path = $path_info['dirname'].DIRECTORY_SEPARATOR;
            }

            // Basic zip slip prevention
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                if (!$stat || !isset($stat['name'])) { continue; }
                $name = $stat['name'];
                if (strpos($name, '..') !== false) { $zip->close(); return false; }
                if ($name !== '' && ($name[0] === '/' || $name[0] === '\\')) { $zip->close(); return false; }
                if (preg_match('/^[A-Za-z]:\\\\/', $name) === 1) { $zip->close(); return false; }
            }

            $zip->extractTo($extract_path);
            $zip->close();
            return true;

        } return false;
    }

    public static function formatPhone($input, $country_code = 1, $format = '+%1$s (%2$s) %3$s-%4$s') {
        $clean_input = substr(preg_replace('/\D+/i', '', $input), -10);
        if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $clean_input, $matches)) {
            $result = sprintf($format, $country_code, $matches[1], $matches[2], $matches[3]);
            return $result;
        }

        return $input;
    }

    public static function formatEin($input) {
        $clean_input = substr(preg_replace('/\D+/i', '', $input), -9);
        if (preg_match('/^(\d{2})(\d{7})$/', $clean_input, $matches)) {
            $result = $matches[1].'-'.$matches[2];
            return $result;
        }

        return $input;
    }

    public static function formatSsn($input) {
        $clean_input = substr(preg_replace('/\D+/i', '', $input), -9);
        if (preg_match('/^(\d{3})(\d{2})(\d{4})$/', $clean_input, $matches)) {
            $result = $matches[1].'-'.$matches[2].'-'.$matches[3];
            return $result;
        }

        return $input;
    }

    /**
     * Obtain a brand constant from a PAN
     * https://stackoverflow.com/a/21617574/3685987
     *
    * @param string $pan               Credit card number
    * @param bool $include_sub_types   Include detection of sub visa brands
     * @return string
     */
    public static function getCardType($pan, $include_sub_types = false) {
        $pan = preg_replace('/\D/i', '', $pan);
        //maximum length is not fixed now, there are growing number of CCs has more numbers in length, limiting can give false negatives atm

        //these regexps accept not whole cc numbers too
        //visa
        $visa_regex = '/^4[0-9]{0,}$/';
        $vpreca_regex = '/^428485[0-9]{0,}$/';
        $postepay_regex = '/^(402360|402361|403035|417631|529948){0,}$/';
        $cartasi_regex = '/^(432917|432930|453998)[0-9]{0,}$/';
        $entropay_regex = '/^(406742|410162|431380|459061|533844|522093)[0-9]{0,}$/';
        $o2money_regex = '/^(422793|475743)[0-9]{0,}$/';

        // MasterCard
        $mastercard_regex = '/^(5[1-5]|222[1-9]|22[3-9]|2[3-6]|27[01]|2720)[0-9]{0,}$/';
        $maestro_regex = '/^(5[06789]|6)[0-9]{0,}$/';
        $kukuruza_regex = '/^525477[0-9]{0,}$/';
        $yunacard_regex = '/^541275[0-9]{0,}$/';

        // American Express
        $amex_regex = '/^3[47][0-9]{0,}$/';

        // Diners Club
        $diners_regex = '/^3(?:0[0-59]{1}|[689])[0-9]{0,}$/';

        //Discover
        $discover_regex = '/^(6011|65|64[4-9]|62212[6-9]|6221[3-9]|622[2-8]|6229[01]|62292[0-5])[0-9]{0,}$/';

        //JCB
        $jcb_regex = '/^(?:2131|1800|35)[0-9]{0,}$/';

        //ordering matter in detection, otherwise can give false results in rare cases
        if (preg_match($jcb_regex, $pan)) {
            return 'jcb';
        }

        if (preg_match($amex_regex, $pan)) {
            return 'amex';
        }

        if (preg_match($diners_regex, $pan)) {
            return 'diners_club';
        }

        //sub visa/mastercard cards
        if ($include_sub_types) {
            if (preg_match($vpreca_regex, $pan)) {
                return 'v-preca';
            }
            if (preg_match($postepay_regex, $pan)) {
                return 'postepay';
            }
            if (preg_match($cartasi_regex, $pan)) {
                return 'cartasi';
            }
            if (preg_match($entropay_regex, $pan)) {
                return 'entropay';
            }
            if (preg_match($o2money_regex, $pan)) {
                return 'o2money';
            }
            if (preg_match($kukuruza_regex, $pan)) {
                return 'kukuruza';
            }
            if (preg_match($yunacard_regex, $pan)) {
                return 'yunacard';
            }
        }

        if (preg_match($visa_regex, $pan)) {
            return 'visa';
        }

        if (preg_match($mastercard_regex, $pan)) {
            return 'mastercard';
        }

        if (preg_match($discover_regex, $pan)) {
            return 'discover';
        }

        if (preg_match($maestro_regex, $pan)) {
            if ($pan[0] == '5') {//started 5 must be mastercard
                return 'mastercard';
            }
                return 'maestro'; //maestro is all 60-69 which is not something else, thats why this condition in the end

        }

        return 'unknown'; //unknown for this system
    }

    /**
     * Create a compressed zip file
     * @param  array   $files       files (filename => file_location)
     * @param  string  $destination destination of the zip file
     * @param  boolean $overwrite   overwrite if zip file exists
     * @return [type]               true if success, otherwise false
     */
    public static function zip($files = [], $destination = '', $overwrite = false) {
        // if the zip file already exists and overwrite is false, return false
        if (file_exists($destination) && !$overwrite) {
            return false;
        }

        $valid_files = [];
        $files = is_array($files) ? $files : [$files];
        // if files were passed in...
        if ($files) {
            // cycle through each file
            foreach ($files as $filename => $file) {
                // make sure the file exists
                if (file_exists($file)) {
                    if (is_int($filename)) $filename = basename($file);
                    $valid_files[$filename] = $file;
                }
            }
        }

        // if we have good files...
        if (count($valid_files)) {
            // create the archive
            $zip = new \ZipArchive();
            if ($zip->open($destination, \ZipArchive::OVERWRITE | \ZipArchive::CREATE) !== true) {
                return false;
            }
            // add the files
            foreach ($valid_files as $filename => $file) {
                $zip->addFile($file, $filename);
            }

            // close the zip -- done!
            $zip->close();

            // check to make sure the file exists
            return file_exists($destination);
        } else {
            return false;
        }
    }

    // https://stackoverflow.com/questions/16482303/convert-well-known-text-wkt-from-mysql-to-google-maps-polygons-with-php
    public static function parseGeom($ps) {
        $arr = array();

        //match '(' and ')' plus contents between them which contain anything other than '(' or ')'
        preg_match_all('/\([^\(\)]+\)/', $ps, $matches);

        if ($matches = $matches[0]) {
            foreach ($matches as $match) {
                preg_match_all('/-?\d+\.?\d*/', $match, $tmp_matches);
                if ($tmp_matches = $tmp_matches[0]) {
                    //convert all the coordinate sets in tmp from strings to Numbers and convert to LatLng objects
                    $position = array();
                    for ($i = 0; $i < count($tmp_matches); $i += 2) {
                        $lng = (float)$tmp_matches[$i];
                        $lat = (float)$tmp_matches[$i + 1];
                        $position[] = array($lat, $lng);
                    }

                    $arr[] = $position;
                }
            }
        }

        //array of arrays of LatLng objects, or empty array
        return $arr;
    }
}

?>
