<?php


namespace App\Service;


use Faker\Factory;
use Faker\Provider\Base;

class InnGeneratorService
{
    public static function getInn()
    {
        return self::generate(Base::numberBetween(10, 91));
    }

    /**
     * Generates INN
     *
     * @param $area_code
     * @param string $mask
     * @return string
     */
    private static function generate($area_code)
    {
        $inn_base = Base::numerify('########');

        self::addAreaCode($area_code, $inn_base);

        return $inn_base . static::checksum($inn_base);
    }

    public static function checksum($inn)
    {
        if(strlen($inn) != 10) {
            throw new \RuntimeException('The length of the base INN must be 10');
        }

        $n11 = static::checkDigit($inn, [7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
        $n12 = static::checkDigit($inn . $n11, [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
        return $n11.$n12;
    }

    /**
     * Add area code to INN
     *
     * @param $area_code
     * @param $inn
     */
    private static function addAreaCode($area_code, &$inn)
    {
        if ($area_code === "" || intval($area_code) == 0) {
            //Simple generation code for areas in Russian without check for valid
            $area_code = Base::numberBetween(1, 91);
        } else {
            $area_code = intval($area_code);
        }

        $area_code = str_pad($area_code, 2, '0', STR_PAD_LEFT);

        $inn = $area_code . $inn;
    }

    /**
     * Calculate check digit
     *
     * @param $inn
     * @param $coefficients
     * @return string
     */
    private static function checkDigit($inn, $coefficients)
    {
        $n = 0;
        foreach ($coefficients as $i => $k) {
            $n += $k * (int)$inn{$i};
        }
        return strval($n % 11 % 10);
    }

    private static function getFaker(): \Faker\Generator
    {
        return Factory::create('ru_RU');
    }
}