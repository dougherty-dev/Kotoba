<?php declare(strict_types = 1);

require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$kotoba = new 言葉;

if (isset($_REQUEST['uppdatera_röster']) && $_REQUEST['uppdatera_röster']) {
	$kotoba->hämta_röster();
} elseif (isset($_REQUEST['dammsug_databas']) && $_REQUEST['dammsug_databas']) {
	$kotoba->db->dammsug();
} elseif (isset($_REQUEST['säkerhetskopiera']) && $_REQUEST['säkerhetskopiera']) {
	$kotoba->db->spara_backup();
}
