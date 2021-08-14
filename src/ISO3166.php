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
    public function getCountryNames($locales = [])
    {
        if (!$locales || gettype($locales) !== 'array') {
            return [];
        }

        $translations = [];

        $metaDataPath = self::$basepath.'3166-1/meta.json';
        $supportedCountries = array_keys(self::read($metaDataPath));

        foreach ($locales as $locale) {
            if (count($supportedCountries) <= count($translations)) {
                break;
            }

            $path = self::$basepath.'3166-1/'.$locale.'.json';
            $countryNames = self::read($path);

            foreach ($countryNames as $A2 => $translation) {
                if (!array_key_exists($A2, $translations)) {
                    $translations[$A2] = $translation;
                }
            }
        }

        ksort($translations);
        return $translations;
    }

    /**
     * Get all iso3166-1 supported Countries including meta data for provided locale
     * @param $locale - locale for country name translation
     * @return array - associative array of objects containing country meta data and translated name
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
    public function getCountries($locales = [])
    {
        if (!$locales || gettype($locales) !== 'array') {
            return [];
        }

        $metaDataPath = self::$basepath.'3166-1/meta.json';
        $countries = self::read($metaDataPath);
        $translations = self::getCountryNames($locales);

        foreach ($translations as $A2 => $translation) {
            if (array_key_exists($A2, $countries)) {
                $countries[$A2]['name'] = $translation;
            }
        }

        return $countries;
    }

    /**
     * @param $A2 - Two character alpha-2 code of Country (parent of requested subdivisions)
     * @param $locale - locale for sub division name translation
     * @return array - associative array of sub division names for provided country
     * 
     * [
     *    [US-CA] => California
     * ]
     */
    public function getSubDivisionNames($A2, $locales = [])
    {
        if (!$locales || gettype($locales) !== 'array') {
            return [];
        }
        
        $subDivisionNames = [];

        $metaDataPath = self::$basepath.'3166-2/'.$A2.'/meta.json';
        $supportedSubDivisions = array_keys(self::read($metaDataPath));
        
        foreach ($locales as $locale) {
            if (count($supportedSubDivisions) <= count($subDivisionNames)) {
                break;
            }

            $path = self::$basepath.'3166-2/'.$A2.'/'.$locale.'.json';
            $translations = self::read($path);
            
            foreach ($translations as $code => $translation) {
                if (!array_key_exists($code, $subDivisionNames)) {
                    $subDivisionNames[$code] = $translation;
                }
            }
        }

        ksort($subDivisionNames);
        return $subDivisionNames;
    }

    /**
     * @param $A2 - Two character alpha-2 code of Country (parent of requested subdivisions)
     * @param $locale - locale for subdivision name translation
     * @return array - associative array of a country's subdivisions meta data and translations
     * 
     * [
     *    [US-CA] => Array
     *       (
     *          [code] => US-CA
     *          [type] => State
     *          [name] => California
     *       )
     * ]
     */
    public function getSubDivisions($A2, $locales = [])
    {
        if (!$locales || gettype($locales) !== 'array') {
            return [];
        }

        $metaDataPath = self::$basepath.'3166-2/'.$A2.'/meta.json';
        $subDivisions = self::read($metaDataPath);

        foreach ($locales as $locale) {
            if (self::translationsComplete($subDivisions)) {
                return $subDivisions;
            }

            $translationsPath = self::$basepath.'3166-2/'.$A2.'/'.$locale.'.json';
            $translations = self::read($translationsPath);

            foreach($translations as $code => $name) {
                if (array_key_exists($code, $subDivisions) && !array_key_exists('name', $subDivisions[$code])) {
                    $subDivisions[$code]['name'] = $name;
                }
            }
        }

        return $subDivisions;
    }

    /**
     * Get all supported locales for country data
     * @return array - ordered array of all supported locales for country translations
     */
    public function getCountryLocales()
    {
        $path = self::$basepath.'/3166-1';
        $dir = new \DirectoryIterator($path);

        $locales = [];

        foreach ($dir as $file) {
            if ($file->isDot()) {
                continue;
            }

            $locales[] = explode(".", $file->getFilename())[0];
        }

        sort($locales);
        return $locales;
    }

    /**
     * Check whether translations have been found for given subdivisions
     * @return boolean - false if subdivision is found without a translation
     */
    private function translationsComplete($subdivisions)
    {
        foreach ($subdivisions as $A2 => $data) {
            if (!array_key_exists('name', $data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * File read function
     * @return array - contents of file in array
     */
    private function read(string $path)
    {
        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (null === $data) {
            return null;
        }

        return $data;
    }
}