<?php declare(strict_types = 1);

require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$kotoba = new 言葉;

if (isset($_REQUEST['nytt_kapitel_kapitelnamn'])) {
	$nytt_kapitel_kapitelnamn = filter_var($_REQUEST['nytt_kapitel_kapitelnamn'], FILTER_SANITIZE_SPECIAL_CHARS);

	if ($nytt_kapitel_kapitelnamn !== '' && $nytt_kapitel_kapitelnamn !== FALSE) {
		$sats = $kotoba->db->instans->prepare("INSERT INTO `kapitel` (`kapitelnamn`, `bokid`, `språkid`) VALUES (:kapitelnamn, :bokid, :sprakid)");
		$sats->bindValue(':kapitelnamn', $nytt_kapitel_kapitelnamn, PDO::PARAM_STR);
		$sats->bindValue(':bokid', $kotoba->bokid, PDO::PARAM_INT);
		$sats->bindValue(':sprakid', $kotoba->språkid, PDO::PARAM_INT);
		$sats->execute();
		$nytt_kapitel_kapitelid = $kotoba->db->instans->lastInsertId();

		if ($kotoba->kapitelnamn === '') {
			$kotoba->kapitelid = (int) $nytt_kapitel_kapitelid;
			$kotoba->kapitelnamn = $nytt_kapitel_kapitelnamn;
			$kotoba->db->spara_preferens('kapitelid', (string) $kotoba->kapitelid);
			$kotoba->db->spara_preferens('kapitelnamn', $kotoba->kapitelnamn);
		}
	}
} elseif (isset($_REQUEST['redigera_kapitel_kapitelnamn'])) {
	$redigera_kapitel_kapitelnamn = filter_var($_REQUEST['redigera_kapitel_kapitelnamn'], FILTER_SANITIZE_SPECIAL_CHARS);
	if ($redigera_kapitel_kapitelnamn !== '' && $redigera_kapitel_kapitelnamn !== FALSE) {
		$sats = $kotoba->db->instans->prepare("UPDATE `kapitel` SET `kapitelnamn`=:kapitelnamn WHERE `kapitelid`=:kapitelid");
		$sats->bindValue(':kapitelnamn', $redigera_kapitel_kapitelnamn, PDO::PARAM_STR);
		$sats->bindValue(':kapitelid', filter_var($_REQUEST['redigera_kapitel_kapitelid']), PDO::PARAM_INT);
		$sats->execute();
		$kotoba->kapitelnamn = $redigera_kapitel_kapitelnamn;
		$kotoba->db->spara_preferens('kapitelnamn', $kotoba->kapitelnamn);
	}
} elseif (isset($_REQUEST['radera_kapitel_kapitelid'])) {
	$radera_kapitel_kapitelid = filter_var($_REQUEST['radera_kapitel_kapitelid'], FILTER_VALIDATE_INT);
	if ($radera_kapitel_kapitelid > 0) {
		foreach (['kapitel', 'glosor'] as $tabell) {
			$sats = $kotoba->db->instans->prepare("DELETE FROM `$tabell` WHERE `kapitelid`=:kapitelid");
			$sats->bindValue(':kapitelid', $radera_kapitel_kapitelid, PDO::PARAM_INT);
			$sats->execute();
		}

		$kotoba->finn_kapitel();
	}
} elseif (isset($_REQUEST['välj_kapitel_kapitelid'])) {
	$välj_kapitel_kapitelid = filter_var($_REQUEST['välj_kapitel_kapitelid'], FILTER_VALIDATE_INT);
	if ($välj_kapitel_kapitelid > 0) {
		$kotoba->kapitelid = (int) $välj_kapitel_kapitelid;
		$sats = $kotoba->db->instans->prepare("SELECT * FROM `kapitel` WHERE `kapitelid`=:kapitelid");
		$sats->bindValue(':kapitelid', $kotoba->kapitelid, PDO::PARAM_INT);
		$sats->bindColumn('kapitelnamn', $kotoba->kapitelnamn, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);
		$kotoba->db->spara_preferens('kapitelnamn', $kotoba->kapitelnamn);
		$kotoba->db->spara_preferens('kapitelid', (string) $kotoba->kapitelid);
	}
}
