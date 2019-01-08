<?php
// src/BandejaBundle/Controller/HelloController.php
namespace BandejaBundle\Controller;
 
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    
class BandejaController extends Controller
{
    public function indexAction()
    {
        $response = $this->forward('BandejaBundle:Bandeja:recibidos');
        return $response;
    }

    public function recibidosAction($page = 0)
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:Departamentos');

        $query =  $repository->createQueryBuilder('depto')
               ->orderBy('depto.descripcion', 'ASC')
               ->getQuery();

        $deptos = $query->getResult();

        return $this->render(
            'BandejaBundle:Bandeja:index.html.twig', 
            array(
                'page' => $page,
                'menu_op' => 'bandeja',
                'deptos' => $deptos
            )
        );
   
    }
 
    public function porrecibirAction($page = 0)
    {
        return $this->render(
            'BandejaBundle:Bandeja:porrecibir.html.twig',
            array(
                'page' => $page,
                'menu_op' => 'porrecibir'
            )
        );
    }

    public function despachadosAction($page = 0)
    {
        return $this->render(
            'BandejaBundle:Bandeja:despachados.html.twig',
            array(
                'page' => $page,
                'menu_op' => 'despachados'
            )
        );
    }

    public function verAction($id)
    {
        $deptos = $this->getDeptos();

        return $this->render(
            'BandejaBundle:Bandeja:ver.html.twig',
            array(
                'id' => $id,
                'deptos' => $deptos
            )
        );
    }

    public function editarAction($id)
    {
        $deptos = $this->getDeptos();
        $tipos = $this->getTiposDocs();

        return $this->render(
            'BandejaBundle:Bandeja:editar.html.twig',
            array(
                'id' => $id,
                'deptos' => $deptos,
                'tipos' => $tipos
            )
        );
    }

    private function getDeptos()
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:Departamentos');

        $query =  $repository->createQueryBuilder('depto')
               ->orderBy('depto.descripcion', 'ASC')
               ->getQuery();

        return $query->getResult();
    }


    private function getTiposDocs()
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:TiposDocumentos');

        $query =  $repository->createQueryBuilder('tipodoc')
               ->orderBy('tipodoc.abrev', 'ASC')
               ->getQuery();

        return $query->getResult();
    }

}
