<?php

namespace App\Controller;

use App\Calendar;
use App\Entity\Entry;
use App\Entity\Stay;
use App\Form\StayType;
use App\Repository\EntryRepository;
use App\Repository\StayRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StayController extends AbstractController
{

    /**
     * @var StayRepository
     */
    private $stayRepository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(StayRepository $stayRepository, ObjectManager $em)
    {
        $this->stayRepository = $stayRepository;
        $this->em = $em;
    }

    /**
     * @Route("/sejours", name="stay.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request) : Response
    {
        $currentStays = $this->stayRepository->findCurrentStays(Calendar::getTodayDate());
        $lastStays = $paginator->paginate($this->stayRepository->findAllLastStaysQuery(Calendar::getTodayDate()),
            $request->query->getInt('page',1),
            15
        );
        return $this->render("pages/sejours/index.html.twig", [
            'currentStays' => $currentStays,
            'lastStays' => $lastStays
        ]);
    }

    /**
     * @Route("/sejours/add", name="stay.add", options = { "expose" = true })
     * @param Request $request
     * @param EntryRepository $entryRepository
     * @return Response
     */
    public function add(Request $request, EntryRepository $entryRepository) : Response
    {
        $stay = new Stay();
        $stay->setArrivedAt(Calendar::getTodayDate());
        $stay->setLeavedAt(Calendar::getTomorrowDate());
        $entries = $entryRepository->findAll();
        $form = $this->createForm(StayType::class, $stay);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if($form['arrived_at']->getData()->format('Y-m-d') <= Calendar::getTodayDate()->format('Y-m-d')){
                $stay->setBooking(0);
            }else{
                $stay->setBooking(1);
            }
            $this->em->persist($stay);
            $this->em->flush();
            if ($stay->getBooking() === false){
                $this->addFlash('success', 'Votre séjour a bien été ajouté');
                return $this->redirectToRoute('stay.index');
            }else{
                $this->addFlash('success', 'Votre réservation a bien été ajouté');
                return $this->redirectToRoute('booking.index');
            }
        }

        return $this->render("pages/sejours/add.html.twig",[
            'form' => $form->createView(),
            'entries' => $entries
        ]);
    }

    /**
     * @Route("/sejours/remove-{id}", name="stay.remove")
     * @param Request $request
     * @param Stay $stay
     * @return Response
     */
    public function delete(Request $request,Stay $stay) : Response
    {
        if ($stay->getBooking() === false){
            $this->addFlash('success', 'Votre séjour a bien été supprimé');
        }else{
            $this->addFlash('success', 'Votre réservation a bien été supprimé');
        }
        $this->em->remove($stay);
        $this->em->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/sejours/modify-{id}", name="stay.modify")
     * @param Request $request
     * @param Stay $stay
     * @param EntryRepository $entryRepository
     * @return Response
     */
    public function modify(Request $request, Stay $stay, EntryRepository $entryRepository) : Response
    {
        $form = $this->createForm(StayType::class, $stay);
        $form->get('arrived_at')->setData($stay->getArrivedAt());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($stay);
            $this->em->flush();
            if ($stay->getBooking() === false){
                $this->addFlash('success', 'Votre séjour a bien été modifié');
            }else{
                $this->addFlash('success', 'Votre réservation a bien été modifié');
            }
            return $this->redirectToRoute('entry.view', ['id' => $stay->getEntry()->getId()]);
        }

        return $this->render("pages/sejours/modify.html.twig",[
            'form' => $form->createView(),
            'id' => $stay->getId()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function research(Request $request) : Response
    {
        $name = $request->query->get('research');
        $result = $this->entryRepository->findAllStayByCriteria($name);
        return $this->json($result);
    }

}
