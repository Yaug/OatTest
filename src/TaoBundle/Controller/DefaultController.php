<?php

namespace TaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\VarDumper\VarDumper;
use TaoBundle\Entity\Taker;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('@Tao/Default/index.html.twig');
    }

    /**
     * @param Request $request
     *
     * @Route("/api/takers/{from}", name="get_takers", defaults={"from"=0}))
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTakersAction(Request $request, $from)
    {
        $takers = $this->getDoctrine()->getRepository(Taker::class)->loadTakers($from, 10);

        return new JsonResponse($takers);
    }

    /**
     * @param Request $request
     *
     * @Route("/api/taker/{id}", name="get_taker"))
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTakerAction(Request $request, $id)
    {
        $taker = $this->getDoctrine()->getRepository(Taker::class)->find($id);
        if(!$taker instanceof Taker) {
            throw new NotFoundHttpException("Taker not found");
        } else {
            return new JsonResponse($taker->toArray());
        }
    }
}
