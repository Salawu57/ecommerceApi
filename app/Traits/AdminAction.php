<?php

namespace App\Traits;

use App\Models\User;


trait AdminAction{


  public function before(User $user, string $ability): bool|null
  {
  if ($user->isAdmin()) {
    
      return true;
  }

  return null;
  }

}