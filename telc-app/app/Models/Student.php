<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;

    public const GENDER_MALE = 'M';
    public const GENDER_FEMALE = 'F';
    public const LANGUAGE_FRENCH = 'French';
    public const LANGUAGE_ENGLISH = 'English';

    public const GENDER_OPTIONS = [
        self::GENDER_MALE => 'Male',
        self::GENDER_FEMALE => 'Female',
    ];

    public const LANGUAGE_OPTIONS = [
        self::LANGUAGE_FRENCH,
        self::LANGUAGE_ENGLISH,
    ];

    // Liste des villes par pays avec leurs variations
    private const CITIES_BY_COUNTRY = [
        'CM' => [
            'Yaoundé' => ['Yaoundé', 'Yaounde', 'yaoundé', 'yaounde', 'YAOUNDÉ', 'YAOUNDE'],
            'Douala' => ['Douala', 'douala', 'DOUALA'],
            'Bamenda' => ['Bamenda', 'bamenda', 'BAMENDA'],
            'Bafoussam' => ['Bafoussam', 'bafoussam', 'BAFOUSSAM'],
            'Garoua' => ['Garoua', 'garoua', 'GAROUA'],
            'Maroua' => ['Maroua', 'maroua', 'MAROUA'],
            'Buea' => ['Buea', 'buea', 'BUEA'],
            'Kumba' => ['Kumba', 'kumba', 'KUMBA'],
            'Limbe' => ['Limbe', 'limbe', 'LIMBE'],
            'Ngaoundéré' => ['Ngaoundéré', 'Ngaoundere', 'ngaoundéré', 'ngaoundere', 'NGAOUNDÉRÉ', 'NGAOUNDERE'],
            'Edéa' => ['Edéa', 'Edea', 'edéa', 'edea', 'EDÉA', 'EDEA'],
            'Kribi' => ['Kribi', 'kribi', 'KRIBI'],
            'Foumban' => ['Foumban', 'foumban', 'FOUMBAN'],
            'Dschang' => ['Dschang', 'dschang', 'DSCHANG'],
            'Bertoua' => ['Bertoua', 'bertoua', 'BERTOUA'],
            'Ebolowa' => ['Ebolowa', 'ebolowa', 'EBOLOWA'],
            'Mbouda' => ['Mbouda', 'mbouda', 'MBOUDA'],
            'Kousséri' => ['Kousséri', 'Kousseri', 'kousséri', 'kousseri', 'KOUSSÉRI', 'KOUSSERI'],
            'Sangmélima' => ['Sangmélima', 'Sangmelima', 'sangmélima', 'sangmelima', 'SANGMÉLIMA', 'SANGMELIMA'],
        ],
        'GA' => [
            'Libreville' => ['Libreville', 'libreville', 'LIBREVILLE'],
            'Port-Gentil' => ['Port-Gentil', 'Port Gentil', 'port-gentil', 'port gentil', 'PORT-GENTIL'],
            'Franceville' => ['Franceville', 'franceville', 'FRANCEVILLE'],
            'Oyem' => ['Oyem', 'oyem', 'OYEM'],
            'Moanda' => ['Moanda', 'moanda', 'MOANDA'],
            'Lambaréné' => ['Lambaréné', 'Lambarene', 'lambaréné', 'lambarene', 'LAMBARÉNÉ', 'LAMBARENE'],
        ],
        'CD' => [
            'Kinshasa' => ['Kinshasa', 'kinshasa', 'KINSHASA'],
            'Lubumbashi' => ['Lubumbashi', 'lubumbashi', 'LUBUMBASHI'],
            'Mbuji-Mayi' => ['Mbuji-Mayi', 'Mbuji Mayi', 'mbuji-mayi', 'mbuji mayi', 'MBUJI-MAYI', 'MBUJI MAYI'],
            'Kisangani' => ['Kisangani', 'kisangani', 'KISANGANI'],
            'Kananga' => ['Kananga', 'kananga', 'KANANGA'],
            'Likasi' => ['Likasi', 'likasi', 'LIKASI'],
            'Kolwezi' => ['Kolwezi', 'kolwezi', 'KOLWEZI'],
            'Tshikapa' => ['Tshikapa', 'tshikapa', 'TSIKAPA'],
        ],
        'CF' => [
            'Bangui' => ['Bangui', 'bangui', 'BANGUI'],
            'Bimbo' => ['Bimbo', 'bimbo', 'BIMBO'],
            'Mbaïki' => ['Mbaïki', 'Mbaiki', 'mbaïki', 'mbaiki', 'MBAÏKI', 'MBAIKI'],
            'Bambari' => ['Bambari', 'bambari', 'BAMBARI'],
            'Bouar' => ['Bouar', 'bouar', 'BOUAR'],
            'Berbérati' => ['Berbérati', 'Berberati', 'berbérati', 'berberati', 'BERBÉRATI', 'BERBERATI'],
            'Kaga-Bandoro' => ['Kaga-Bandoro', 'Kaga Bandoro', 'kaga-bandoro', 'kaga bandoro', 'KAGA-BANDORO', 'KAGA BANDORO'],
        ],
        'CG' => [
            'Brazzaville' => ['Brazzaville', 'brazzaville', 'BRAZZAVILLE'],
            'Pointe-Noire' => ['Pointe-Noire', 'Pointe Noire', 'pointe-noire', 'pointe noire', 'POINTE-NOIRE', 'POINTE NOIRE'],
            'Nkayi' => ['Nkayi', 'nkayi', 'NKAYI'],
            'Dolisie' => ['Dolisie', 'dolisie', 'DOLISIE'],
            'Madingou' => ['Madingou', 'madingou', 'MADINGOU'],
            'Owando' => ['Owando', 'owando', 'OWANDO'],
            'Gamboma' => ['Gamboma', 'gamboma', 'GAMBOMA'],
        ],
        'TD' => [
            'N\'Djaména' => ['N\'Djaména', 'NDjamena', 'n\'djaména', 'ndjamena', 'N\'DJAMÉNA', 'NDJAMENA'],
            'Moundou' => ['Moundou', 'moundou', 'MOUNDOU'],
            'Sarh' => ['Sarh', 'sarh', 'SARH'],
            'Abéché' => ['Abéché', 'Abeche', 'abéché', 'abeche', 'ABÉCHÉ', 'ABECHE'],
            'Doba' => ['Doba', 'doba', 'DOBA'],
            'Kelo' => ['Kelo', 'kelo', 'KELO'],
            'Bongor' => ['Bongor', 'bongor', 'BONGOR'],
            'Mongo' => ['Mongo', 'mongo', 'MONGO'],
        ],
        'GQ' => [
            'Malabo' => ['Malabo', 'malabo', 'MALABO'],
            'Bata' => ['Bata', 'bata', 'BATA'],
            'Ebebiyín' => ['Ebebiyín', 'Ebebiyin', 'ebebiyín', 'ebebiyin', 'EBEBIYÍN', 'EBEBIYIN'],
            'Annobón' => ['Annobón', 'Annobon', 'annobón', 'annobon', 'ANNOBÓN', 'ANNOBON'],
            'Luba' => ['Luba', 'luba', 'LUBA'],
            'Moka' => ['Moka', 'moka', 'MOKA'],
            'Mongomo' => ['Mongomo', 'mongomo', 'MONGOMO'],
        ],
        'STP' => [
            'São Tomé' => ['São Tomé', 'Sao Tome', 'são tomé', 'sao tome', 'SÃO TOMÉ', 'SAO TOME'],
            'Príncipe' => ['Príncipe', 'Principe', 'príncipe', 'principe', 'PRÍNCIPE', 'PRINCIPE'],
            'Trindade' => ['Trindade', 'trindade', 'TRINDADE'],
            'Guadalupe' => ['Guadalupe', 'guadalupe', 'GUADALUPE'],
            'Neves' => ['Neves', 'neves', 'NEVES'],
            'Santana' => ['Santana', 'santana', 'SANTANA'],
        ],
        'AO' => [
            'Luanda' => ['Luanda', 'luanda', 'LUANDA'],
            'Huambo' => ['Huambo', 'huambo', 'HUAMBO'],
            'Lobito' => ['Lobito', 'lobito', 'LOBITO'],
            'Kuito' => ['Kuito', 'kuito', 'KUITO'],
            'Lubango' => ['Lubango', 'lubango', 'LUBANGO'],
            'Malanje' => ['Malanje', 'malanje', 'MALANJE'],
            'Namibe' => ['Namibe', 'namibe', 'NAMIBE'],
            'Soyo' => ['Soyo', 'soyo', 'SOYO'],
        ],
        'ZM' => [
            'Lusaka' => ['Lusaka', 'lusaka', 'LUSAKA'],
            'Kitwe' => ['Kitwe', 'kitwe', 'KITWE'],
            'Ndola' => ['Ndola', 'ndola', 'NDOLA'],
            'Kabwe' => ['Kabwe', 'kabwe', 'KABWE'],
            'Chingola' => ['Chingola', 'chingola', 'CHINGOLA'],
            'Mufulira' => ['Mufulira', 'mufulira', 'MUFULIRA'],
            'Livingstone' => ['Livingstone', 'livingstone', 'LIVINGSTONE'],
            'Solwezi' => ['Solwezi', 'solwezi', 'SOLWEZI'],
        ],
        'DE' => [
            'Berlin' => ['Berlin', 'berlin', 'BERLIN'],
            'Munich' => ['Munich', 'munich', 'MUNICH'],
            'Hamburg' => ['Hamburg', 'hamburg', 'HAMBURG'],
            'Frankfurt' => ['Frankfurt', 'frankfurt', 'FRANKFURT'],
            'Cologne' => ['Cologne', 'cologne', 'COLOGNE'],
        ],
        'FR' => [
            'Paris' => ['Paris', 'paris', 'PARIS'],
            'Lyon' => ['Lyon', 'lyon', 'LYON'],
            'Marseille' => ['Marseille', 'marseille', 'MARSEILLE'],
            'Toulouse' => ['Toulouse', 'toulouse', 'TOULOUSE'],
            'Nice' => ['Nice', 'nice', 'NICE'],
        ],
    ];

    // Liste des villes valides avec leurs variations (pour compatibilité)
    private const VALID_CITIES = [
        'Berlin' => ['Berlin', 'berlin', 'BERLIN'],
        'Yaoundé' => ['Yaoundé', 'Yaounde', 'yaoundé', 'yaounde', 'YAOUNDÉ', 'YAOUNDE'],
        'Douala' => ['Douala', 'douala', 'DOUALA'],
        'Bamenda' => ['Bamenda', 'bamenda', 'BAMENDA'],
        'Bafoussam' => ['Bafoussam', 'bafoussam', 'BAFOUSSAM'],
        'Garoua' => ['Garoua', 'garoua', 'GAROUA'],
        'Maroua' => ['Maroua', 'maroua', 'MAROUA'],
        'Buea' => ['Buea', 'buea', 'BUEA'],
        'Kumba' => ['Kumba', 'kumba', 'KUMBA'],
        'Limbe' => ['Limbe', 'limbe', 'LIMBE'],
        'Ngaoundéré' => ['Ngaoundéré', 'Ngaoundere', 'ngaoundéré', 'ngaoundere', 'NGAOUNDÉRÉ', 'NGAOUNDERE'],
        'Edéa' => ['Edéa', 'Edea', 'edéa', 'edea', 'EDÉA', 'EDEA'],
        'Kribi' => ['Kribi', 'kribi', 'KRIBI'],
        'Foumban' => ['Foumban', 'foumban', 'FOUMBAN'],
        'Dschang' => ['Dschang', 'dschang', 'DSCHANG'],
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'place_of_birth',
        'base_language',
        'gender',
        'city',
        'country',
        'center_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class)->withTrashed();
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getGenderLabelAttribute()
    {
        return self::genderLabel($this->gender);
    }

    public function getLanguageLabelAttribute()
    {
        return self::languageLabel($this->base_language);
    }

    /**
     * Normalise le nom d'une ville pour éviter les problèmes de filtre
     */
    public static function normalizeCity(string $city): string
    {
        $city = trim($city);
        
        if (empty($city)) {
            return '';
        }

        // Conversion en minuscules et suppression des accents
        $normalized = strtolower($city);
        $normalized = Str::ascii($normalized);
        
        // Normalisation spécifique pour toutes les villes
        $allCities = [];
        foreach (self::CITIES_BY_COUNTRY as $countryCode => $cities) {
            foreach ($cities as $cityName => $variations) {
                foreach ($variations as $variation) {
                    $allCities[strtolower(Str::ascii($variation))] = $cityName;
                }
            }
        }

        return $allCities[$normalized] ?? $city;
    }

    /**
     * Normalise le nom d'un pays pour éviter les problèmes de filtre
     */
    public static function normalizeCountry(string $country): string
    {
        $country = trim($country);
        
        if (empty($country)) {
            return '';
        }

        // Conversion en minuscules et suppression des accents
        $normalized = strtolower($country);
        $normalized = Str::ascii($normalized);
        
        // Normalisation des pays
        $countryMappings = [
            'cameroon' => 'CM',
            'cameroun' => 'CM',
            'cm' => 'CM',
            'gabon' => 'GA',
            'libreville' => 'GA',
            'ga' => 'GA',
            'dr congo' => 'CD',
            'congo' => 'CD',
            'rdc' => 'CD',
            'cd' => 'CD',
            'central african republic' => 'CF',
            'centrafrique' => 'CF',
            'car' => 'CF',
            'cf' => 'CF',
            'republic of congo' => 'CG',
            'congo brazzaville' => 'CG',
            'cg' => 'CG',
            'chad' => 'TD',
            'tchad' => 'TD',
            'td' => 'TD',
            'equatorial guinea' => 'GQ',
            'guinée équatoriale' => 'GQ',
            'gq' => 'GQ',
            'sao tome and principe' => 'STP',
            'sao tomé et principe' => 'STP',
            'stp' => 'STP',
            'angola' => 'AO',
            'ao' => 'AO',
            'zambia' => 'ZM',
            'zambie' => 'ZM',
            'zm' => 'ZM',
            'germany' => 'DE',
            'allemagne' => 'DE',
            'de' => 'DE',
            'deutschland' => 'DE',
            'france' => 'FR',
            'fr' => 'FR',
        ];

        return $countryMappings[$normalized] ?? $country;
    }

    /**
     * Retourne la liste des villes pour un pays spécifique
     */
    public static function getCitiesByCountry(string $countryCode): array
    {
        return self::CITIES_BY_COUNTRY[$countryCode] ?? [];
    }

    /**
     * Retourne la liste des pays valides pour les sélecteurs
     */
    public static function getValidCountries(): array
    {
        return [
            'CM' => 'Cameroun',
            'GA' => 'Gabon',
            'CD' => 'République Démocratique du Congo',
            'CF' => 'République Centrafricaine',
            'CG' => 'République du Congo',
            'TD' => 'Tchad',
            'GQ' => 'Guinée Équatoriale',
            'STP' => 'Sao Tomé et Principe',
            'AO' => 'Angola',
            'ZM' => 'Zambie',
            'DE' => 'Germany', 
            'FR' => 'France',
        ];
    }

    /**
     * Retourne la liste des villes valides pour les sélecteurs (compatibilité)
     */
    public static function getValidCities(): array
    {
        $allCities = [];
        foreach (self::CITIES_BY_COUNTRY as $countryCode => $cities) {
            $allCities = array_merge($allCities, $cities);
        }
        
        return $allCities;
    }

    protected static function normalizeText($value): string
    {
        $normalized = strtolower((string) $value);

        if (function_exists('iconv')) {
            $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $normalized);
            if ($converted !== false) {
                $normalized = strtolower($converted);
            }
        }

        return $normalized;
    }

    public static function normalizeGender($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $normalized = self::normalizeText($value);

        if (in_array($normalized, ['m', 'male', 'masculin', 'homme'], true)) {
            return self::GENDER_MALE;
        }

        if (in_array($normalized, ['f', 'female', 'feminin', 'femme'], true)) {
            return self::GENDER_FEMALE;
        }

        return $value;
    }

    public static function genderLabel($value)
    {
        $normalized = self::normalizeGender($value);

        if ($normalized === self::GENDER_MALE) {
            return 'Male';
        }

        if ($normalized === self::GENDER_FEMALE) {
            return 'Female';
        }

        return $normalized ?: null;
    }

    public static function normalizeLanguage($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $normalized = self::normalizeText($value);

        if (in_array($normalized, ['fr', 'francais', 'french'], true)) {
            return self::LANGUAGE_FRENCH;
        }

        if (in_array($normalized, ['en', 'anglais', 'english'], true)) {
            return self::LANGUAGE_ENGLISH;
        }

        return $value;
    }

    public static function languageLabel($value)
    {
        $normalized = self::normalizeLanguage($value);

        if ($normalized === self::LANGUAGE_FRENCH) {
            return self::LANGUAGE_FRENCH;
        }

        if ($normalized === self::LANGUAGE_ENGLISH) {
            return self::LANGUAGE_ENGLISH;
        }

        return $normalized ?: null;
    }

    /**
     * Boot du modèle pour normaliser automatiquement les données
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($student) {
            if ($student->city) {
                $student->city = self::normalizeCity($student->city);
            }
            if ($student->country) {
                $student->country = self::normalizeCountry($student->country);
            }
        });
    }
}




