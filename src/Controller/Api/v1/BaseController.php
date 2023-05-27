<?php

namespace App\Controller\Api\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public const DEFAULT_NUMBER_PAGE = 1;
    public const DEFAULT_COUNT_IN_PAGE = 10;
}
