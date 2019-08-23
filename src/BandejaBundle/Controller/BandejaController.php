<?php
namespace BandejaBundle\Controller;


use BandejaBundle\Entity\Adjuntos;
use BandejaBundle\Entity\Departamentos;
use BandejaBundle\Entity\DepUsu;
use BandejaBundle\Entity\Derivaciones;
use BandejaBundle\Entity\Documentos;
use BandejaBundle\Entity\Paginator;
use BandejaBundle\Entity\Personas;
use BandejaBundle\Entity\TiposDocumentos;
use BandejaBundle\Form\ArchivarType;
use BandejaBundle\Form\BuscarType;
use BandejaBundle\Form\DerivarType;
use BandejaBundle\Form\DesarchivarType;
use BandejaBundle\Form\FiltersType;
use BandejaBundle\Form\NuevoDocumentoType;
use BandejaBundle\Form\PersonaType;
use BandejaBundle\Form\RecibirType;
use BandejaBundle\Form\RemitenteType;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\Session;

class BandejaController extends Controller
{
    public function indexAction()
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        return $this->redirectToRoute('recibidos_bandeja');
    }

    public function recibidosAction(Request $request, $page = 1)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        $searchForm = $this->createForm('BandejaBundle\Form\BuscarType', null, ['action' => $this->generateUrl('buscar_bandeja')]);
        $derivarForm = $this->createForm('BandejaBundle\Form\DerivarType');
        $filtersForm = $this->createForm('BandejaBundle\Form\FiltersType');
        $archivarForm = $this->createForm('BandejaBundle\Form\ArchivarType');
        $desarchivarForm = $this->createForm('BandejaBundle\Form\DesarchivarType');

        $searchForm->handleRequest($request);
        $derivarForm->handleRequest($request);
        $filtersForm->handleRequest($request);
        $archivarForm->handleRequest($request);
        $desarchivarForm->handleRequest($request);

        $docsByPage = 25; // documentos mostrados por pagina

        if ($filtersForm->isSubmitted() && $filtersForm->isValid()) {
            $filtersData = $filtersForm->getData();

            extract($filtersData); // $mostrar y $limite
        }

        $mostrar = isset($mostrar) ? $mostrar : null;
        $limite = isset($limite) ? $limite : null;

        $max_docs = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('BandejaBundle:Documentos')
                  ->countRecibidosByDepto($this->get('session')->get('departamento'),
                                          $mostrar, $limite);

        $results = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('BandejaBundle:Documentos')
                 ->findRecibidosByDepto($this->get('session')->get('departamento'),
                                        $mostrar, $limite,
                                        ($page - 1) * $docsByPage,
                                        $docsByPage);

        $max_page = (int) ceil($max_docs / $docsByPage);
        $max_page = ! $max_page ? 1 : $max_page; // si es $max_page == 0, cambia a 1

        $documentos = array_filter($results, function($var) {
            return $var instanceof Documentos;
        });

        $derivaciones = array_filter($results, function($var) {
            return $var instanceof Derivaciones;
        });

        $paginator = new Paginator(
            $max_docs,
            (int) $page,
            $docsByPage,
            $this->generateUrl('recibidos_bandeja')
        );

        if ($archivarForm->isSubmitted()) {
            $docs = $request->get('docs');
            $documentos = array();

            if (isset($docs) && ! empty($docs)) {
                $query = $this->getDoctrine()->getManager()->getRepository('BandejaBundle:Documentos')
                       ->createQueryBuilder('docs')
                       ->where('docs.idDoc IN (:DOCS)')
                       ->setParameter('DOCS', $docs)
                       ->getQuery();

                $documentos = $query->getResult();
            } else {
                $this->addFlash('danger', 'No se encontraron documentos para recibir ');

                return $this->redirectToRoute('recibidos_bandeja');
            }

            $em = $this->getDoctrine()->getManager();

            $archivarData = $archivarForm->getData();

            $loginUser = $this->getUser();

            foreach ($documentos as $d) {
                $d->setFechaM(new \DateTime);
                $d->setEstado(0);

                $log = $d->getLog('log');
                $log = ! $log ? $log : $log . '\n';
                $d->setLog($log . date('Y-m-d H:i:s') . ' '
                           . 'ARCHIVAR '
                           . $loginUser->getNombre() . ' '
                           . $archivarData['razon']);

                $em->persist($d);
            }

            $em->flush();

            $last = array_pop($documentos);

            if (count($documentos) > 0) {
                $key = array_search($last->getIdDoc(), $docs);
                unset($docs[$key]);

                $msn = 'Los IDDOC\'s ' . implode(', ', $docs) . ' y ' . $last->getIdDoc() . ' fueron archivados';
            } else
                $msn = 'El IDDOC ' . $last->getIdDoc() . ' fue archivado';;

            $this->addFlash('success', $msn);

            return $this->redirectToRoute('recibidos_bandeja');
        }

        if ($desarchivarForm->isSubmitted()) {
            $docs = $request->get('docs');
            $documentos = array();

            if (isset($docs) && ! empty($docs)) {
                $query = $this->getDoctrine()->getManager()->getRepository('BandejaBundle:Documentos')
                       ->createQueryBuilder('docs')
                       ->where('docs.idDoc IN (:DOCS)')
                       ->setParameter('DOCS', $docs)
                       ->getQuery();

                $documentos = $query->getResult();
            } else {
                $this->addFlash('danger', 'No se encontraron documentos para recibir ');

                return $this->redirectToRoute('recibidos_bandeja');
            }

            $em = $this->getDoctrine()->getManager();

            $desarchivarData = $desarchivarForm->getData();

            $loginUser = $this->getUser();

            foreach ($documentos as $d) {
                $d->setFechaM(new \DateTime);
                $d->setEstado(1);

                $log = $d->getLog('log');
                $log = ! $log ? $log : $log . '\n';
                $d->setLog($log . date('Y-m-d H:i:s') . ' '
                           . 'DESARCHIVADO '
                           . $loginUser->getNombre() . ' '
                           . $desarchivarData['razon']);

                $em->persist($d);
            }

            $em->flush();

            $last = array_pop($documentos);

            if (count($documentos) > 0) {
                $key = array_search($last->getIdDoc(), $docs);
                unset($docs[$key]);

                $msn = 'Los IDDOC\'s ' . implode(', ', $docs) . ' y ' . $last->getIdDoc() . ' fueron desarchivados';
            } else
                $msn = 'El IDDOC ' . $last->getIdDoc() . ' fue desarchivado';;

            $this->addFlash('success', $msn);

            return $this->redirectToRoute('recibidos_bandeja');
        }

        if ($derivarForm->isSubmitted() && $derivarForm->isValid()) {
            $docs = $request->get('docs');
            $documentos = array();

            if (isset($docs) && ! empty($docs)) {
                $query = $this->getDoctrine()->getManager()->getRepository('BandejaBundle:Documentos')
                       ->createQueryBuilder('docs')
                       ->where('docs.idDoc IN (:DOCS)')
                       ->setParameter('DOCS', $docs)
                       ->getQuery();

                $documentos = $query->getResult();
            } else {
                $this->addFlash('danger', 'No se encontraron documentos para recibir ');

                return $this->redirectToRoute('recibidos_bandeja');
            }

            $em = $this->getDoctrine()->getManager();

            $derivarData = $derivarForm->getData();

            $loginUser = $this->getUser();

            foreach ($documentos as $d) {
                $d->setFechaM(new \DateTime);
                $d->setEstado(2);
                $em->persist($d);

                // guardar archivos adjuntos
                $files = $derivarData['adjuntos'];

                if (count($files)) {
                    foreach ($files as $file) {
                        $adjunto = $this->createNewAdjunto($file, $loginUser, $d);
                        $adjunto->upload();
                        $em->persist($adjunto);
                    }
                }

                $expr = new Comparison('encargado', '=', true);
                $encargadoCriteria = new Criteria();
                $encargadoCriteria->where( $expr );

                $derivaciones = $d->getDerivaciones();

                foreach ($derivaciones as $derivacion) {
                    if (! $derivacion->getFechaE())
                        $derivacion->setFechaE(new \DateTime);

                    $em->persist($derivacion);
                }

                foreach ($derivarData['originales'] as $depto) {
                    $derivacion = $this->createNewDerivacion(
                        array('tipo' => 1, 'nota' => $derivarData['nota_original']),
                        $d,
                        $loginUser, $loginUser->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkDepto(),
                        $depto->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkUsuario(), $depto);

                    $em->persist($derivacion);
                }

                foreach ($derivarData['copias'] as $depto) {
                    $derivacion = $this->createNewDerivacion(
                        array('tipo' => 2, 'nota' => $derivarData['nota_copias']),
                        $d,
                        $loginUser, $loginUser->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkDepto(),
                        $depto->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkUsuario(), $depto);

                    $em->persist($derivacion);
                }
            }

            $em->flush();

            $last = array_pop($documentos);

            if (count($documentos) > 0) {
                $key = array_search($last->getIdDoc(), $docs);
                unset($docs[$key]);

                $msn = 'Los IDDOC\'s ' . implode(', ', $docs) . ' y ' . $last->getIdDoc() . ' fueron derivados';
            } else
                $msn = 'El IDDOC ' . $last->getIdDoc() . ' fue derivado';;

            $this->addFlash('success', $msn);

            return $this->redirectToRoute('recibidos_bandeja');
        }

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchData = $searchForm->getData();

            return $this->redirectToRoute('buscar_bandeja', $searchData);
        }

        return $this->render(
            'BandejaBundle:Bandeja:index.html.twig',
            array(
                'paginator' => $paginator->createView(),
                'page' => $page,
                'max_page' => $max_page,
                'menu_op' => 'recibidos',
                'searchForm' => $searchForm->createView(),
                'derivarForm' => $derivarForm->createView(),
                'filtersForm' => $filtersForm->createView(),
                'archivarForm' => $archivarForm->createView(),
                'desarchivarForm' => $desarchivarForm->createView(),
                'documentos' => $documentos,
                'derivaciones' => $derivaciones,
            )
        );
    }

    public function porrecibirAction(Request $request, $page = 1)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        $searchForm = $this->createForm('BandejaBundle\Form\BuscarType', null, ['action' => $this->generateUrl('buscar_bandeja')]);
        $recibirForm = $this->createForm('BandejaBundle\Form\RecibirType');

        $searchForm->handleRequest($request);
        $recibirForm->handleRequest($request);

        $docsByPage = 25; // documentos por página

        $max_docs = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('BandejaBundle:Documentos')
                  ->countPorrecibirByDepto($this->get('session')->get('departamento'));

        $max_page  = (int) ceil($max_docs / $docsByPage);
        $max_page = ! $max_page ? 1 : $max_page; // si es $max_page == 0, cambia a 1

        $results = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('BandejaBundle:Documentos')
                 ->findPorrecibirByDepto($this->get('session')->get('departamento'), ($page - 1) * $docsByPage, $docsByPage);

        $documentos = array_filter($results, function($var) {
            return $var instanceof Documentos;
        });

        $derivaciones = array_filter($results, function($var) {
            return $var instanceof Derivaciones;
        });

        // accion de recibir
        if ($recibirForm->isSubmitted()) {
            $docs = $request->get('docs');
            $documentos = array();

            if (isset($docs) && ! empty($docs)) {
                $query = $this->getDoctrine()->getManager()->getRepository('BandejaBundle:Documentos')
                       ->createQueryBuilder('docs')
                       ->where('docs.idDoc IN (:DOCS)')
                       ->setParameter('DOCS', $docs)
                       ->getQuery();

                $documentos = $query->getResult();
            } else {
                $this->addFlash('danger', 'No se encontraron documentos para recibir ');
            }

            $em = $this->getDoctrine()->getManager();

            foreach ($documentos as $d) {
                $d->setFechaM(new \DateTime);
                $d->setEstado(1); // 1 = NORMAL

                $em->persist($d);
            }

            $em->flush();

            $msn = count($documentos) > 1
                 ? 'Los IDDOC\'s ' . implode(', ', $docs) . ' fueron recibidos'
                 : 'El IDDOC ' . implode(', ', $docs) . ' fue recibido';
            $this->addFlash('success', $msn);

            return $this->redirectToRoute('recibidos_bandeja');
        }

        return $this->render(
            'BandejaBundle:Bandeja:porrecibir.html.twig',
            array(
                'documentos' => $documentos,
                'derivaciones' => $derivaciones,
                'page' => $page,
                'max_page' => $max_page,
                'menu_op' => 'porrecibir',
                'searchForm' => $searchForm->createView(),
                'recibirForm' => $recibirForm->createView(),
            )
        );
    }

    public function despachadosAction(Request $request, $page = 1)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        $searchForm = $this->createForm('BandejaBundle\Form\BuscarType', null,
                                        ['action' => $this->generateUrl('buscar_bandeja')]);

        $docsByPage = 25; // documentos por página

        $max_docs = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('BandejaBundle:Documentos')
                  ->countDespachadosByUsuario($this->getUser(),
                                              $this->get('session')->get('departamento'));

        $max_page  = (int) ceil($max_docs / $docsByPage);
        $max_page = ! $max_page ? 1 : $max_page; // si es $max_page == 0, cambia a 1

        $results = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('BandejaBundle:Documentos')
                 ->findDespachadosByUsuario($this->getUser(),
                                            $this->get('session')->get('departamento'),
                                            ($page - 1) * $docsByPage, $docsByPage);

        $documentos = array_filter($results, function($var) {
            return $var instanceof Documentos;
        });

        $derivaciones = array_filter($results, function($var) {
            return $var instanceof Derivaciones;
        });

        return $this->render(
            'BandejaBundle:Bandeja:despachados.html.twig',
            array(
                'documentos' => $documentos,
                'derivaciones' => $derivaciones,
                'page' => $page,
                'max_page' => $max_page,
                'menu_op' => 'despachados',
                'searchForm' => $searchForm->createView(),
            )
        );
    }

    public function buscarAction(Request $request, $page = 0)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        $searchForm = $this->createForm('BandejaBundle\Form\BuscarType', null, ['action' => $this->generateUrl('buscar_bandeja')]);

        $searchForm->handleRequest($request);

        $searchData = $searchForm->getData();

        $docsByPage = 25; // documentos por página

        $max_docs = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('BandejaBundle:Documentos')
                  ->countBuscarByQuery($this->getUser(), $searchData['query']) / 2;

        $results = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('BandejaBundle:Documentos')
                 ->findBuscarByQuery($this->getUser(), $searchData['query'],
                                     ($page - 1) * $docsByPage, $docsByPage);

        $documentos = array_filter($results, function($var) {
            return $var instanceof Documentos;
        });

        $derivaciones = array_filter($results, function($var) {
            return $var instanceof Derivaciones;
        });

        $max_page  = (int) ceil($max_docs / $docsByPage);
        $max_page = ! $max_page ? 1 : $max_page; // si es $max_page == 0, cambia a 1

        return $this->render(
            'BandejaBundle:Bandeja:buscar.html.twig',
            array(
                'documentos' => $documentos,
                'derivaciones' => $derivaciones,
                'searchForm' => $searchForm->createView(),
                'menu_op' => 'buscar',
                'max_page' => $max_page,
                'page' => $page,
                'searchData' => $searchData,
            )
        );
    }

    public function verAction(Request $request, $id)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        $em = $this->getDoctrine()->getManager();

        $derivarForm = $this->createForm('BandejaBundle\Form\DerivarType');
        $recibirForm = $this->createForm('BandejaBundle\Form\RecibirType');
        $archivarForm = $this->createForm('BandejaBundle\Form\ArchivarType');
        $desarchivarForm = $this->createForm('BandejaBundle\Form\DesarchivarType');

        $derivarForm->handleRequest($request);
        $recibirForm->handleRequest($request);
        $archivarForm->handleRequest($request);
        $desarchivarForm->handleRequest($request);

        // get documento
        $repo = $this->getDoctrine()->getRepository('BandejaBundle:Documentos');
        $query = $repo->createQueryBuilder('doc')
               ->where('doc.idDoc = :ID')
               ->andWhere('doc.fechaE is NULL')
               ->setParameter('ID', $id)
               ->getQuery();

        $documento = $query->getResult(); // getResult() retorna siempre un array

        // get last derivacion
        $repo = $this->getDoctrine()->getRepository('BandejaBundle:Derivaciones');
        $query = $repo->createQueryBuilder('der')
               ->select('max(der.idDerivacion) as idDerivacion')
               ->where('der.fkDoc = :ID')
               ->groupBy('der.fkDoc')
               ->setParameter('ID', $id)
               ->getQuery();

        $maxIdDer = $query->getArrayResult()[0]['idDerivacion'];

        $query = $repo->createQueryBuilder('der')
               ->where('der.idDerivacion = :ID')
               ->setParameter('ID', $maxIdDer)
               ->getQuery();

        $derivacion = $query->getResult()[0];

        $urlArray = explode('/', $request->headers->get('referer'));

        $loginUser = $this->getUser();

        if (!isset($documento) || empty($documento)) {
            $this->addFlash('warning', 'No existe en docuemnto IDDOC ' . $id);
            return $this->redirectToRoute(end($urlArray) . '_bandeja');
        } else
            $documento = $documento[0];

        // get Persona from documento
        $repo = $this->getDoctrine()->getRepository('BandejaBundle:Personas', 'customer');
        $query = $repo->createQueryBuilder('pers')
               ->where('pers.rut = :RUT')
               ->setParameter('RUT', $documento->getFkRutPersona())
               ->getQuery();

        $personaDoc = $query->getResult();

        if (empty($personaDoc)) {
            $this->addFlash('warning', 'Los datos del Remitente no se encuentran disponibles.');
            $personaDoc = new Personas();
        } else
            $personaDoc = $personaDoc[0];

        $adjuntos = $this->getDoctrine()->getRepository('BandejaBundle:Adjuntos')
                  ->findBy(array('fkDoc' => $documento));

        if ($derivarForm->isSubmitted() && $derivarForm->isValid()) {
            $derivarData = $derivarForm->getData();

            // guardar archivos adjuntos
            $files = $derivarData['adjuntos'];

            if (count($files)) {
                foreach ($files as $file) {
                    $adjunto = $this->createNewAdjunto($file, $loginUser, $documento);
                    $adjunto->upload();
                    $em->persist($adjunto);
                }
            }

            $expr = new Comparison('encargado', '=', true);
            $encargadoCriteria = new Criteria();
            $encargadoCriteria->where( $expr );

            $derivaciones = $documento->getDerivaciones();
            $documento->setEstado(2);

            $em->persist($documento);

            foreach ($derivaciones as $derivacion) {
                $derivacion->setFechaE(new \DateTime);
                $em->persist($derivacion);
            }

            foreach ($derivarData['originales'] as $depto) {
                $derivacion = $this->createNewDerivacion(
                    array('tipo' => 1, 'nota' => $derivarData['nota_original']),
                    $documento,
                    $loginUser, $loginUser->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkDepto(),
                    $depto->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkUsuario(), $depto);

                $em->persist($derivacion);
            }

            foreach ($derivarData['copias'] as $depto) {

                $derivacion = $this->createNewDerivacion(
                    array('tipo' => 2, 'nota' => $derivarData['nota_copias']),
                    $documento,
                    $loginUser, $loginUser->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkDepto(),
                    $depto->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkUsuario(), $depto);

                $em->persist($derivacion);
            }

            $em->flush();

            $this->addFlash('success', sprintf(
                'IDDOC %d derivado a %s',
                $documento->getIdDoc(),
                $derivacion->getFkDeptodes()->getDescripcion())
            );

            return $this->redirectToRoute('recibidos_bandeja');
        }

        if ($recibirForm->isSubmitted() && $recibirForm->isValid()) {
            $recibirData = $recibirForm->getData();

            $documento->setEstado(1); // estado NORMAL
            $documento->setFechaM(new \DateTime);

            $em->persist($documento);
            $em->flush();

            $this->addFlash('success', sprintf('IDDOC %d recibido', $documento->getIdDoc()));
            return $this->redirectToRoute('recibidos_bandeja');
        }

        if ($archivarForm->isSubmitted() && $archivarForm->isValid()) {
            $archivarData = $archivarForm->getData();

            $documento->setFechaM(new \DateTime);
            $documento->setEstado(0);

            $log = $documento->getLog('log');
            $log = ! $log ? $log : $log . '\n';
            $documento->setLog($log . date('Y-m-d H:i:s') . ' '
                               . 'ARCHIVADO '
                               . $loginUser->getNombre() . ' '
                               . $archivarData['razon']);

            $em->persist($documento);
            $em->flush();

            $this->addFlash('success', sprintf('IDDOC %d archivado', $documento->getIdDoc()));
            return $this->redirectToRoute('recibidos_bandeja');
        }

        if ($desarchivarForm->isSubmitted() && $desarchivarForm->isValid()) {
            $desarchivarData = $desarchivarForm->getData();

            $documento->setFechaM(new \DateTime);
            $documento->setEstado(1);

            $log = $documento->getLog('log');
            $log = ! $log ? $log : $log . '\n';
            $documento->setLog($log . date('Y-m-d H:i:s') . ' '
                               . 'DESARCHIVADO '
                               . $loginUser->getNombre() . ' '
                               . $desarchivarData['razon']);

            $em->persist($documento);
            $em->flush();

            $this->addFlash('success', sprintf('IDDOC %d desarchivado', $documento->getIdDoc()));
            return $this->redirectToRoute('recibidos_bandeja');
        }

        return $this->render(
            'BandejaBundle:Bandeja:ver.html.twig',
            array(
                'id' => $id,
                'menu_op' => end($urlArray),
                'documento' => $documento,
                'cardex' => $documento->getDerivaciones(),
                'derivacion' => $derivacion,
                'personaDoc' => $personaDoc,
                'adjuntos' => $adjuntos,
                'files_dir' => Adjuntos::UPLOADED_FILE_DIRECTORY,
                'derivarForm' => $derivarForm->createView(),
                'recibirForm' => $recibirForm->createView(),
                'archivarForm' => $archivarForm->createView(),
                'desarchivarForm' => $desarchivarForm->createView(),
            )
        );
    }

    public function descargarAdjuntoAction(Request $request, $id)
    {
        if (! $this->setOnSession())
            return false;

        $adjunto = $this->getDoctrine()->getRepository('BandejaBundle:Adjuntos')
               ->findBy(array('idAdjunto' => $id))[0];

        $response = new BinaryFileResponse(
            Adjuntos::UPLOADED_FILE_DIRECTORY
            . DIRECTORY_SEPARATOR
            . $adjunto->getUrl());

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $adjunto->getNombreOriginal()
        );

        return $response;
    }

    public function nuevoAction(Request $request)
    {
        if (! $this->setOnSession())
            return $this->redirectToRoute('login_access');

        $em = $this->getDoctrine()->getManager();
        $em_admin_lf_barrio = $this->getDoctrine()->getManager('customer');

        $tipos = $this->getTiposDocs();

        $derivarForm = $this->createForm('BandejaBundle\Form\DerivarType');
        $nuevoForm = $this->createForm('BandejaBundle\Form\NuevoDocumentoType');
        $personaForm = $this->createForm('BandejaBundle\Form\PersonaType');
        $remitenteForm = $this->createForm('BandejaBundle\Form\RemitenteType');

        // $nuevoForm es el principal
        $derivarForm->handleRequest($request);
        $nuevoForm->handleRequest($request);
        $personaForm->handleRequest($request);
        $remitenteForm->handleRequest($request);

        if ($nuevoForm->isSubmitted() && $nuevoForm->isValid()) {
            $nuevoData = $nuevoForm->getData();
            $derivarData = $derivarForm->getData();
            $remitenteData = $remitenteForm->getData();
            $personaData = $personaForm->getData();

            if ($remitenteData['id_persona']) {
                $persona = $this->getDoctrine()
                         ->getRepository('BandejaBundle:Personas', 'customer')
                         ->find( $remitenteData['id_persona'] );
            } elseif ($remitenteData['id_depto']) {
                $persona = $this->getDoctrine()
                         ->getRepository('BandejaBundle:Departamentos')
                         ->findEncargado($remitenteData['id_depto']);
            } else {
                $persona = $this->createNewPersona($personaData);

                $em_admin_lf_barrio->persist($persona);
            }

            $loginUser = $this->getUser();
            $nuevoData['persona'] = $persona;

            $documento = $this->createNewDocumento($nuevoData, 2);

            $em->persist($documento);

            // guardar archivos adjuntos
            $files = $derivarData['adjuntos'];

            if (count($files)) {
                foreach( $files as $file ) {
                    $adjunto = $this->createNewAdjunto($file, $loginUser, $documento);

                    $adjunto->upload();

                    $em->persist($adjunto);
                }
            }

            $expr = new Comparison('encargado', '=', true);
            $encargadoCriteria = new Criteria();
            $encargadoCriteria->where( $expr );

            if ($request->get('guardar') === 'derivar') {
                foreach ($derivarData['originales'] as $depto) {
                    $derivacion = $this->createNewDerivacion(
                        array('tipo' => 1, 'nota' => $derivarData['nota_original']),
                        $documento,
                        $loginUser, $loginUser->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkDepto(),
                        $depto->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkUsuario(), $depto);

                    $em->persist($derivacion);
                }

                foreach ($derivarData['copias'] as $depto) {
                    $derivacion = $this->createNewDerivacion(
                        array('tipo' => 2, 'nota' => $derivarData['nota_copias']),
                        $documento,
                        $loginUser, $loginUser->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkDepto(),
                        $depto->getDepUsus()->matching($encargadoCriteria)->get(0)->getFkUsuario(), $depto);

                    $em->persist($derivacion);
                }

                $em->flush();
                $em_admin_lf_barrio->flush();

                $this->addFlash('success', 'Documento derivado');
                return $this->redirectToRoute('recibidos_bandeja');

            } elseif ($request->get('guardar') === 'guardar') {
                $derivacion = $this->createNewDerivacion(
                    array('tipo' => 1, 'nota' => $derivarData['nota_original']),
                    $documento,
                    $loginUser, $loginUser->getDepUsus()->matching($criteria)->get(0)->getFkDepto(),
                    $loginUser, $loginUser->getDepUsus()->matching($criteria)->get(0)->getFkDepto());

                $em->persist($derivacion);
                $em->flush();
                $em_admin_lf_barrio->flush();

                $this->addFlash('success', 'Documento guardado');
                return $this->redirectToRoute('porrecibir_bandeja');
            }
        }

        return $this->render(
            'BandejaBundle:Bandeja:editar.html.twig',
            array(
                'menu_op' => '',
                'tipos' => $tipos,
                'derivarForm' => $derivarForm->createView(),
                'nuevoForm' => $nuevoForm->createView(),
                'personaForm' => $personaForm->createView(),
                'remitenteForm' => $remitenteForm->createView(),
            )
        );
    }

    public function personasAction($str = "")
    {
        if (! $this->setOnSession())
            return false;

        $personas = $this->getPersonas($str);
        $persArray = [];

        foreach($personas as $p) {
            $persArray[ $p->getRut() ] = array(
                'rut' => number_format($p->getRut(), 0, ',', '.') . '-' . $p->getVRut(),
                'id' => $p->getRut(),
                'nombre' => $p->getNombreCompleto(),
                'tipo' => 'pers',
            );
        }

        $response = new JsonResponse($persArray);

        return $response;
    }

    public function departamentosAction($str = "")
    {
        if (! $this->setOnSession())
            return false;

        $deptos = $this->getDeptos($str);
        $deptosArray = [];

        foreach($deptos as $d) {
            $expr = new Comparison('encargado', '=', true);

            $criteria = new Criteria();
            $criteria->where( $expr );

            $encargados = $d->getDepUsus()->matching( $criteria );

            if (! $encargados->isEmpty())
                $persona = $this->getDoctrine()->getRepository('BandejaBundle:Personas', 'customer')
                         ->find($encargados->first()->getFkUsuario()->getFkPersona());
            else
                $persona = null;

            //dump($persona); die;
            $deptosArray[ $d->getIdDepartamento() ] = array(
                'idDepartamento' => $d->getIdDepartamento(),
                'descripcion' => $d->getDescripcion(),
                'encargado' => ! $persona ? null : $persona->getNombreCompleto(),
                'idEncargado' => $encargados->isEmpty() ? null : $encargados->first()->getFkUsuario()->getIdUsuario(),
                'tipo' => 'depto',
            );
        }

        $response = new JsonResponse($deptosArray);

        return $response;
    }

    public function toggleMenuAction(Request $request)
    {
        $cookies = $request->cookies;
        $response = new Response('OK', Response::HTTP_OK, array('content-type' => 'text/plain') );

        if ($cookies->get('open_menu') === null)
            $response->headers->setCookie(new Cookie('open_menu', false));

        if ($cookies->get('open_menu'))
            $response->headers->setCookie(new Cookie('open_menu', false));
        else
            $response->headers->setCookie(new Cookie('open_menu', true));

        return $response;
    }

    private function getDeptos($str = "")
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:Departamentos');

        $query = $repository->createQueryBuilder('depto')
               ->where('depto.descripcion like :STR')
               ->orderBy('depto.descripcion', 'ASC')
               ->setParameter(':STR', '%'.$str.'%')
               ->getQuery();

        return $query->getResult();
    }

    private function getTiposDocs()
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:TiposDocumentos');

        $query = $repository->createQueryBuilder('tipodoc')
               ->orderBy('tipodoc.abrev', 'ASC')
               ->getQuery();

        return $query->getResult();
    }

    private function getPersonas($str)
    {
        $repository = $this->getDoctrine()->getRepository('BandejaBundle:Personas', 'customer');

        $query = $repository->createQueryBuilder('pers')
               ->where('pers.nombres like :STR')
               ->orWhere('pers.apellidopaterno like :STR')
               ->orWhere('pers.apellidomaterno like :STR')
               ->orWhere('CONCAT(pers.rut,\'-\',pers.vrut) like :STR')
               ->setParameter(':STR', $str.'%')
               ->orderBy('pers.apellidopaterno, pers.apellidomaterno, pers.nombres', 'ASC')
               ->getQuery();

        return $query->getResult();
    }

    private function createNewPersona($personaData)
    {
        $persona = new Personas();

        $persona->setRut( $personaData['rut'] );
        $persona->setVrut( $personaData['dv'] );
        $persona->setNombres( $personaData['nombres'] );
        $persona->setApellidoPaterno( $personaData['apellidopaterno']);
        $persona->setApellidoMaterno( $personaData['apellidomaterno']
                                      ? $personaData['apellidomaterno']
                                      : '');

        $persona->setNombreCalle( $personaData['nombre_calle'] );
        $persona->setNumdirec( $personaData['numdirec'] );
        $persona->setReferenciadir( $personaData['referenciadir'] ? $personaData['referenciadir'] : '' );
        $persona->setNombreComuna( $personaData['nombre_comuna'] );

        $persona->setFono( $personaData['fono'] ? $personaData['fono'] : '' );
        $persona->setFono2( $personaData['fono_2'] ? $personaData['fono_2'] : '' );
        $persona->setEmail( $personaData['email'] ? $personaData['email'] : '' );

        $persona->setFechaNacimiento( $personaData['fecha_nacimiento'] ? $personaData['fecha_nacimiento'] : '0000-00-00' );
        $persona->setSexo( $personaData['sexo'] );

        $persona->setUnidadV('');
        $persona->setFechaReg( new \DateTime() );

        return $persona;
    }

    private function createNewDocumento($nuevoData, $tipo = 2)
    {
        $documento = new Documentos();
        $documento->setFkTipoDoc($nuevoData['fkTipoDoc']);
        $documento->setNroExpediente($nuevoData['nroExpediente']);
        $documento->setFechaDoc( $nuevoData['fechaDoc'] );
        $documento->setAnt($nuevoData['ant']);
        $documento->setMat($nuevoData['mat']);
        $documento->setExt($nuevoData['ext']);
        $documento->setEstado($tipo); // 0=ARCHIVADO,1=NORMAL,2=PORRECIBIR
        $documento->setFkRutPersona($nuevoData['persona']->getRut());
        $documento->setFkUsuario($this->getUser());
        $documento->setFechaC( new \DateTime() );
        $documento->setFechaM( new \DateTime() );

        return $documento;
    }

    private function createNewDerivacion($derivarData, $documento, $remitente, $deptorem, $destinatario, $deptodes)
    {
        $derivacion = new Derivaciones();

        $derivacion->setTipo($derivarData['tipo']);
        $derivacion->setNota($derivarData['nota']);
        $derivacion->setFkDoc($documento);
        $derivacion->setFechaC(new \DateTime());
        $derivacion->setFechaM(new \DateTime());

        $derivacion->setFkRemitente($remitente);
        $derivacion->setFkDeptorem($deptorem);

        $derivacion->setFkDestinatario($destinatario);
        $derivacion->setFkDeptodes($deptodes);

        return $derivacion;
    }

    private function createNewAdjunto($file, $usuario, $documento)
    {
        $adjunto = new Adjuntos();
        $adjunto->setFile($file);
        $adjunto->setNombreOriginal($file->getClientOriginalName());
        $adjunto->setUrl(uniqid(null, true));

        $adjunto->setTipo(1);
        $adjunto->setFkDoc($documento);
        $adjunto->setFechaC(new \DateTime());
        $adjunto->setFechaM(new \DateTime());
        $adjunto->setFkUsuario($usuario);

        return $adjunto;
    }

    private function setOnSession()
    {
        $session = $this->get('session');
        $loginUser = $this->getUser();
        $persona = $this->getDoctrine()
                 ->getRepository('BandejaBundle:Personas', 'customer')
                 ->find($loginUser->getFkPersona());

        // Check the login user data
        if (! $loginUser) {
            $this->addFlash('danger', 'No existe el usuario.');
            return false;
        }

        if (! $persona) {
            $this->addFlash('danger', 'El usuario no tiene sus datos personales.');
            return false;
        }

        if (! $loginUser->getDepUsus()->get(0)->getFkDepto() instanceof Departamentos) {
            $this->addFlash('danger', 'El usuario no pertenece a ningun departamento');
            return false;
        }

        // Set session variables
        if ($session->get('departamento') === null)
            $session->set('departamento', $loginUser->getDepUsus()->get(0)->getFkDepto());

        if ($session->get('user_persona') === null)
            $session->set('user_persona', $persona);

        return true;
    }
}
