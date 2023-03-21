<?php

/**
 * Výchozí kontroler pro MVC redakční systém
 */
abstract class Kontroler
{

    /**
     * @var array Pole, jehož indexy jsou poté viditelné v šabloně jako běžné proměnné
     */
    protected array $data = array();
    /**
     * @var string Název šablony bez přípony
     */
    protected string $pohled = "";
    /**
     * @var array|string[] Hlavička HTML stránky
     */
    protected array $hlavicka = array('titulek' => '', 'klicova_slova' => '', 'popis' => '');


    /**
     * Hlavní metoda controlleru
     * @param array $parametry Pole parametrů pro využití kontrolerem
     * @return void
     */
    abstract function zpracuj(array $parametry): void;

    /**
     * Vyrenderuje pohled
     * @return void
     */
    public function vypisPohled(): void
    {
        if ($this->pohled) {
            extract($this->osetri($this->data)); //ošetří html entity
            extract($this->data, EXTR_PREFIX_ALL, ""); //zadám-li proměnnou s podtržítkem, html kód se zpracuje
            require("pohledy/" . $this->pohled . ".phtml");
        }
    }

    /**
     * Přesměruje na dané URL
     * @param string $url URL adresa, na kterou přesměrovat
     * @return never
     */
    public function presmeruj(string $url): never
    {
        header("Location: /$url");
        header("Connection: close");
        exit;
    }

    /**
     * Ošetří proměnnou pro výpis do HTML stránky
     * @param mixed $x Proměnná k ošetření
     * @return mixed Proměnná ošetřená proti XSS útoku
     */
    private function osetri($x = null)
    {
        if (!isset($x))
            return null;
        elseif (is_string($x))
            return htmlspecialchars($x, ENT_QUOTES);
        elseif (is_array($x)) {
            foreach ($x as $k => $v) {
                $x[$k] = $this->osetri($v);
            }
            return $x;
        } else
            return $x;
    }

    /**
     * Přidá zprávu pro uživatele
     * @param string $zprava Hláška k zobrazení
     * @return void
     */
    public function pridejZpravu(string $zprava): void
    {
        if (isset($_SESSION['zpravy'])) //zkontrolujeme, jestli existuje daná session s polem zpráv
            $_SESSION['zpravy'][] = $zprava; //pokud ano, přidáme do tohoto pole další zprávu
        else
            $_SESSION['zpravy'] = array($zprava); //pokud ne, vytvoří v session pole s touto jednou zprávou
    }

    /**
     * Vrátí zprávy pro uživatele
     * @return array Všechny uložené hlášky k zobrazení
     */
    public function vratZpravy(): array
    {
        if (isset($_SESSION['zpravy'])) {
            $zpravy = $_SESSION['zpravy'];
            unset($_SESSION['zpravy']); //po navrácení zprávy session vymažeme, aby se nezobrazovaly stále znovu
            return $zpravy; //vrátí pole zpráv z session pokud existuje
        } else
            return array(); //pokud neexistuje, vrátí prázdné pole
    }

    /**
	 * Ověří, zda je přihlášený uživatel, případně přesměruje na login
	 * @param bool $admin TRUE, pokud musí být přihlášený uživatel i administrátorem
	 * @return void
	 */
    public function overUzivatele(bool $admin = false): void
    {
        $spravceUzivatelu = new SpravceUzivatelu();
        $uzivatel = $spravceUzivatelu->vratUzivatele();
        if (!$uzivatel || ($admin && !$uzivatel['admin'])) {
            $this->pridejZpravu('Nedostatečná oprávnění.');
            $this->presmeruj('prihlaseni');
        }
    }
}
