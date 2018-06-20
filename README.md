Php AWS S3 quick uploads
=======================

- type in terminal: composer install
- in index.php set your params: $key, $secret, $bucket



1) upload file
```php
$s3 = new S3();
$s3->uploadFile('filename.ext');
```
