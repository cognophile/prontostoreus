<?php
/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */
require dirname(__DIR__) . '/vendor/autoload.php';

require dirname(__DIR__) . '/config/bootstrap.php';

$_SERVER['PHP_SELF'] = '/';

use Cake\Core\Configure;
Configure::write('Folder.TestResources', '/resources/');
Configure::write('File.TestResponseMessages', 'test.json');

Configure::write('Api.Scope', '/api/v1/');
