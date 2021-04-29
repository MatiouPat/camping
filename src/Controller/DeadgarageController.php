<?php


namespace App\Controller;


use App\Entity\Deadgarage;
use App\Repository\DeadgarageRepository;
use App\Repository\StayRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeadgarageController extends AbstractController
{

    /**
     * @var DeadgarageRepository
     */
    private $deadgarageRepository;
    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * @var StayRepository
     */
    private $stayRepository;

    public function __construct(DeadgarageRepository $deadgarageRepository, StayRepository $stayRepository, ObjectManager $em)
    {
        $this->deadgarageRepository = $deadgarageRepository;
        $this->stayRepository = $stayRepository;
        $this->em = $em;
    }

    public function add(Deadgarage $deadGarageForm)
    {
        $this->em->persist($deadGarageForm);
        $this->em->flush();
    }

}