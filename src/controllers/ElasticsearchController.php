<?php
/**
 * Elasticsearch plugin for Craft CMS 3.x
 *
 * Bring the power of Elasticsearch to you Craft 3 CMS project
 *
 * @link      https://www.lahautesociete.com
 * @copyright Copyright (c) 2018 Alban Jubert
 */

namespace lhs\elasticsearch\controllers;

use Craft;
use craft\helpers\UrlHelper;
use craft\web\Controller;
use lhs\elasticsearch\Elasticsearch;

/**
 * Elasticsearch Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Alban Jubert
 * @package   Elasticsearch
 * @since     1.0.0
 */
class ElasticsearchController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    //protected $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

    /**
     * Test the elasticsearch connection
     *
     * @return mixed
     */
    public function actionTestConnection()
    {
        if(Elasticsearch::$plugin->elasticsearch->testConnection() === true) {
            Craft::$app->session->setNotice(Craft::t('elasticsearch', 'Successfully connected to {http_address}', ['http_address' => $this->module->settings->http_address]));
        }
        else {
            Craft::$app->session->setError(Craft::t('elasticsearch', 'Could not establish connection with {http_address}', ['http_address' => $this->module->settings->http_address]));
        }

        return $this->redirect(UrlHelper::cpUrl('utilities/elasticsearch-utilities'));
    }

    /**
     * Reindex Craft entries into elasticsearch (called from utility panel)
     *
     * @return \yii\web\Response
     */
    public function actionReindexAll()
    {
        Elasticsearch::$plugin->elasticsearch->reindexAll();

        if (\Craft::$app->getRequest()->getAcceptsJson()) {
            return $this->asJson([
                'success' => true
            ]);
        }

        return $this->redirect(UrlHelper::cpUrl('utilities/elasticsearch-utilities'));
    }
}