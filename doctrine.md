

# ManyToOne - OneToMany

form_result
- id

form_result_field
- id
- result_id


```php
/**
 * @ORM\Entity
 * @ORM\Table(name="form_result")
 */
class FormResult extends Base
{
    /**
     * @ORM\OneToMany(targetEntity="FormResultField", mappedBy="formResult")
     */
    protected $resultFields;

    public function __construct()
    {
        $this->resultFields = new ArrayCollection();
    }

    public function getResultFields()
    {
        return $this->resultFields;
    }

    public function addResultField(FormResultField $item): self
    {
        if (!$this->resultFields->contains($item)) {
            $this->resultFields[] = $item;
            $item->setFormResult($this);
        }
        return $this;
    }

    public function removeResultField(FormResultField $item): self
    {
        if ($this->resultFields->contains($item)) {
            $this->resultFields->removeElement($item);
            if ($item->getFromResult() === $this) {
                $item->setFormResult(null);
            }
        }
        return $this;
    }

}



/**
 * @ORM\Entity
 * @ORM\Table(name="form_result_field")
 */
class FormResultField extends Base
{
    /**
     * @ORM\ManyToOne(targetEntity="FormResult", inversedBy="resultFields")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $formResult;

    public function setFormResult(FormResult $item)
    {
        $this->formResult = $item;
        return $this;
    }

    public function getFromResult()
    {
        return $this->formResult;
    }

}



```
