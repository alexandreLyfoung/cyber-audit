<?php

namespace App\Services;

use App\Entity\Contribution;

class StatsService
{
    public function getTotalCalculated(array $contributions, string $year = null){


        $filtered = array_filter($contributions, function(Contribution $ctb) use ($year){
            return $ctb->getYear() === $year;
        });

       return array_reduce($filtered,function($carry,Contribution $ctb){
           $carry += $ctb->getAmount();
           return $carry;
       },0);
    }

    public function getTotalPaid(array $contributions, string $year = null){
        $filtered = array_filter($contributions, function(Contribution $ctb) use ($year){
            return $ctb->getPayment();
        });

        return array_reduce($filtered,function($carry,Contribution $ctb){
            $carry += $ctb->getAmount();
            return $carry;
        },0);
    }
}
