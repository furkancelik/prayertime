<?php

/**
 * Class Prayer
 * @link http://www.harunmemur.com
 * @author Harun MEMUR
 * @author Harun MEMUR <lugihaue@gmail.com>
 * @author Harun MEMUR <http://harunmemur.com/>
 * @version 1.0
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

class Prayer {

    const   COUNTRY_URL         = 'http://www.diyanet.gov.tr/PrayerTime/FillState?countryCode=' ,
            CITY_URL            = 'http://www.diyanet.gov.tr/PrayerTime/FillCity?itemId=',
            PRAYER_POST_URL     = 'http://www.diyanet.gov.tr/PrayerTime/PrayerTimesSet';

    /**
     * @var array $citiesName , $citiesCode
     */

    private $citiesName  , $citiesCode = array();

    /**
     * Belirtilen ülkenin bütün şehirlerinin verileri bu diziye atanır.
     * @var array|mixed
     */

    static $allCities = array();

    /**
     *  COUNTRY_URL sabitinin ki url değerinin içeriğini alır ve json'ı array tipine çevirir
     * @throws Exception.
     * @param string|integer $countryCode
     */

    public function __construct($countryCode = 2)
    {
        $getContents = file_get_contents(self::COUNTRY_URL . $countryCode);

        if ($getContents === false)throw new \Exception('Ülke bilgileri alınamıyor !' , 404);

            self::$allCities    = json_decode($getContents, true);

            $this->setAllCitiesName();
            $this->setAllCitiesCode();
    }

    /**
     *  Belirtilen ülkeye ait verilen bütün şehirlerin isimlerini $citiesName property'sini set eder.
     * @access private
     * @return void
     */

    private function setAllCitiesName()
    {
       foreach( self::$allCities as $value){

            $this->citiesName[$value['Value']] = $value['Text'];

        }
    }

    /**
     * Belirtilen ülkeye ait verilen bütün şehirlerin kodlarını $citiesCode property'sini set eder.
     * @access private
     * @return void
     */

    private function setAllCitiesCode()
    {
        foreach (self::$allCities as $value) {

            $this->citiesCode[$value['Text']] = $value['Value'];

        }

        $this->citiesCode = $this->citiesCode;
    }

    /**
     * Belirtilen ülkeye ait gelen bütün şehir isimlerini verir.
     * Key = şehir kodu , Value = şehir ismi
     * @access public
     * @return array
     */

    public function getAllCitiesName()
    {
        return $this->citiesName;
    }

    /**
     * Belirtilen ülkeye ait gelen bütün şehir kodlarını verir.
     * Key = şehir ismi , Value = şehir kodu
     * @access public
     * @return array
     */

    public function getAllCitiesCode()
    {
        return $this->citiesCode;
    }

    /**
     * Kodu belirtilen şehrin ismini verir.
     * @param $cityCode
     * @return bool|int
     */

    public function getCityName($cityCode)
    {
        foreach ($this->citiesName as $key => $value) {

            if ($key == $cityCode) return $value;
        }

        return false;
    }

    /**
     * İsmi belirtilen şehrin kodunu verir.
     * @param string $cityName
     * @return string|integer|bool
     */

    public function getCityCode($cityName)
    {
        $cityName = mb_strtoupper($cityName , 'UTF-8');

        foreach ($this->citiesCode as $key => $value) {

            if ($key == $cityName) return $value;
        }

        return false;
    }

    /**
     * Belirtilen şehir koduna ait bütün ilçelerin kodlarını verir.
     * $center parametresi true olarak belirtilirse sadece merkez ilçenin kodunu verir.
     * @throws Exception.
     * @param string|integer $cityCode
     * @param bool $center
     * @return mixed
     */

    public function getDistrictCode($cityCode , $center = false)
    {
        $getCityContents = file_get_contents(self::CITY_URL . $cityCode);

            if($getCityContents === false) throw new \Exception('İlçe namaz kodları alınamıyor !' , 404);

                $getCityCode = json_decode($getCityContents , true);

            if ($center) return $getCityCode[0]['Value'];

                foreach ($getCityCode as $value) {

                    $allDistrictCode[$value['Text']] = $value['Value'];
                }

            return $allDistrictCode;
    }

    /**
     * Belirtilen ilçe kodunun namaz vakitlerini ve detaylarını return eder.
     * @only parametresi true olarak belirtilirse sadece ;
     * ( date , imsak , gunes , ogle , ikindi , aksam , satsi , kible , enlem , boylam , kibleacisi , ulkeadi , sehiradi , kiblesaati , gunesbatis , gunesdogus) bilgilerini verir.
     * @throws Exception.
     * @param integer|string $code
     * @param bool $onlyTimes
     * @return array|mixed
     */
  
    public function getPrayerTimes($code , $only = true)
    {
        $getPrayerTimes = $this->getData($code);

        if ($getPrayerTimes === false) throw new \Exception('Namaz vakti bilgileri alınamıyor !' , 503);

            $getPrayerTimes= json_decode($getPrayerTimes , true);

                if($only){

                    $newPrayerTime['date'] =(string)strtotime('today');

                    $newPrayerTime = array_merge($newPrayerTime  , array_slice($getPrayerTimes , 0 , 6 , true));

                    $newPrayerTime['kible'] = $getPrayerTimes['KibleSaati'];

                    $newPrayerTime = array_merge($newPrayerTime  , array_slice($getPrayerTimes , 11 , 8 , true));

                    $newPrayerTime = array_change_key_case($newPrayerTime , CASE_LOWER);

                        return $newPrayerTime;
                }

       return $getPrayerTimes;
    }

    /**
     * Bütün şehirlerin namaz vakitlerini verir.
     * @throws Exception.
     * @return array|bool
     */
  
    public function getAllPrayerTimes()
    {
        $allCityCode = $this->getAllCitiesCode();

        foreach ($allCityCode as $key => $value) {

            $allCitiesContent = file_get_contents(self::CITY_URL. $value);

                if ($allCitiesContent === false) throw new \Exception('Namaz vakti bilgileri alınamıyor !' , 503);

                    $allCitiesCenterCode[$key] = json_decode($allCitiesContent , true)['0']['Value'];
        }

        foreach ($allCitiesCenterCode as $key => $value) {

             $cityContent = $this->getData($value);

                if ($cityContent === false) return false;

                    $allCitiesPrayerContent = json_decode($cityContent , true);

                    $allCitiesPrayerTimes[$key]['date'] =(string)strtotime('today');

                    $allCitiesPrayerTimes[$key] = array_merge($allCitiesPrayerTimes[$key]  , array_slice($allCitiesPrayerContent , 0 , 6 , true));

                    $allCitiesPrayerTimes[$key]['kible'] = $allCitiesPrayerContent['KibleSaati'];

                    $allCitiesPrayerTimes[$key] = array_merge($allCitiesPrayerTimes[$key]  , array_slice($allCitiesPrayerContent , 11 , 8 , true));

                    $allCitiesPrayerTimes[$key] = array_change_key_case($allCitiesPrayerTimes[$key] , CASE_LOWER);
        }

        return $allCitiesPrayerTimes;
    }

    /**
     * PRAYER_POST_URL sabitinde belirtilen URL'ye POST işlemi yapar.
     * Belirtilen il kodunun namaz vakitlerini getirir.
     * @throws Exception.
     * @param $data
     * @return json
     */

    private function getData($data)
    {
        $curlConnect = curl_init(self::PRAYER_POST_URL);

            $curlOption = array(

                CURLOPT_POST            => 1 ,

                CURLOPT_POSTFIELDS      => array('name' => $data),

                CURLOPT_FOLLOWLOCATION  => true ,

                CURLOPT_TIMEOUT         => 300 ,

                CURLOPT_RETURNTRANSFER  => 1 ,

            );

        curl_setopt_array($curlConnect , $curlOption);

        $result = curl_exec($curlConnect);

            if ($result === false) throw new \Exception('Curl bağlantısı başarısız !' , 503);

                curl_close($curlConnect);

                return $result;
    }

}
