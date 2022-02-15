<?php

namespace UrbanDataAnalytics;

class Valuation extends Model
{
    /**
     * @var Competitor[]
     */
    public $competitors;

    /**
     * @var array
     */
    public $indicators;

    /**
     * @var Asset
     */
    public $attributes;

    /**
     * @var array
     */
    public $best_score;

    /* @var int */
    public $id;
    public $forecast;
}