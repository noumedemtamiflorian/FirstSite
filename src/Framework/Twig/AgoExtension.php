<?php


namespace App\Framework\Twig;

use App\Blog\Entity\Post;
use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AgoExtension extends AbstractExtension
{
   
    public function getFunctions()
    {
        return [
            new  TwigFunction('Ago', [$this,'ago'])
        ];
    }
    public function ago(DateTime $createdAt)
    {
        $diff = $createdAt->diff(new DateTime());
        $result = null;
        if ($diff->y !=0) {
            if ($diff->y == 1) {
                $result .= " $diff->y annee " ;
            } else {
                $result .= " $diff->y annees " ;
            }
        }
        if ($diff->m !=0) {
            $result .= ($result == null) ? "": "," ;
            $result .= " $diff->m mois " ;
        }
        if ($diff->d !=0) {
            $result .= ($result == null) ? "": "," ;
            if ($diff->d == 1) {
                $result .= " $diff->d jour " ;
            } else {
                $result .= " $diff->d jours " ;
            }
        }
        if ($diff->h !=0) {
            $result .= ($result == null) ? "": "," ;
            if ($diff->h == 1) {
                $result .= " $diff->h heure" ;
            } else {
                $result .= " $diff->h heures " ;
            }
        }
        if ($diff->i !=0) {
            $result .= ($result == null) ? "": "," ;
            if ($diff->i == 1) {
                $result .= " $diff->i minute " ;
            } else {
                $result .= " $diff->i minutes " ;
            }
        }
        if ($diff->s !=0) {
            if ($diff->s == 1) {
                $result .= " et $diff->s seconde " ;
            }
            $result .= " et $diff->s secondes " ;
        }
        return "Cree il'y  $result ";
    }
}
