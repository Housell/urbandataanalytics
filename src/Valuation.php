<?php

namespace UrbanDataAnalytics;

class Valuation
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
    public $id;
    public $forecast;
}