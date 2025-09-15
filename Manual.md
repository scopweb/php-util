# Manual de uso – `scopweb/php-util`

Pequeña librería de utilidades en PHP (compatible 7.4–8.4) para tareas comunes en CLI y HTTP: manejo de arrays/objetos, texto, cabeceras HTTP, validación de campos, fechas amigables, CSV, zips, formatos de teléfono/SSN/EIN, detección de tarjetas, etc.

Importa el namespace:

```php
use Common\Util;
```

## Índice rápido
- HTTP y JSON: `printStatus`, `setStatus`, `setContentType`, `getHeader`, `getHttpStatus`
- Entrada/validación: `get`, `getOptions`, `verifyFields`
- Texto: `slugify`, `truncate`, `escapeHtml`, `descapeHtml`, `br2nl`, `inString`
- Estructuras: `toArray`, `toObject`, `explodeClean`, `explodeIds`, `implodeAnd`, `setValues`
- Archivos: `zip`, `unzip`, `readCsv`, `getExtension`
- Fechas y tiempo: `timeInWords`
- Red y cliente: `isCli`, `isAjax`, `isPjax`, `getClientIp`, `getBrowserInfo`, `redirect`
- Cripto y aleatorios: `encrypt`, `decrypt`, `uuid`, `randomInt`, `token`, `urlBase64Encode/Decode`
- Utilidades varias: `distance`, `formatPhone`, `formatSsn`, `formatEin`, `getCardType`, `formatAddress`, `parseEmail`, `parseGeom`

---

## HTTP y JSON

- `Util::printStatus(int|string|bool $status = 200, array|string $data = [], int $options = JSON_PRETTY_PRINT|JSON_NUMERIC_CHECK, bool $return = false)`
  - Envía un JSON y establece el código HTTP. Si `$status` es `true` → 200, `false` → 400, `'ok'` → 200.
  - Ejemplo:
    ```php
    Util::setContentType('application/json');
    Util::printStatus(404, ['message' => 'No encontrado']);
    // HTTP 404 y body: {"message":"No encontrado","error":"Not Found"}
    ```

- `Util::setStatus(int $code)` y `Util::getHttpStatus($code)`
  - `setStatus(201)` fija HTTP 201. `getHttpStatus(404)` → "Not Found".

- `Util::setContentType(string $type = 'application/json')`
  - Fija header Content-Type.

- `Util::getHeader(string $name, $headers = null)`
  - Obtiene el valor de una cabecera. Acepta arrays como los de `headers_list()` o construye desde `$_SERVER` si no existe `getallheaders()`.
  - Ejemplo:
    ```php
    $ct = Util::getHeader('Content-Type');
    ```

## Entrada y validación

- `Util::get($field, $source = null, $default = null, $possible_values = [])`
  - Devuelve `$source[$field]` (array u objeto). Si `$source` es `null`, usa `$_GET`.
  - Con `$possible_values`, valida contra una lista.
  - Ejemplo:
    ```php
    $page = Util::get('page', $_GET, 1);
    $mode = Util::get('mode', $_GET, 'view', ['view','edit']);
    ```

- `Util::getOptions(array|string $config, string &$message = null)`
  - Parsea opciones de CLI (o usa `$_GET` si no es CLI). Soporta short/long y requeridos (`:` requerido, `::` opcional).
  - Ejemplo CLI:
    ```php
    // php script.php --name=Alice -v
    $opts = Util::getOptions([
      'name,n:' => 'Nombre del usuario',
      'verbose,v::' => 'Salida detallada'
    ], $msg);
    if ($opts === false) { fwrite(STDERR, $msg."\n"); exit(1); }
    ```

- `Util::verifyFields(array $required, array|object $fields, array &$missing = [])`
  - Verifica requeridos. Devuelve `true/false` y llena `$missing`.

## Texto

- `Util::slugify(string $text, bool $lowercase = true, string $skip_chars = '', string $replace = '-')`
  - Convierte a slug amigable.
  - Ejemplo: `Util::slugify('Hola Mundo!')` → `hola-mundo`.

- `Util::truncate(string $s, int $limit, string $break = ' ', string $pad = '&hellip;')`
  - Corta una cadena en un límite sin partir palabras cuando es posible.

- `Util::escapeHtml($src, bool $nl2br = false)` y `Util::descapeHtml($src)`
  - Escapa/desescapa HTML en arrays/objetos/strings.

- `Util::br2nl(string $html)`
  - Convierte `<br>` en saltos de línea.

- `Util::inString($needle, string $haystack)`
  - Busca una subcadena o cualquiera de una lista. Ej: `Util::inString(['error','fail'], $log)`.

## Estructuras

- `Util::toArray(mixed $obj)` y `Util::toObject(array $arr, bool $recursive = false)`
  - Conversión recursiva útil al normalizar datos.

- `Util::explodeClean($src, string $sep = ';')` / `Util::explodeIds($src, string $sep = ';')`
  - Divide limpiando espacios; `explodeIds` filtra valores numéricos.

- `Util::implodeAnd(array $items)`
  - Une con comas y “and” final. Ej: `a, b and c`.

- `Util::setValues(array $defaults, $values, string $default_key = '')`
  - Sobrescribe defaults solo con claves existentes; útil para configs seguras.

## Archivos

- `Util::zip(array|string $files, string $dest, bool $overwrite = false)`
  - Crea zip desde ruta(s). Claves del array permiten renombrar dentro del zip.

- `Util::unzip(string $zip_file, ?string $extract_path = null)`
  - Extrae zip con prevención básica de “zip slip” y respetando `DIRECTORY_SEPARATOR`.

- `Util::readCsv(string $filename, bool $with_header = true, ?array $headers = null, string $delimiter = ',')`
  - Lee CSV en arrays asociativos si hay encabezado. Si las columnas no coinciden, añade un registro con `error`.

- `Util::getExtension(string $mime)`
  - Devuelve extensión a partir de un MIME conocido.

## Fechas y tiempo

- `Util::timeInWords(string $date, bool $with_time = true)`
  - Formatea tiempos relativos: “a few seconds ago”, “Yesterday at 3:00 PM”, etc.

## Red y cliente

- `Util::isCli()`, `Util::isAjax()`, `Util::isPjax()`
  - Detecta contexto de ejecución.

- `Util::getClientIp()`
  - Obtiene IP del cliente inspeccionando variables de entorno/cabeceras. Nota: validar cabeceras de proxy en producción.

- `Util::getBrowserInfo()`
  - Devuelve user-agent y un nombre de navegador estimado.

- `Util::redirect(string $location)`
  - Envía redirección y finaliza script.

## Cripto y aleatorios

- `Util::encrypt(string $data, string $key, string $iv)` / `Util::decrypt(...)`
  - AES-256-CBC. Nota: no autenticado (considera AES-256-GCM para AEAD si necesitas integridad).

- `Util::uuid()`
  - Genera un UUID v4 usando CSPRNG.

- `Util::randomInt(int $min, int $max)` y `Util::token(int $length = 16)`
  - Entero seguro y token alfanumérico.

- `Util::urlBase64Encode/Decode(string $str)`
  - Base64 amigable para URLs reemplazando `+`, `=`, `/`.

## Utilidades varias

- `Util::distance(array $origin, array $dest, float $radius = 3959)`
  - Distancia en millas (Haversine). Para kilómetros usar `6371`.

- `Util::formatPhone(string $input, int $country_code = 1, string $format = '+%1$s (%2$s) %3$s-%4$s')`
  - Normaliza números en el formato indicado.

- `Util::formatSsn(string $input)` / `Util::formatEin(string $input)`
  - Formatea SSN/EIN.

- `Util::getCardType(string $pan, bool $include_sub_types = false)`
  - Devuelve `visa`, `mastercard`, `amex`, etc. No valida Luhn.

- `Util::formatAddress($data, string $line_suffix = 'street')`
  - Construye una cadena de dirección combinando campos.

- `Util::parseEmail(string $str, string $separator = ',')`
  - Parsea cadenas como `"Nombre" <user@dominio>, otro@dominio` en objetos con `name` y `email`.

- `Util::parseGeom(string $wkt)`
  - Convierte WKT simple a arreglos de coordenadas `[lat, lng]`.

---

## Ejemplos rápidos

```php
use Common\Util;

// 1) JSON de error sencillo
Util::setContentType('application/json');
Util::printStatus(400, 'Parámetros inválidos');

// 2) CLI: opciones con corto/largo
$msg = '';
$opts = Util::getOptions([
  'input,i:' => 'Ruta del archivo de entrada',
  'verbose,v::' => 'Salida detallada (opcional)'
], $msg);
if ($opts === false) { fwrite(STDERR, $msg."\n"); exit(1); }

// 3) Slug y truncado
$slug = Util::slugify('Título de Ejemplo!');           // titulo-de-ejemplo
$short = Util::truncate('un texto muy largo...', 12);  // un texto…

// 4) CSV a arreglos asociativos
$rows = Util::readCsv('data.csv');

// 5) Token seguro
$token = Util::token(24);

// 6) Distancia (km)
$km = Util::distance([40.4168,-3.7038],[41.3874,2.1686], 6371);
```

---

## Notas y buenas prácticas
- Evita exponer errores internos con `printStatus` en producción; controla los mensajes.
- Para criptografía autenticada considera una variante con `AES-256-GCM`.
- Si usas `getClientIp` detrás de proxies, valida cabeceras permitidas o usa la IP del `reverse proxy` de confianza.
- `unzip` incluye comprobaciones anti “zip slip”, pero valida también el origen del fichero zip.

## Requisitos
- PHP >= 7.4 (probado hasta 8.4)
- Extensiones: `openssl`, `zip`

## Instalación
```bash
composer require scopweb/php-util
```
