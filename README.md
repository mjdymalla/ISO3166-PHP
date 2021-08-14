# ISO3166-PHP
PHP implementation for retrieving ISO3166-1 and ISO3166-2 country codes and data. These codes are the standard for defining countries, dependent territories, special areas of geographical interest, and their prinicpal subdivisions.

### Data
Country data provided is taken from [ISO3166-Library](https://github.com/MJDymalla/ISO3166-Library).

### Installing
```
$ composer require mjdymalla/iso3166-php
```
### Locales
The data supports over 150 different locales. Although, it is worth noting that at country level (iso3166-1) 'en' (english) as a locale is supported but at subdivision level (iso3166-2) to avoid ambiguity on whether a subdivisions name is in english or infact the native country's latin equivalent 'mul_Latn' (multi-latin) should be used instead.

### Usage
All methods accept an array of locales which is used to fallback on, i.e. if provided locales are ['it', 'en'] translations in 'it' will be prioritized, if a translation is not found then it will fallback to 'en'.

#### Country names  
```
$iso3166 = new ISO3166;
$data = $iso3166->getCountryNames(['en']);
```
```
[
   [AU] => 'Australia'
]
```
#### Country names including meta data
```
$data = $iso3166->getCountries(['en']);
```
```
[
   [AU] => Array
      (
         [alpha_2] => AU
         [alpha_3] => AUS
         [numeric] => 036
         [name] => Australia
      )
]
```
#### Subdivision names
```
$data = $iso3166->getSubDivisionNames('US', ['mul_Latn']);
```
```
[
   [US-CA] => 'California',
   [US-CO] => 'Colorado',
   [US-CT] => 'Connecticut'
]
```
#### Subdivision names including meta data
```
$data = $iso3166->getSubDivisions('US', ['ja']);
```
```
[
   [US-CA] => Array
      (
         [code] => US-CA
         [type] => State
         [name] => カリフォルニア
      )
   [US-CO] => Array
      (
         [code] => US-CO
         [type] => State
         [name] => コロラド
      )
]
```

