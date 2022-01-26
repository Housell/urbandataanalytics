<?php

namespace UrbanDataAnalytics;

/**
 * <code>
 * ---------------------------------------------------------------------------------------------------------------------
 * | Key               | Type    | Description
 * ---------------------------------------------------------------------------------------------------------------------
 * | admin0            | String  | Country name
 * | admin3            | String  | Municipality name
 * | admin4            | String  | District name / code
 * | admin5            | String  | Neighborhood name / code
 * | construction_year | Integer | Construction year
 * | contact_name      | String  | Contact name
 * | days              | Integer | Difference between date_in and date_out
 * | distance          | Decimal | Distance (in meters) from the principal asset’s location (lat/lon specified in the request) to the competitor’s location (lat/lon especified in the response).
 * | images            | JSON    | Array of available images. Each element has a url attribute with the URL to be used to access the image, together with some metadata and a capture_date.
 * | is_stock          | Boolean | Whether this competitor was found in “stock market” or “closed market” portfolios.
 * | is_vpo            | Boolean | Whether this competitor belongs to a subsidized housing program.
 * | relevant          | Boolean | Whether this competitor has been flagged as “relevant”.
 * | rank              | Decimal | Similarity rank. Possible values: 1.0 (very similar), 0.5 (somewhat similar), 0.0 (not necessarily similar)
 * | source_url        | String  | URL of the original source this asset was extracted from.
 * ---------------------------------------------------------------------------------------------------------------------
 * </code>
 */
class Competitor extends Asset
{
    /* @var string */
    public $admin0;

    /* @var string */
    public $admin3;

    /* @var string */
    public $admin4;

    /* @var string */
    public $admin5;

    /* @var integer */
    public $construction_year;

    /* @var string */
    public $contact_name;

    /* @var integer */
    public $days;

    /* @var float */
    public $distance;

    /* @var array */
    public $images;

    /* @var Boolean */
    public $is_stock;

    /* @var Boolean */
    public $is_vpo;

    /* @var Boolean */
    public $relevant;

    /* @var float */
    public $rank;

    /* @var string */
    public $source_url;
}