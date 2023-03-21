<?php

/**
 * Třída poskytuje metody pro správu článků v redakčním systému
 */
class SpravceClanku
{

    /**
     * Vrátí článek z databáze podle jeho URL
     * @param string $url URL článku k zobrazení
     * @return array Data článku z databáze jako asociativní pole
     */
    public function vratClanek(string $url): array|bool //bool jsem připsala, protože v případě neexistující URL neproběhlo přesměrování na chybovou stránku, ale vyhodila se mi chybová hláška
    {
        return Db::dotazJeden('
            SELECT clanky_id, titulek, obsah, url, popisek, klicova_slova, datum_pridani
            FROM clanky
            WHERE url = ?
        ', array($url));
    }

    /**
     * Vrátí seznam článků v databázi
     * @return array Základní informace o všech článcích jako numerické pole asociativních polí
     */
    public function vratClanky(): array
    {
        return Db::dotazVsechny('
            SELECT clanky_id, titulek, url, popisek, datum_pridani
            FROM clanky
            ORDER BY clanky_id DESC
        ');
    }

    /**
	 * Uloží článek do systému. Pokud je ID false, vloží nový, jinak provede editaci.
	 * @param int|bool $id ID článku k editaci, FALSE pro vložení nového článku
	 * @param array $clanek Asociativní pole s informacemi o článku
	 * @return void
	 */
    public function ulozClanek(int|bool $id, array $clanek): void
    {
        if (!$id)
            Db::vloz('clanky', $clanek);
        else
            Db::zmen('clanky', $clanek, 'WHERE clanky_id = ?', array($id));
    }

    /**
     * Odstraní článek s danou URL adresou
     * @param string $url URL článku k odstranění
     */
    public function odstranClanek(string $url): void
    {
        Db::dotaz('
        DELETE FROM clanky
        WHERE url = ?
    ', array($url));
    }
}
