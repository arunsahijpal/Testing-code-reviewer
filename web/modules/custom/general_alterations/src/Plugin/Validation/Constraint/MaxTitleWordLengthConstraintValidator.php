<?php

namespace Drupal\general_alterations\Plugin\Validation\Constraint;

use Drupal\Core\Field\FieldItemList;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validator class for the max title length constraint.
 */
class MaxTitleWordLengthConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    if ($value instanceof FieldItemList && !$value->isEmpty()) {
      // Extract the first item's string value.
      $title = $value->first()->getValue()['value'] ?? '';

      // Split into words and check their lengths.
      $words = preg_split('/\s+/', $title);
      foreach ($words as $word) {
        if (mb_strlen($word) > 50) {
          $this->context->addViolation($constraint->message, ['@word' => $word]);
        }
      }
    }
  }

}
