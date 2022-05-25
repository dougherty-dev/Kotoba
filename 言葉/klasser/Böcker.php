<?php declare(strict_types = 1);

final class Böcker {
	private 言葉 $kotoba;

	public function __construct(言葉 $kotoba) {
		$this->kotoba = $kotoba;
		if ($this->kotoba->språknamn !== '') $this->visa_böcker();
	}

	private function visa_böcker(): void {
		echo <<< EOT
			<div id="flikar-bocker">
{$this->kotoba->rullgardin_böcker()}
				<hr/>
				<table>
					<tbody>
						<tr>
							<td>Boknamn:<input type="hidden" id="redigera_böcker_bokid" value="{$this->kotoba->bokid}"/></td>
							<td><input type="text" id="redigera_böcker_boknamn" size="25" autocomplete="off" value="{$this->kotoba->boknamn}"/></td>
						</tr>
					</tbody>
				</table>
				<p><button id="radera_bok">Radera bok</button></p>
				<hr/>
				<table>
					<tbody>
						<tr>
							<td>Boknamn:</td>
							<td><input type="text" id="ny_bok_boknamn" size="25" autocomplete="off"/></td>
						</tr>
					</tbody>
				</table>
				<p><button id="ny_bok">Ny bok</button></p>
			</div> <!-- flikar-bocker -->

EOT;
	}

}
