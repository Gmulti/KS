<?php

namespace KS\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KSUserBundle extends Bundle
{ 
	public function getParent()
    {
        return 'FOSUserBundle';
    }

}
