<?php
namespace Craft;

class Spectrum_ColorPickerFieldType extends BaseFieldType
{
    public function getName()
    {
        return Craft::t('Spectrum color picker');
    }

    public function getInputHtml($name, $value)
    {
        // Reformat the input name into something that looks more like an ID
        $id = craft()->templates->formatInputId($name);

        // Figure out what that ID is going to look like once it has been namespaced
        $namespacedId = craft()->templates->namespaceInputId($id);

        craft()->templates->includeJsResource('spectrum/spectrum-lib.js');
        craft()->templates->includeCssResource('spectrum/spectrum-lib.css');
        craft()->templates->includeCssResource('spectrum/spectrum.css');

        $settings = array();

        $inputSettings = $this->getSettings();

        // Start our spectrum input
        $spectrum = "$('#{$namespacedId}').spectrum({";

        $spectrum .= "color: \"" . $value . "\",";

        foreach($inputSettings as $key => $value)
        {
            if($value)
            {
                if($key == 'palette')
                {
                    $colors = '[';
                    foreach($value as $color)
                    {
                        $colors .= "\"{$color['color']}\"" . ",";
                    }
                    $colors .= ']';
                    $spectrum .= "palette: [" . $colors . "],";
                }
                else
                {
                    $spectrum .= $key . ": \"" . $value . "\",";
                }
            }
        }

        $spectrum .= "});";

        craft()->templates->includeJs($spectrum);

        return craft()->templates->render('spectrum/field/input', array(
            'name'  => $name,
            'id' => $id,
            'value' => $value
        ));
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('spectrum/field/settings', array(
            'settings' => $this->getSettings()
        ));
    }

    protected function defineSettings()
    {
        return array(
            'palette' => AttributeType::Mixed,
            'allowEmpty' => AttributeType::Bool,
            'showInput' => AttributeType::Bool,
            'showAlpha' => AttributeType::Bool,
            'showPalette' => AttributeType::Bool,
            'showPaletteOnly' => AttributeType::Bool,
            'preferredFormat' => AttributeType::String
        );
    }

}