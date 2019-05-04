<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class NewsForm
{    
    /**
     * Query text
     *
     * @Assert\NotBlank()
     */
    private $qText;
    
    public function getQtext()
    {
        return $this->qText;
    } 
    
    public function setQtext($qText)
    {
        $this->qText = $qText;
    } 
}
