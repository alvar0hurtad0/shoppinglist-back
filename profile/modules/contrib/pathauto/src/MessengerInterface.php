<?php

/**
 * @file
 * Contains Drupal\pathauto\MessengerInterface
 */

namespace Drupal\pathauto;

/**
 * Provides an interface for Messengers.
 */
interface MessengerInterface {

  /**
   * Adds a message.
   *
   * @param string $message
   *   The message to add.
   * @param string $op
   *   (sync) The operation being performed.
   */
  public function addMessage($message, $op = NULL);

}
