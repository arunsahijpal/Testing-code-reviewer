<?php

namespace Drupal\buy_product\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a custom address form.
 */
class AddressForm extends FormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a AddressForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, MessengerInterface $messenger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'buy_product';
  }

  /**
   * Form constructor.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Address'),
      '#required' => TRUE,
      '#description' => $this->t('Enter your address.'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Form submission handles the address and updates it to user profile.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $address = $form_state->getValue('address');
  
    // Get the current user.
    $user = \Drupal::currentUser();
    $uid = $user->id();
  
    $user_storage = $this->entityTypeManager->getStorage('user');
    $user_entity = $user_storage->load($uid);
  
    if ($user_entity) {
      if ($user_entity->hasField('field_address') && !$user_entity->get('field_address')->isEmpty()) {
        $this->messenger->addWarning($this->t('Your address already exists, It will be updated with the new address.'));
      }
      
      $user_entity->set('field_address', $address);
      $user_entity->save();
  
      $this->messenger->addMessage($this->t('Your address %address has been saved.', ['%address' => $address]));
    }
    else {
      $this->messenger->addError($this->t('The user does not exist.'));
    }
  }
}
