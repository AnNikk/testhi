<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\NewsList;

class NewsLst
{
    public function listNews(Array $arrNews)
    {
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $arrNews, 
            $array->getInt('page', 1), 
            10 /*limit per page*/
        );

        // parameters to template
        return $this->render('listnews.html.twig', array('pagination' => $pagination));
    }    
}
