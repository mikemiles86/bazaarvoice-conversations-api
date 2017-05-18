<?php

namespace BazaarvoiceConversations\ContentType;

/**
 * Class Questions
 * @package BazaarvoiceConversations\ContentType
 */
class Questions extends ContentTypeBase implements RetrieveContentInterface, SubmitContentInterface  {

  public function getSingle($id, array $parameters = []) {
    $single_content = FALSE;
    // Call getMultiple, passing ID as an array.
    if ($multiple_content = $this->getMultiple([$id], $parameters)) {
      // Pop off the returned object.
      $single_content = array_pop($multiple_content);
    }
    return $single_content;
  }

  public function getMultiple(array $ids, array $parameters = []) {
    // Set default filter for ids.
    $parameters['filter']['id'] = $ids;
    return $this->getAll($parameters);
  }

  public function getAll(array $parameters = []) {
    $configuration = [
      'arguments' => $parameters,
    ];

    return $this->retrieveRequest('data/questions', $configuration);
  }

  public function getProductQuestions($product_id, array $parameters = []) {
    $parameters['filter']['productid'] = $product_id;
    return $this->getAll($parameters);
  }

  public function getSubmissionForm(array $parameters = []) {

    $arguments = [];

    if (isset($parameters['User'])) {
      $arguments['User'] = $parameters['User'];
    } elseif (isset($parameters['UserId'])) {
      $arguments['UserId'] = $parameters['UserId'];
    }

    return $this->submit($arguments);
  }

  public function previewSubmission(array $submission_values = []) {
    $submission_values['Action'] = 'Preview';
    return $this->submit($submission_values);
  }

  public function submitSubmission(array $submission_values = []) {
    $submission_values['Action'] = 'Submit';
    return $this->submit($submission_values);
  }

  private function submit(array $arguments = []) {

    $configuration = [
      'method' => 'POST',
      'arguments' => $arguments,
    ];

    return $this->submitRequest('data/submitquestion', $configuration, 'BazaarvoiceConversations\\Response\\QuestionSubmitResponse');
  }
}