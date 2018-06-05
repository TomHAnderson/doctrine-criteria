<?php

namespace ZF\Doctrine\Criteria\Filter;

use Doctrine\Common\Collections\Criteria;

class IsMemberOf extends AbstractFilter
{
    /**
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param $metadata
     * @param $option
     */
    public function filter(Criteria $criteria, $metadata, $option)
    {
        if (isset($option['where'])) {
            if ($option['where'] === 'and') {
                $queryType = 'andWhere';
            } elseif ($option['where'] === 'or') {
                $queryType = 'orWhere';
            }
        }

        if (! isset($queryType)) {
            $queryType = 'andWhere';
        }

        if (! isset($option['alias'])) {
            $option['alias'] = 'row';
        }

        $format = null;
        if (isset($option['format'])) {
            $format = $option['format'];
        }

        $value = $this->typeCastField($metadata, $option['field'], $option['value'], $format);

        $parameter = uniqid('a');
        $criteria->$queryType(
            $criteria
                ->expr()
                ->isMemberOf(':' . $parameter, $option['alias'] . '.' . $option['field'])
        );
        $criteria->setParameter($parameter, $value);
    }
}
