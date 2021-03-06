<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Form\Type;

use Mautic\LeadBundle\Model\FieldModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class LeadFieldsType.
 */
class LeadFieldsType extends AbstractType
{
    /**
     * @var FieldModel
     */
    protected $fieldModel;

    /**
     * @param FieldModel $fieldModel
     */
    public function __construct(FieldModel $fieldModel)
    {
        $this->fieldModel = $fieldModel;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        /** @var \Mautic\LeadBundle\Model\FieldModel $model */
        $model = $this->fieldModel;
        $resolver->setDefaults([
            'choices' => function (Options $options) use ($model) {
                $fieldList = $model->getFieldList();
                if ($options['with_tags']) {
                    $fieldList['Core']['tags'] = 'mautic.lead.field.tags';
                }
                if ($options['with_company_fields']) {
                    $fieldList['Company'] = $model->getFieldList(false, true, ['isPublished' => true, 'object' => 'company']);
                }
                if ($options['with_utm']) {
                    $fieldList['UTM']['utm_campaign'] = 'mautic.lead.field.utmcampaign';
                    $fieldList['UTM']['utm_content']  = 'mautic.lead.field.utmcontent';
                    $fieldList['UTM']['utm_medium']   = 'mautic.lead.field.utmmedium';
                    $fieldList['UTM']['utm_source']   = 'mautic.lead.field.umtsource';
                    $fieldList['UTM']['utm_term']     = 'mautic.lead.field.utmterm';
                }

                return $fieldList;
            },
            'global_only'           => false,
            'required'              => false,
            'with_company_fields'   => false,
            'with_tags'             => false,
            'with_utm'              => false,
        ]);
    }

    /**
     * @return null|string|\Symfony\Component\Form\FormTypeInterface
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'leadfields_choices';
    }
}
