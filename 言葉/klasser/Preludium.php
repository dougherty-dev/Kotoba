<?php declare(strict_types = 1);

require_once dirname(__FILE__) . '/../funktioner/konstanter.php';
require_once FUNKTIONER . '/funktioner.php';

new Preludium;

final class Preludium {
	public function __construct() {
		setlocale(LC_TIME, 'sv_SE');

		if (defined('FELRAPPORTERING')) {
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		} else {
			error_reporting(0);
			ini_set('display_errors', '0');
		}

		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '0');

		mb_internal_encoding('UTF-8');
		mb_http_output('UTF-8');

		set_include_path(implode(PATH_SEPARATOR, [KLASSER, EGENSKAPER]));
		spl_autoload_register(function (string $klass): void {
			$mappar = explode(PATH_SEPARATOR, (string) get_include_path());
			foreach ($mappar as $mapp) {
				is_file($fil = $mapp . "/$klass.php") and require $fil;
			}
		});
	}
}
