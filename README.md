#使用
```php
require_once __DIR__ . '/vendor/autoload.php';

use kongServer\Application;
$ksobj = new Application('&');
if($ksobj->check())
{
    echo "success";
}
```

