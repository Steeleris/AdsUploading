<?php

namespace MainBundle\Controller;

use JMS\Serializer\SerializerBuilder;
use MainBundle\Entity\Product;
use MainBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * Returns products data in JSON
     *
     * @return Response
     */
    public function getProductsDataAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('MainBundle:Product')
            ->findSixOrderDesc();

        $serializer = SerializerBuilder::create()->build();
        $data = $serializer->serialize($products, 'json');

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Main page rendering and form handling
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function indexAction(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $ajax = $request->isXmlHttpRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();

                return new JsonResponse(array('status'=>'success'));
            } else {
                if ($ajax) {
                    $err = $this->get('main.from_errors');
                    $errors = $err->getFormErrors($form);

                    return new JsonResponse(array(
                        'status' => 'fail',
                        'errors' => $errors,
                        'html' => $this->renderView('MainBundle:Default:index.html.twig', array(
                            'form' => $form->createView()
                        ))
                    ));
                }
            }
        }

        return $this->render('MainBundle:Default:index.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
