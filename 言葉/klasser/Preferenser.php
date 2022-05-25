<?php declare(strict_types = 1);

final class Preferenser {
	public function __construct() {
		$this->visa_preferenser();
	}

	private function visa_preferenser(): void {
		echo <<< EOT
			<div id="flikar-preferenser">
				<p><button id="uppdatera_röster">Uppdatera röster</button></p>
				<p><button id="dammsug_databas">Dammsug databas</button></p>
				<p><button id="säkerhetskopiera">Säkerhetskopiera</button></p>
			</div> <!-- flikar-preferenser -->

EOT;
	}

}
