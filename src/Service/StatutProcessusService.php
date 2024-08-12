<?php

namespace App\Service;

use App\Repository\TacheRepository;

class StatutProcessusService
{
    private $roleRepository;

    public function __construct(TacheRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getStatutProcessus(): string
    {
        $rolesIncomplets = $this->roleRepository->findIncompleteRoles();
        return count($rolesIncomplets) === 0 ? 'complet' : 'incomplet';
    }

}