<?php

namespace Absolute\Module\Settings\Presenter;

use Nette\Http\Response;
use Nette\Application\Responses\JsonResponse;
use Absolute\Module\Settings\Manager\SettingsManager;
use Absolute\Module\Settings\Manager\SettingsCRUDManager;

class DefaultPresenter extends SettingsBasePresenter
{

    /** @var \Absolute\Module\Settings\Manager\SettingsCRUDManager @inject */
    public $settingsCRUDManager;

    /** @var \Absolute\Module\Settings\Manager\SettingsManager @inject */
    public $settingsManager;

    public function startup()
    {
        parent::startup();
    }

    public function renderDefault($resourceId)
    {
        switch ($this->httpRequest->getMethod())
        {
            case 'GET':
                if ($resourceId != null)
                    $this->_getRequest($resourceId);
                else
                    $this->_getListRequest();
                break;
            case 'PUT':
                $this->_putRequest($resourceId);
                break;

                break;
        }
        $this->sendResponse(new JsonResponse(
                $this->jsonResponse, "application/json;charset=utf-8"
        ));
    }

    private function _getRequest($id)
    {
        {
            $settings = $this->settingsManager->getByKey($id);
            if (!$settings)
            {
                $this->httpResponse->setCode(Response::S404_NOT_FOUND);
                return;
            }
            $this->sendJson($settings);
            $this->httpResponse->setCode(Response::S200_OK);
        }
    }

    private function _getListRequest()
    {
        {
            $settings = $this->settingsManager->getList();
            if (!$settings)
            {
                $this->httpResponse->setCode(Response::S500_INTERNAL_SERVER_ERROR);
                return;
            }
            $this->httpResponse->setCode(Response::S200_OK);
            $this->sendJson($settings);
        }
    }

    private function _putRequest($id)
    {
        $post = json_decode($this->httpRequest->getRawBody(), true);
        if ($this->settingsManager->canUserEdit($id, $this->user->id))
        {
            if (isset($post['key']))
            {
                $res = $this->settingsCRUDManager->update($id, $post['key']);
                if (!$res)
                    $this->httpResponse->setCode(Response::S500_INTERNAL_SERVER_ERROR);
            }
            else
                $this->httpResponse->setCode(Response::S400_BAD_REQUEST);
        }
        else
        {
            $this->jsonResponse->payload = [];
            $this->httpResponse->setCode(Response::S403_FORBIDDEN);
        }
    }

}
