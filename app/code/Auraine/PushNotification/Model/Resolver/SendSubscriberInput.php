<?php
namespace Auraine\PushNotification\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Auraine\PushNotification\Model\SubscriberFactory;
use Magento\Framework\App\Config\ScopeConfigInterface ;

class SendSubscriberInput implements ResolverInterface
{
    private $subscriberFactory;

    /** Data provider for the
     *
     * @var dataProvider
     */
    private $dataProvider;
    /** Constructor function
     *
    * @param String $dataProvider
    */
    /** Subscriber Data provider for the
     *
     * @var subscriberDataProvider
     */
    private $subscriberDataProvider;
    /** Constructor function
     *
    * @param String $dataProvider
    */
    /**
     * @var $_scopeConfig
     */
    private $_scopeConfig;
 
    public function __construct(
        SubscriberFactory $subscriberFactory,
        \Auraine\PushNotification\Model\Resolver\DataProvider\TemplateList $dataProvider,
        ScopeConfigInterface $scopeConfig,
        \Auraine\PushNotification\Model\Resolver\DataProvider\SubscriberList $subscriberDataProvider,
        )
    {
        $this->subscriberFactory = $subscriberFactory;
        $this->dataProvider = $dataProvider;
        $this->_scopeConfig = $scopeConfig;
        $this->subscriberDataProvider = $subscriberDataProvider;
    }
 
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(
                __('"Input" value should be specified and in the form of array.')
            );
        }
 
        $data = $args['input'];
        $device_token = $data['device_token'] ?? null;
        $template_id = $data['template_id'] ?? null;
        $device_type = $data['device_type'] ?? null; 
        $gender = $data['gender'] ?? null; 
        
        if (empty($template_id)) {
            throw new GraphQlInputException(__('Template Id is a required field.'));
        }

        $url = $this->_scopeConfig->getValue(
                'push_notification/push_notification_setting/url',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        $server_key = $this->_scopeConfig->getValue(
            'push_notification/push_notification_setting/pushnotifications_notification_fcm_server_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $template_res = $this->dataProvider->getTemplateList($template_id);
        $title = $template_res[0]['subject'];
        $description = $template_res[0]['content'];
        $icon = $template_res[0]['icon'];
        $date = date('d-M-Y');
        $time = date('h:i:s');

        $subscriber_res = $this->subscriberDataProvider->getSubscriberList($device_type, $device_token, $gender);
        foreach($subscriber_res as $res) {
            $token = $res['token'];
            $curl = curl_init();curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                                        "to":"'.$token.'",
                                        "notification": {
                                        "title": "'.$title.'",
                                        "body": "'.$description.'",
                                        "mutable_content": true,
                                        "sound": "Tri-tone"
                                        }, "data": {
                                        "title": "'.$title.'",
                                        "date": "'.$date.'",
                                        "time": "'.$time.'",
                                        "description": "'.$description.'",
                                        "imageUrl":"https://thumbs.dreamstime.com/z/special-offer-price-tag-sign-paper-against-rustic-red-painted-barn-wood-55863934.jpg",
                                        "screen":"notification" }
                                        }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: key='.$server_key
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            echo $response;
        }
    }
}
