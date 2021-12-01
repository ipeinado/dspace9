<?php
/**
 * @file
 * Contains Drupal\github_repo_importer\Form\GithubPATForm.
 */

namespace Drupal\github_repo_importer\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class GithubPATForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'github_repo_importer.adminsettings'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'github_repo_importer_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('github_repo_importer.adminsettings');

    $form['github_personal_access_token'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Github Personal Access Token'),
      '#description' => $this->t('Personal Access Token to use the Github API'),
      '#default_value' => $config->get('github_personal_access_token')
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('github_repo_importer.adminsettings')
      ->set('github_personal_access_token', $form_state->getValue('github_personal_access_token'))
      ->save();
  }
}