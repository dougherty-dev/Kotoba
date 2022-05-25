<?php declare(strict_types = 1);

final class 言葉 {
	public Databas $db;
	/** @var array<int|string, string[]> $röster */ public array $röster = [];
	public ?int $språkid, $bokid, $kapitelid;
	public string $språknamn, $lokalspråk, $romanisering, $röst, $boknamn, $kapitelnamn;

	public function __construct() {
		$this->initiera();
		$this->cron();
	}

	public function initiera(): void {
		$this->db = new Databas;

		$this->språkid = (int) $this->db->hämta_preferens('språkid');
		$this->bokid = (int) $this->db->hämta_preferens('bokid');
		$this->kapitelid = (int) $this->db->hämta_preferens('kapitelid');
		$this->språknamn = $this->db->hämta_preferens('språknamn');
		$this->lokalspråk = $this->db->hämta_preferens('lokalspråk');
		$this->romanisering = $this->db->hämta_preferens('romanisering');
		$this->röst = $this->db->hämta_preferens('röst');
		$this->boknamn = $this->db->hämta_preferens('boknamn');
		$this->kapitelnamn = $this->db->hämta_preferens('kapitelnamn');
	}

	public function cron(): void {
		if (time() - (int) $this->db->hämta_preferens('rösttid') > EN_VECKA) {
			$this->hämta_röster();
			$this->db->spara_backup();
			$this->db->dammsug();
		} else {
			$this->röster = [];
			$sats = $this->db->instans->query("SELECT * FROM `språkkoder` NATURAL JOIN `röster` ORDER BY `kodid`, `röstid` ASC");
			if ($sats !== FALSE) foreach($sats->fetchAll(PDO::FETCH_ASSOC) as $r) {
				$this->röster[$r['språkkod']][] = $r['röst'];
			}
		}
	}

	public function uppdatera_aktuell(): void {
		$this->db->spara_preferens('språkid', (string) $this->språkid);
		$this->db->spara_preferens('bokid', (string) $this->bokid);
		$this->db->spara_preferens('kapitelid', (string) $this->kapitelid);
		$this->db->spara_preferens('språknamn', $this->språknamn);
		$this->db->spara_preferens('lokalspråk', $this->lokalspråk);
		$this->db->spara_preferens('romanisering', $this->romanisering);
		$this->db->spara_preferens('röst', $this->röst);
		$this->db->spara_preferens('boknamn', $this->boknamn);
		$this->db->spara_preferens('kapitelnamn', $this->kapitelnamn);
	}

	public function hämta_röster(): void {
		$this->röster = [];
		exec("/usr/bin/say -v '?' | awk '{print $1,$2}'", $röster);
		foreach ($röster as $i => $röst) {
			if (str_contains($röst, '_')) {
				[$röstnamn, $språkkod] = explode(' ', $röst);
				$this->röster[$språkkod][] = $röstnamn;
			}
		}
		ksort($this->röster);

		$sats = $this->db->instans->query("DELETE FROM `språkkoder`");
		$sats = $this->db->instans->query("DELETE FROM `röster`");
		$kodid= 1;
		foreach ($this->röster as $språkkod => $röster) {
			$sats = $this->db->instans->prepare("REPLACE INTO `språkkoder` (`språkkod`) VALUES (:sprakkod)");
			$sats->bindValue(':sprakkod', $språkkod, PDO::PARAM_STR);
			$sats->execute();
			foreach ($röster as $r => $röst) {
				$sats = $this->db->instans->prepare("REPLACE INTO `röster` (`röstid`, `kodid`, `röst`) VALUES (:rostid, :kodid, :rost)");
				$sats->bindValue(':rostid', $r + 1, PDO::PARAM_INT);
				$sats->bindValue(':kodid', $kodid, PDO::PARAM_INT);
				$sats->bindValue(':rost', $röst, PDO::PARAM_STR);
				$sats->execute();
			}
			$kodid++;
		}

		$this->db->spara_preferens('rösttid', (string) time());
	}

	public function finn_språk(): void {
		$this->språkid = NULL;
		$this->språknamn = '';
		$this->lokalspråk = '';
		$this->romanisering = '';
		$this->röst = '';

		$sats = $this->db->instans->prepare("SELECT * FROM `språk` ORDER BY `språkid` ASC LIMIT 1");
		$sats->bindColumn('språkid', $this->språkid, PDO::PARAM_INT);
		$sats->bindColumn('språknamn', $this->språknamn, PDO::PARAM_STR);
		$sats->bindColumn('lokalspråk', $this->lokalspråk, PDO::PARAM_STR);
		$sats->bindColumn('romanisering', $this->romanisering, PDO::PARAM_STR);
		$sats->bindColumn('röst', $this->röst, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);

		$this->finn_bok();
	}

	public function finn_bok(): void {
		$this->bokid = NULL;
		$this->boknamn = '';

		$sats = $this->db->instans->prepare("SELECT * FROM `böcker` WHERE `språkid`=:sprakid ORDER BY `bokid` ASC LIMIT 1");
		$sats->bindValue(':sprakid', $this->språkid, PDO::PARAM_INT);
		$sats->bindColumn('bokid', $this->bokid, PDO::PARAM_INT);
		$sats->bindColumn('boknamn', $this->boknamn, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);

		$this->finn_kapitel();
	}

	public function finn_kapitel(): void {
		$this->kapitelid = NULL;
		$this->kapitelnamn = '';

		$sats = $this->db->instans->prepare("SELECT * FROM `kapitel` WHERE `språkid`=:sprakid AND `bokid`=:bokid ORDER BY `bokid` ASC LIMIT 1");
		$sats->bindValue(':sprakid', $this->språkid, PDO::PARAM_INT);
		$sats->bindValue(':bokid', $this->bokid, PDO::PARAM_INT);
		$sats->bindColumn('kapitelid', $this->kapitelid, PDO::PARAM_INT);
		$sats->bindColumn('kapitelnamn', $this->kapitelnamn, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);

		$this->uppdatera_aktuell();
	}

	public function rullgardin_språk(): string {
		$språktext = $boktext = '';
		$sats = $this->db->instans->prepare("SELECT * FROM `språk`");
		$sats->execute();
		$sats->bindColumn('språkid', $språkid, PDO::PARAM_INT);
		$sats->bindColumn('språknamn', $språknamn, PDO::PARAM_STR);
		$sats->bindColumn('lokalspråk', $lokalspråk, PDO::PARAM_STR);
		$sats->bindColumn('romanisering', $romanisering, PDO::PARAM_STR);
		$sats->bindColumn('röst', $röst, PDO::PARAM_STR);
		while ($sats->fetch(PDO::FETCH_OBJ)) {
			$vald = ($this->språkid === $språkid) ? ' selected="selected"' : '';
			$språktext .= <<< EOT
					<option$vald value="$språkid">$språknamn ($lokalspråk)</option>

EOT;
		}

		return <<< EOT
				<p><select class="välj_språk_språkid">
$språktext				</select></p>
EOT;
	}

	public function rullgardin_böcker(): string {
		$boktext = '';
		$sats = $this->db->instans->prepare("SELECT `bokid`, `boknamn` FROM `böcker` WHERE `språkid`=:sprakid");
		$sats->bindValue(':sprakid', $this->språkid, PDO::PARAM_INT);
		$sats->bindColumn('bokid', $bokid, PDO::PARAM_INT);
		$sats->bindColumn('boknamn', $boknamn, PDO::PARAM_STR);
		$sats->execute();
		while ($sats->fetch(PDO::FETCH_OBJ)) {
			$vald = ($this->bokid === $bokid) ? ' selected="selected"' : '';
			$boktext .= <<< EOT
					<option$vald value="$bokid">$boknamn</option>

EOT;
		}

		return <<< EOT
				<p><select class="välj_bok_bokid">
$boktext				</select></p>
EOT;
	}

	public function rullgardin_kapitel(): string {
		$kapiteltext = '';
		$sats = $this->db->instans->prepare("SELECT `kapitelid`, `kapitelnamn` FROM `kapitel` WHERE `bokid`=:bokid");
		$sats->bindValue(':bokid', $this->bokid, PDO::PARAM_INT);
		$sats->bindColumn('kapitelid', $kapitelid, PDO::PARAM_INT);
		$sats->bindColumn('kapitelnamn', $kapitelnamn, PDO::PARAM_STR);
		$sats->execute();
		while ($sats->fetch(PDO::FETCH_OBJ)) {
			$vald = ($this->kapitelid === $kapitelid) ? ' selected="selected"' : '';
			$kapiteltext .= <<< EOT
					<option$vald value="$kapitelid">$kapitelnamn</option>

EOT;
		}

		return <<< EOT
				<p><select class="välj_kapitel_kapitelid">
$kapiteltext				</select></p>
EOT;
	}
}
