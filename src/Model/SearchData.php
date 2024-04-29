<?php

namespace App\Model;

use App\Entity\Ambiance;
use App\Entity\Category;
use App\Entity\SpecialRegime;
use DateTime;

class SearchData
{
    /**
     * @var datetime
     */
    public DateTime $open_hours;

    /**
     * @var datetime
     */
    public DateTime $close_hours;

    /**
     * @var Category[]
     */
    public array $category = [];

    /**
     * @var Ambiance[]
     */
    public array $ambiance_event = [];

    /**
     * @var SpecialRegime[]
     */
    public array $specialRegime_event = [];
}
