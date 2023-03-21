<?php

class EditorKontroler extends Kontroler
{
    public function zpracuj(array $parametry) : void
    {
        // Editor smí používat jen administrátoři
        $this->overUzivatele(true);
        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Editor článků';
        // Vytvoření instance modelu
        $spravceClanku = new SpravceClanku();
        // Příprava prázdného článku
        $clanek = array(
            'clanky_id' => '',
            'titulek' => '',
            'obsah' => '',
            'url' => '',
            'popisek' => '',
            'klicova_slova' => '',
        );
        // Je odeslán formulář
        if ($_POST)
        {
            // Získání článku z $_POST
            $klice = array('titulek', 'obsah', 'url', 'popisek', 'klicova_slova');
            $clanek = array_intersect_key($_POST, array_flip($klice));//ořízneme pole jen na ty klíče, které nás zajímají - obrana před útokem mass assignment
            // Převedení titulku na url adresu
            $prevodni_tabulka = [
                'ä' => 'a', 'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'č' => 'c', 'ć' => 'c', 'ď' => 'd',
                'ě' => 'e', 'é' => 'e', 'ë' => 'e', 'è' => 'e', 'ê' => 'e', 'í' => 'i', 'ï' => 'i', 'ì' => 'i', 'î' => 'i', 'ľ' => 'l',
                'ĺ' => 'l', 'ń' => 'n', 'ň' => 'n', 'ñ' => 'n', 'ó' => 'o', 'ö' => 'o', 'ô' => 'o', 'ò' => 'o', 'õ' => 'o', 'ő' => 'o',
                'ř' => 'r', 'ŕ' => 'r', 'š' => 's', 'ś' => 's', 'ť' => 't', 'ú' => 'u', 'ů' => 'u', 'ü' => 'u', 'ù' => 'u', 'ũ' => 'u',
                'û' => 'u', 'ý' => 'y', 'ž' => 'z', 'ź' => 'z'
            ];
            $url = trim($clanek['titulek']);
            $url = mb_strtolower($url);
            $url = strtr($url, $prevodni_tabulka);
            $url = str_replace(" ", "-", $url);
            $clanek['url'] = $url;
            // Uložení článku do DB
            $spravceClanku->ulozClanek($_POST['clanky_id'], $clanek);
            $this->pridejZpravu('Článek byl úspěšně uložen.');
            $this->presmeruj('clanek/' . $clanek['url']);
        }
        // Je zadané URL článku k editaci
        else if (!empty($parametry[0]))
        {
            $nactenyClanek = $spravceClanku->vratClanek($parametry[0]);
            if ($nactenyClanek)
                $clanek = $nactenyClanek;
            else
                $this->pridejZpravu('Článek nebyl nalezen');
        }

        $this->data['clanek'] = $clanek;
        $this->pohled = 'editor';
    }
}