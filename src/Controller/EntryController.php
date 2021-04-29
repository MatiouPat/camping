<?php

namespace App\Controller;

use App\Calendar;
use App\Entity\Deadgarage;
use App\Entity\Entry;
use App\Entity\Service;
use App\Form\DeadgarageType;
use App\Form\EntryType;
use App\Form\ServiceType;
use App\Repository\DeadgarageRepository;
use App\Repository\EntryRepository;
use App\Repository\ServiceRepository;
use App\Repository\StayRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntryController extends AbstractController
{

    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(EntryRepository $entryRepository, ObjectManager $em)
    {
        $this->entryRepository = $entryRepository;
        $this->em = $em;
    }

    /**
     * @Route("/clients", name="entry.index")
     * @return Response
     */
    public function index() : Response
    {
        $entries = $this->entryRepository->findAll();
        return $this->render("pages/clients/index.html.twig", compact('entries'));
    }

    /**
     * @Route("/clients/add", name="entry.add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request) : Response
    {
        $entry = new Entry();
        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($entry);
            $this->em->flush();
            $this->addFlash('success', 'Votre client a bien été créé');
            return $this->redirectToRoute('entry.index');
        }

        return $this->render("pages/clients/add.html.twig",[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/clients/remove-{id}", name="entry.remove")
     * @param Request $request
     * @param Entry $entry
     * @return Response
     */
    public function delete(Request $request, Entry $entry) : Response
    {
        $this->em->remove($entry);
        $this->em->flush();
        $this->addFlash('success', 'Votre client a bien été supprimé');
        return $this->redirectToRoute('entry.index');
    }

    /**
     * @Route("/clients/modify-{id}", name="entry.modify")
     * @param Request $request
     * @param Entry $entry
     * @return Response
     */
    public function modify(Request $request, Entry $entry) : Response
    {
        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($entry);
            $this->em->flush();
            $this->addFlash('success', 'Votre client a bien été modifié');
            return $this->redirectToRoute('entry.view', ['id' => $entry->getId()]);
        }

        return $this->render("pages/clients/modify.html.twig",[
            'form' => $form->createView(),
            'id' => $entry->getId(),
            'name' => $entry->getName()
        ]);
    }

    /**
     * @Route("/clients/view-{id}", name="entry.view", options = { "expose" = true })
     * @param Request $request
     * @param Entry $entry
     * @param StayRepository $stayRepository
     * @param ServiceRepository $serviceRepository
     * @param DeadgarageRepository $deadgarageRepository
     * @return Response
     * @throws \Exception
     */
    public function view(Request $request, Entry $entry, StayRepository $stayRepository, ServiceRepository $serviceRepository, DeadgarageRepository $deadgarageRepository) : Response
    {
        $serviceController = new ServiceController($serviceRepository, $this->entryRepository, $this->em);
        $deadGarageController = new DeadgarageController($deadgarageRepository, $stayRepository, $this->em);
        $currentDate = new \DateTime();
        $currentStays = $stayRepository->findCurrentStaysByEntry($entry->getId(), $currentDate->format('Y-m-d'));
        $currentStayNumber = count($currentStays);
        $lastStay = $stayRepository->findLastStayByEntry($entry->getId(), $currentDate->format('Y-m-d'));
        $booking = $stayRepository->findNextBookingByEntry($entry->getId(), $currentDate->format('Y-m-d'));
        $notPaidStays = $stayRepository->findNotPaidStaysByEntry($entry->getId());
        $deadGarages = null;
        if ($currentStays != []){
            $deadGarages = $deadgarageRepository->findDeadGaragebyStay($currentStays[0]->getId());
        }

        $deadGarage = new Deadgarage();
        $deadGarage->setStartedAt(Calendar::getTodayDate());
        $deadGarage->setStoppedAt(Calendar::getTomorrowDate());
        $deadGarageForm = $this->createForm(DeadgarageType::class, $deadGarage);
        $deadGarageForm->handleRequest($request);
        if ($deadGarageForm->isSubmitted() && $deadGarageForm->isValid()){
            $deadGarage->setStay($currentStays[0]);
            $deadGarageController->add($deadGarage);
            $this->addFlash('success', 'Votre garage mort a bien été ajouté');
            return $this->redirectToRoute('entry.view', ['id' => $entry->getId()]);
        }

        $services = $serviceController->view($entry->getId());
        $service = new Service();
        $serviceForm = $this->createForm(ServiceType::class, $service);
        $serviceForm->handleRequest($request);
        if ($serviceForm->isSubmitted() && $serviceForm->isValid()){
            if ($serviceForm->getData()->getWashingToken() != 0 || $serviceForm->getData()->getDryingToken() != 0 || $serviceForm->getData()->getExternalShower() != 0){
                $service->setEntry($entry);
                $serviceController->add($service);
                $this->addFlash('success', 'Vos services ont bien été ajouté');
                return $this->redirectToRoute('entry.view', ['id' => $entry->getId()]);
            }
        }

        return $this->render("pages/clients/view.html.twig", [
            'entry' => $entry,
            'currentStays' => $currentStays,
            'currentStayNumber' => $currentStayNumber,
            'lastStay' => $lastStay,
            'booking' => $booking,
            'services' => $services,
            'serviceForm' => $serviceForm->createView(),
            'deadGarages' => $deadGarages,
            'deadGarageForm' => $deadGarageForm->createView(),
            'notPaidStays' => $notPaidStays
        ]);
    }

    /**
     * @Route("/clients/research", name="entry.research", options = { "expose" = true })
     * @param Request $request
     * @return Response
     */
    public function research(Request $request) : Response
    {
        $name = $request->query->get('name');
        $result = $this->entryRepository->findAllEntryByCriteria($name);
        return $this->json($result);
    }

}
