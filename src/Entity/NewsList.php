<?php

namespace App\Entity;

class NewsList
{    
    const ACCESS_TOKEN = 'd4a5efead4a5efead4a5efea82d4cfda28dd4a5d4a5efea88644fe05224f624e8da4494';
    
    private $nList;
    private $nUsers;
    private $nGroups;

    public function __construct($q)
    {
        $this->arrNews = $this->vkRequest($q);
    }

    public function getNList()
    {
        return $this->nList;
    }  
    
    public function getNUsers()
    {
        return $this->nUsers;
    }  
    
    public function getNGroups()
    {
        return $this->nGroups;
    }  
    
    public function listNews()
    {
    }

    protected function vkRequest($q)
    {
        $vq = "https://api.vk.com/method/newsfeed.search?v=5.95&q=" . urlencode($q) . "&extended=1&count=100&access_token=" . self::ACCESS_TOKEN;
        $res = file_get_contents("$vq");
        $res = json_decode($res);

       
        //dump($q);
        //dump($vq);
        //dd($nList);
        
        $this->nList  = $res->response->items;
        $this->nUsers = $res->response->profiles;
        $this->nGroups = $res->response->groups;
        
        // добавляем в nList информацию о пользователе
        foreach ($this->nList as $i => &$n)
        {
            $uid = $n->from_id;
            //dump($uid);
            if ($uid > 0)
            {
                foreach ($this->nUsers as $j => $u)
                {
                    if ($u->id == $uid)
                    {
                        $n->name = $u->first_name . ' ' . $u->last_name;
                        break;
                    }
                }
            } else 
            {
                foreach ($this->nGroups as $k => $g)
                {
                    if (-$g->id == $uid)
                    {
                        $n->name = $g->name;
                        break;
                    }
                }
            }
        }

        //dd($this->nList);

    }
}
