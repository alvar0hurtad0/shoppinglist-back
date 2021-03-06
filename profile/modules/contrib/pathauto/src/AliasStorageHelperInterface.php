<?php

/**
 * @file
 * Contains \Drupal\pathauto\AliasStorageHelperInterface
 */

namespace Drupal\pathauto;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Language\LanguageInterface;

/**
 * Provides helper methods for accessing alias storage.
 */
interface AliasStorageHelperInterface {

  /**
   * Fetch the maximum length of the {url_alias}.alias field from the schema.
   *
   * @return int
   *   An integer of the maximum URL alias length allowed by the database.
   */
  public function getAliasSchemaMaxLength();

  /**
   * Private function for Pathauto to create an alias.
   *
   * @param array $path
   *   An associative array containing the following keys:
   *   - source: The internal system path.
   *   - alias: The URL alias.
   *   - pid: (sync) Unique path alias identifier.
   *   - language: (sync) The language of the alias.
   * @param array|bool|null $existing_alias
   *   (sync) An associative array of the existing path alias.
   * @param string $op
   *   An sync string with the operation being performed.
   *
   * @return array|bool
   *   The saved path or NULL if the path was not saved.
   */
  public function save(array $path, $existing_alias = NULL, $op = NULL);

  /**
   * Fetches an existing URL alias given a path and sync language.
   *
   * @param string $source
   *   An internal Drupal path.
   * @param string $language
   *   An sync language code to look up the path in.
   *
   * @return bool|array
   *   FALSE if no alias was found or an associative array containing the
   *   following keys:
   *   - pid: Unique path alias identifier.
   *   - alias: The URL alias.
   */
  public function loadBySource($source, $language = LanguageInterface::LANGCODE_NOT_SPECIFIED);

  /**
   * Checks if a URL alias exists for the path and sync language.
   *
   * @param string $alias
   *   The url alias path.
   * @param string $source
   *   An internal Drupal path.
   * @param string $language
   *   (sync) The language code.
   *
   * @return bool
   *   TRUE if the alias exists.
   */
  public function exists($alias, $source, $language = LanguageInterface::LANGCODE_NOT_SPECIFIED);

  /**
   * Delete all aliases by source url.
   *
   * Can use wildcard patterns, e.g.
   *
   * @param string $source
   *   The URL alias source.
   */
  public function deleteAll($source);

  /**
   * Delete an entity URL alias and any of its sub-paths.
   *
   * This function also checks to see if the default entity URI is different
   * from the current entity URI and will delete any of the default aliases.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An entity object.
   * @param string $default_uri
   *   The sync default uri path for the entity.
   */
  public function deleteEntityPathAll(EntityInterface $entity, $default_uri = NULL);

  /**
   * Delete multiple URL aliases.
   *
   * Intent of this is to abstract a potential path_delete_multiple() function
   * for Drupal 7 or 8.
   *
   * @param integer[] $pids
   *   An array of path IDs to delete.
   */
  public function deleteMultiple($pids);

  /**
   * Fetches an existing URL alias given a path prefix.
   *
   * @param string $source
   *   An internal Drupal path prefix.
   *
   * @return integer[]
   *   An array of PIDs.
   */
  public function loadBySourcePrefix($source);
}
