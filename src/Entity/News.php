<?php

namespace App\Entity;

class News
{    
    private $nnews;
    private $newsDate;
    private $newsAuthor;
    private $newsText;
    private $newsPhotos;
    private $newsLikes;

    public function __construct($id, $nl)
    {
        foreach ($nl as $nn)
        {
            if ($nn->id == $id){
                $this->nnews = $nn;
                break;
            }
        }
        if (!empty($this->nnews))
        {
            $this->minmaxPhotos();
        }
        //dd($this->nnews);
    }
    
    public function getNnews()
    {
        return $this->nnews;
    }
    
    protected function minmaxPhotos()
    {
        if (isset($this->nnews->attachments))
        {
            foreach ($this->nnews->attachments as $i => $att)
            {
                if ($att->type == 'photo')
                {
                    $minX = 999999;
                    $maxX = 0;
                    $jmin = $jmax = 0;
                    foreach ($att->photo->sizes as $j => $ph)
                    {
                        if ($ph->width < $minX){$minX = $ph->width; $jmin = $j;}
                        if ($ph->width > $maxX){$maxX = $ph->width; $jmax = $j;}
                    }
                    $att->photo->minphoto = $att->photo->sizes[$jmin];
                    $att->photo->maxphoto = $att->photo->sizes[$jmax];
                }
            }
        }
    }
}
