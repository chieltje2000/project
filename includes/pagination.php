<?php

// This is a helper class to make pagination
// records easy
class Pagination
{
    public $currentPage;
    public $perPage;
    public $totalCount;

    public function __construct($page = 1, $perPage = 20, $totalCount = 0)
    {
        $this->currentPage = (int)$page;
        $this->perPage = (int)$perPage;
        $this->totalCount = (int)$totalCount;
    }

    public function offset()
    {
        // Assuming 20 items per page:
        // page 1 has an offset of 0 (1-1)*20
        // page 2 has an offset of 1 (2-1)*20
        // in other words page 2 starts with item 21
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function totalPages()
    {
        return ceil($this->totalCount / $this->perPage);
    }

    public function previousPage()
    {
        return $this->currentPage - 1;
    }

    public function nextPage()
    {
        return $this->currentPage + 1;
    }

    public function hasPreviousPage()
    {
        return $this->previousPage() >= 1 ? true : false;
    }

    public function hasNextPage()
    {
        return $this->nextPage() <= $this->totalPages() ? true : false;
    }


}