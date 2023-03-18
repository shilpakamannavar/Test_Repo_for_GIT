// JavaScript Document
var config = {
    deps: ['Alternativetechlab_Mobilelogin/js/login'],
    map: {
        '*': {
            'Magento_Checkout/template/authentication':
                'Alternativetechlab_Mobilelogin/template/authentication'
        },
         '*': {
            'Magento_Customer/js/model/authentication-popup':'Alternativetechlab_Mobilelogin/js/model/authentication-popup'
        }
    },
        'config': {
        'mixins': {
            'Magento_Checkout/js/view/authentication': {
                'Alternativetechlab_Mobilelogin/js/view/authentication': true
            }
        }
    }


};
