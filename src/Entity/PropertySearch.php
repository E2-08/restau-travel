<?php
namespace App\Entity;
class PropertySearch{
    /**
     * @var string|null
     */
    private $language;

    /**
     * @var string|null
     */
    private $city;



    /**
     * Get the value of language
     *
     * @return  string|null
     */ 
    public function getlanguage()
    {
        return $this->language;
    }

    /**
     * Set the value of language
     *
     * @param  string|null  $language
     *
     * @return  self
     */ 
    public function setlanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get the value of city
     *
     * @return  string|null
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @param  string|null  $city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }
}
