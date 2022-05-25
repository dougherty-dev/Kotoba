<?php declare(strict_types = 1);

final class Uppmärkning {
	use Eka;
	public 言葉 $kotoba;
	public Språk $språk;
	public Böcker $böcker;
	public Kapitel $kapitel;
	public Glosor $glosor;
	public Preferenser $preferenser;

	public function __construct() {
		$this->kotoba = new 言葉;
		$this->initiera_uppmärkning();
		$this->visa_kotoba();
		$this->språk = new Språk($this->kotoba);
		$this->böcker = new Böcker($this->kotoba);
		$this->kapitel = new Kapitel($this->kotoba);
		$this->glosor = new Glosor($this->kotoba);
		$this->preferenser = new Preferenser();
		$this->terminera_uppmärkning();
	}

	private function visa_kotoba(): void {
		$typid = $this->kotoba->db->hämta_preferens('typid');
		if ($typid === '') $typid = 'kapitelid';
		$vald = ['kapitelid' => '', 'bokid' => '', 'språkid' => ''];
		$vald[$typid] = ' checked="checked"';

		$testa_från_grad = (int) $this->kotoba->db->hämta_preferens('testa_från_grad');
		foreach ($gradval = array_fill(1, 5, '') as $i => $g) {
			$gradval[$i] = ($testa_från_grad === $i) ? ' selected="selected"' : '';
		}

		echo <<< EOT
			<div id="flikar-kotoba">
{$this->kotoba->rullgardin_språk()}
{$this->kotoba->rullgardin_böcker()}
{$this->kotoba->rullgardin_kapitel()}
				<hr/>
				<p><button id="testa_glosor">Testa glosor</button>
					<label>i kapitel<input type="radio" name="testa_glosor" value="kapitelid"{$vald['kapitelid']}/></label>
					<label>i bok<input type="radio" name="testa_glosor" value="bokid"{$vald['bokid']}/></label>
					<label>i språk<input type="radio" name="testa_glosor" value="språkid"{$vald['språkid']}/></label></p>
				<p><select id="testa_från_grad">
					<option value="1"{$gradval[1]}>Från grad: 1/5</option>
					<option value="2"{$gradval[2]}>Från grad: 2/5</option>
					<option value="3"{$gradval[3]}>Från grad: 3/5</option>
					<option value="4"{$gradval[4]}>Från grad: 4/5</option>
					<option value="5"{$gradval[5]}>Från grad: 5/5</option>
				</select></p>
			</div> <!-- flikar-kotoba -->

EOT;
	}

	private function initiera_uppmärkning(): void {
		echo <<< EOT
<!doctype html>
<html lang="sv">
<head>
	<meta charset="UTF-8">
	<script src="/js/jquery-3.6.0.min.js"></script>
	<script src="/js/jquery-ui.1.13.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/jquery-ui.1.13.1.min.css">
	<link rel="stylesheet" type="text/css" href="/css/jquery-ui.structure.1.13.1.min.css">
	<link rel="stylesheet" type="text/css" href="/css/jquery-ui.theme.1.13.1.min.css">
	<link rel="stylesheet" type="text/css" href="/css/kotoba.css?{$this->eka(VERSIONSDATUM)}">
	<title>kotoba {$this->eka(VERSION)}</title>
</head>
<body>
	<div class="kotoba">
		<p id="hem" class="större"><a href="/">🏠</a></p>
		<div id="flikar">
			<div id="fliklista">
				<ul>
					<li><a href="#flikar-kotoba">Kotoba</a></li>
					<li><a href="#flikar-glosor">Glosor</a></li>
					<li><a href="#flikar-kapitel">Kapitel</a></li>
					<li><a href="#flikar-bocker">Böcker</a></li>
					<li><a href="#flikar-sprak">Språk</a></li>
					<li><a href="#flikar-preferenser">Preferenser</a></li>
				</ul>
			</div> <!-- fliklista -->

EOT;
	}

	private function terminera_uppmärkning(): void {
		echo <<< EOT
			<div id="testyta"></div>
		</div> <!-- flikar -->
	</div> <!-- kotoba -->
	<script src="/js/kotoba.js?{$this->eka(VERSIONSDATUM)}"></script>
	<script src="/js/kotoba-testa.js?{$this->eka(VERSIONSDATUM)}"></script>
</body>
</html>

EOT;
	}

}
