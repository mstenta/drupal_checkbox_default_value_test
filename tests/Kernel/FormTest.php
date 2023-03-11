<?php

namespace Drupal\Tests\quick_test\Kernel;

use Drupal\Core\Form\FormState;
use Drupal\Core\Url;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test the test form.
 */
class FormTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'quick_test',
    'user',
    'system',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->setUpCurrentUser([], [], TRUE);
  }

  /**
   * Test form submission 1.
   */
  public function testTestForm1() {

    # Set all checkbox values to FALSE.
    $values['checkbox_with_default_true'] = FALSE;
    $values['checkbox_without_default'] = FALSE;

    $this->submitForm1($values);

    # Confirm that the correct values were saved to state.
    $this->assertEquals($values['checkbox_with_default_true'], \Drupal::state()->get('checkbox_with_default_true'));
    $this->assertEquals($values['checkbox_without_default'], \Drupal::state()->get('checkbox_without_default'));
  }

  /**
   * Submit form using \Drupal::formBuilder()->submitForm().
   */
  protected function submitForm1($values) {
    $form_arg = '\Drupal\quick_test\Form\TestForm';
    $form_state = (new FormState())->setValues($values);
    \Drupal::formBuilder()->submitForm($form_arg, $form_state, 'test_form');
  }

  /**
   * Test form submission 2.
   */
  public function testTestForm2() {

    # Set all checkbox values to FALSE.
    $values['checkbox_with_default_true'] = FALSE;
    $values['checkbox_without_default'] = FALSE;

    $this->submitForm2($values);

    # Confirm that the correct values were saved to state.
    $this->assertEquals($values['checkbox_with_default_true'], \Drupal::state()->get('checkbox_with_default_true'));
    $this->assertEquals($values['checkbox_without_default'], \Drupal::state()->get('checkbox_without_default'));
  }

  /**
   * Submit form using Request::create().
   */
  protected function submitForm2($values) {

    # Define the form route name.
    $route_name = 'quick_test.form';

    # Perform a GET request to load the form HTML.
    $url = Url::fromRoute($route_name, [])->toString();
    $request = Request::create($url);
    $response = $this->container->get('http_kernel')->handle($request);
    $this->assertEquals(200, $response->getStatusCode());

    # Load the response content into class properties.
    $this->setRawContent((string) $response->getContent());

    # Fill in the form values necessary for submit.
    $values['form_build_id'] = (string) $this->cssSelect('input[name="form_build_id"]')[0]->attributes()->value[0];
    $values['form_token'] = (string) $this->cssSelect('input[name="form_token"]')[0]->attributes()->value[0];
    $values['form_id'] = (string) $this->cssSelect('input[name="form_id"]')[0]->attributes()->value[0];
    $values['op'] = 'Save';

    # Perform a POST request to submit the quick form with the provided values.
    $request = Request::create($url, 'POST', $values);
    $response = $this->container->get('http_kernel')->handle($request);
    $this->assertEquals(303, $response->getStatusCode());
  }

}
