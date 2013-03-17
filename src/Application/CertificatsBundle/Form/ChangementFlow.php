<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\CertificatsBundle\Form\CertificatsProjetType;

use Craue\FormFlowBundle\Form\FormFlow;

class ChangementFlow extends FormFlow {

    protected $maxSteps = 3;



protected function loadStepDescriptions() {
    return array(
        'Account',
        'Password',
        'Terms of service',
    );
}
}