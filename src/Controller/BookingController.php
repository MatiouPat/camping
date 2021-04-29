<?php


namespace App\Controller;


use App\Calendar;
use App\Entity\Stay;
use App\Form\StayType;
use App\Repository\EntryRepository;
use App\Repository\StayRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{

    /**
     * @var StayRepository
     */
    private $stayRepository;
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(StayRepository $stayRepository, EntryRepository $entryRepository, ObjectManager $em)
    {

        $this->stayRepository = $stayRepository;
        $this->entryRepository = $entryRepository;
        $this->em = $em;
    }

    /**
     * @Route("/reservations", name="booking.index", options = { "expose" = true })
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request) : Response
    {
        if ($request->query->get('status')){
            $calendar = new Calendar();
            $month = Calendar::findNumberByMonth($request->query->get('month'));
            $year = intval($request->query->get('year'));
            $booking = [];
            if ($request->query->get('status') === '-'){
                $month--;
                if ($month === 0){
                    $month = 12;
                    $year--;
                }
                $calendar = new Calendar($month, $year);
                $bookings = $this->stayRepository->findAllBooking();
                foreach ($bookings as $b){
                    if ($b->getArrivedAt()->format('Y-m') === strval($year).'-'.strval($month)){
                        if(!isset($booking[$b->getArrivedAt()->format('Y-m-d')])){
                            $booking[$b->getArrivedAt()->format('Y-m-d')] = [['name' => $b->getEntry()->getGenderName() . ' ' . $b->getEntry()->getSurname() . ' ' . $b->getEntry()->getName(), 'arrived_at' => $b->getArrivedAt()]];
                        }else{
                            $booking[$b->getArrivedAt()->format('Y-m-d')][] = ['name' => $b->getEntry()->getGenderName() . ' ' . $b->getEntry()->getSurname() . ' ' . $b->getEntry()->getName(), 'arrived_at' => $b->getArrivedAt()];
                        }
                    }
                }
            }
            elseif ($request->query->get('status') === '+'){
                $month++;
                if ($month === 13){
                    $month = 1;
                    $year++;
                }
                $calendar = new Calendar($month, $year);

                $bookings = $this->stayRepository->findAllBooking();
                foreach ($bookings as $b){
                    if ($b->getArrivedAt()->format('Y-m') === strval($year).'-'.strval($month)){
                        if(!isset($booking[$b->getArrivedAt()->format('Y-m-d')])){
                            $booking[$b->getArrivedAt()->format('Y-m-d')] = [['name' => $b->getEntry()->getGenderName() . ' ' . $b->getEntry()->getSurname() . ' ' . $b->getEntry()->getName(), 'arrived_at' => $b->getArrivedAt()]];
                        }else{
                            $booking[$b->getArrivedAt()->format('Y-m-d')][] = ['name' => $b->getEntry()->getGenderName() . ' ' . $b->getEntry()->getSurname() . ' ' . $b->getEntry()->getName(), 'arrived_at' => $b->getArrivedAt()];
                        }
                    }
                }
            }

            $start = $calendar->getStartingDay();
            $start = $start->format('N') == "1" ? $start : $calendar->getStartingDay()->modify('last monday');
            $week = [];
            $month = [];
            for ($i = 0; $i < $calendar->getWeeks(); $i++){
                foreach ($calendar->days as $k => $day){
                    $in = $calendar->withinMonth($start->modify("+" . ($k + $i * 7) . "days"));
                    $bookingForDay = $booking[$start->modify("+" . ($k + $i * 7) . "days")->format('Y-m-d')] ?? [];
                    array_push($week, [$day, $start->modify("+" . ($k + $i * 7) . "day")->format('d'), $in, $bookingForDay]);
                }
                array_push($month, $week);
                $week = [];
            }
            return $this->json([
                'start' => $start,
                'date' => $calendar->toString(),
                'month' => $month
            ]);
        }else{
            $calendar = new Calendar();
            $date = new \DateTime('now');
            $start = $calendar->getStartingDay();
            $start = $start->format('N') == "1" ? $start : $calendar->getStartingDay()->modify('last monday');
            $week = [];
            $month = [];

            $bookings = $this->stayRepository->findAllBooking();
            foreach ($bookings as $b){
                if ($b->getArrivedAt()->format('Y-m') === $date->format('Y-m')){
                    if(!isset($booking[$b->getArrivedAt()->format('Y-m-d')])){
                        $booking[$b->getArrivedAt()->format('Y-m-d')] = [$b];
                    }else{
                        $booking[$b->getArrivedAt()->format('Y-m-d')][] = $b;
                    }
                }
            }

            for ($i = 0; $i < $calendar->getWeeks(); $i++){
                foreach ($calendar->days as $k => $day){
                    $in = $calendar->withinMonth($start->modify("+" . ($k + $i * 7) . "day"));
                    $bookingForDay = $booking[$start->modify("+" . ($k + $i * 7) . "days")->format('Y-m-d')] ?? [];
                    array_push($week, [$day, $start->modify("+" . ($k + $i * 7) . "day")->format('d'), $in, $bookingForDay]);
                }
                array_push($month, $week);
                $week = [];
            }

            $entries = $this->entryRepository->findAll();
            return $this->render("pages/reservations/index.html.twig", [
                'date' => $calendar->toString(),
                'month' => $month,
                'entries' => $entries
            ]);
        }
    }

    /**
     * @Route("/reservations/view-{date}", name="booking.view", options = { "expose" = true })
     * @return Response
     * @throws \Exception
     */
    public function view(string $date) : Response
    {
        $date = new \DateTime($date);
        $booking = [];
        $woodCaravan = [];
        $bookings = $this->stayRepository->findAllBookingForBareGround();
        $woodCaravans = $this->stayRepository->findAllBookingForWoodenCaravan();
        foreach ($woodCaravans as $w){
            if ($w->getArrivedAt()->format('Y-m-d') === strval($date->format('Y-m-d'))){
                if(!isset($booking[$w->getArrivedAt()->format('Y-m-d')])){
                    $woodCaravan[] = [$w];
                }else{
                    $woodCaravan[] = $w;
                }
            }
        }
        foreach ($bookings as $b){
            if ($b->getArrivedAt()->format('Y-m-d') === strval($date->format('Y-m-d'))){
                if(!isset($booking[$b->getArrivedAt()->format('Y-m-d')])){
                    $booking[] = [$b];
                }else{
                    $booking[][] = $b;
                }
            }
        }
        return $this->render("pages/reservations/view.html.twig", [
            'date' => $date->format('d/m/Y'),
            'woodenCaravan' => $woodCaravan,
            'booking' => $booking
        ]);
    }

    /**
     * @Route("/reservations/arrival-{id}", name="booking.arrival")
     * @param Request $request
     * @param Stay $stay
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function arrival(Request $request, Stay $stay)
    {
        $stay->setBooking(0);
        $stay->setArrivedAt(Calendar::getTodayDate());
        $this->em->persist($stay);
        $this->em->flush();
        $this->addFlash('success', 'Votre réservation a bien été changé en séjour en cours');
        return $this->redirect($request->headers->get('referer'));
    }
}