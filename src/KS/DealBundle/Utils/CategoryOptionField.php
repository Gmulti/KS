<?php

namespace KS\DealBundle\Utils;

use Symfony\Component\HttpFoundation\Request;


class CategoryOptionField
{

    public function getCategoryField($field){

        switch ($field) {
            case 'user':
                $result = 'user_selector';
                break;
            case 'medias':
                $result = 'media_selector';
                break;
            case 'categories':
                $result = 'category_selector';
                break;
            case 'types':
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
            default:
                $result = array();
                break;
        }

        return $result;
    }


}