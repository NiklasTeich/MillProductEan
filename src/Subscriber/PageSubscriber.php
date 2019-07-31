<?php declare(strict_types=1);

namespace Mill\ProductEan\Subscriber;

use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class PageSubscriber
 *
 * @package Mill\ProductEan\Subscriber
 */

class PageSubscriber implements EventSubscriberInterface
{

    /**
     * {@inheritDoc}
     */

    public static function getSubscribedEvents(): array
    {

        return [
            ProductPageLoadedEvent::class => 'onProductPageLoaded'
        ];

    }

    /**
     * Event-function to add the ean item prop
     *
     * @param ProductPageLoadedEvent $event
     */

    public function onProductPageLoaded(ProductPageLoadedEvent $event)
    {

        $page = $event->getPage();

        $product = $page->getProduct();

        $ean = (string) $product->getEan();

        $page->assign(
            [
                'millProductEan' => [
                    'itemProp' => $this->getEanItemProp($ean)
                ]
            ]
        );

    }

    /**
     * Helper-function to get the correct item prop
     *
     * @param string $ean
     *
     * @return string
     */

    private function getEanItemProp($ean): string
    {

        $length = strlen($ean);

        switch ($length) {

            case 8:

                $itemprop = 'gtin8';
                break;

            case 12:

                $itemprop = 'gtin12';
                break;

            case 14:

                $itemprop = 'gtin14';
                break;

            default:

                $itemprop = 'gtin13';
                break;


        }

        return $itemprop;

    }

}