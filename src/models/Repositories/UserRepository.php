<?php

namespace App\Models\Repositories;

use App\Models\Abstract\AbstractManager\AbstractEntityManager;
use App\Models\Entities\User;

/**
 * Classe UserManager pour gérer les requêtes liées aux users et à l'authentification.
 */

class UserRepository extends AbstractEntityManager
{
    /**
     * Récupère un user par son login.
     * @param string $login
     * @return ?User
     */
    public function getUserByLogin(string $login) : ?User
    {
        $sql = "SELECT * FROM user WHERE login = :login";
        $result = $this->db->query($sql, ['login' => $login]);
        $user = $result->fetch();
        if ($user) {
            return new User($user);
        }
        return null;
    }
}
