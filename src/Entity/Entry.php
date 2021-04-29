<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntryRepository")
 */
class Entry
{

    const GENDERS = ["Mme", "Mr", "Mr & Mme"];

    const NATIONALITY = array(
        'AF' => 'Afghanistan',
        'ZA' => 'Afrique Du Sud',
        'AX' => 'Åland, Îles',
        'AL' => 'Albanie',
        'DZ' => 'Algérie',
        'DE' => 'Allemagne',
        'AD' => 'Andorre',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctique',
        'AG' => 'Antigua-Et-Barbuda',
        'SA' => 'Arabie Saoudite',
        'AR' => 'Argentine',
        'AM' => 'Arménie',
        'AW' => 'Aruba',
        'AU' => 'Australie',
        'AT' => 'Autriche',
        'AZ' => 'Azerbaïdjan',
        'BS' => 'Bahamas',
        'BH' => 'Bahreïn',
        'BD' => 'Bangladesh',
        'BB' => 'Barbade',
        'BY' => 'Bélarus',
        'BE' => 'Belgique',
        'BZ' => 'Belize',
        'BJ' => 'Bénin',
        'BM' => 'Bermudes',
        'BT' => 'Bhoutan',
        'BO' => 'Bolivie, L\'état Plurinational De',
        'BQ' => 'Bonaire, Saint-Eustache Et Saba',
        'BA' => 'Bosnie-Herzégovine',
        'BW' => 'Botswana',
        'BV' => 'Bouvet, Île',
        'BR' => 'Brésil',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgarie',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KY' => 'Caïmans, Îles',
        'KH' => 'Cambodge',
        'CM' => 'Cameroun',
        'CA' => 'Canada',
        'CV' => 'Cap-Vert',
        'CF' => 'Centrafricaine, République',
        'CL' => 'Chili',
        'CN' => 'Chine',
        'CX' => 'Christmas, Île',
        'CY' => 'Chypre',
        'CC' => 'Cocos (Keeling), Îles',
        'CO' => 'Colombie',
        'KM' => 'Comores',
        'CG' => 'Congo',
        'CD' => 'Congo, La République Démocratique Du',
        'CK' => 'Cook, Îles',
        'KR' => 'Corée, République De',
        'KP' => 'Corée, République Populaire Démocratique De',
        'CR' => 'Costa Rica',
        'CI' => 'Côte D\'ivoire',
        'HR' => 'Croatie',
        'CU' => 'Cuba',
        'CW' => 'Curaçao',
        'DK' => 'Danemark',
        'DJ' => 'Djibouti',
        'DO' => 'Dominicaine, République',
        'DM' => 'Dominique',
        'EG' => 'Égypte',
        'SV' => 'El Salvador',
        'AE' => 'Émirats Arabes Unis',
        'EC' => 'Équateur',
        'ER' => 'Érythrée',
        'ES' => 'Espagne',
        'EE' => 'Estonie',
        'US' => 'États-Unis',
        'ET' => 'Éthiopie',
        'FK' => 'Falkland, Îles (Malvinas)',
        'FO' => 'Féroé, Îles',
        'FJ' => 'Fidji',
        'FI' => 'Finlande',
        'FR' => 'France',
        'GA' => 'Gabon',
        'GM' => 'Gambie',
        'GE' => 'Géorgie',
        'GS' => 'Géorgie Du Sud-Et-Les Îles Sandwich Du Sud',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Grèce',
        'GD' => 'Grenade',
        'GL' => 'Groenland',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernesey',
        'GN' => 'Guinée',
        'GW' => 'Guinée-Bissau',
        'GQ' => 'Guinée Équatoriale',
        'GY' => 'Guyana',
        'GF' => 'Guyane Française',
        'HT' => 'Haïti',
        'HM' => 'Heard-Et-Îles Macdonald, Île',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hongrie',
        'IM' => 'Île De Man',
        'UM' => 'Îles Mineures Éloignées Des États-Unis',
        'VG' => 'Îles Vierges Britanniques',
        'VI' => 'Îles Vierges Des États-Unis',
        'IN' => 'Inde',
        'ID' => 'Indonésie',
        'IR' => 'Iran, République Islamique D\'',
        'IQ' => 'Iraq',
        'IE' => 'Irlande',
        'IS' => 'Islande',
        'IL' => 'Israël',
        'IT' => 'Italie',
        'JM' => 'Jamaïque',
        'JP' => 'Japon',
        'JE' => 'Jersey',
        'JO' => 'Jordanie',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KG' => 'Kirghizistan',
        'KI' => 'Kiribati',
        'KW' => 'Koweït',
        'LA' => 'Lao, République Démocratique Populaire',
        'LS' => 'Lesotho',
        'LV' => 'Lettonie',
        'LB' => 'Liban',
        'LR' => 'Libéria',
        'LY' => 'Libye',
        'LI' => 'Liechtenstein',
        'LT' => 'Lituanie',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macédoine, L\'ex-République Yougoslave De',
        'MG' => 'Madagascar',
        'MY' => 'Malaisie',
        'MW' => 'Malawi',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malte',
        'MP' => 'Mariannes Du Nord, Îles',
        'MA' => 'Maroc',
        'MH' => 'Marshall, Îles',
        'MQ' => 'Martinique',
        'MU' => 'Maurice',
        'MR' => 'Mauritanie',
        'YT' => 'Mayotte',
        'MX' => 'Mexique',
        'FM' => 'Micronésie, États Fédérés De',
        'MD' => 'Moldova, République De',
        'MC' => 'Monaco',
        'MN' => 'Mongolie',
        'ME' => 'Monténégro',
        'MS' => 'Montserrat',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibie',
        'NR' => 'Nauru',
        'NP' => 'Népal',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigéria',
        'NU' => 'Niué',
        'NF' => 'Norfolk, Île',
        'NO' => 'Norvège',
        'NC' => 'Nouvelle-Calédonie',
        'NZ' => 'Nouvelle-Zélande',
        'IO' => 'Océan Indien, Territoire Britannique De L\'',
        'OM' => 'Oman',
        'UG' => 'Ouganda',
        'UZ' => 'Ouzbékistan',
        'PK' => 'Pakistan',
        'PW' => 'Palaos',
        'PS' => 'Palestinien Occupé, Territoire',
        'PA' => 'Panama',
        'PG' => 'Papouasie-Nouvelle-Guinée',
        'PY' => 'Paraguay',
        'NL' => 'Pays-Bas',
        'PE' => 'Pérou',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Pologne',
        'PF' => 'Polynésie Française',
        'PR' => 'Porto Rico',
        'PT' => 'Portugal',
        'QA' => 'Qatar',
        'RE' => 'Réunion',
        'RO' => 'Roumanie',
        'GB' => 'Royaume-Uni',
        'RU' => 'Russie, Fédération De',
        'RW' => 'Rwanda',
        'EH' => 'Sahara Occidental',
        'BL' => 'Saint-Barthélemy',
        'SH' => 'Sainte-Hélène, Ascension Et Tristan Da Cunha',
        'LC' => 'Sainte-Lucie',
        'KN' => 'Saint-Kitts-Et-Nevis',
        'SM' => 'Saint-Marin',
        'MF' => 'Saint-Martin(Partie Française)',
        'SX' => 'Saint-Martin (Partie Néerlandaise)',
        'PM' => 'Saint-Pierre-Et-Miquelon',
        'VA' => 'Saint-Siège (État De La Cité Du Vatican)',
        'VC' => 'Saint-Vincent-Et-Les Grenadines',
        'SB' => 'Salomon, Îles',
        'WS' => 'Samoa',
        'AS' => 'Samoa Américaines',
        'ST' => 'Sao Tomé-Et-Principe',
        'SN' => 'Sénégal',
        'RS' => 'Serbie',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapour',
        'SK' => 'Slovaquie',
        'SI' => 'Slovénie',
        'SO' => 'Somalie',
        'SD' => 'Soudan',
        'SS' => 'Soudan Du Sud',
        'LK' => 'Sri Lanka',
        'SE' => 'Suède',
        'CH' => 'Suisse',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard Et Île Jan Mayen',
        'SZ' => 'Swaziland',
        'SY' => 'Syrienne, République Arabe',
        'TJ' => 'Tadjikistan',
        'TW' => 'Taïwan, Province De Chine',
        'TZ' => 'Tanzanie, République-Unie De',
        'TD' => 'Tchad',
        'CZ' => 'Tchèque, République',
        'TF' => 'Terres Australes Françaises',
        'TH' => 'Thaïlande',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinité-Et-Tobago',
        'TN' => 'Tunisie',
        'TM' => 'Turkménistan',
        'TC' => 'Turks-Et-Caïcos, Îles',
        'TR' => 'Turquie',
        'TV' => 'Tuvalu',
        'UA' => 'Ukraine',
        'UY' => 'Uruguay',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela, République Bolivarienne Du',
        'VN' => 'Vietnam',
        'WF' => 'Wallis Et Futuna',
        'YE' => 'Yémen',
        'ZM' => 'Zambie',
        'ZW' => 'Zimbabwe'
    );

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="integer")
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nationality;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $home_phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Stay", mappedBy="entry")
     */
    private $stays;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Service", mappedBy="entry")
     */
    private $services;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Family", inversedBy="entries")
     */
    private $family;

    public function __construct()
    {
        $this->stays = new ArrayCollection();
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormattedId(): ?string
    {
        $r = "C";
        for ($i = 0; $i < (10 - strlen(strval($this->id))); $i++){
            $r .= "0";
        }
        $r .= strval($this->id);
        return $r;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function getGenderName(): ?string
    {
        return self::GENDERS[$this->gender];
    }

    public function setGender(int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getCountry(): ?string
    {
        return strtoupper(self::NATIONALITY[$this->nationality]);
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getHomePhone(): ?string
    {
        return $this->home_phone;
    }

    public function setHomePhone(?string $home_phone): self
    {
        $this->home_phone = $home_phone;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|Stay[]
     */
    public function getStays(): Collection
    {
        return $this->stays;
    }

    public function addStay(Stay $stay): self
    {
        if (!$this->stays->contains($stay)) {
            $this->stays[] = $stay;
            $stay->setEntry($this);
        }

        return $this;
    }

    public function removeStay(Stay $stay): self
    {
        if ($this->stays->contains($stay)) {
            $this->stays->removeElement($stay);
            // set the owning side to null (unless already changed)
            if ($stay->getEntry() === $this) {
                $stay->setEntry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setEntry($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            // set the owning side to null (unless already changed)
            if ($service->getEntry() === $this) {
                $service->setEntry(null);
            }
        }

        return $this;
    }

    public function getFamily(): ?Family
    {
        return $this->family;
    }

    public function setFamily(?Family $family): self
    {
        $this->family = $family;

        return $this;
    }
}
