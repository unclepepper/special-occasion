<?php

declare(strict_types = 1);

namespace App\Domain\Users\Type;



use App\Domain\Common\UuidType\Uid;


class UserUid extends Uid
{
    public const TYPE = 'user_id';

}