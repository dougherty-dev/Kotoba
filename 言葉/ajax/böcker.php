<?php declare(strict_types = 1);

require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$kotoba = new 言葉;

if (isset($_REQUEST['ny_bok_boknamn'])) {
	$ny_bok_boknamn = filter_var($_REQUEST['ny_bok_boknamn'], FILTER_SANITIZE_SPECIAL_CHARS);

	if ($ny_bok_boknamn !== '' && $ny_bok_boknamn !== FALSE) {
		$sats = $kotoba->db->instans->prepare("INSERT INTO `böcker` (`boknamn`, `språkid`) VALUES (:boknamn, :sprakid)");
		$sats->bindValue(':boknamn', $ny_bok_boknamn, PDO::PARAM_STR);
		$sats->bindValue(':sprakid', $kotoba->språkid, PDO::PARAM_INT);
		$sats->execute();
		$ny_bok_bokid = $kotoba->db->instans->lastInsertId();

		if ($kotoba->boknamn === '') {
			$kotoba->bokid = (int) $ny_bok_bokid;
			$kotoba->boknamn = $ny_bok_boknamn;
			$kotoba->db->spara_preferens('boknamn', (string) $kotoba->boknamn);
		}
	}
} elseif (isset($_REQUEST['redigera_böcker_boknamn'])) {
	$redigera_böcker_boknamn = filter_var($_REQUEST['redigera_böcker_boknamn'], FILTER_SANITIZE_SPECIAL_CHARS);
	if ($redigera_böcker_boknamn !== '' && $redigera_böcker_boknamn !== FALSE) {
		$sats = $kotoba->db->instans->prepare("UPDATE `böcker` SET `boknamn`=:boknamn WHERE `bokid`=:bokid");
		$sats->bindValue(':boknamn', $redigera_böcker_boknamn, PDO::PARAM_STR);
		$sats->bindValue(':bokid', filter_var($_REQUEST['redigera_böcker_bokid']), PDO::PARAM_INT);
		$sats->execute();
		$kotoba->boknamn = $redigera_böcker_boknamn;
		$kotoba->db->spara_preferens('boknamn', (string) $kotoba->boknamn);
	}
} elseif (isset($_REQUEST['radera_bok_bokid'])) {
	$radera_bok_bokid = filter_var($_REQUEST['radera_bok_bokid'], FILTER_VALIDATE_INT);
	if ($radera_bok_bokid > 0) {
		foreach (['böcker', 'kapitel', 'glosor'] as $tabell) {
			$sats = $kotoba->db->instans->prepare("DELETE FROM `$tabell` WHERE `bokid`=:bokid");
			$sats->bindValue(':bokid', $radera_bok_bokid, PDO::PARAM_INT);
			$sats->execute();
		}

		$kotoba->finn_bok();
	}
} elseif (isset($_REQUEST['välj_bok_bokid'])) {
	$välj_bok_bokid = filter_var($_REQUEST['välj_bok_bokid'], FILTER_VALIDATE_INT);
	if ($välj_bok_bokid > 0) {
		$kotoba->bokid = $välj_bok_bokid;
		$sats = $kotoba->db->instans->prepare("SELECT * FROM `böcker` WHERE `bokid`=:bokid");
		$sats->bindValue(':bokid', $kotoba->bokid, PDO::PARAM_INT);
		$sats->bindColumn('boknamn', $kotoba->boknamn, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);

		$kotoba->finn_kapitel();
	}
}
