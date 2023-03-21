<?php

// Správce uživatelů redakčního systému
class SpravceUzivatelu
{

    /**
	 * Vrátí otisk hesla
	 * @param string $heslo Heslo pro vypočítání otisku
	 * @return string Otisk hesla
	 */
    public function vratOtisk(string $heslo) : string
    {
        return password_hash($heslo, PASSWORD_DEFAULT);
    }

    /**
	 * Registruje nového uživatele do systému
	 * @param string $jmeno Přihlašovací jméno
	 * @param string $heslo Přihlašovací heslo
	 * @param string $hesloZnovu Zopakované heslo
	 * @param string $rok Zadaný rok do antispamového pole
	 * @return void
	 */
    public function registruj(string $jmeno, string $heslo, string $hesloZnovu, string $rok) : void
    {
        if ($rok != date('Y'))
            throw new ChybaUzivatele('Chybně vyplněný antispam.');
        if ($heslo != $hesloZnovu)
            throw new ChybaUzivatele('Hesla nesouhlasí.');
        $uzivatel = array(
            'jmeno' => $jmeno,
            'heslo' => $this->vratOtisk($heslo),
        );
        try
        {
            Db::vloz('uzivatele', $uzivatel);
        }
        catch (PDOException $chyba)
        {
            throw new ChybaUzivatele('Uživatel s tímto jménem je již zaregistrovaný.');
        }
    }

    /**
	 * Přihlásí uživatele do systému
	 * @param string $jmeno Přihlašovací jméno
	 * @param string $heslo Přihlašovací heslo
	 * @return void
	 */
    public function prihlas(string $jmeno, string $heslo) : void
    {
        $uzivatel = Db::dotazJeden('
            SELECT uzivatele_id, jmeno, admin, heslo
            FROM uzivatele
            WHERE jmeno = ?
        ', array($jmeno));
        if (!$uzivatel || !password_verify($heslo, $uzivatel['heslo']))
            throw new ChybaUzivatele('Neplatné jméno nebo heslo.');
        $_SESSION['uzivatel'] = $uzivatel;
    }

    /**
	 * Odhlásí uživatele
	 * @return void
	 */
    public function odhlas() : void
    {
        unset($_SESSION['uzivatel']);
    }

    /**
	 * Vrátí aktuálně přihlášeného uživatele
	 * @return array|null Pole s informacemi o přihlášeném uživateli nebo NULL, pokud není žádný uživatel přihlášen
	 */
	public function vratUzivatele() : array|null
	{
        if (isset($_SESSION['uzivatel']))
            return $_SESSION['uzivatel'];
        return null;
    }

}