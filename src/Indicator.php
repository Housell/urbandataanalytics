<?php

namespace UrbanDataAnalytics;

/**
 * Class Indicator
 *
 * indicator (mandatory): string with indicator code. See supported values.
 * admin_level (mandatory): number with the administrative level code. See supported administrative divisions.
 * taxonomy (optional): string with taxonomy code. See supported taxonomies and categories. Currently, the only value supported for taxonomy is "P". If this is missing, the indicator returned won’t be segmented by any taxonomy. category is also supported instead of taxonomy for historical reasons, but may be deprecated in the future.
 * period (optional): string with period code. See supported periods. If missing, the latest available period will be used.
 *
 * <code>
 * indicators
 *
 * Below are the real estate market indicators available so far:
 *
 * ---------------------------------------------------------------------------------------------------------------------
 * | Code      | Unit               | Description
 * ---------------------------------------------------------------------------------------------------------------------
 * | s_p       | %                  | Number of properties for sale/rent during a given quarter divided by the housing stock in that same geographical area
 * | s_u       | units              | Number of properties for sale/rent during a given quarter.
 * | s_u_qq    | %                  | Percentage difference between the number of properties for sale/rent in a given quarter compared to the previous quarter.
 * | s_u_qq_rk | percentile         | Percentile of the percentage difference between the number of properties for sale/rent in the last quarter compared to the previous quarter.
 * | o_u_qq    | %                  | Percentage difference between the number of properties sold/leased in a given quarter compared to the previous quarter
 * | o_u_qq_rk | percentile         | Percentile of the percentage difference between the number of properties sold/leased in a given quarter compared to the previous quarter
 * | i_u_qq_rk | percentile         | Percentile of the percentage difference between the number of new properties that appeared in stock in a given quarter compared to the previous quarter
 * | o_a       | %                  | Total absorption as a ratio between the units of homes in stock and rented sales (outputs) of the period
 * | o_pm      | price (€)          | Average price of all properties sold/rented in a given quarter.
 * | o_pu      | unit price (€/m²)  | Average unit price of all properties sold/rented in a given quarter.
 * | o_pu_qq   | %                  | Percentage difference between the average unit price of the properties sold/leased in a given quarter compared to the previous quarter.
 * | s_t       | Time (weeks)       | Estimated number of weeks necessary to sale/rent the property.
 * | s_t_qq    | %                  | Percentage difference between the estimated weeks for the sale/rental of the property in a given quarter compared to the previous quarter.
 * | y_r       | %                  | Gross rent profitability: is the percentage of the total price of the home covered by the annual rental income.
 * | y_s       | %                  | Gross profitability of the sale: it is the percentage of increase in the price of the property after 12 months from its purchase.
 * | r_g       | Punctuation (0-10) | It is the investment indicator of uDA that combines the RED partial indicators in the most appropriate and precise way for an investment profile of medium-high profitability and medium risk. This RED grade indicator combines dynamics of stock, sales, sale prices, expected time of sale and profitability of the different real estate segments.
 * | s_fn      | %                  | Negotiation factor: distance between prices offered and closing prices of registry operations.
 * ---------------------------------------------------------------------------------------------------------------------
 *
 *
 * admin_levels
 *
 * ---------------------------------------------------------------------------------------------------------------------
 * | Code | Name
 * ---------------------------------------------------------------------------------------------------------------------
 * | 0    | Country
 * | 1    | State
 * | 2    | Province
 * | 3    | City
 * | 4    | District
 * | 5    | Neighborhood
 * ---------------------------------------------------------------------------------------------------------------------
 *
 *
 * taxonomies
 *
 * There’re several ways to classify a property:
 *
 * According to its area, it can be “small”, “medium”, “big” or “huge”.
 * According to the number of rooms, it can be an “apartment”, a “house” or a “palace”.
 * According to the age, it can be “brand new”, “medium age”, “old” or “historical”
 * and so on…
 * Those ways of classification are taxonomies.
 *
 * The segments or labels within each taxonomy are called categories.
 *
 * Taxonomies are identified by a single letter.
 *
 * Currently supported taxonomies:
 *
 * ---------------------------------------------------------------------------------------------------------------------
 * | Code | Name
 * ---------------------------------------------------------------------------------------------------------------------
 * | P    | Classification by area
 * ---------------------------------------------------------------------------------------------------------------------
 * Within taxonomy P, the supported categories are:
 *
 * ---------------------------------------------------------------------------------------------------------------------
 * | Code    | Name         | Meaning
 * ---------------------------------------------------------------------------------------------------------------------
 * | P_01    | Very small   | Less than 70 m²
 * | P_02    | Small        | Between 71 m² and 99 m²
 * | P_03    | Medium-small | Between 100 m² and 139 m²
 * | P_04    | Medium-big   | Between 140 m² and 179 m²
 * | P_05    | Big          | Between 180 m² and 249 m²
 * | P_06    | Very big     | Between 250 m² and 2000 m²
 * ---------------------------------------------------------------------------------------------------------------------
 * Warning: Do not hard-code these values in your code. They may change at any time without any notice. If you need to get the category for one specific size, use Category by area endpoint.
 *
 *
 * periods
 *
 * A period represents a range of dates with fixed and known length.
 *
 * Currently supported periods are:
 *
 * ---------------------------------------------------------------------------------------------------------------------
 * | Code   | Name    | Sample
 * ---------------------------------------------------------------------------------------------------------------------
 * | YYYY   | Year    | 2017 means the period from 1/Jan/2017 until 31/Dec/2017
 * | YYYYQn | Quarter | 2017Q2 means the period from 1/Apr/2017 until 30/Jun/2017
 * ---------------------------------------------------------------------------------------------------------------------
 * </code>
 */
class Indicator extends Model
{
    /**
     * @var string
     */
    public $indicator;

    /**
     * @var number
     */
    public $admin_level;

    /**
     * @var string
     */
    public $taxonomy;

    /**
     * @var string
     */
    public $period;

    protected $mandatory_fields = [
        'indicator',
        'admin_level',
    ];
}