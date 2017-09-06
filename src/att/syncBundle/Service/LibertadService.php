<?php


namespace att\syncBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use DataDog\PagerBundle\Pagination;

class LibertadService {
    
        protected $emctrlacc;
        protected $container;
        protected $emlibertad;
        protected $em;
    
        /**
         * 
         * @param EntityManager $em
         * @param EntityManager $emctrlacc
         * @param EntityManager $emlibertad
         * @param ContainerInterface $container
         */
        public function __construct(EntityManager $em, EntityManager $emctrlacc, EntityManager $emlibertad , ContainerInterface $container) {
            $this->container =  $container;
            $this->emctrlacc =  $emctrlacc;
            $this->emlibertad = $emlibertad;
            $this->em = $em;
        }
        
        
        public function getAllEvents(Request $request){
        
            $dql = $this->emlibertad->getRepository('att\syncBundle\Entity\Libertad\Eventos')->createQueryBuilder('e');

        
            $options = [
                 'sorters' => ['e.tipoEvent'=> 'ASC'],
                 'applyFilter' => [$this, 'filtersEvents'],
            ];
            $eventype = [            
                Pagination::$filterAny => 'Any',
                                          '41' => 'Ingreso',
                                          '42' => 'Egreso'
                      ]; 
         
            $paginator = new Pagination($dql, $request, $options);
        
            return array ("paginator"=> $paginator, "eventType" => $eventype);
        }
            
        public function filtersEvents(QueryBuilder $qb, $key, $val){
            
            switch ($key) {
                
                case 'e.tipoEvent':
                    if ($val) {
                        $qb->andWhere($qb->expr()->like('e.tipoEvent', ':tipo'));
                        $qb->setParameter('tipo', "%$val%");
                    }
                    break;                
                    
                case 'e.legajo':
                    if ($val) {
                        $qb->andWhere($qb->expr()->like('e.legajo', ':legajo'));
                        $qb->setParameter('legajo', "%$val%");
                    }
                    break;
            
             default:
                 throw new \Exception("filter not allowed");

        }
    }
        
}
