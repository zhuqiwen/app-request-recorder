## Install
use composer:
```shell
composer require iu-vpcm/request-recorder
```


## Usage
```php
use \Edu\IU\VPCM\RequestRecorder\RequestRecorder;
$extra = ['name'=>'tom'];
$recorder = new RequestRecorder('folder-path', 'file-name', $extra);
$recorder->append();
```