

# Query

```php

$rep = $em->getRepository(FormResult::class);

$item = $rep->find(675);
$items = $rep->findBy(['formResult' => $item])

// update
$qb = $rep->createQueryBuilder('mo');

$qb->update()
    ->set('mo.userUuid', ':newUuid')
    ->where('mo.userUuid = :oldUuid')
    ->setParameters([
        'newUuid' => $newUuid,
        'oldUuid' => $oldUuid,
    ]);

$qb->getQuery()->execute();

```


# Relations

- Unidirectional - когда связь прописана только на одной стороне.
- Bidirectional 
- Self-referencing


[article1](https://codereviewvideos.com/course/symfony2-tutorial-for-beginners/video/doctrine-relationships-for-beginners)

## ManyToOne - OneToMany

ManyToOne - OneToMany: это одна связь, ManyToOne ставиться у хозяина, OneToMany у подчиненного. В нашем примере form_result_field является хозяином, т.к. хранит в себе связь, а form_result подчиненным.

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
     * У тебя одного может быть много FormResultField
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
     * Тебя много, и вы можете принадлежать одному  FormResult
     * @ORM\ManyToOne(targetEntity="FormResult", inversedBy="resultFields")
     * @ORM\JoinColumn(name="result_id", referencedColumnName="id")
     * @ORM\OrderBy({"sortableRank" = "ASC"}) // можно добавить сотировку
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

$form = new FormResult();
$em->persist($form);

$formResult1 = new FormResultField();
$formResult1 = new FormResultField();
$em->persist($formResult1);
$em->persist($formResult1);

$em->flush();
```


PersistentCollection - это временнаяя коллекция для ленивой загрузки, она будте вызвана с случае обращения к элементу. За это отвечает параметр fetch и может иметь три параметра "LAZY", "EAGER", "EXTRA_LAZY".

