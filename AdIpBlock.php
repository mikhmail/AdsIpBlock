<?php
namespace YourNameSpace;

error_reporting(E_ERROR);

require __DIR__ . '/autoload.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\v201809\cm\CampaignCriterionService;
use Google\AdsApi\AdWords\v201809\cm\IpBlock;
use Google\AdsApi\AdWords\v201809\cm\NegativeCampaignCriterion;
use Google\AdsApi\AdWords\v201809\cm\CampaignCriterionOperation;
use Google\AdsApi\AdWords\v201809\cm\Operator;

class BlockedIP {
  public static function runExample(AdWordsServices $adWordsServices,
        AdWordsSession $session,
        $campaignId,
        $ip) {

    $campaignCriterionService =
        $adWordsServices->get($session, CampaignCriterionService::class);


    $campaignCriteria = [];

    // Add a negative campaign criterion.
    $ipBlock = new IpBlock();
    $ipBlock->setIpAddress($ip);
    $negativeCriterion = new NegativeCampaignCriterion();
    $negativeCriterion->setCampaignId($campaignId);
    $negativeCriterion->setCriterion($ipBlock);

    $operation = new CampaignCriterionOperation();
    $operation->setOperator(Operator::ADD);
    $operation->setOperand($negativeCriterion);
    $operations[] = $operation;

    $result = $campaignCriterionService->mutate($operations);

    // Print out some information about added campaign criteria.
    foreach ($result->getValue() as $campaignCriterion) {
      printf(
          "Campaign targeting criterion with ID %d and type '%s' was added.\n",
          $campaignCriterion->getCriterion()->getId(),
          $campaignCriterion->getCriterion()->getType());
    }
  }

  public static function add($campaignId, $ip) {
    // Generate a refreshable OAuth2 credential for authentication.
    $oAuth2Credential = (new OAuth2TokenBuilder())
        ->fromFile()
        ->build();

    // Construct an API session configured from a properties file and the OAuth2
    // credentials above.
    $session = (new AdWordsSessionBuilder())
        ->fromFile()
        ->withOAuth2Credential($oAuth2Credential)
        ->build();

    self::runExample(new AdWordsServices(), $session, $campaignId, $ip);
  }
}


// start
BlockedIP::add($campaignId, $ip);