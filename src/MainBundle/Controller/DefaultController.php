<?php

namespace MainBundle\Controller;

use JMS\Serializer\SerializerBuilder;
use MainBundle\Entity\Product;
use MainBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function getProductsDataAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('MainBundle:Product');

        $query = $repository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(8)
            ->getQuery();

        $products = $query->getResult();

        $serializer = SerializerBuilder::create()->build();
        $data = $serializer->serialize($products, 'json');

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    public function indexAction(Request $request)
    {
        $product = new Product();
        $em = $this->getDoctrine()->getEntityManager();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            var_dump($product);
            // ... save the meetup, redirect etc.
            return new Response(json_encode(array('status'=>'success')));
        }


        //$form->handleRequest($request);
        /*
                if ($form->isValid()) {

                    $em->persist($product);
                    $em->flush();

                    //return $this->redirectToRoute('prorent_admin_products');
                }*/

        return $this->render('MainBundle:Default:index.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
