<?php namespace Modules\Documents\Listeners;


use Dingo\Api\Event\ResponseWasMorphed;

class AddSuccessDirectiveToApiResponse
{
    public function handle(ResponseWasMorphed $event)
    {
        if ($event->response->getStatusCode() == 200) {
            return $event->content['success'] = true;
        }
        return $event->content['success'] = false;
    }
}
