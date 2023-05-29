<?php

namespace CODOF\Search;
/**
 * We can use Search.php directly but it would be better to create an interface to it
 * so that we can simply replace the implementation with a new one without changing a lot of things
 *
 * This acts like an adapter to use our SearchDTO with our existing code in Search.php i.e Adapter pattern
 */
class SearchAdapter
{
    public $_search;

    public function search(SearchDTO $searchDTO)
    {
        $search = new Search();
        $search->str = $searchDTO->query;
        $search->num_results = $searchDTO->numRecords;
        $search->from = $searchDTO->from;
        $search->count_rows = $searchDTO->shouldCountRecords;

        $search->cats = count($searchDTO->categoryIds) == 0 ? null : $searchDTO->categoryIds;
        $search->match_titles = $searchDTO->shouldMatchTitles;
        $search->order = $searchDTO->orderBy == 'ASC' ? 'Asc' : 'Desc';
        $search->sort = $searchDTO->sortOn;
        $search->time_within = $searchDTO->timeWithin;

        if (count($searchDTO->categoryIds) > 0) {
            $search->cats = $searchDTO->categoryIds;
        }

        $this->_search = $search;
        return $search->search();
    }

    public function getTotalCount()
    {
        return $this->_search->get_total_count();
    }
}