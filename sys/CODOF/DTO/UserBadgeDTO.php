<?php


namespace CODOF\DTO;


class UserBadgeDTO extends BadgeDTO
{
    public /*int*/
        $isRewarded = false;

    public function __construct(int $id, string $name, string $description, string $badgeLocation, bool $isRewarded)
    {
        parent::__construct($id, $name, $description, $badgeLocation);
        $this->isRewarded = $isRewarded;
    }

}