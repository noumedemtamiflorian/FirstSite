<?php


namespace App\Shop\Entity;

class Product
{
    public $id;
    public $name;
    public $price;
    public $somme;
    public $quantite;

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @param mixed $quantite
     */
    public function setQuantite($quantite): void
    {
        $this->quantite = $quantite;
    }

    /**
     * @param mixed $somme
     */
    public function setSomme($somme): void
    {
        $this->somme = $somme;
    }
}
