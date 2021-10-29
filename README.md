# SmobilPay for e-commerce - Mobile Money Gateway for Magento 2.4.x +

SmobilPay for Magento is a simple and powerful Payment Gateway module.

You can add to your Magento shop, the ability to receive easily Mobile Money payment from Cameroon


The usage of this plugin is completely free. You have to just have an SmobilPay account:
* [Sign up](https://enkap.cm/) for a free account
* Ask SmobilPay Team for consumerKey and consumerSecret

# About the Smobilpay for e-Commerce Plugin
Smobilpay for e-commerce is an online payment service aggregator that allows web users and e-Commerce merchants to buy and sell on the Internet using international and all existing local payment methods in Cameroon.
We enable digital organizations to accept cash, Mobile Money, or card payment via a simple, unique API permitting local businesses to participate in the vast digital economy by transforming their service offerings into the world of e-commerce sites.

This fully functional plugin has been developed for Magento, to help merchants diminish payment friction for their customers by meeting customers where they spend more and more time – online!

It works in both Sandbox (development mode) and Live (production mode)

# About the plugin
Cameroonians avoid online buying because it lacks local payments known to consumers.
Customers are more likely to finalize a purchase on a site that has their preferred payment options. This SmobilPay for e-commerce plugin permits you to use all Mobile money payment gateways available in Cameroon with the Maganto plugin. This integration uses a single API. The user experience for the end-user be it tech-savvy or not, is hassle-free.

# What is Smobilpay?
A digital one-stop-shop, providing cashless payment solutions for Government, Corporations and businesses, and also providing a seamless Digital payment solution, used by third-party agents to sell digital services to end consumers.
The smobilpay platform Boasts of being a major partner with giant service providers in Cameroon such as ENEO (Prepaid and Postpaid), CamWater, MTN, Orange, Yoomee, Canal+, and more. Its reliability, invulnerable security, and efficiency are evident enough via its partnership with GIMAC-the interoperability switch of BEAC (the Bank Of Central African States), where smobilpay serves as the lone digital service aggregator for the entire CEMAC(Central African Economic and Monetary Community) region.


# Features

* Pay with Cameroon MTN Mobile Money
* Pay with Cameroon Orange Mobile Money
* Pay with Express Union Mobile Money
* Pay with SmobilPay Cash

#### More details can be found on the [documentation website](https://support.enkap.cm)


## installation and Manage the Gateway
We assume you already installed Magento 2.4.x and configured it successfully

### Requirements :
* Command line (CLI) access to the magento installation.
* Magento Version:2.4 or higher
* Tested up to:2.4.3-p1
* PHP Version:7.3 or higher

1. Download the [smobilpay.magento.zip file](https://github.com/camoo/smobilpay-for-magento/releases/download/1.0.0/smobilpay.magento.zip)
2. With command line (CLI) move into `app` from your Magento root. `cd /path/to/magento && cd app`
3. Create code folder if missing: `mkdir -p code`
4. Move into `code` : `cd code` and copy the zip file here `cp /path/to/zip/smobilpay.magento.zip .`
5. Unzip  `unzip smobilpay.magento.zip`.
6. Run `cd /path/to/magento && php bin/magento setup:upgrade`.
7. In your Magento Dashboard go to \"STORES\" -> \"Configuration\" .
8.  Look for the tab \"SALES\" -> \"Payment Methods\".
10. Look for \"SmobilPay for e-commerce\" and select it to add your API credentials.
