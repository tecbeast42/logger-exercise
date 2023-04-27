# Logger Exercise

> In the spirit of an exercise I decided against using an existing library like monolog

## Installation

> this package is not published to packagist 

```shell
composer require tecbeast42/logger-exercise 
```

## Usage

To integrate the Logger you need to provide the logger class with a logging 
target and a minimum severity.

The logger target has to be a filesystem from the flysystem package.

Example to create a filesystem to write into `/tmp/remove-me/`

```php
$rootPath = "/tmp/remove/"
$adapter = new League\Flysystem\Local\LocalFilesystemAdapter($rootPath);
$filesystem = new League\Flysystem\Filesystem($adapter);
$logger = new Tecbeast42\LoggerExample\Logger("info", $filesystem, "test.log");

// USAGE
$logger->debug("I am logging stuff"); // will not be writen to file due to severity
$logger->info("will be written to file");
```

If you want to just log to stdout you can omit the filesystem

```php
$logger = new Tecbeast42\LoggerExample\Logger("info");
```

The logger defaults to severity `error` and output goes to stdout

```php
$logger = new Tecbeast42\LoggerExample\Logger;
```

## Using multiple logger targets

To send different logs to different target we can just create as many loggers as we want. 
Depending on the application using this library they could then be used in a helper function 
or resolved by a service container. Log levels can be adjusted on a per logger basis.

## Multi threading 

Since this is a PHP library multi threading is not a concern. In production a lot of processes 
would spawn and could saturate multiple cores. Depending on the Filesystem there might be 
a connection limit on the receiving server.

## Extending

If we want to send e.g. email or write to something other then a filesystem, this library needs 
another abstraction layer, to allow for more then just he Flysystem Filesystems. Alternatively 
it is possible to write our own filesystem adapter, which can then target anything PHP can target.

## Improvments

First this library needs some testing to cover basic functionality. 

Second custom severity levels would be nice

Third custom formatters like e.g. json widen the usability of this library

Fourth if the amount of parameters for the logger constructor become to many 
a builder pattern would make it digestable
```php
$logger = (new Logger)->severity("info")->mailTo()->smsTo() ...
```

Time to complete ~1h
