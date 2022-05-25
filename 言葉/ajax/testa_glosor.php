<?php declare(strict_types = 1);

require_once dirname($_SERVER['SCRIPT_FILENAME']) . '/../klasser/Preludium.php';
$kotoba = new 言葉;

if (isset($_REQUEST['valt_typid'])) {
	$valt_typid = filter_var($_REQUEST['valt_typid'], FILTER_SANITIZE_SPECIAL_CHARS);
	$kotoba->db->spara_preferens('typid', (string) $valt_typid);

} elseif (isset($_REQUEST['testa_från_grad'])) {
	$testa_från_grad = filter_var($_REQUEST['testa_från_grad'], FILTER_VALIDATE_INT);
	$kotoba->db->spara_preferens('testa_från_grad', (string) $testa_från_grad);

} elseif (isset($_REQUEST['ifylld'])) {
	$ifylld = filter_var($_REQUEST['ifylld'], FILTER_VALIDATE_INT);
	$id = filter_var($_REQUEST['id'], FILTER_SANITIZE_SPECIAL_CHARS);
	$kotoba->db->spara_preferens((string) $id, (string) $ifylld);

} elseif (isset($_REQUEST['typid'])) {
	$typid = filter_var($_REQUEST['typid'], FILTER_SANITIZE_SPECIAL_CHARS);
	$gloslista = [];
	$testa_från_grad = (int) $kotoba->db->hämta_preferens('testa_från_grad');

	$sats = $kotoba->db->instans->prepare("SELECT `glosid`, `glosa`, `romanisering`, `översättning`, `grad`
		FROM `glosor` WHERE `$typid`=:typid AND `grad`>=:gradval");
	$sats->bindValue(':typid', $kotoba->$typid, PDO::PARAM_INT);
	$sats->bindValue(':gradval', $testa_från_grad, PDO::PARAM_INT);
	$sats->bindColumn('glosid', $glosid, PDO::PARAM_INT);
	$sats->bindColumn('glosa', $glosa, PDO::PARAM_STR);
	$sats->bindColumn('romanisering', $romanisering, PDO::PARAM_STR);
	$sats->bindColumn('översättning', $översättning, PDO::PARAM_STR);
	$sats->bindColumn('grad', $grad, PDO::PARAM_INT);
	$sats->execute();
	while ($sats->fetch(PDO::FETCH_OBJ)) {
		$gloslista[] = [$glosid, $glosa, $romanisering, $översättning, $grad];
	}

	$antal_glosor = count($gloslista);
	shuffle($gloslista);

	$ljudval = ((bool) $kotoba->db->hämta_preferens('ljud')) ? ' checked="checked"' : '';
	$riktning = ((bool) $kotoba->db->hämta_preferens('från_översättning')) ? ' checked="checked"' : '';

	$utdata = <<< EOT
				<div id="testyta">
					<p><label>Ljud<input id="ljud" type="checkbox"$ljudval/></label>&nbsp;
						<label>Från översättning till {$kotoba->lokalspråk}<input id="från_översättning" type="checkbox"$riktning/></label>
					<p><button disabled id="antal"><span id="kvar">$antal_glosor</span>/<span id="ursprungligt">$antal_glosor</a></button>
						<button id="test_börja">Testa</button>
						<button id="test_visa">Visa</button>
						<button id="test_nästa">Nästa</button>
						<button id="test_klart">Klart</button></p>
					<table id="testtabell">
						<thead>
							<tr class="testhuvud">
								<th>grad</th>
								<th>översättning</th>
								<th>{$kotoba->romanisering}</th>
								<th>{$kotoba->lokalspråk}</th>
							</tr>
						</thead>
						<tbody>

EOT;

	foreach ($gloslista as $g) {
		$utdata .= <<< EOT
							<tr class="testrad">
								<td><input type="hidden" class="glostest_glosid" value="{$g[0]}"/>
									<input type="number" min="1" max="5" class="glostest_grad" value="{$g[4]}"/></td>
								<td class="glostest_översättning">{$g[3]}</td>
								<td class="glostest_romanisering">{$g[2]}</td>
								<td class="glostest_glosa">{$g[1]}</td>
							</tr>

EOT;
	}

	$utdata .= <<< EOT
						</tbody>
					</table>
				</div>


EOT;

	echo $utdata;
}
