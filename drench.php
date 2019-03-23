#! /usr/bin/env php
<?php
use Symfony\Component\Console\Application;
use Acme\NewCommand;
require 'vendor/autoload.php';

$app = new Application('Laravel Drench Installer','1.0');

$app->add(new NewCommand(new GuzzleHttp\Client)); 
$app->run();
 
  