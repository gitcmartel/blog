<?php

namespace Application\Models;

enum UserFunction {
    case Administrator;
    case Creator;
    case Reader;
    case Else;

    public function toString() : string
    {
        return match($this){
            self::Administrator => 'Administrateur', 
            self::Creator => 'Createur', 
            self::Reader => 'Lecteur',
            self::Else => ''
        };
    }
}