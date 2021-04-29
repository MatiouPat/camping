<?php


namespace App;


class Calendar
{
    /**
     * Jours de la semaine
     * @var array
     */
    public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    /**
     * Mois de l'année
     * @var array
     */
    private $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    /**
     * Mois demandé
     * @var int
     */
    private $month;

    /**
     * Année demandé
     * @var int
     */
    private $year;

    /**
     * Calendar constructor.
     * @param int $month Le compris entre 1 et 12
     * @param int $year L'année
     * @throws \Exception
     */
    public function __construct(?int $month = null, ?int $year = null)
    {
        if ($month === null || $month < 1 || $month > 12){
            $month = intval(date('m'));
        }
        if ($year === null){
            $year = intval(date('Y'));
        }
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Retourne le mois en toute lettre (ex: Octobre 2019)
     * @return string
     */
    public function toString () : string
    {
        return $this->months[$this->month - 1] . ' ' . $this->year;
    }

    /**
     * Retourne le premier jour du mois
     * @return \DateTimeInterface
     * @throws \Exception
     */
    public function getStartingDay () : \DateTimeInterface
    {
        return new \DateTimeImmutable("{$this->year}-{$this->month}-01");
    }

    /**
     * Retourne le nombre de semaines dans le mois
     * @return int
     * @throws \Exception
     */
    public function getWeeks () : int
    {
        $start = $this->getStartingDay();
        $end = $start->modify('+1 month -1 day');
        $startWeek = intval($start->format('W'));
        $endWeek = intval($end->format('W'));
        if ($endWeek === 1){
            $endWeek = intval($end->modify('-7 days')->format('W')) + 1;
        }
        $weeks = $endWeek - $startWeek + 1;
        if ($weeks < 0){
            $weeks = intval($end->format('W'));
        }
        return $weeks;
    }

    /**
     * Regarde si le jour est dans le mois en cours
     * @param \DateTimeImmutable $date
     * @return bool
     * @throws \Exception
     */
    public function withinMonth (\DateTimeImmutable $date) : bool
    {
        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');
    }

    public static function getTodayDate() : \DateTime
    {
        $date = new \DateTime('now');
        $date->setTime(0,0,0,0);
        return $date;
    }

    public static function getTomorrowDate() : \DateTime
    {
        $date = date_modify(new \DateTime('now'), '+1 day');
        $date->setTime(0,0,0,0);
        return $date;
    }

    /**
     * @param string $month
     * @return int
     */
    public static function findNumberByMonth(string $month) : int
    {
        switch ($month){
            case "Janvier":
                return 1;
            case "Février":
                return 2;
            case "Mars":
                return 3;
            case "Avril":
                return 4;
            case "Mai":
                return 5;
            case "Juin":
                return 6;
            case "Juillet":
                return 7;
            case "Août":
                return 8;
            case "Septembre":
                return 9;
            case "Octobre":
                return 10;
            case "Novembre":
                return 11;
            case "Décembre":
                return 12;
            default:
                return "Il n\'existe aucun numéro";
        }
    }

    /**
     * @param int $number
     * @return string
     */
    public static function findDayByNumber(int $number) : string
    {
        switch ($number){
            case 1:
                return "Lundi";
            case 2:
                return "Mardi";
            case 3:
                return "Mercredi";
            case 4:
                return "Jeudi";
            case 5:
                return "Vendredi";
            case 6:
                return "Samedi";
            case 7:
                return "Dimanche";
            default:
                return "Il n\'existe aucuns jours avec ce numéro";
        }
    }

    /**
     * @param int $number
     * @return string
     */
    public static function findMouthByNumber(int $number) : string
    {
        switch ($number){
            case 1:
                return "Janvier";
            case 2:
                return "Février";
            case 3:
                return "Mars";
            case 4:
                return "Avril";
            case 5:
                return "Mai";
            case 6:
                return "Juin";
            case 7:
                return "Juillet";
            case 8:
                return "Août";
            case 9:
                return "Septembre";
            case 10:
                return "Octobre";
            case 11:
                return "Novembre";
            case 12:
                return "Décembre";
            default:
                return "Il n\'existe aucuns mois avec ce numéro";
        }
    }


}