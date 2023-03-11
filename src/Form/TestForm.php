<?php

namespace Drupal\quick_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an example form.
 */
class TestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'test_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['checkbox_with_default_true'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Checkbox with default TRUE'),
      '#default_value' => TRUE,
    ];
    $form['checkbox_without_default'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Checkbox without default'),
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Save the submitted values to state.
    \Drupal::state()->set('checkbox_with_default_true', $form_state->getValue('checkbox_with_default_true'));
    \Drupal::state()->set('checkbox_without_default', $form_state->getValue('checkbox_without_default'));
  }

}
