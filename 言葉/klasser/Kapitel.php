<?php declare(strict_types = 1);

final class Kapitel {
	private 言葉 $kotoba;

	public function __construct(言葉 $kotoba) {
		$this->kotoba = $kotoba;
		if ($this->kotoba->boknamn !== '') $this->visa_kapitel();
	}

	private function visa_kapitel(): void {
		echo <<< EOT
			<div id="flikar-kapitel">
{$this->kotoba->rullgardin_kapitel()}
				<hr/>
				<table>
					<tbody>
						<tr>
							<td>Kapitelnamn:<input type="hidden" id="redigera_kapitel_kapitelid" value="{$this->kotoba->kapitelid}"/></td>
							<td><input type="text" id="redigera_kapitel_kapitelnamn" size="25" autocomplete="off" value="{$this->kotoba->kapitelnamn}"/></td>
						</tr>
					</tbody>
				</table>
				<p><button id="radera_kapitel">Radera kapitel</button></p>
				<hr/>
				<table>
					<tbody>
						<tr>
							<td>Kapitelnamn:</td>
							<td><input type="text" id="nytt_kapitel_kapitelnamn" size="25" autocomplete="off"/></td>
						</tr>
					</tbody>
				</table>
				<p><button id="nytt_kapitel">Nytt kapitel</button></p>
			</div> <!-- flikar-kapitel -->

EOT;
	}

}
