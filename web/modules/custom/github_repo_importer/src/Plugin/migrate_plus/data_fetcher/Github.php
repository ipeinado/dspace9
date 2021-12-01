<?php

namespace Drupal\github_repo_importer\Plugin\migrate_plus\data_fetcher;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate_plus\DataFetcherPluginBase;
// use GuzzleHttp\Exception\RequestException;
use Github\Client;
use Github\Exception\ErrorException;

/**
 * Retrieve data over an HTTP connection for migration.
 *
 * Example:
 *
 * @code
 * source:
 *   plugin: url
 *   data_fetcher_plugin: github
 *   api_call: starring or topic
 *   repository_info:
 *    - owner
 *    - name
 * @endcode
 *
 * @DataFetcher(
 *   id = "github",
 *   title = @Translation("github")
 * )
 */
class Github extends DataFetcherPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The HTTP client.
   *
   * @var \Github\Client
   */
  protected $githubClient;

  /**
   * Type of migration ("starring" or "topics")
   * 
   * @var str
   */
  protected $apiCall;

  /**
   * Repository information
   * 
   * @var array
   */
  protected $repositoryInfo;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->githubClient = new \Github\Client();    
    $this->apiCall = $this->configuration['api_call'][0];
    $this->repositoryInfo = $this->configuration['repository_info'];
  }

  /**
   * {@inheritdoc}
   */
  public function setRequestHeaders(array $headers) {
    $this->headers = $headers;
  }

  /**
   * {@inheritdoc}
   */
  public function getRequestHeaders() {
    return !empty($this->headers) ? $this->headers : [];
  }

  /**
   * {@inheritdoc}
   */
  public function getResponse($url) {
    try {
      
      $pat = \Drupal::config('github_repo_importer.adminsettings')->get('github_personal_access_token');
    
      $this->githubClient->authenticate($pat, 'access_token_header');

      $detailed_repos = [];
      
      $starring_api = $this->githubClient->api('current_user')->starring();
      $paginator  = new \Github\ResultPager($this->githubClient);
      $response_paginated = $paginator->fetchAll($starring_api, 'all', array());

      // var_dump($response_paginated[0]);

      // foreach ($response_paginated as $simple_repo) {
      //   $detailed_repo = $this->githubClient->api('repo')->show($simple_repo["owner"]["login"], $simple_repo["name"]);
      //   $readme = $this->githubClient->api('repo')->contents()->readme($simple_repo["owner"]["login"], $simple_repo["name"], "reference");
      //   array_push($detailed_repos, $detailed_repo);
      // }
        
      $response = json_encode($response_paginated);
    
      if (empty($response)) {
        throw new MigrateException('No response at ' . $url_paginated . '.');
      }  
    }
    catch (ErrorException $e) {
      throw new ErrorException('Error message: ' . $e->getMessage() . ' at ' . $url . '.');
    }
    
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function getResponseContent($url) {
    $response = $this->getResponse($url);
    return $response;
  }
}
