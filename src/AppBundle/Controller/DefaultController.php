<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Document\Enquiry;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        ini_set('mongo.long_as_object', 1);

        $dm = $this->get('doctrine_mongodb')->getManager();
        $qb = $dm->createQueryBuilder('AppBundle:Enquiry')
            ->distinct('country')
            ;
        $query = $qb->getQuery();
        $countries = $query->execute();

        $municipalities = array();
        if(($handle = fopen($this->get('kernel')->locateResource('@AppBundle/Resources/municipalities.csv'), "r")) !== FALSE)
        {
            while(($data = fgetcsv($handle, 1000, ";")) !== FALSE)
            {
                $municipalities[$data[1]] = $data[0];
            }
            fclose($handle);
        }

        $categories = array(
            1 => 'Gastgewerbliche Betriebe (1-3)',
            'Gastgewerbliche Betriebe (4-5)',
            'Privatvermieter',
            'Bauernhöfe',
            'Sonstiges',
        );

        $qb = $dm->createQueryBuilder('AppBundle:Enquiry')
            ->distinct('adults')
            ;
        $query = $qb->getQuery();
        $adults = $query->execute();

        $qb = $dm->createQueryBuilder('AppBundle:Enquiry')
            ->distinct('children')
            ;
        $query = $qb->getQuery();
        $children = $query->execute();

        $presets = $filters = array();

        $filters = array();
        $filters['group'] = 0;
        $filters['name'] = "Leer";
        $filters['filters'][] = array();
        $presets[] = $filters;

        $filters = array();
        $filters['group'] = 0;
        $filters['name'] = "Test";
        $filters['filters'][] = array(
            'arrival' => '2015-08-01..2015-08-31',
            'departure' => '2015-08-01..2015-08-31',
            'arrivalDepartureOr' => true,
        );
        $filters['filters'][] = array(
            'arrival' => '2015-09-01..2015-09-31',
            'departure' => '2015-09-01..2015-09-31',
            'arrivalDepartureOr' => true,
        );
        $presets[] = $filters;

        $filters = array();
        $filters['group'] = 1;
        $filters['name'] = 'Wie hoch ist die durchschnittliche Aufenthaltsdauer im August in Gröden? Vergleiche dies mit Südtirol Süden!';
        $filters['filters'][] = array(
            'destination' => array(21085,21061,21089,21039),
            'arrival' => '2015-08-01..2015-08-31',
            'departure' => '2015-08-01..2015-08-31',
            'arrivalDepartureOr' => true,
        );
        $filters['filters'][] = $filters['filters'][0];
        $filters['filters'][1]['destination'] = array(21001,21003,21002,21060,21004,21015,21024,21025,21045,21053,21029,21076,21097,21098,21102);
        $presets[] = $filters;

        $filters = array();
        $filters['group'] = 0;
        $filters['name'] = 'Wann wird für Ferragosto angefragt?'; // /gebucht
        $filters['filters'][] = array(
            'arrival' => '2015-08-10..2015-08-23',
            'departure' => '2015-08-10..2015-08-23',
            'arrivalDepartureOr' => true,
        );
        $presets[] = $filters;

        $filters = array();
        $filters['group'] = 0;
        $filters['name'] = 'Wann wird für Gröden angefragt?'; // /gebucht
        $filters['filters'][] = array(
            'destination' => array(21001,21003,21002,21060,21004,21015,21024,21025,21045,21053,21029,21076,21097,21098,21102),
        );
        $presets[] = $filters;

        $filters = array();
        $filters['group'] = 0;
        $filters['name'] = 'Wann wird für Bozen angefragt?'; // /gebucht
        $filters['filters'][] = array(
            'destination' => array(21008),
        );
        $presets[] = $filters;

        $filters = array();
        $filters['group'] = 0;
        $filters['name'] = 'Verlauf im Jahr der Anfragen von Bruneck, Brixen & Bozen'; // /Buchungen
        $filters['filters'][] = array(
            'destination' => array(21013), // Bruneck
        );
        $filters['filters'][] = array(
            'destination' => array(21011), // Brixen
        );
        $filters['filters'][] = array(
            'destination' => array(21008), // Bozen
        );
        $presets[] = $filters;

        $filters = array();
        $filters['group'] = 0;
        $filters['name'] = "Verlauf im Jahr der Anfragen von {$categories[1]} & {$categories[2]}"; // /Buchungen
        $filters['filters'][] = array(
            'category' => array(1),
        );
        $filters['filters'][] = array(
            'category' => array(2),
        );
        $presets[] = $filters;

        $filters = array();
        $filters['group'] = 2;
        $filters['name'] = "Delta zwischen Anreisetag und Anfragetag pro {$categories[1]} & {$categories[2]}";
        $filters['filters'][] = array(
            'category' => array(1),
        );
        $filters['filters'][] = array(
            'category' => array(2),
        );
        $presets[] = $filters;

        $filters = array();
        $preset = ( isset( $_GET['preset'] ) ? (int) $_GET['preset'] : 0);
        $filters = $presets[$preset];

        $results = array();
        $anz_filters = count( $filters['filters'] );
        if ( $anz_filters > 0 ) {
            $group = $filters['group'];
            $collection = $dm->getDocumentCollection('AppBundle:Enquiry');

            foreach ( $filters['filters'] as $_key => $filter ) {
                $pipeline = $matches = array();
                if ( 1 == $group ) {
                    $pipeline[] = [
                        '$group' => [
                            '_id' => [ 'month' => [ '$month' => '$submitted_on' ], 'day' => [ '$dayOfMonth' => '$submitted_on' ], 'year' => [ '$year' => '$submitted_on' ] ],
                            'count' => [ '$avg' => [ '$subtract' => [ '$departure', '$arrival' ] ] ],
                        ]
                    ];
                } elseif ( 2 == $group ) {
                    $pipeline[] = [
                        '$group' => [
                            '_id' => [ 'month' => [ '$month' => '$submitted_on' ], 'day' => [ '$dayOfMonth' => '$submitted_on' ], 'year' => [ '$year' => '$submitted_on' ] ],
                            'count' => [ '$avg' => [ '$subtract' => [ '$arrival', '$submitted_on' ] ] ],
                        ]
                    ];
                } else {
                    $pipeline[] = [
                        '$group' => [
                            '_id' => [ 'month' => [ '$month' => '$submitted_on' ], 'day' => [ '$dayOfMonth' => '$submitted_on' ], 'year' => [ '$year' => '$submitted_on' ] ],
                            'count' => [ '$sum' => 1 ],
                        ]
                    ];
                }
                foreach ( $filter as $key => $values ) {
                    switch ( $key ) {
                        case in_array( $key, array( 'filter', 'color', 'arrivalDepartureOr' ) ) :
                            // skip field
                        break ;
                        case is_array( $values ) :
                            $matches['$match'][$key] = [ '$in' => $values ];
                        break ;
                        case strpos( $values, '..' ) !== false :
                            $arr = explode( '..', $values );
                            
                            $date1 = new \DateTime($arr[0]);
                            $date2 = new \DateTime($arr[1]);

                            $arrivalDepartureOr = ( isset( $key['arrivalDepartureOr'] ) && $key['arrivalDepartureOr'] ? 1 : 0 );
                            $op = ( $arrivalDepartureOr ? '$or' : '$and' ) ;

                            if ( $date1 && $date2 ) {
                                $matches['$match'][$op][][$key] = [ '$gte' => new \MongoDate($date1->getTimestamp()), '$lt' => new \MongoDate($date2->getTimestamp()) ];
                            } elseif ( $date1 ) {
                                $matches['$match'][$op][][$key] = [ '$gte' => new \MongoDate($date1->getTimestamp()) ];
                            } elseif ( $date2 ) {

                                $matches['$match'][$op][][$key] = [ '$lt' => new \MongoDate($date2->getTimestamp()) ];
                            }
                        break ;
                        default :
                            $matches['$match'][$key] = $values;
                        break ;
                    }
                }
                if ( count( $matches ) ) {
                    array_unshift($pipeline, $matches);
                }
                //$query = $qb->getQuery();
                //$result = $query->execute();
                //var_dump( $pipeline ); exit;
                $result = $collection->aggregate($pipeline);
                foreach($result as $row) {
                    if ( 1 == $group || 2 == $group ) {
                        $year = $row['_id']['year'];
                        $month = $row['_id']['month'];
                        $day = $row['_id']['day'];

                        if ( 2 == $group && abs( $row['count'] / ( 24 * 60 * 60 * 1000 ) ) > 500 ) continue; // hide days > 500

                        $date_key = $year . '-' . $month . '-' . $day;
                        $results[$date_key]['formatted'] = 'new Date(' . $year . ', ' . ( $month - 1 ) . ', ' . $day . ')';
                        $results[$date_key]['counts'][$_key] = (int) abs( $row['count'] / ( 24 * 60 * 60 * 1000 ) ); // days
                    } else {
                        $year = $row['_id']['year'];
                        $month = $row['_id']['month'];
                        $day = $row['_id']['day'];

                        $date_key = $year . '-' . $month . '-' . $day;
                        $results[$date_key]['formatted'] = 'new Date(' . $year . ', ' . ( $month - 1 ) . ', ' . $day . ')';
                        $results[$date_key]['counts'][$_key] = (int) $row['count'];
                    }
                }
            }
        }

        return $this->render('default/index.html.twig', array(
            'countries' => $countries,
            'municipalities' => $municipalities,
            'categories' => $categories,
            'adults' => $adults,
            'children' => $children,

            'filters' => $filters['filters'],
            'results' => $results,
            'group' => $group,

            'presets' => $presets,
        ));
    }
}
