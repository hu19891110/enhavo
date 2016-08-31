<?php
/**
 * CreateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;


use Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class CreateViewer extends AbstractViewer
{
    public function getDefaultConfig()
    {
        return array(
            'buttons' => array(
                'cancel' => array(
                    'type' => 'cancel',
                ),
                'save' => array(
                    'type' => 'save',
                )
            ),
            'form' => array(
                'template' => 'EnhavoAppBundle:View:tab.html.twig',
                'theme' => '',
                'action' => sprintf('%s_%s_create', $this->getBundlePrefix(), $this->getResourceName())
            ),
            'sorting' => array(
                'sortable' => false,
                'position' => 'position',
                'initial' => 'max'
            )
        );
    }

    public function getTabs()
    {
        $tabs = $this->getConfig()->get('tabs');
        if(empty($tabs)) {
            return array(
                $this->getResourceName() => array(
                    'label' => $this->getResourceName().'.label.'.$this->getResourceName(),
                    'template' => 'EnhavoAppBundle:Tab:default.html.twig'
                )
            );
        }
        return $tabs;
    }

    public function getButtons()
    {
        $buttons = $this->getConfig()->get('buttons');

        foreach($buttons as $button) {
            if(!array_key_exists('type', $button)) {
                throw new TypeNotFoundException(sprintf('button type is not defined'));
            }
        }

        return $buttons;
    }

    public function getFormTemplate()
    {
        return $this->getConfig()->get('form.template');
    }

    public function getFormAction()
    {
        $action = $this->getConfig()->get('form.action');
        return $this->container->get('router')->generate($action);
    }

    public function getSorting()
    {
        $sorting = $this->getConfig()->get('sorting');

        if (!$sorting or !is_array($sorting)) {
            $sorting = array();
        }

        if (!isset($sorting['sortable'])) {
            $sorting['sortable'] = false;
        }
        if (!isset($sorting['position'])) {
            $sorting['position'] = 'position';
        }
        if (!isset($sorting['initial'])) {
            $sorting['initial'] = 'max';
        }
        if (strtoupper($sorting['initial']) == 'MIN') {
            $sorting['initial'] = 'min';
        } elseif (strtoupper($sorting['initial']) == 'MAX') {
            $sorting['initial'] = 'max';
        } else {
            throw new InvalidConfigurationException('Invalid configuration value for _viewer.sorting.initial, expecting "min" or "max", got "' . $sorting['initial'] . '"');
        }

        return $sorting;
    }

    public function getParameters()
    {
        $parameters = array(
            'buttons' => $this->getButtons(),
            'form' => $this->getForm(),
            'viewer' => $this,
            'tabs' => $this->getTabs(),
            'form_template' => $this->getFormTemplate(),
            'form_action' => $this->getFormAction(),
            'translationDomain' => $this->getTranslationDomain(),
            'sorting' => $this->getSorting(),
            'data' => $this->getResource()
        );

        $parameters = array_merge($this->getTemplateVars(), $parameters);

        return $parameters;
    }
}