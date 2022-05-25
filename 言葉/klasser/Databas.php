<?php declare(strict_types = 1);

final class Databas {
	public PDO $instans;

	public function __construct() {
		$this->anslut();
	}

	public function anslut(): void {
		$this->instans = new PDO('sqlite:' . DB . '/kotoba.db');
		$pragma = 'PRAGMA temp_store = MEMORY; PRAGMA mmap_size = 1000000000; PRAGMA auto_vacuum = FULL';
		$this->instans->exec($pragma);
	}

	public function integritetskontroll(): string {
		$sats = $this->instans->prepare("PRAGMA integrity_check");
		$sats->execute();
		return (string) $sats->fetchColumn();
	}

	public function dammsug(): void {
		$this->instans->exec('VACUUM');
	}

	public function spara_backup(): void {
		$datum = date('Y-m-d');
		file_exists(BACKUP . "/$datum" . '.db') or copy(DB . '/kotoba.db', BACKUP . "/$datum" . '.db');
	}

	public function hämta_preferens(string $namn): string {
		$värde = '';
		$sats = $this->instans->prepare("SELECT `värde` FROM `preferenser` WHERE `namn`=:namn LIMIT 1");
		$sats->bindValue(':namn', $namn, PDO::PARAM_STR);
		$sats->bindColumn('värde', $värde, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);
		return $värde;
	}

	public function spara_preferens(string $namn, string $värde): void {
		$sats = $this->instans->prepare("REPLACE INTO `preferenser` (`namn`, `värde`) VALUES (:namn, :varde)");
		$sats->bindValue(':namn', $namn, PDO::PARAM_STR);
		$sats->bindValue(':varde', $värde, PDO::PARAM_STR);
		$sats->execute();
	}
}
