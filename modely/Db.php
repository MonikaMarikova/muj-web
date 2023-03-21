<?php

/**
 * Wrapper pro snadnější práci s databází s použitím PDO a automatickým
 * zabezpečením parametrů (proměnných) v dotazech.
 */
class Db
{
    /**
     * @var PDO Databázové spojení
     */
    private static PDO $spojeni; //sdílení instance spojení

    /**
     * @var array Výchozí nastavení ovladače
     */
    private static array $nastaveni = array( //klíče asociativního pole jsou konstanty z třídy PDO
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //způsob reakce na databázové chyby
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", //inicializační příkaz - nastaví kódování, aby fungovala diakritika
        PDO::ATTR_EMULATE_PREPARES => false, //přenechá vkládání parametrů do dotazu na databázi
    );


    /**
     * Připojí se k databázi pomocí daných údajů
     * @param string $host Hostitel databáze
     * @param string $uzivatel Přihlašovací jméno
     * @param string $heslo Přihlašovací heslo
     * @param string $databaze Název databáze
     * @return void
     */
    public static function pripoj(string $host, string $uzivatel, string $heslo, string $databaze): void
    {
        if (!isset(self::$spojeni)) {
            self::$spojeni = @new PDO( //@ před výrazem new PDO() slouží k potlačení chyby, když používám @ měla bych mít připravený vlastní mechanismus, který mě o chybě informuje
                "mysql:host=$host;dbname=$databaze",
                $uzivatel,
                $heslo,
                self::$nastaveni
            );
        }
    }
    // - uloží instanci PDO s klasickými parametry pro připojení k db do statické proměnné $spojeni
    // - máme ošetřené, aby se db nepokoušela znovu připojit, když již spojení existuje

    /**
     * Spustí dotaz a vrátí z něj první řádek
     * @param string $dotaz SQL dotaz s parametry nahrazenými otazníky
     * @param array $parametry Parametry pro doplnění do připraveného SQL dotazu
     * @return array Asociativní pole s informacemi z prvního řádku výsledku
     */
    public static function dotazJeden(string $dotaz, array $parametry = array()): array|bool
    {
        $navrat = self::$spojeni->prepare($dotaz); //na instanci spojení zavoláme metodu prepare(do které se vloží text dotazu se zástupnými znaky a dotaz se připraví)
        $navrat->execute($parametry); //execute() k  dotazu připojí pole parametrů a dotaz provede
        return $navrat->fetch(); //získáme 1. řádek metodou fetch() jako asociativní pole, které vrátíme
    }

    /**
     * Spustí dotaz a vrátí všechny jeho řádky
     * @param string $dotaz SQL dotaz s parametry nahrazenými otazníky
     * @param array $parametry Parametry pro doplnění do připraveného SQL dotazu
     * @return array Pole asociativních pole s informacemi o všech řádcích výsledku
     */
    public static function dotazVsechny(string $dotaz, array $parametry = array()): array|bool
    {
        $navrat = self::$spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->fetchAll();
    }
    //dotaz na více řádků - vrátí nám pole řádků, které odpovídají dotazu (použijeme např. pro výpis komentářů pod článek)

    /**
     * Spustí dotaz a vrátí z něj první sloupec prvního řádku
     * @param string $dotaz SQL dotaz s parametry nahrazenými otazníky
     * @param array $parametry Parametry pro doplnění do připraveného SQL dotazu
     * @return string|null Hodnota v prvním sloupci prvního řádku výsledku
     */
    public static function dotazSamotny(string $dotaz, array $parametry = array()): string
    {
        $vysledek = self::dotazJeden($dotaz, $parametry);
        return $vysledek[0];
    }
    //podobná metodě dotazJeden(), takto si ušetříme několik řádků, ve složitější aplikaci nám to přijde vhod

    /**
     * Spustí dotaz a vrátí počet ovlivněných řádků
     * @param string $dotaz SQL dotaz s parametry nahrazenými otazníky
     * @param array $parametry Parametry pro doplnění do připraveného SQL dotazu
     * @return int Počet ovlivněných řádků
     */
    public static function dotaz(string $dotaz, array $parametry = array()): int
    {
        $navrat = self::$spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->rowCount();
    }

    /**
	 * Vloží do tabulky nový řádek jako data z asociativního pole
	 * @param string $tabulka Název databázové tabulky
	 * @param array $parametry Asociativní pole parametrů pro vložení
	 * @return bool TRUE v případě úspěšného provedení dotazu
	 */
    public static function vloz(string $tabulka, array $parametry = array()): bool
    {
        return self::dotaz( //k vykonaní dotazu využijeme metodu dotaz(), která nám vrátí i počet ovlivněných řádků
            "INSERT INTO `$tabulka` (`" .
                implode('`, `', array_keys($parametry)) .
                "`) VALUES (" . str_repeat('?,', sizeOf($parametry) - 1) . "?)",
            array_values($parametry)
        );
    }

    /**
	 * Změní řádek v tabulce tak, aby obsahoval data z asociativního pole
	 * @param string $tabulka Název databázové tabulky
	 * @param array $hodnoty Asociativní pole hodnot ke změně
	 * @param $podminka Podmínka pro ovlivňované záznamy ("WHERE ...")
	 * @param array $parametry Asociativní pole dalších parametrů
	 * @return bool TRUE v případě úspěšného provedení dotazu
	 */
    public static function zmen(string $tabulka, array $hodnoty = array(), string $podminka, array $parametry = array()): bool
    {
        return self::dotaz(
            "UPDATE `$tabulka` SET `" .
                implode('` = ?, `', array_keys($hodnoty)) .
                "` = ? " . $podminka,
            array_merge(array_values($hodnoty), $parametry)
        );
    }

    /**
     * Vrátí ID posledně vloženého záznamu (NE změněného nebo odstraněného)
     * @return int ID posledního vloženého záznamu
	 */
    public static function posledniId(): int
    {
        return self::$spojeni->lastInsertId();
    }
}
