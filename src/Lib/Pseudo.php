<?php

namespace Application\Lib;

use Application\Models\UserRepository;

class Pseudo
{
    //region Functions

    static public function checkPseudo(string $pseudo) : string
    {
        $userRepository = new UserRepository();

        if($userRepository->exists($pseudo, 'pseudo')){
            return 'Ce pseudo existe déjà';
        }

        if(trim($pseudo) === ""){
            return 'Le champ pseudo doit être complété';
        }

        return '';
    }

    //endregion
}