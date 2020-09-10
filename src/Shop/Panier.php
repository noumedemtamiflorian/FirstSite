<?php


namespace App\Shop;

use App\Framework\Database\NoRecordException;
use App\Framework\Session\SessionInterface;
use App\Shop\Table\ProductTable;

class Panier
{
    public int $total = 0;
    public int $taille = 0;

    /**
     * @var SessionInterface
     */
    private SessionInterface $session;
    /**
     * @var ProductTable
     */
    private ProductTable $productTable;

    public function __construct(SessionInterface $session, ProductTable $productTable)
    {
        $this->productTable = $productTable;
        $this->session = $session;
        if ($this->session["panier"] == null) {
            $this->session["panier"] = [];
        }
    }


    /**
     * Permet d'enregistrer un produit
     * @param int $id
     * @return null
     */
    public function addProduct(int $id)
    {
        try {
            $reponse = $this->productTable->find($id);
        } catch (NoRecordException $e) {
            return null;
        }
        $panier = $this->session["panier"];
        $somme = isset($panier["id$id"]) ? $panier["id$id"] : 0;
        $somme += 1;
        $this->session["panier"] = array_merge($panier, ["id$id" => $somme]);
    }


    /**
     * Permet de supprimer un produit du panier
     * @param int $id
     * @return null
     */
    public function deleteProduct(int $id)
    {
        try {
            $reponse = $this->productTable->find($id);
        } catch (NoRecordException $e) {
            return null;
        }
        $panier = $this->session["panier"];
        $somme = isset($panier["id$id"]) ? $panier["id$id"] : 0;
        $somme -= 1;
        $this->session["panier"] = array_merge($panier, ["id$id" => (($somme < 0) ? 0 : $somme)]);
    }

    /**
     * Calcule la taille du panier
     * @return int
     */
    public function getTaille()
    {
        $panier = $this->session['panier'];
        $somme = 0;
        foreach ($panier as $key => $value) {
            $somme += $value;
        }
        return $somme;
    }

    /**
     * @return float|int
     */
    public function getSomme()
    {
        if ($this->getTaille() == 0) {
            return 0;
        }
        $panier = $this->session['panier'];
        $somme = 0;
        foreach ($panier as $key => $value) {
            $key = str_replace("id", "", $key);
            try {
                $price = $this->productTable->find($key)->price;
            } catch (NoRecordException $e) {
                return 0;
            }
            $somme += $price * $value;
        }
        return $somme;
    }
}
