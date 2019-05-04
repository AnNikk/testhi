<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\SearchForm;
use App\Entity\NewsForm;
use App\Entity\NewsList;
use App\Entity\News;

class NewsController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request)
    {
        $sf = new NewsForm();
        $form = $this->createForm(SearchForm::class, $sf);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $q = $sf->getQtext();
            //dd($q);
            
            $this->container->get('session')->set('q', $q);
            
            return $this->redirectToRoute('news_list');
        }
        
        return $this->render('searchform.html.twig', array(
              'form' => $form->createView(),
            ));
    }
    
    /**
     * @Route("/news/{id}", name="news")
     */
    public function news($id)
    {
        $nl = $this->container->get('session')->get('nl');
        $nn = new News($id, $nl);
        $nnews = $nn->getNnews();

        if (empty($nnews))
        {
            return $this->render('error.html.twig', array(
                  'error' => 'Ошибка: Неверный id новости',
                ));
        }
        
        return $this->render('news.html.twig', array(
              'nnews' => $nnews,
            ));
    }

    /**
     * @Route("/newslist", name="news_list")
     */
    public function newsList(PaginatorInterface $paginator, Request $request)
    {
        $q = $this->container->get('session')->get('q');
        $nl = new NewsList($q);
        $nList = $nl->getNList();
        
        //dd($nList);
        
        if (empty($nList))
        {
            //dd($nl);
            return $this->render('error.html.twig', array(
                  'error' => 'Ошибка: Недоступен список новостей ВК. Вероятно, превышен лимит запросов.',
                ));
        }
        
        $this->container->get('session')->set('nl', $nList);
        
        $page = 1;
        $pagination = $paginator->paginate($nList, $request->query->getInt('page', 1), 10);
        
        //dd($pagination);
        
        return $this->render('listnews.html.twig', array(
              'newslist' => $pagination,
              'qqq' => $q,
            ));
        //return $this->render('listnews.html.twig', array(
        //      'newslist' => $nList,
        //    ));
    }
}
