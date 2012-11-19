<?php

namespace App\CommonBundle\Twig;

class CommonExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'price' => new \Twig_Filter_Method($this, 'priceFilter'),
            'mb_substr' => new  \Twig_Filter_Method($this, 'mbSubstrFilter'),
        );
    }
    public function mbSubstrFilter($str, $start=0, $width=256, $trimmarker='...')
    {
        return mb_strimwidth($str, $start, $width, $trimmarker);
    }
    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$' . $price;

        return $price;
    }

    public function getName()
    {
        return 'common_extension';
    }
}