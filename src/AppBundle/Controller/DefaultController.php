<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Giveaway;
use AppBundle\Form\GiveawayType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * @Route("/search", name="search")
     */
    public function listAction(Request $request)
    {
  	



      $form = $this->get('form.factory')->create(new GiveawayType());
      if ($this->get('request')->query->has('submit-filter')) { 
        $form->bind($this->get('request'));
        
        // initialize a query builder
        $filterBuilder = $this->get('doctrine.orm.entity_manager')
          ->getRepository('AppBundle:Giveaway')
          ->createQueryBuilder('e');

          $formEntry = $request->get('appbundle_giveaway'); 
         $filterBuilder->where("e.name like :search")
		->setParameter("search",  '%'.$formEntry['name'].'%');
          
        // build the query from the given form object
        //$this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder); 
        $resultQuery = $filterBuilder->getQuery();

        $filteredEntities = $resultQuery->getArrayResult();
        return $this->render('AppBundle:Giveaway:list.html.twig', array(
        	'form' => $form->createView(),
          'giveawaylist' => $filteredEntities,
          ));
      }



      return $this->render('AppBundle:Giveaway:list.html.twig', array(
        	'form' => $form->createView(),
        	'giveawaylist' => false,
        ));

 
    }

}
