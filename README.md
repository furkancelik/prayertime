Namaz vakitleri
=========

Diyanet işleri bakanlığının alt yapısını kullanarak namaz vakitlerini temin eden basit bir class'tır.

Not: Şuan sadece Türkiye için namaz vakitleri sağlanmaktadır.

Kullanımı
----

> Bütün şehirlerin isimlerini almak.

```sh
// Bütün şehir isimlerini dizi halinde getirir.
// key = şehir kodu 
// value = şehir ismi şeklindedir.
$prayer = new Prayer();

$prayer->getAllCitiesName();
```


> Bütün şehirlerin kodlarını almak.

```sh
// Bütün şehir kodlarını dizi halinde getirir.
// key = şehir ismi 
// value = şehir kodu şeklindedir.
$prayer = new Prayer();

$prayer->getAllCitiesCode();
```



> Bütün şehirlerin kodlarını almak.

```sh
// Bütün şehir kodlarını dizi halinde getirir.
// key = şehir ismi 
// value = şehir kodu şeklindedir.
$prayer = new Prayer();

$prayer->getAllCitiesCode();
```

** Tekil Kullanımlar için ise :**


> Belirtilen kodun şehir ismini almak.

```sh
// Parametrede belirtilen şehir kodunun ismini getirir.
$prayer = new Prayer();

$prayer->getCityName(520); // Bursa
```

> Belirtilen şehir isminin kodunu almak.

```sh
// Parametrede belirtilen şehir isminin kodunu getirir.
$prayer = new Prayer();

$prayer->getCityCode('BURSA'); // 520
```

> Belirtilen şehir koduna ait ilçelerin kodlarını getirir.

```sh
// Parametrede belirtilen şehir koduna ait ilçelerin isimlerini ve kodlarını getirir.
$prayer = new Prayer();

$prayer->getDistrictCode(520);

// İkinci parametre olarak true belirtilirse sadece merkez ilçenin kodunu getirir.
$prayer = new Prayer();

$prayer->getDistrictCode(520 , true); // 9335
```

> Belirtilen ilçe koduna ait namaz vakitlerini bir dizi olarak getirir .

```sh
// Parametrede belirtilen şehir isminin kodunu getirir.
$prayer = new Prayer();

$prayer->getPrayerTimes(9335);
```

##### Dizi içeriği;

* Date (Geçerli günü verir yani "strtotime('today')  şeklinde.)
* İmsak
* Günes(Sabah)
* Ögle
* İkindi
* Akşam
* Yatsı
* Kıble
* Enlem
* Boylam
* Kıble açısı
* Ülke adı
* Şehir adı
* Kıble saati
* Güneş batış saati
* Güneş doğuş saati

> Ülkenin bütün şehirlerinin merkez ilçelerinin namaz vakitlerini almak

__construct'da belirtilen ülke'ye ailt şehirlerin merkez ilçelerinin namaz vakitlerini dizi olarak getirir.

```sh
$prayer = new Prayer();

$prayer->getAllPrayerTimes();
```


NOT
--

Gelen verileri cache ederek kullanmanızı şiddetle tavsiye ederim. Tam olarak güncellenme saatini bilmiyorum ama siz gece 01:00'den sonra uygun gördüğünüz bir
saate günlük cron ayarlayıp namaz vakitlerini güncel tutarsınız.

Lisans
----

[MIT] ile lisanslanmıştır.

[MIT]:http://opensource.org/licenses/MIT
