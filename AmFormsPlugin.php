<?php
/**
 * Forms for Craft.
 *
 * @package   Am Forms
 * @author    Hubert Prein
 */
namespace Craft;

class AmFormsPlugin extends BasePlugin
{
    public function getName()
    {
        if (craft()->plugins->getPlugin('amforms')) {
            $pluginName = craft()->amForms_settings->getSettingsByHandleAndType('pluginName', AmFormsModel::SettingGeneral);
            if ($pluginName && $pluginName->value) {
                return $pluginName->value;
            }
        }
        return Craft::t('a&m forms');
    }

    public function getVersion()
    {
        return '1.0.1';
    }

    public function getDeveloper()
    {
        return 'a&m impact';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.am-impact.nl';
    }

    /**
     * Plugin has control panel section.
     *
     * @return boolean
     */
    public function hasCpSection()
    {
        return true;
    }

    /**
     * Plugin has Control Panel routes.
     *
     * @return array
     */
    public function registerCpRoutes()
    {
        return array(
            'amforms/forms' => array(
                'action' => 'amForms/forms/index'
            ),
            'amforms/forms/new' => array(
                'action' => 'amForms/forms/editForm'
            ),
            'amforms/forms/edit/(?P<formId>\d+)' => array(
                'action' => 'amForms/forms/editForm'
            ),

            'amforms/submissions' => array(
                'action' => 'amForms/submissions/index'
            ),
            'amforms/submissions/edit/(?P<submissionId>\d+)' => array(
                'action' => 'amForms/submissions/editSubmission'
            ),

            'amforms/submissions/edit/(?P<submissionId>\d+)/notes' => array(
                'action' => 'amForms/notes/displayNotes'
            ),

            'amforms/fields' => array(
                'action' => 'amForms/fields/index'
            ),
            'amforms/fields/new' => array(
                'action' => 'amForms/fields/editField'
            ),
            'amforms/fields/edit/(?P<fieldId>\d+)' => array(
                'action' => 'amForms/fields/editField'
            ),

            'amforms/exports' => array(
                'action' => 'amForms/exports/index'
            ),
            'amforms/exports/new' => array(
                'action' => 'amForms/exports/editExport'
            ),
            'amforms/exports/edit/(?P<exportId>\d+)' => array(
                'action' => 'amForms/exports/editExport'
            ),

            'amforms/settings' => array(
                'action' => 'amForms/settings/index'
            ),
            'amforms/settings/antispam' => array(
                'action' => 'amForms/settings/antispam'
            ),
            'amforms/settings/recaptcha' => array(
                'action' => 'amForms/settings/recaptcha'
            )
        );
    }

    /**
     * Plugin has user permissions.
     *
     * @return array
     */
    public function registerUserPermissions()
    {
        return array(
            'editAmFormsSettings'   => array(
                'label' => Craft::t('Edit settings')
            )
        );
    }

    /**
     * Install essential information after installing the plugin.
     */
    public function onAfterInstall()
    {
        craft()->amForms_install->install();
    }

    /**
     * Uninstall information that is no longer required.
     */
    public function onBeforeUninstall()
    {
        // Override Craft's default context and content
        craft()->content->fieldContext = AmFormsModel::FieldContext;
        craft()->content->contentTable = AmFormsModel::FieldContent;

        // Delete our own context fields
        $fields = craft()->fields->getAllFields('id', AmFormsModel::FieldContext);
        foreach ($fields as $field) {
            craft()->fields->deleteField($field);
        }

        // Delete content table
        craft()->db->createCommand()->dropTable('amforms_content');
    }
}
