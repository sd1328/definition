<?php
declare(strict_types=1);

namespace Sd1328\Definition;

class Query
{
    private string $definitionClass;

    private array $criteria = [];

    private string $usedList = 'list';

    public function __construct(string $definitionClass)
    {
        $this->definitionClass = $definitionClass;
    }

    public function onlyUsed(): self
    {
        $this->usedList = 'usedList';
        return $this;
    }

    public function onlyUnUsed(): self
    {
        $this->usedList = 'unUsedList';
        return $this;
    }

    public function where(string $field, $value): self
    {
        $this->criteria[] = [
            'field' => $field,
            'value' => $value,
        ];
        return $this;
    }

    public function first(): ?Definition
    {
        foreach ($this->execute() as $item) {
            return $item;
        }
        return null;
    }

    public function get(): \Generator
    {
        return $this->execute();
    }

    public function toArray(): array
    {
        return iterator_to_array($this->execute());
    }

    protected function execute(): \Generator
    {
        foreach (call_user_func([$this->definitionClass, $this->usedList]) as $key => $value) {
            if ($this->criteriaComparator($value)) {
                yield $key => $value;
            }
        }
    }

    protected function criteriaComparator(Definition $definitionItem): bool
    {
        foreach ($this->criteria as $criterion) {
            $field = $criterion['field'];
            if ($definitionItem->$field != $criterion['value']) {
                return false;
            }
        }
        return true;
    }
}
