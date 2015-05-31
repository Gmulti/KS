<?php

namespace KS\DealBundle\Utils;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryOptionField
{

    public function getCategoryField($field){

        switch ($field) {
            case 'user':
                $result = 'user_selector';
                break;
            case 'medias':
            case 'mediaProfile':
                $result = 'media_selector';
                break;
            case 'categories':
                $result = 'category_selector';
                break;
            case 'type':
                $result = 'type_selector';
                break;
            default:
                $result = null;
                break;
        }

        return $result;
    }

    public function getOptionsField($field){

        switch ($field) {
            case 'medias':
                $result = array(
                    'multiple' => true,
                    'attr' => array(
                        'multiple' => 'multiple'
                    )
                );
                break;
            case 'promoCode':
            case 'reductionType':
            case 'reduction':
            case 'currency':
                $result = array(
                    'constraints' => array(
                        new NotBlank()
                    )

                );
                break;
            default:
                $result = array();
                break;
        }

        return $result;
    }


}