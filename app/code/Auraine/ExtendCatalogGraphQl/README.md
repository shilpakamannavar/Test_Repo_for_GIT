# Mage2 Module Auraine ExtendCatalogGraphQl

    ``auraine/module-extendcataloggraphql``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
This module is responsible for overriding core StockProcessor process method 
Using this customisation we can fetch out of stock product variant in graphql

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Auraine`
 - Enable the module by running `php bin/magento module:enable Auraine_ExtendCatalogGraphQl`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration




## Specifications

 - Plugin
	- aroundProcess - Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor\StockProcessor > Auraine\ExtendCatalogGraphQl\Plugin\Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessor\StockProcessor


## Attributes



