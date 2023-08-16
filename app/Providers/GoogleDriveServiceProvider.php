<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Storage;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('google', function ($app, $config) {
            $client = new \Google\Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            //$service = new \Google_Service_Drive($client);
            $service = new \Google\Service\Drive($client);
            //$client->setApplicationName('My Google Drive App');

            // $options = [];
            // if (isset($config['teamDriveId'])) {
            //     $options['teamDriveId'] = $config['teamDriveId'];
            // }

            // $adapter = new GoogleDriveAdapter($service, $config['folderId'], $options);
            $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, 'My_App_Root');

            return new Filesystem($adapter);
        });
    }
}