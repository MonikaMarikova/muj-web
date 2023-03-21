<?php

class DovednostiKontroler extends Kontroler
{
    public function zpracuj(array $parametry): void
    {
        $this->hlavicka = array(
            'titulek' => 'Dovednosti',
            'klicova_slova' => 'dovednosti, Monika, Mariková',
            'popis' => 'Přehled mých dovedností.'
        );

        $this->pohled = 'dovednosti';
    }
}