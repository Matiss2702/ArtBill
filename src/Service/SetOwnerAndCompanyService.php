<?php

namespace App\Service;

class SetOwnerAndCompanyService
{
    public function process($entity, $user)
    {
        $entity->setOwner($user);
        $company = $user->getCompany();
        $entity->setCompany($company);
    }
}
