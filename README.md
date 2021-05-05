Хелперы для phpunit тестов
==========================

### Инсталяция

```bash
composer --dev require ostiwe-com/phpunit-helpers
```

### Использование

```php
<?php

namespace App\Tests\Unit;

use Ostiwe\PhpUnitHelpers\TestCase;

class SomeClassTest extends TestCase
{
    public function testSetPrivateProperty(): void
    {
        $object = new SomeClass();
        $data = 'somePrivateData';
        $this->setPrivateProperty($object, 'privateProperty', $data);
        self::assertSame($object->getPrivateProperty(), $data);
    }
    
    public function testCallPrivateMethod(): void
    {
        $object = new SomeClass();
        $args = ['argOne', 'argTwo'];
        $data = $this->callMethod($object, 'methodName', $args);
        
        self::assertSame($data, $args);
    }
}

```