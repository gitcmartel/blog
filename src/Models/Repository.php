<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;

abstract class Repository
{
    //region Properties
    protected readonly DatabaseConnexion $connexion;
    //endregion

    //region Constructor
    public function __construct()
    {
        $this->connexion = new DatabaseConnexion();
    }

    //endregion

    //region Functions

    /**
     * Checks if the ids exist in the database
     * Returns a boolean
     * @param array $ids
     * @param string $tableName
     * @param string $idFieldName
     * @return bool
     */
    public function checkIds(array $ids, string $tableName, string $idFieldName): bool
    {

        $idsString = implode(',', $ids);

        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT count(*) as count FROM $tableName WHERE $idFieldName IN ($idsString);"
        );

        $statement->execute();

        $row = $statement->fetch();

        return ($row['count'] === count($ids));
    }
    //endregion
}
