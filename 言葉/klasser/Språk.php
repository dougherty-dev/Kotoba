<?php declare(strict_types = 1);

final class Språk {
	private 言葉 $kotoba;

	public function __construct(言葉 $kotoba) {
		$this->kotoba = $kotoba;
		$this->visa_språk();
	}

	private function visa_språk(): void {
		$röster = '';
		foreach ($this->kotoba->röster as $språkkod => $grupper) {
			$röster .= <<< EOT
								<optgroup label="$språkkod">

EOT;
			foreach ($grupper as $k => $röst) {
				$vald = ($this->kotoba->röst === $röst) ? ' selected="selected"' : '';
				$röster .= <<< EOT
									<option$vald value="$röst">$röst</option>

EOT;
			}
			$röster .= <<< EOT
								</optgroup>

EOT;
		}

		echo <<< EOT
			<div id="flikar-sprak">
{$this->kotoba->rullgardin_språk()}
				<hr/>
				<table>
					<tbody>
						<tr>
							<td>Språknamn:<input type="hidden" id="redigera_språk_språkid" value="{$this->kotoba->språkid}"/></td>
							<td><input type="text" id="redigera_språk_språknamn" size="25" autocomplete="off" value="{$this->kotoba->språknamn}"/></td>
						</tr>
						<tr>
							<td>Lokalt namn:</td>
							<td><input type="text" id="redigera_språk_lokalspråk" size="25" autocomplete="off" value="{$this->kotoba->lokalspråk}"/></td>
						</tr>
						<tr>
							<td>Romanisering:</td>
							<td><input type="text" id="redigera_språk_romanisering" size="25" autocomplete="off" value="{$this->kotoba->romanisering}"/></td>
						</tr>
						<tr>
							<td>Röst:</td>
							<td><select id="redigera_språk_röst">
$röster							</select></td>
						</tr>
					</tbody>
				</table>
				<p><button id="radera_språk">Radera språk</button></p>
				<hr/>
				<table>
					<tbody>
						<tr>
							<td>Språknamn (japanska):</td>
							<td><input type="text" id="nytt_språk_språk" size="25" autocomplete="off"/></td>
						</tr>
						<tr>
							<td>Lokalt namn (日本語):</td>
							<td><input type="text" id="nytt_språk_lokalspråk" size="25" autocomplete="off"/></td>
						</tr>
						<tr>
							<td>Romanisering (カナ):</td>
							<td><input type="text" id="nytt_språk_romanisering" size="25" autocomplete="off"/></td>
						</tr>
						<tr>
							<td>Röst:</td>
							<td><select id="nytt_språk_röst">
$röster							</select></td>
						</tr>
					</tbody>
				</table>
				<p><button id="nytt_språk">Nytt språk</button></p>
			</div> <!-- flikar-sprak -->

EOT;
	}

}
