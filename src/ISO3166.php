<?php

namespace MJDymalla\PHP;

Class ISO3166
{
    private static string $basepath = '../vendor/mj-dymalla/iso3166/data/';

    /**
     * Get all iso3166-1 supported Country names for provided locale
     * @param $locale - locale of requested country names
     * @return array - associative array of country names
     * [
     *    [AU] => Australia
     * ]
     */
    public function getCountryNames($locale)
    {
        $path = $basepath.'3166-1/'.$locale.'.json';
        $countryNames = json_decode(file_get_contents($path), true);

        return $countryNames;
    }

    /**
     * Get all iso3166-1 supported Countries including meta data for provided locale
     * @param $locale - locale for country name translation
     * @return array - associative array of objects hydrated with country meta data and translated name
     * [
     *    [AU] => Array
     *       (
     *          [alpha_2] => AU
     *          [alpha_3] => AUS
     *          [numeric] => 036
     *          [name] => Australia
     *       )
     * ]
     */
    public function getCountries($locale)
    {
        $countries = [];

        $metaDataPath = $basepath.'3166-1/meta.json';
        $countryNamesPath = $basepath.'3166-1/'.$locale.'.json';
        
        $countries = json_decode(file_get_contents($metaDataPath), true);
        $countryNames = json_decode(file_get_contents($countryNamesPath), true);

        foreach ($countryNames as $A2 => $name) {
            if (array_key_exists($A2, $countries)) {
                $countries[$A2]['name'] = $name; 
            }
        }

        return $countries;
    }

    /**
     * @param $A2 - Two character alpha-2 code of Country (parent of requested subdivisions)
     * @param $locale - locale for sub division name translation
     * @return array - associative array of sub division names for provided country
     * [
     *    [AU-ACT] => Australian Capital Territory
     * ]
     */
    public function getSubDivisionNames($A2, $locale)
    {
        $path = $basepath.'3166-2/'.$A2.'/'.$locale.'.json';
        $decoded = json_decode(file_get_contents($path), true);

        return $decoded;
    }

    /**
     * @param $A2 - Two character alpha-2 code of Country (parent of requested subdivisions)
     * @param $locale - locale for subdivision name translation
     * @return array - associative array of objects hydrated with subdivision meta data and translated name
     * e.g. mul_Latn
     * [
     *    [AU-QLD] => Array
     *       (
     *          [code] => AU-QLD
     *          [type] => State
     *          [name] => Queensland
     *       )
     * ]
     */
    public function getSubDivisions($A2, $locale)
    {
        $subdivisions = [];

        $metaDataPath = self::$basepath.'3166-2/'.$A2.'/meta.json';
        $subDivisionNamesPath = self::$basepath.'3166-2/'.$A2.'/'.$locale.'.json';

        $subDivisions = json_decode(file_get_contents($metaDataPath), true);
        $subDivsionNames = json_decode(file_get_contents($subDivisionNamesPath), true);

        foreach ($subDivsionNames as $code => $name) {
            if (array_key_exists($code, $subDivisions)) {
                $subDivisions[$code]['name'] = $name;
            }
        }

        return $subDivisions;
    }
}