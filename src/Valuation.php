<?php

namespace UrbanDataAnalytics;

class Valuation extends Model
{
    /* @var Competitor[] */
    public $competitors;

    /* @var array */
    public $indicators;

    /* @var Asset */
    public $attributes;

    /* @var object */
    public $best_score;

    /* @var int */
    public $id;

    /* @var object */
    public $forecast;
}