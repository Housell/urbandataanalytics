# Urban Data Analytics PHP API

This is a fast and secure integration for uDA Real Estate Data, S.L.

This library allows creating and checking applications thought the webservice.

Check the documentation https://api.urbandataanalytics.com/docs

## Installation

### Composer

To install the library, you must execute the following code:

    composer require housell/urbandataanalytics master

### Manual

Download the library from https://github.com/Housell/urbandataanalytics

Include all the files from the "installation path/src"

    function autoload($path) {
        $items = glob($path . DIRECTORY_SEPARATOR . "*");
    
        foreach($items as $item) {
            $isPhp = pathinfo($item) ["extension"] === "php";

            if (is_file($item) && $isPhp) {
                require_once $item;
            } elseif (is_dir($item)) {
                autoload($item);
            }
        }
    }
    
    autoload($installation_path . DIRECTORY_SEPARATOR . 'src');

## Usage

To instance the API

    use UrbanDataAnalytics\Api;

    $Api = new Api($YOUR_API_TOKEN);

### Create an Asset

Example to create an asset

    use UrbanDataAnalytics\Asset;

    $Asset = new Asset();
    $Asset->operation = Asset::OPERATION_SALE;
    $Asset->area = 40;
    $Asset->lat = 40.45454062568134;
    $Asset->lon = -3.7071921786304336;
    $Asset->portfolio_id = 123456;

### Create the indicators

The indicators to ask

    use UrbanDataAnalytics\Indicator;

    $indicator = new Indicator();
    $indicator->indicator = Indicator::AVG_PRICE;
    $indicator->admin_level = Indicator::ADMIN_LEVEL_NEIGHBORHOOD;

### Basic Asset valuation

To ask for the valuation of an Asset, we need to send the asset and the indicators via the valuation function of the API

Example:

    try {
        $Valuation = $Api->valuation($Asset, 123456, $indicators);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

Throws Exception on error

Returns Valuation 

# Documentation

## Api
    // Configuration
    public $authorization_token = '';

    // Registry
    public $call_url;
    public $post_data;
    public $raw_response;
    public $info;

### Api::valuation(Asset $Asset, $portfolio_id, array $Indicators = null, $price_type = null)

To ask for the valuation of an Asset, we need to send the asset and the indicators via the valuation function of the API

* $Asset is the asset whe want to valuate
* $portfolio_id the portfolio to save the valuation
* $Indicators (optional) is an array of Indicator objects
* $price_type (optional) can be "asking"|"closing"

## Model

Dynamically validates the objects and creates json objects from the public properties on __toString 

## Object classes

All the classes that extend from Model

Every class has its own constants and public properties

* Asset
* Competitor
* Indicator
* Valuation

### Asset

Mandatory properties "area", "lat", "lon" and "portfolio_id"

    // operation
    const OPERATION_RENT = 0;
    const OPERATION_SALE = 1;

    // property_type
    const PROPERTY_TYPE_UNKNOWN = 0;
    const PROPERTY_TYPE_PENTHOUSE = 1;
    const PROPERTY_TYPE_DUPLEX = 2;
    const PROPERTY_TYPE_HOUSE = 3;
    const PROPERTY_TYPE_APARTMENT = 4;
    const PROPERTY_TYPE_SEMI_DETACHED_HOUSE = 5;
    const PROPERTY_TYPE_TERRACED_HOUSE = 6;
    const PROPERTY_TYPE_STUDIO = 7;
    const PROPERTY_TYPE_COUNTRY_SIDE_HOUSE = 8;

    // construction_type
    const CONSTRUCTION_TYPE_BRAND_NEW = 1;
    const CONSTRUCTION_TYPE_SECOND_HAND = 2;

    // energy_cert
    const ENERGY_CERT_UNKNOWN = 0;
    const ENERGY_CERT_A_PLUS = 11;
    const ENERGY_CERT_A = 1;
    const ENERGY_CERT_B = 2;
    const ENERGY_CERT_C = 3;
    const ENERGY_CERT_D = 4;
    const ENERGY_CERT_E = 5;
    const ENERGY_CERT_F = 6;
    const ENERGY_CERT_G = 7;
    const ENERGY_CERT_EXEMPT_PROPERTY = 8;
    const ENERGY_CERT_NOT_INDICATED = 9;
    const ENERGY_CERT_IN_PROCESS = 10;

    // status
    const STATUS_UNKNOWN = 0;
    const STATUS_GOOD_CONDITION = 1;
    const STATUS_PARTIAL_REFORMATION = 2;
    const STATUS_TOTAL_REFORMATION = 5;
    const STATUS_BRAND_NEW = 6;

    // usage
    const USAGE_UNKNOWN = 0;
    const USAGE_RESIDENTIAL = 1;
    const USAGE_OFFICE = 2;
    const USAGE_LOCAL = 5;
    const USAGE_SHED = 6;
    const USAGE_PARKING_LOT = 7;
    const USAGE_TERRAIN = 8;

    // furnished
    const FURNISHED_UNKNOWN = 0;
    const FURNISHED_UNFURNISHED = 1;
    const FURNISHED_PARTLY_FURNISHED = 2;
    const FURNISHED_FULLY_FURNISHED = 3;

    /* @var bool */
    public $ac;

    /* @var string */
    public $address;

    /* @var bool */
    public $agency;

    /* @var int */
    public $area;

    /* @var string JSON value */
    public $avm;

    /* @var int */
    public $bathrooms;

    /* @var int */
    public $boundary_id;

    /* @var string */
    public $boundary_code;

    /* @var bool */
    public $common_zones;

    /* @var string GeoJSON */
    public $competitors_geom;

    /* @var string Date YYYY-MM-DD HH:MM:SS */
    public $date_in;

    /* @var string Date YYYY-MM-DD HH:MM:SS */
    public $date_out;

    /* @var int */
    public $construction_type;

    /* @var string JSON */
    public $custom_attrs;

    /* @var bool */
    public $elevator;

    /* @var int */
    public $energy_cert;

    /* @var string */
    public $external_id;

    /* @var string Decimal */
    public $floor;

    /* @var int */
    public $furnished;

    /* @var bool */
    public $garage;

    /* @var bool */
    public $gymn;

    /* @var int */
    public $id;

    /* @var float Decimal */
    public $lat;

    /* @var float Decimal */
    public $lon;

    /* @var string */
    public $notes;

    /* @var int */
    public $operation;

    /* @var bool */
    public $orientation_east;

    /* @var bool */
    public $orientation_north;

    /* @var bool */
    public $orientation_south;

    /* @var bool */
    public $orientation_west;

    /* @var bool */
    public $outside;

    /* @var bool */
    public $pool;

    /* @var int */
    public $portfolio_id;

    /* @var int */
    public $price;

    /* @var int */
    public $price_sale;

    /* @var int */
    public $price_rent;

    /* @var int */
    public $property_type;

    /* @var string */
    public $reference;

    /* @var string */
    public $report_url;

    /* @var int */
    public $rooms;

    /* @var bool */
    public $simulated;

    /* @var bool */
    public $sports_area;

    /* @var int */
    public $status;

    /* @var bool */
    public $storage;

    /* @var bool */
    public $terrace;

    /* @var int */
    public $usage;

### Competitor

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

### Indicator

Mandatory properties 'indicator' and 'admin_level'

    // indicators
    const INDICATOR_SALE_RENT_PERCENT = 's_p';
    const INDICATOR_SALE_RENT_UNITS = 's_u';
    const INDICATOR_SALE_RENT_DIFF_PREV_Q_PERCENT = 's_u_qq';
    const INDICATOR_SALE_RENT_DIFF_PREV_Q_PERCENTILE = 's_u_qq_rk';
    const INDICATOR_SOLD_RENTED_DIFF_PREV_Q_PERCENT = 'o_u_qq';
    const INDICATOR_SOLD_RENTED_DIFF_PREV_Q_PERCENTILE = 'o_u_qq_rk';
    const INDICATOR_NEW_STOCK_DIFF_PREV_Q_PERCENTILE = 'i_u_qq_rk';
    const INDICATOR_TOTAL_ABSORPTION_RATIO_PERCENT = 'o_a';
    const INDICATOR_AVG_PRICE = 'o_pm';
    const INDICATOR_AVG_PRICE_PER_METER = 'o_pu';
    const INDICATOR_AVG_PRICE_PRE_METER_DIFF_PREV_Q_PERCENT = 'o_pu_qq';
    const INDICATOR_ESTIMATED_TIME_TO_SELL_RENT = 's_t';
    const INDICATOR_ESTIMATED_TIME_TO_SELL_RENT_DIFF_PREV_Q_PERCENT = 's_t_qq';
    const INDICATOR_GROSS_RENT_PROFITABILITY = 'y_r';
    const INDICATOR_GROSS_SALE_PROFITABILITY = 'y_s';
    const INDICATOR_INVESTMENT_INDICATOR = 'r_g';
    const INDICATOR_NEGOTIATION_FACTOR = 's_fn';

    // admin_levels
    const ADMIN_LEVEL_COUNTRY = 0;
    const ADMIN_LEVEL_STATE = 1;
    const ADMIN_LEVEL_PROVINCE = 2;
    const ADMIN_LEVEL_CITY = 3;
    const ADMIN_LEVEL_DISTRICT = 4;
    const ADMIN_LEVEL_NEIGHBORHOOD = 5;

    /* @var string */
    public $indicator;

    /* @var number */
    public $admin_level;

    /* @var string */
    public $taxonomy;

    /* @var string */
    public $period;

### Valuation

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

### Cadastre
    /* @var string */
    public $reference;

    /* @var int */
    public $usage;

    /* @var int */
    public $area;

    /* @var string */
    public $address;

    /* @var float */
    public $lon;

    /* @var float */
    public $lat;

    /* @var int */
    public $property_type;

    /* @var string */
    public $origin;

    /* @var string */
    public $domain;

    /* @var string */
    public $payload;
