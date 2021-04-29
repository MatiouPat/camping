<?php


namespace App\Controller;


use App\Entity\Service;
use App\Repository\EntryRepository;
use App\Repository\ServiceRepository;
use Doctrine\Common\Persistence\ObjectManager;

class ServiceController
{

    /**
     * @var ServiceRepository
     */
    private $serviceRepository;
    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * @var EntryRepository
     */
    private $entryRepository;

    public function __construct(ServiceRepository $serviceRepository, EntryRepository $entryRepository, ObjectManager $em)
    {
        $this->serviceRepository = $serviceRepository;
        $this->em = $em;
        $this->entryRepository = $entryRepository;
    }

    public function add(Service $serviceForm)
    {
        $date = new \DateTime('now');
        $entry = $this->entryRepository->find($serviceForm->getEntry()->getId());

        $service = $this->serviceRepository->findActualServiceByEntryForDate($entry, $date->format('Y-m-d'));
        if ($service == []){
            $service = new Service();
        }else{
            $service = $service[0];
        }
        $service->setEntry($entry);
        $service->setGivedAt($date);

        $service->setWashingToken($service->getWashingToken() + $serviceForm->getWashingToken());
        $service->setDryingToken($service->getDryingToken() + $serviceForm->getDryingToken());
        $service->setExternalShower($service->getExternalShower() + $serviceForm->getExternalShower());
        $this->em->persist($service);
        $this->em->flush();
    }

    public function view(int $entry)
    {
        $services = $this->serviceRepository->findActualServiceByEntry($entry);
        $r = new Service();
        $r->setWashingToken(0);
        $r->setDryingToken(0);
        $r->setExternalShower(0);
        if ($services === []){
            return $r;
        }else{
            foreach ($services as $service){
                $r->setWashingToken( $r->getWashingToken() + $service->getWashingToken());
                $r->setDryingToken( $r->getDryingToken() + $service->getDryingToken());
                $r->setExternalShower( $r->getExternalShower() + $service->getExternalShower());
            }
            return $r;
        }
    }

}