<?php

namespace CODOF\Search;

class SearchDTO
{
    public $from;
    public $query;
    public $numRecords;

    public $categoryIds = [];

    public $shouldCountRecords = true;
    public $shouldMatchTitles = true;


    public $orderBy = 'DESC';
    public $sortOn = 'post_created';

    public $timeWithin = 'anytime'; // hour/day/week/month/year


    // Below setter functions will allow us to add security features later on if required
    // So always use setter to set values

    public function setQuery($query)
    {
        if (strlen($query) < 1)
            $this->query = "Search string must be atleast 1 character";
        else
            $this->query = $query;
    }

    public function setTimeWithin($timeWithin)
    {
        $this->timeWithin = in_array($timeWithin, ['anytime', 'hour', 'day', 'week', 'month', 'year']) ?
            $timeWithin : 'anytime';
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = in_array($orderBy, ['ASC', 'DESC']) ?
            $orderBy : 'DESC';
    }

    public function setSortOn($sortOn)
    {
        $this->sortOn = in_array($sortOn, ['post_created', 'last_post_time', 'no_posts', 'no_views']) ?
            $sortOn : 'DESC';
    }

    public function setCategoryIds($cids)
    {
        if ($cids == "") return;

        $cats = explode(",", $cids);
        foreach ($cats as $catId) {
            $this->categoryIds[] = (int)$catId;
        }
    }

}