<?php

namespace UrbanDataAnalytics;

/**
 * reference alphanumeric code.
 * usage: usage of the property (see supported values).
 * area: size of the property in m².
 * address: postal address:street name, number, etc..
 * lon: property’s longitude.
 * lat: property’s latitude.
 * property_type: type of property (see supported values).
 * origin: the source of the information returned. Possible values are local (when the information returned comes from uDA’s database) or remote (when the information returned comes from property registry service).
 * domain: only used then origin is remote. Returns the property registry service data returned in payload attribute. For Spain, it currently will return catastro value, because it uses the Spanish Cadastre web services.
 * payload: data following the format of the service specified in domain.
 */
class Cadastre extends Model
{
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
}
