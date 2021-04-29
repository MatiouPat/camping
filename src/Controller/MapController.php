<?php

namespace App\Controller;

use App\Calendar;
use App\Repository\DeadgarageRepository;
use App\Repository\EntryRepository;
use App\Repository\StayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @var \DateTime
     */
    private $datetime;
    /**
     * @var StayRepository
     */
    private $stayRepository;
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var DeadgarageRepository
     */
    private $deadgarageRepository;

    /**
     * MapController constructor.
     * @throws \Exception
     */
    public function __construct(EntryRepository $entryRepository, StayRepository $stayRepository, DeadgarageRepository $deadgarageRepository)
    {
        $this->datetime = new \DateTime();
        $this->datetime->setTime(0,0,0,0);
        $this->stayRepository = $stayRepository;
        $this->entryRepository = $entryRepository;
        $this->deadgarageRepository = $deadgarageRepository;
    }

    /**
     * @Route("/", name="map.index")
     * @return Response
     */
    public function index() : Response
    {
        $mouth = Calendar::findMouthByNumber($this->datetime->format('n'));
        $dayNumber = Calendar::findDayByNumber($this->datetime->format('N'));
        $date = [
            $dayNumber,
            $this->datetime->format('d'),
            $this->datetime->format('W'),
            $mouth,
            $this->datetime->format('Y')
        ];

        return $this->render("pages/map.html.twig", [
            'date' => $date
        ]);
    }

    /**
     * @Route("/map", name="map.info", options = { "expose" = true })
     * @param Request $request
     * @param DeadgarageRepository $deadgarageRepository
     * @return Response
     * @throws \Exception
     */
    public function mapinfo(Request $request) : Response
    {
        $tab = [];
        $date = $request->query->get('date');
        $dateWeek = $request->query->get('weekNumber');
        $week = $request->query->get('week');
        $day = $request->query->get('day');
        if ($week){
            if ($week === '-'){
                $this->datetime->setISODate(intval(substr($date,0,4)), $dateWeek-1, '1');
                $this->datetime->setTime(0,0,0,0);
            }else{
                $this->datetime->setISODate(intval(substr($date,0,4)), $dateWeek+1, '1');
                $this->datetime->setTime(0,0,0,0);
            }
        }elseif ($day){
            if ($day === '-'){
                $this->datetime->setDate(intval(substr($date,0,4)), intval(substr($date,5,7)), intval(substr($date,8,10))-1);
                $this->datetime->setTime(0,0,0,0);
            }else{
                $this->datetime->setDate(intval(substr($date,0,4)), intval(substr($date,5,7)), intval(substr($date,8,10))+1);
                $this->datetime->setTime(0,0,0,0);
            }
        }else{
            $this->datetime->setDate(intval(substr($date,0,4)), intval(substr($date,5,7)), intval(substr($date,8,10)));
            $this->datetime->setTime(0,0,0,0);
        }

        $entries = $this->entryRepository->findAll();
        foreach ($entries as $entry){
            $stays = $this->stayRepository->findCurrentStaysAndBookingsByEntry($entry, $this->datetime);
            foreach ($stays as $stay){
                $deadgarage = $this->deadgarageRepository->findCurrentDeadGaragebyStay($stay->getId(), $this->datetime);
                if ($deadgarage){
                    $deadgarage = true;
                }else{
                    $deadgarage = false;
                }
                array_push($tab, [
                    $stay->getInformation($this->datetime),
                    $deadgarage,
                    [
                        "arrived_at" => $stay->getArrivedAt()->format('d/m/Y'),
                        "leaved_at" => $stay->getLeavedAt()->format('d/m/Y')
                    ]
                ]);
            }
        }

        $mouth = Calendar::findMouthByNumber($this->datetime->format('n'));
        $dayNumber = Calendar::findDayByNumber($this->datetime->format('N'));
        $date = [
            $dayNumber,
            $this->datetime->format('d'),
            $this->datetime->format('W'),
            $mouth,
            $this->datetime->format('Y')
        ];
        $tabFinal = [
            $tab,
            $date
        ];
        return $this->json($tabFinal);
    }

}