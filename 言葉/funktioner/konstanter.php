<?php declare(strict_types = 1);

define('VERSION', '1.0.1');
define('VERSIONSDATUM', '2022-06-01');
define('FÖRFATTARE', 'Niklas Dougherty');

define('LOCAL', TRUE);
define('FELRAPPORTERING', TRUE);

define('BAS', realpath(dirname(__FILE__) . '/..'));
define('DB', BAS . '/db');
define('FUNKTIONER', BAS . '/funktioner');
define('KLASSER', BAS . '/klasser');
define('EGENSKAPER', BAS . '/egenskaper');
define('AJAX', BAS . '/ajax');

define('EN_VECKA', 604800);
define('BACKUP', BAS . '/../kotoba-backup');

/*

1.0.1		Buggfixar; PHPStan 1.7, nivå 9
1.0.0		Grundläggande version
0.0.1		Skelett

*/
