# JsonpPaser
The processing of JSONP decoding and encoding.

# Usage

### Encoder

- encode

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->encoder(['name' => 'example'])
    ->encode();
```

```php
// default callbackName = callback
string(28) "callback({"name":"example"})"
```

---

- change callback name.

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->encoder(['name' => 'example'])
    ->callback('example')
    ->encode();
```

```php
string(28) "example({"name":"example"})"
```

---

- depth(default 512)

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser
    ->encoder([['name' => 'example']])
    ->depth(1)
    ->encode();
```

```php
false
```

---

- option(default 0)

@link json.constants
http://php.net/manual/en/json.constants.php

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser
    ->encoder([['name' => 'example']])
    ->option(JSON_UNESCAPED_UNICODE)
    ->encode();
```

### Decoder

- toArray

```php
$parser = new Rmtram\JsonpParser\Jsonp();
var_dump($parser->decoder('callback({"name": "example"})')->toArray());
```

```php
array(1) {
  ["name"]=> string(7) "example"
}
```

---

- toObject 

```php
$parser = new Rmtram\JsonpParser\Jsonp();
var_dump($parser->decoder('callback({"name": "example"})')->toObject());
```

```php
object(stdClass)#45 (1) {
  ["name"]=> string(7) "example"
}
```

---

- trim the EOL.

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->decoder('callback(
{"name":"example"})'
)->trimEOL()->toArray();
```

```php
array(1) {
  ["name"]=> string(7) "example"
}
```

- not trim the EOL.

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->decoder('callback(
{"name":"example"})'
)->toArray();
```

```php
null
```

---

- depth(default 512)

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser
    ->decoder('callback([{"name": "example"}])')
    ->depth(1)
    ->toArray();
```

```php
null
```

---

- option(default 0)

@link json.constants
http://php.net/manual/en/json.constants.php

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser
    ->decoder('callback([{"name": "example"}])')
    ->option(JSON_BIGINT_AS_STRING)
    ->toArray();
```

### Util

- check jsonp format.

```
//@param string $jsonp jsonp string.
//@param boolean $trimEOL (trim CR+LF/CR/LF) 
//@param boolean $strict true => 'regex and json_decode', false => 'regexp'
is($jsonp, $trimEOL = false, $strict = true)
```

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->is('callback([1,2,3])');
```

```php
true
```

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->is('callback[1,2,3])');
```

```php
false
```

`trim eol`

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->is('callback(
[1,2,3])', true);
```

```php
true
```


```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->is('callback(
[1,2,3])', false);
```

```php
false
```

`strict mode`

normal

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->is('callback([1,2,3])(1,2,3)', false, false);
```

```php
true
```

strict

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->is('callback([1,2,3])(1,2,3)', false, true);
```

```php
false
```

---

normal

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->is('callback([1,2,3])(1,2,3', false, false);
```

```php
false
```

strict

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->is('callback([1,2,3])(1,2,3', false, true);
```

```php
false
```

---

normal

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->is('callback(example)', false, false);
```

```php
true
```

strict

```php
$parser = new Rmtram\JsonpParser\Jsonp();
$parser->is('callback(example)', false, true);
```

```php
false
```

### LICENSE

MIT LICENSE