<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;

date_default_timezone_set('Europe/Rome');

Class S3 {

    function __construct(){ 
        $this->key          = ''; // set key 
        $this->secret       = ''; // set secret
        $this->bucket       = ''; // set bucket name
        $this->permissions  = 'public-read'; // set permissions
        $this->s3           = $this->connection();
    }

    public function getFilename($filename){
        $mime   = mime_content_type($filename);
        $type   = explode('/',$mime)[0];
        $ext    = pathinfo($filename, PATHINFO_EXTENSION);
        $name   = $type.'-'.date('Ymdhis').'.'.$ext;  
        return [
            'mime'      => $mime,
            'type'      => $type,
            'ext'       => $ext,
            'name'      => $name
        ];
    }

    public function connection(){
        return S3Client::factory([
            'version' => 'latest',
            'region'  => 'eu-west-3',
            'credentials' => [
                'key'       => $this->key,
                'secret'    => $this->secret
            ]
        ]);
    }

    public function uploadFile($file){
        $data = [
            'Bucket'       => $this->bucket,
            'Key'          => $this->getFilename($file)['name'],
            'SourceFile'   => $file,
            'ContentType'  => $this->getFilename($file)['mime'],
            'ACL'          => $this->permissions,
            'StorageClass' => 'REDUCED_REDUNDANCY'
        ];
        
        return $this->s3->putObject($data)['ObjectURL'];
    }

}