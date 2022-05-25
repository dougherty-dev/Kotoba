<?php declare(strict_types = 1);

require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$kotoba = new 言葉;

if (isset($_REQUEST['nytt_språk_språk'])) {
	$nytt_språk_språk = filter_var($_REQUEST['nytt_språk_språk'], FILTER_SANITIZE_SPECIAL_CHARS);

	if ($nytt_språk_språk !== '' && $nytt_språk_språk !== FALSE) {
		$nytt_språk_lokalspråk = filter_var($_REQUEST['nytt_språk_lokalspråk'], FILTER_SANITIZE_SPECIAL_CHARS);
		$nytt_språk_romanisering = filter_var($_REQUEST['nytt_språk_romanisering'], FILTER_SANITIZE_SPECIAL_CHARS);
		$nytt_språk_röst = filter_var($_REQUEST['nytt_språk_röst'], FILTER_SANITIZE_SPECIAL_CHARS);

		$sats = $kotoba->db->instans->prepare("INSERT INTO `språk` (`språknamn`, `lokalspråk`, `romanisering`, `röst`)
			VALUES (:spraknamn, :lokalsprak, :romanisering, :rost)");
		$sats->bindValue(':spraknamn', $nytt_språk_språk, PDO::PARAM_STR);
		$sats->bindValue(':lokalsprak', $nytt_språk_lokalspråk, PDO::PARAM_STR);
		$sats->bindValue(':romanisering', $nytt_språk_romanisering, PDO::PARAM_STR);
		$sats->bindValue(':rost', $nytt_språk_röst, PDO::PARAM_STR);
		$sats->execute();
		$nytt_språk_språkid = $kotoba->db->instans->lastInsertId();

		if ($kotoba->språknamn === '') {
			$kotoba->språkid = (int) $nytt_språk_språkid;
			$kotoba->språknamn = $nytt_språk_språk;
			$kotoba->lokalspråk = (string) $nytt_språk_lokalspråk;
			$kotoba->romanisering = (string) $nytt_språk_romanisering;
			$kotoba->röst = (string) $nytt_språk_röst;
			$kotoba->uppdatera_aktuell();
		}
	}
} elseif (isset($_REQUEST['redigera_språk_språknamn'])) {
	$redigera_språk_språknamn = filter_var($_REQUEST['redigera_språk_språknamn'], FILTER_SANITIZE_SPECIAL_CHARS);
	if ($redigera_språk_språknamn !== '' && $redigera_språk_språknamn !== FALSE) {
		$sats = $kotoba->db->instans->prepare("UPDATE `språk` SET `språknamn`=:spraknamn WHERE `språkid`=:sprakid");
		$sats->bindValue(':spraknamn', $redigera_språk_språknamn, PDO::PARAM_STR);
		$sats->bindValue(':sprakid', filter_var($_REQUEST['redigera_språk_språkid']), PDO::PARAM_INT);
		$sats->execute();
		$kotoba->språknamn = $redigera_språk_språknamn;
		$kotoba->db->spara_preferens('språknamn', $kotoba->språknamn);
	}
} elseif (isset($_REQUEST['redigera_språk_lokalspråk'])) {
	$redigera_språk_lokalspråk = filter_var($_REQUEST['redigera_språk_lokalspråk'], FILTER_SANITIZE_SPECIAL_CHARS);
	if ($redigera_språk_lokalspråk !== '' && $redigera_språk_lokalspråk !== FALSE) {
		$sats = $kotoba->db->instans->prepare("UPDATE `språk` SET `lokalspråk`=:lokalsprak WHERE `språkid`=:sprakid");
		$sats->bindValue(':lokalsprak', $redigera_språk_lokalspråk, PDO::PARAM_STR);
		$sats->bindValue(':sprakid', filter_var($_REQUEST['redigera_språk_språkid']), PDO::PARAM_INT);
		$sats->execute();
		$kotoba->lokalspråk = $redigera_språk_lokalspråk;
		$kotoba->db->spara_preferens('lokalspråk', $kotoba->lokalspråk);
	}
} elseif (isset($_REQUEST['redigera_språk_romanisering'])) {
	$redigera_språk_romanisering = filter_var($_REQUEST['redigera_språk_romanisering'], FILTER_SANITIZE_SPECIAL_CHARS);
	if ($redigera_språk_romanisering !== '' && $redigera_språk_romanisering !== FALSE) {
		$sats = $kotoba->db->instans->prepare("UPDATE `språk` SET `romanisering`=:romanisering WHERE `språkid`=:sprakid");
		$sats->bindValue(':romanisering', $redigera_språk_romanisering, PDO::PARAM_STR);
		$sats->bindValue(':sprakid', filter_var($_REQUEST['redigera_språk_språkid']), PDO::PARAM_INT);
		$sats->execute();
		$kotoba->romanisering = $redigera_språk_romanisering;
		$kotoba->db->spara_preferens('romanisering', $kotoba->romanisering);
	}
} elseif (isset($_REQUEST['redigera_språk_röst'])) {
	$redigera_språk_röst = filter_var($_REQUEST['redigera_språk_röst'], FILTER_SANITIZE_SPECIAL_CHARS);
	if ($redigera_språk_röst !== '' && $redigera_språk_röst !== FALSE) {
		$sats = $kotoba->db->instans->prepare("UPDATE `språk` SET `röst`=:rost WHERE `språkid`=:sprakid");
		$sats->bindValue(':rost', $redigera_språk_röst, PDO::PARAM_STR);
		$sats->bindValue(':sprakid', filter_var($_REQUEST['redigera_språk_språkid']), PDO::PARAM_INT);
		$sats->execute();
		$kotoba->röst = $redigera_språk_röst;
		$kotoba->db->spara_preferens('röst', $kotoba->röst);
	}
} elseif (isset($_REQUEST['radera_språk_språkid'])) {
	$radera_språk_språkid = filter_var($_REQUEST['radera_språk_språkid'], FILTER_VALIDATE_INT);
	if ($radera_språk_språkid > 0) {
		foreach (['språk', 'böcker', 'kapitel', 'glosor'] as $tabell) {
			$sats = $kotoba->db->instans->prepare("DELETE FROM `$tabell` WHERE `språkid`=:sprakid");
			$sats->bindValue(':sprakid', $radera_språk_språkid, PDO::PARAM_INT);
			$sats->execute();
		}

		$kotoba->finn_språk();
	}
} elseif (isset($_REQUEST['välj_språk_språkid'])) {
	$välj_språk_språkid = filter_var($_REQUEST['välj_språk_språkid'], FILTER_VALIDATE_INT);
	if ($välj_språk_språkid > 0) {
		$kotoba->språkid = $välj_språk_språkid;
		$sats = $kotoba->db->instans->prepare("SELECT * FROM `språk` WHERE `språkid`=:sprakid");
		$sats->bindValue(':sprakid', $kotoba->språkid, PDO::PARAM_INT);
		$sats->bindColumn('språknamn', $kotoba->språknamn, PDO::PARAM_STR);
		$sats->bindColumn('lokalspråk', $kotoba->lokalspråk, PDO::PARAM_STR);
		$sats->bindColumn('romanisering', $kotoba->romanisering, PDO::PARAM_STR);
		$sats->bindColumn('röst', $kotoba->röst, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);

		$kotoba->finn_bok();
	}
}
