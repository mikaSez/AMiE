<?php
namespace AMiE\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AMiEUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
