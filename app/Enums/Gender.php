<?php

namespace  App\Enums ;

enum  Gender:string
{
  case Male= 'Male';
  case Female = 'Female' ;
  case Autre = 'Autre';
     public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}