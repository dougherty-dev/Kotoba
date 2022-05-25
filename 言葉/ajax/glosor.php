<?php declare(strict_types = 1);

require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$kotoba = new 言葉;

if (isset($_REQUEST['ny_glosa_glosa'])) {
	$ny_glosa_glosa = filter_var($_REQUEST['ny_glosa_glosa'], FILTER_SANITIZE_SPECIAL_CHARS);

	if ($ny_glosa_glosa !== '' && $ny_glosa_glosa !== FALSE) {
		$ny_glosa_romanisering = filter_var($_REQUEST['ny_glosa_romanisering'], FILTER_SANITIZE_SPECIAL_CHARS);
		$ny_glosa_översättning = filter_var($_REQUEST['ny_glosa_översättning'], FILTER_SANITIZE_SPECIAL_CHARS);
		$ny_glosa_grad = filter_var($_REQUEST['ny_glosa_grad'], FILTER_VALIDATE_INT);

		$sats = $kotoba->db->instans->prepare("INSERT INTO `glosor` (`glosa`, `romanisering`, `översättning`, `grad`, `språkid`, `bokid`, `kapitelid`)
			VALUES (:glosa, :romanisering, :oversattning, :grad, :sprakid, :bokid, :kapitelid)");
		$sats->bindValue(':glosa', $ny_glosa_glosa, PDO::PARAM_STR);
		$sats->bindValue(':romanisering', $ny_glosa_romanisering, PDO::PARAM_STR);
		$sats->bindValue(':oversattning', $ny_glosa_översättning, PDO::PARAM_STR);
		$sats->bindValue(':grad', $ny_glosa_grad, PDO::PARAM_INT);
		$sats->bindValue(':sprakid', $kotoba->språkid, PDO::PARAM_INT);
		$sats->bindValue(':bokid', $kotoba->bokid, PDO::PARAM_INT);
		$sats->bindValue(':kapitelid', $kotoba->kapitelid, PDO::PARAM_INT);
		$sats->execute();
		$ny_glosa_glosid = $kotoba->db->instans->lastInsertId();

		echo <<< EOT
					<tr>
						<td><span class="mindre radera_glosa">✖️</span><input type="hidden" value="$ny_glosa_glosid"/></td>
						<td><input class="glosa" type="text" size="25" autocomplete="off" value="$ny_glosa_glosa"/></td>
						<td><input class="romanisering" type="text" size="25" autocomplete="off" value="$ny_glosa_romanisering"/></td>
						<td><input class="översättning" type="text" size="25" autocomplete="off" value="$ny_glosa_översättning"/></td>
						<td><input class="grad" type="number" min="1" max="5" size="1" autocomplete="off" value="$ny_glosa_grad"/></td>
						<td><span class="tala_glosa">🔊</span></td>
					</tr>

EOT;
	}
} elseif (isset($_REQUEST['ändra_glosa_glosid'])) {
	$glosid = filter_var($_REQUEST['ändra_glosa_glosid'], FILTER_VALIDATE_INT);
	$kolumn = filter_var($_REQUEST['kolumn'], FILTER_SANITIZE_SPECIAL_CHARS);
	$värde = filter_var($_REQUEST['värde'], FILTER_SANITIZE_SPECIAL_CHARS);
	if ($värde !== '' && $glosid > 0) {
		$sats = $kotoba->db->instans->prepare("UPDATE `glosor` SET `$kolumn`=:glosa WHERE `glosid`=:glosid");
		$sats->bindValue(':glosa', $värde, PDO::PARAM_STR);
		$sats->bindValue(':glosid', $glosid, PDO::PARAM_INT);
		$sats->execute();
	}
} elseif (isset($_REQUEST['radera_glosa_glosid'])) {
	$glosid = filter_var($_REQUEST['radera_glosa_glosid'], FILTER_VALIDATE_INT);
	if ($glosid > 0) {
		$sats = $kotoba->db->instans->prepare("DELETE FROM `glosor` WHERE `glosid`=:glosid");
		$sats->bindValue(':glosid', $glosid, PDO::PARAM_INT);
		$sats->execute();
	}
} elseif (isset($_REQUEST['tala_glosa'])) {
	$tala_glosa = filter_var($_REQUEST['tala_glosa'], FILTER_SANITIZE_SPECIAL_CHARS);
	$röst = (string) $kotoba->röst;
	exec("say -v $röst $tala_glosa");
}
