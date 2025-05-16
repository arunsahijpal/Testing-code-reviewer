<?php

namespace Drupal\general_alterations\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Defines a validation constraint to limit word length in the title.
 *
 * @Constraint(
 *   id = "MaxTitleWordLengthConstraint",
 *   label = @Translation("Max Title Word Length Constraint", context = "Validation"),
 *   type = "string"
 * )
 */
class MaxTitleWordLengthConstraint extends Constraint {

  /**
   * {@inheritdoc}
   */
  public $message = "The word '@word' in the title exceeds the maximum allowed length of 50 characters.";

}
