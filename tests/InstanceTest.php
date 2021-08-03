<?php

namespace Zipzoft\Repository\Tests;

use Zipzoft\Repository\Criteria;
use Zipzoft\Repository\EloquentRepository;
use Zipzoft\Repository\Expression\StopCriteria;
use Zipzoft\Repository\HasCriteria;
use Zipzoft\Repository\RepositoryInterface;

class InstanceTest extends TestCase
{

    /**
     * @var EloquentRepository
     */
    private $repository;


    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new class($this->app) extends EloquentRepository {
            protected function getModelIdentifier(): string
            {
                return TestModel::class;
            }
        };
    }


    public function criteriaProvider(): array
    {
        $criteria = new class extends Criteria {
            public function apply($query, RepositoryInterface $repository)
            {
                return $query;
            }
        };

        return [
            // Normally
            [$criteria],

            // By closure
            [
                function () use ($criteria) {
                    return $criteria;
                }
            ],

            // With Expression
            [
                function () {
                    return new class extends Criteria {
                        public function apply($query, RepositoryInterface $repository)
                        {
                            return new StopCriteria($query);
                        }
                    };
                }
            ]
        ];
    }


    public function testInstance()
    {
        $this->assertInstanceOf(RepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(HasCriteria::class, $this->repository);
    }

    /**
     * @dataProvider criteriaProvider
     */
    public function testPushCriteria($criteria)
    {
        $this->repository->pushCriteria($criteria);

        $this->assertCount(1, $this->repository->getCriteria());

        $this->repository->applyCriteria();
    }
}

