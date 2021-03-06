<?php

namespace MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\ProductRepository")
 * @JMS\ExclusionPolicy("all")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Paliktas tuščias laukelis - Markė")
     * @JMS\Expose
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity="Model")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Paliktas tuščias laukelis - Modelis")
     * @JMS\Expose
     */
    private $model;

    /**
     * @ORM\ManyToMany(targetEntity="Feature", inversedBy="products")
     * @ORM\JoinTable(name="products_features")
     * @JMS\Expose
     *
     */
    private $features;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     * @Assert\Length(
     *      min = 10,
     *      max = 350,
     *      minMessage = "Komantarai turi būti bent {{ limit }} simbolių ilgio",
     *      maxMessage = "Komentarai negali būti ilgesni, nei {{ limit }} simbolių"
     * )
     * @JMS\Expose
     */
    private $comment;

    public function __construct()
    {
        $this->features = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Product
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set brand
     *
     * @param \MainBundle\Entity\Brand $brand
     *
     * @return Product
     */
    public function setBrand(\MainBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \MainBundle\Entity\Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Add feature
     *
     * @param \MainBundle\Entity\Feature $feature
     *
     * @return Product
     */
    public function addFeature(\MainBundle\Entity\Feature $feature)
    {
        $this->features[] = $feature;

        return $this;
    }

    /**
     * Remove feature
     *
     * @param \MainBundle\Entity\Feature $feature
     */
    public function removeFeature(\MainBundle\Entity\Feature $feature)
    {
        $this->features->removeElement($feature);
    }

    /**
     * Get features
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set model
     *
     * @param \MainBundle\Entity\Model $model
     *
     * @return Product
     */
    public function setModel(\MainBundle\Entity\Model $model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return \MainBundle\Entity\Model
     */
    public function getModel()
    {
        return $this->model;
    }
}
