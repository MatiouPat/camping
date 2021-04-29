<?php

namespace App\Controller;

use App\Calendar;
use App\Repository\BillRepository;
use App\Repository\DeadgarageRepository;
use App\Repository\EntryRepository;
use App\Repository\ServiceRepository;
use App\Repository\StayRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BillController extends AbstractController
{

    /**
     * @var BillRepository
     */
    private $billRepository;
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var StayRepository
     */
    private $stayRepository;
    /**
     * @var ServiceRepository
     */
    private $serviceRepository;
    /**
     * @var DeadgarageRepository
     */
    private $deadgarageRepository;

    public function __construct(BillRepository $billRepository, EntryRepository $entryRepository, StayRepository $stayRepository, ServiceRepository $serviceRepository, DeadgarageRepository $deadgarageRepository)
    {

        $this->billRepository = $billRepository;
        $this->entryRepository = $entryRepository;
        $this->stayRepository = $stayRepository;
        $this->serviceRepository = $serviceRepository;
        $this->deadgarageRepository = $deadgarageRepository;
    }

    /**
     * @param Request $request
     * @Route("/factures/pay", name="bill.pay")
     * @return Response
     */
    public function pay(Request $request)
    {
        $entry = $this->entryRepository->find($request->query->get('entry'));
        $service = [];
        if ($request->query->get('service')){
            $service = [
                'washing_token' => $request->query->get('service')[0][1],
                'drying_token' => $request->query->get('service')[0][3],
                'external_shower' => $request->query->get('service')[0][5]
            ];
        }
        $stays = [];
        $staysGet = null;
        if ($request->query->get('stays')){
            $staysGet = $request->query->get('stays');
        }
        if ($staysGet){
            foreach ($staysGet as $stayGet){
                array_push($stays, $this->stayRepository->findStayWithDeadGarage($stayGet)[0]);
            }
        }

        $bill = $this->calcul($stays, $service);

        return $this->render("pages/factures/pay.html.twig",[
            'entry' => $entry,
            'bill' => $bill
        ]);
    }

    /**
     * @param Request $request
     * @param Pdf $pdf
     * @param EntryRepository $entryRepository
     * @return Response
     * @throws \Exception
     * @Route("/factures/view/pdf", name="bill.viewPdf")
     */
    public function viewPdf(Request $request, Pdf $pdf)
    {
        /*Prérequis du Controller*/
        /*$stayId = $request->query->get('stays');
        $billId = $request->query->get('bill');
        $entryId = $request->query->get('entry');*/
        $billId = 2;
        $entryId = 1;


        $bill = $this->billRepository->find($billId);
        $stay = $this->stayRepository->findStaysByBill($billId);
        $service = $this->serviceRepository->findServicesByBill($billId);
        $entry = $this->entryRepository->find($entryId);
        $datetime = new \DateTime();
        $mouth = Calendar::findMouthByNumber($datetime->format('n'));
        $dayNumber = Calendar::findDayByNumber($datetime->format('N'));
        $date = [
            $dayNumber,
            $datetime->format('d'),
            $datetime->format('W'),
            $mouth,
            $datetime->format('Y')
        ];


        $html = $this->renderView('pages/factures/viewPdf.html.twig', [
            'date' => $date,
            'bill' => $bill,
            'entry' => $entry,
            'stays' => $stay,
            'services' => $service
        ]);

        $filename = 'test';
        return new Response(
            $pdf->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
        /*return new PdfResponse(
          $pdf->getOutputFromHtml($html),
          'facture.html'
        );*/
    }

    private function calcul(array $stays, array $services) : array
    {
        $r = [
            'total' => null,
            'touristTax' => null,
            'priceHT' => null,
            'reduction' => null,
            'reductionPrice' => null,
            'startingPrice' => null,
            'stay' => [],
            'service' => []
        ];

        if ($stays){
            foreach ($stays as $stayCalcul){
                $stay = [
                    'stay' => null,
                    'totalPrice' => null,
                    'priceWithoutTT' => null,
                    'touristTax' => null
                ];
                $stay['stay'] = $stayCalcul;
                $deadGarageD = 0;
                foreach ($stayCalcul->getDeadgarages() as $deadgarage){
                    $deadGarageD = $deadGarageD + $deadgarage->getDuringDeadGarage();
                }
                $d = $stayCalcul->getDuringStay() - $deadGarageD;
                if ($d >= 20){
                    $r['reduction'] = 20;
                }elseif ( $d >= 10){
                    $r['reduction'] = 10;
                }
                $stay['touristTax'] = ($stayCalcul->getAdult() * 0.22 * $d);
                $stay['priceWithoutTT'] = ($stayCalcul->getAdult() * 2.35 * $d +
                $stayCalcul->getTeenager() * 2.35 * $d +
                $stayCalcul->getChild() * 1.10 * $d +
                $stayCalcul->getCar() * 1.00 * $d +
                $stayCalcul->getMotorBike() * 0.50 * $d +
                $stayCalcul->getCampingCar() * 3.00 * $d +
                $stayCalcul->getCaravan() * 2.00 * $d +
                $stayCalcul->getTent() * 2.00 * $d +
                $stayCalcul->getElectricity() * 1.95 * $d +
                $stayCalcul->getPet() * 0.70 * $d) +
                $deadGarageD * 1.65;
                $stay['totalPrice'] = $stay['priceWithoutTT'] + $stay['touristTax'];
                array_push($r['stay'],$stay);
            }
        }
        if ($services){
            $service = [
                'service' => null,
                'totalPrice' => null
            ];
            $service['service'] = $services;
            $service['totalPrice'] = ($services['washing_token'] * 3.10 +
            $services['drying_token'] * 3.00 +
            $services['external_shower'] * 3.20);
            $r['priceHT'] = $service['totalPrice'];
            array_push($r['service'],$service);
        }
        foreach ($r['stay'] as $stay){
            $r['priceHT'] = $r['priceHT'] + $stay['priceWithoutTT'];
            $r['touristTax'] = $r['touristTax'] + $stay['touristTax'];
        }
        if ($r['reduction']){
            $r['startingPrice'] = $r['priceHT'];
            $r['priceHT'] = ($r['priceHT'] * (100 - $r['reduction']) / 100);
            $r['reductionPrice'] = $r['priceHT'] - $r['startingPrice'];
        }
        $r['total'] = $r['priceHT'] + $r['touristTax'];

        return $r;
    }

}