<?php

namespace Drupal\ucr_global\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements a form to collect security check configuration.
 */
class UcrGlobalSettingsForm extends ConfigFormBase {

  /**
   *
   */
  public function getFormId() {
    return 'ucr_global_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ucr_global.settings'];
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $ucr_config = \Drupal::config('ucr_global.settings');

    // General Description text to let the user know what they are doing.
    $form['ucr_global_description'] = [
      '#markup' => t('Utilize this section to set up and maintain the global configurations of your website.'),
    ];

    // Form elements.
    $form['organization_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Organization Name'),
      '#description' => $this->t('Please enter in the Organization Name that your Dept, College, etc. belongs to.'),
      '#required' => TRUE,
      '#default_value' => $ucr_config->get('organization_name'),
    ];

    $form['organization_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Organization URL'),
      '#description' => $this->t("Please enter the URL to your Organization\'s main website. If it does not have one, leave the field blank."),
      '#required' => TRUE,
      '#default_value' => $ucr_config->get('organization_url'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    if (empty($values['organization_name'])) {
      $form_state->setError($form['organization_name'], $this->t('Please enter in the Organization Name.'));
    }

    if (empty($values['organization_url'])) {
      $form_state->setError($form['organization_url'], $this->t('Please enter in the Organization URL.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();

    $config_info = [
      'organization_name' => $form_values['organization_name'],
      'organization_url' => $form_values['organization_url'],
    ];

    $config = $this->config('ucr_global.settings');

    foreach ($config_info as $key => $value) {
      $config->set($key, $value);
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }

}
