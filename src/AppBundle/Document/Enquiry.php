<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="data")
 */
class Enquiry
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Date
     */
    protected $arrival;

    /**
     * @MongoDB\Date
     */
    protected $departure;

    /**
     * @MongoDB\String
     */
    protected $country;

    /**
     * @MongoDB\Int
     */
    protected $adults;

    /**
     * @MongoDB\Int
     */
    protected $children;

    /**
     * @MongoDB\Int
     */
    protected $destination;

    /**
     * @MongoDB\Int
     */
    protected $category;

    /**
     * @MongoDB\Int
     */
    protected $booking;

    /**
     * @MongoDB\Int
     */
    protected $cancellation;

    /**
     * @MongoDB\Date
     */
    protected $submitted_on;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set arrival
     *
     * @param date $arrival
     * @return self
     */
    public function setArrival($arrival)
    {
        $this->arrival = $arrival;
        return $this;
    }

    /**
     * Get arrival
     *
     * @return date $arrival
     */
    public function getArrival()
    {
        return $this->arrival;
    }

    /**
     * Set departure
     *
     * @param date $departure
     * @return self
     */
    public function setDeparture($departure)
    {
        $this->departure = $departure;
        return $this;
    }

    /**
     * Get departure
     *
     * @return date $departure
     */
    public function getDeparture()
    {
        return $this->departure;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set adults
     *
     * @param int $adults
     * @return self
     */
    public function setAdults($adults)
    {
        $this->adults = $adults;
        return $this;
    }

    /**
     * Get adults
     *
     * @return int $adults
     */
    public function getAdults()
    {
        return $this->adults;
    }

    /**
     * Set children
     *
     * @param int $children
     * @return self
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * Get children
     *
     * @return int $children
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set destination
     *
     * @param int $destination
     * @return self
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * Get destination
     *
     * @return int $destination
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set category
     *
     * @param int $category
     * @return self
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return int $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set booking
     *
     * @param int $booking
     * @return self
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     * Get booking
     *
     * @return int $booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * Set cancellation
     *
     * @param int $cancellation
     * @return self
     */
    public function setCancellation($cancellation)
    {
        $this->cancellation = $cancellation;
        return $this;
    }

    /**
     * Get cancellation
     *
     * @return int $cancellation
     */
    public function getCancellation()
    {
        return $this->cancellation;
    }

    /**
     * Set submittedOn
     *
     * @param date $submittedOn
     * @return self
     */
    public function setSubmittedOn($submittedOn)
    {
        $this->submitted_on = $submittedOn;
        return $this;
    }

    /**
     * Get submittedOn
     *
     * @return date $submittedOn
     */
    public function getSubmittedOn()
    {
        return $this->submitted_on;
    }
}
