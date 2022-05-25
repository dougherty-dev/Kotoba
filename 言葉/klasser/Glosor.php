<?php declare(strict_types = 1);

final class Glosor {
	private 言葉 $kotoba;

	public function __construct(言葉 $kotoba) {
		$this->kotoba = $kotoba;
		$this->visa_glosor();
	}

	private function visa_glosor(): void {
		$glostext = '';
		$sats = $this->kotoba->db->instans->prepare("SELECT `glosid`, `glosa`, `romanisering`, `översättning`, `grad`
			FROM `glosor` WHERE `kapitelid`=:kapitelid");
		$sats->bindValue(':kapitelid', $this->kotoba->kapitelid, PDO::PARAM_INT);
		$sats->bindColumn('glosid', $glosid, PDO::PARAM_INT);
		$sats->bindColumn('glosa', $glosa, PDO::PARAM_STR);
		$sats->bindColumn('romanisering', $romanisering, PDO::PARAM_STR);
		$sats->bindColumn('översättning', $översättning, PDO::PARAM_STR);
		$sats->bindColumn('grad', $grad, PDO::PARAM_INT);
		$sats->execute();
		while ($sats->fetch(PDO::FETCH_OBJ)) {
			$glostext .= <<< EOT
						<tr>
							<td><span class="mindre radera_glosa">✖️</span><input type="hidden" value="$glosid"/></td>
							<td><input class="glosa" type="text" size="25" autocomplete="off" value="$glosa"/></td>
							<td><input class="romanisering" type="text" size="25" autocomplete="off" value="$romanisering"/></td>
							<td><input class="översättning" type="text" size="25" autocomplete="off" value="$översättning"/></td>
							<td><input class="grad" type="number" min="1" max="5" size="1" autocomplete="off" value="$grad"/></td>
							<td><span class="tala_glosa">🔊</span></td>
						</tr>

EOT;
		}

		$språk = ($this->kotoba->lokalspråk === '') ? 'glosa' : $this->kotoba->lokalspråk;
		$romanisering = ($this->kotoba->romanisering === '') ? 'romanisering' : $this->kotoba->romanisering;

		echo <<< EOT
			<div id="flikar-glosor">
				<table>
					<tbody>
						<tr>
							<td>$språk</td>
							<td><input type="text" id="ny_glosa_glosa" size="50" autocomplete="off"/></td>
						</tr>
						<tr>
							<td>$romanisering</td>
							<td><input type="text" id="ny_glosa_romanisering" size="50" autocomplete="off"/></td>
						</tr>
						<tr>
							<td>översättning</td>
							<td><input type="text" id="ny_glosa_översättning" size="50" autocomplete="off"/></td>
						</tr>
						<tr>
							<td>grad</td>
							<td><input type="number" min="1" max="5" id="ny_glosa_grad" size="5" autocomplete="off" value="3"/></td>
						</tr>
					</tbody>
				</table>
				<p><button id="ny_glosa">Ny glosa</button></p>
				<hr/>
				<table id="glosor">
					<thead>
						<tr>
							<th>❌</th>
							<th>$språk</th>
							<th>$romanisering</th>
							<th>översättning</th>
							<th>grad</th>
						</tr>
					</thead>
					<tbody>
$glostext					</tbody>
				</table>
			</div> <!-- flikar-glosor -->

EOT;
	}

}
