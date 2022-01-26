<?php

namespace UrbanDataAnalytics;

class Valuation extends Model
{
    /**
     * @var Competitor[]
     */
    public $competitors;

    /**
     * @var Indicator[]
     */
    public $indicators;

    /**
     * @var Asset
     */
    public $attributes;
    public $best_score;

    /* @var int */
    public $id;
    public $forecast;
}