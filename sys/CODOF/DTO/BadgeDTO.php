<?php


namespace CODOF\DTO;


class BadgeDTO
{
    public /*int*/ $id;
    public /*string*/ $name;
    public /*string*/ $description;
    public /*string*/ $badgeLocation;
    public /*int*/ $order; // Used only in admin because in UI its ordered by query

    public function __construct(int $id, string $name, string $description, string $badgeLocation)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->badgeLocation = $badgeLocation;
    }

}