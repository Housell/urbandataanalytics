<?php

namespace UrbanDataAnalytics;

/**
 * Class Asset
 *
 * <code>
 * Asset attributes
 * ---------------------------------------------------------------------------------------------------------------------
 * | Name              | Required  | Type        | Description
 * ---------------------------------------------------------------------------------------------------------------------
 * | ac                | No        | Boolean     | Air conditioning
 * | address           | No        | String      | Postal address of the asset (street name, number, etc.)
 * | agency            | No        | Boolean     | Operation managed by real estate agency
 * | area              | Yes       | Integer     | Size of the asset in m² (decimal values are not supported)
 * | avm               | No        | JSON        | Stores the asset’s initial valuation, using the same format as best_score.
 * | bathrooms         | No        | Integer     | Number of bathrooms
 * | boundary_id       | No        | Integer     | The identifier of the boundary (geographical area) where the asset is located. This is a computed (and readonly) value based on lat/lon.
 * | boundary_code     | No        | String      | Geographical code associated to the boundary_id. Computed and readonly value.
 * | common_zones      | No        | Boolean     | If the building provides common zones for leisure.
 * | competitors_geom  | No        | GeoJSON     | A valid GeoJSON used to define an area competitors_geom attribute
 * | date_in           | No        | Date        | Date when the asset was added. Expected format is YYYY-MM-DD or YYYY-MM-DD HH:MM:SS.
 * | date_out          | No        | Date        | Date when the asset was sold/rented. Expected format is YYYY-MM-DD or YYYY-MM-DD HH:MM:SS.
 * | construction_type | No        | Integer     | Type of building (see supported values)
 * | custom_attrs      | No        | JSON        | A JSON object containing custom attributes for the asset.
 * | elevator          | No        | Boolean     | Lift or Elevator
 * | energy_cert       | No        | Integer     | The energy certification issued (see supported values
 * | external_id       | No        | String      | An arbitrary identifier for the asset. This value must be unique within the same portfolio
 * | floor             | No        | Decimal     | Floor, level or story. Any positive integer value is supported, together with:
 * |                   |           |             |     -0.5: Lower ground floor
 * |                   |           |             |     0.0: Basement/Ground floor
 * |                   |           |             |     0.5: Mezzanine
 * | furnished         | No        | Integer     | Furnished (see supported values).
 * | garage            | No        | Boolean     | Garage or Parking lot
 * | gymn              | No        | Boolean     | Gymn
 * | id                | Yes       | Integer     | The unique identifier
 * | lat               | Yes       | Decimal     | Asset’s latitude
 * | lon               | Yes       | Decimal     | Asset’s longitude
 * | notes             | No        | String      | Arbitrary text
 * | operation         | Yes       | Integer     | Type of transaction: “sale” or “rent” (see supported values)
 * | orientation_east  | No        | Boolean     | Whether this asset is oriented to the East.
 * | orientation_north | No        | Boolean     | Whether this asset is oriented to the North.
 * | orientation_south | No        | Boolean     | Whether this asset is oriented to the South.
 * | orientation_west  | No        | Boolean     | Whether this asset is oriented to the West.
 * | outside           | No        | Boolean     | Outside/exterior
 * | pool              | No        | Boolean     | Swimming pool
 * | portfolio_id      | Yes       | Integer     | The identifier of belonging portfolio
 * | price             | No        | Integer     | Current asking (advertised) price of the asset
 * | price_sale        | No        | Integer     | Current asking (advertised) price of the asset in case of sale. If this value is specified, price is ignored.
 * | price_rent        | No        | Integer     | Current asking (advertised) price of the asset in case of rental. If this value is specified, price is ignored.
 * | property_type     | No        | Integer     | Type of asset (see supported values)
 * | reference         | No        | String      | External reference. Is Spain, this can be used to store “Referencia Catastral”.
 * | report_url        | Read-only | String      | URL to download last report requested for this asset, if any
 * | rooms             | No        | Integer     | Number of bedrooms
 * | simulated         | No        | Boolean     | Whether this asset is considered a valuation (true) or a physical property (false).
 * | sports_area       | No        | Boolean     | If the building provides sports zones.
 * | status            | No        | Integer     | State of preservation (see supported values).
 * | storage           | No        | Boolean     | Storage, box room or lumber
 * | terrace           | No        | Boolean     | Terrace
 * | usage             | No        | Integer     | The usage of the asset. (see supported values).
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * Asset properties
 *
 * operation
 * ---------------------------------------------------------------------------------------------------------------------
 * Code | Name
 * ---------------------------------------------------------------------------------------------------------------------
 * 0    | Rent
 * 1    | Sale
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * property_type
 * ---------------------------------------------------------------------------------------------------------------------
 * Code | Name
 * ---------------------------------------------------------------------------------------------------------------------
 * 0    | Unknown
 * 1    | Penthouse
 * 2    | Duplex
 * 3    | House or independent house
 * 4    | Apartment/Flat
 * 5    | Semi-detached house
 * 6    | Terraced house
 * 7    | Studio
 * 8    | Country-side property
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * construction_type
 * ---------------------------------------------------------------------------------------------------------------------
 * Code | Name
 * ---------------------------------------------------------------------------------------------------------------------
 * 1    | Brand new
 * 2    | Second hand
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * energy_cert
 * ---------------------------------------------------------------------------------------------------------------------
 * Code | Name
 * ---------------------------------------------------------------------------------------------------------------------
 * 0    | Unknown
 * 11   | A+
 * 1    | A
 * 2    | B
 * 3    | C
 * 4    | D
 * 5    | E
 * 6    | F
 * 7    | G
 * 8    | Exempt property
 * 9    | Not indicated
 * 10   | In process
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * status
 * ---------------------------------------------------------------------------------------------------------------------
 * Code | Name
 * ---------------------------------------------------------------------------------------------------------------------
 * 0    | Unknown
 * 1    | Good condition
 * 2    | Partial reformation
 * 5    | Total reformation
 * 6    | Brand new
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * usage
 * ---------------------------------------------------------------------------------------------------------------------
 * Code | Name
 * ---------------------------------------------------------------------------------------------------------------------
 * 0    | Unknown
 * 1    | Residential
 * 2    | Office
 * 5    | Local
 * 6    | Shed
 * 7    | Parking lot
 * 8    | Terrain
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * furnished
 * ---------------------------------------------------------------------------------------------------------------------
 * Code | Name
 * ---------------------------------------------------------------------------------------------------------------------
 * 0    | Unknown
 * 1    | Unfurnished
 * 2    | Partly furnished
 * 3    | Fully furnished
 * ---------------------------------------------------------------------------------------------------------------------
 * </code>
 *
 * @noinspection PHPUnused
 */
class Asset extends Model
{
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
    const FURNISHED_Partly_FURNISHED = 2;
    const FURNISHED_Fully_FURNISHED = 3;

    /**
     * @var bool
     */
    public $ac;

    /**
     * @var string
     */
    public $address;

    /**
     * @var bool
     */
    public $agency;

    /**
     * @var int
     */
    public $area;

    /**
     * @var string JSON value
     */
    public $avm;

    /**
     * @var int
     */
    public $bathrooms;

    /**
     * @var int
     */
    public $boundary_id;

    /**
     * @var string
     */
    public $boundary_code;

    /**
     * @var bool
     */
    public $common_zones;

    /**
     * @var string GeoJSON
     */
    public $competitors_geom;

    /**
     * @var string Date YYYY-MM-DD HH:MM:SS
     */
    public $date_in;

    /**
     * @var string Date YYYY-MM-DD HH:MM:SS
     */
    public $date_out;

    /**
     * @var int
     */
    public $construction_type;

    /**
     * @var string JSON
     */
    public $custom_attrs;

    /**
     * @var bool
     */
    public $elevator;

    /**
     * @var int
     */
    public $energy_cert;

    /**
     * @var string
     */
    public $external_id;

    /**
     * @var string Decimal
     */
    public $floor;

    /**
     * @var int
     */
    public $furnished;

    /**
     * @var bool
     */
    public $garage;

    /**
     * @var bool
     */
    public $gymn;

    /**
     * @var int
     */
    public $id;

    /**
     * @var float Decimal
     */
    public $lat;

    /**
     * @var float Decimal
     */
    public $lon;

    /**
     * @var string
     */
    public $notes;

    /**
     * @var int
     */
    public $operation;

    /**
     * @var bool
     */
    public $orientation_east;

    /**
     * @var bool
     */
    public $orientation_north;

    /**
     * @var bool
     */
    public $orientation_south;

    /**
     * @var bool
     */
    public $orientation_west;

    /**
     * @var bool
     */
    public $outside;

    /**
     * @var bool
     */
    public $pool;

    /**
     * @var int
     */
    public $portfolio_id;

    /**
     * @var int
     */
    public $price;

    /**
     * @var int
     */
    public $price_sale;

    /**
     * @var int
     */
    public $price_rent;

    /**
     * @var int
     */
    public $property_type;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $report_url;

    /**
     * @var int
     */
    public $rooms;

    /**
     * @var bool
     */
    public $simulated;

    /**
     * @var bool
     */
    public $sports_area;

    /**
     * @var int
     */
    public $status;

    /**
     * @var bool
     */
    public $storage;

    /**
     * @var bool
     */
    public $terrace;

    /**
     * @var int
     */
    public $usage;

    /**
     * @var string[]
     */
    protected $mandatory_fields = [
        'area',
        'id',
        'lat',
        'lon',
        'portfolio_id',
    ];
}