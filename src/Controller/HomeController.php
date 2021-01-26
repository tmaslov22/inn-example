<?php

namespace App\Controller;

use App\Entity\Inn;
use App\Form\InnFormType;
use App\Service\InnApiCheckerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, InnApiCheckerService $apiCheckerService): Response
    {
        $form = $this->createForm(InnFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $innData = $form->getData();

            $innRepo = $this->getDoctrine()->getRepository(Inn::class);
            $innEntity = $innRepo->findActualInn($innData->getInn());


            if(!$innEntity) {

                try {

                    $innEntity = $innRepo->find($innData->getInn());
                    if(!$innEntity) $innEntity = new Inn();

                    $payload = $apiCheckerService->check($innData->getInn());

                    $innEntity->setInn($innData->getInn());
                    $innEntity->setPayload($payload);

                    $em = $this->getDoctrine()->getManager();

                    $em->persist($innEntity);
                    $em->flush();

                } catch (\Throwable $e) {

                    $form->get('inn')->addError(new FormError($e->getMessage()));
                }
            }

            if($form->isValid()) {
                return $this->render('home/result.html.twig', [
                    'innEntity' => $innEntity,
                ]);
            }
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
