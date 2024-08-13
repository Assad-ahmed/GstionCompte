<?php

namespace App\Service;

use App\Repository\TacheRepository;

class AlerteService
{
    private $roleRepository;

    public function __construct(TacheRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getIncompleteRoles()
    {
        return $this->roleRepository->findIncompleteRoles(); // Retourne les rôles non remplis avec les acteurs associés
    }

}