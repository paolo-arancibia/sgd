<?php
namespace BandejaBundle\Controller;
 
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
    
class BandejaController extends Controller
{
    public function indexAction()
    {
        return $this->forward('BandejaBundle:Bandeja:recibidos');
    }

    public function recibidosAction($page = 0)
    {
        $deptos = $this->getDeptos();

        $searchForm = $this->createFormBuilder(null, array(
            'action' => '',
            'method' => 'GET'))
                    ->add('searchText', TextType::class)
                    ->getForm();

        return $this->render(
            'BandejaBundle:Bandeja:index.html.twig', 
            array(
                'page' => $page,
                'menu_op' => 'bandeja',
                'deptos' => $deptos,
                'searchForm' => $searchForm->createView(),
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
