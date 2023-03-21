<?php

/**
 * Kontroler pro zpracování stránky s kontaktním formulářem
 */
class KontaktKontroler extends Kontroler //dědí z třídy Kontroler
{
    public function zpracuj(array $parametry): void //implementuje metodu zpracuj()
    {
        $this->hlavicka = array( //nastavíme hlavičku
            'titulek' => 'Kontaktní formulář',
            'klicova_slova' => 'kontakt, email, formulář',
            'popis' => 'Kontaktní formulář našeho webu.'
        );

        if ($_POST) {
            try {
                $odesilacEmailu = new OdesilacEmailu();
                $odesilacEmailu->odesliSAntispamem($_POST['rok'], "marikovamon@seznam.cz", "Email z webu", $_POST['zprava'], $_POST['email']);
                $this->pridejZpravu('Email byl úspěšně odeslán.');
                $this->presmeruj('kontakt');
            } catch (ChybaUzivatele $chyba) {
                $this->pridejZpravu($chyba->getMessage());
            }
        }

        $this->pohled = 'kontakt'; //nastavíme pohled na "kontakt"

    }
}
