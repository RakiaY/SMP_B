<?php

namespace  App\Enums ;

enum  UserStatut: string
{
  case Active= 'Active';
  case Inactive = 'Inactive' ;
  case Pending = 'Pending';
  case Suspended = 'Suspended';
  case Blocked= 'Blocked';
  case Deleted = 'Deleted';
}

