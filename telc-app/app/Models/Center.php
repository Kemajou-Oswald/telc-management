<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Center extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'city',
        'country',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function primaryUsers()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function sessions()
    {
        return $this->hasMany(ExamSession::class);
    }

    public function bankDeposits()
    {
        return $this->hasMany(BankDeposit::class);
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

    /**
     * Boot du modèle pour normaliser automatiquement les données
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($center) {
            if ($center->city) {
                $center->city = self::normalizeCity($center->city);
            }
            if ($center->country) {
                $center->country = self::normalizeCountry($center->country);
            }
        });
    }
}

