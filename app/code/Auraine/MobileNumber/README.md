# Auraine MobileNumber Custom Module

    ``auraine/module-mobilenumber``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Extended Magecomp Mobile Login module for achieving bellow graphql results
1. mobilenumber in customer query 
```graphql
{
  customer {
    email
    firstname
    lastname
    mobilenumber
  }
}
```
2. checkCustomerExist based on mobile & email
```graphql
{
  checkCustomerExists(field_value: "praveenvishnoi165@gmail.com", type: "email")
}
```
```graphql
{
  checkCustomerExists(field_value: "917815048029", type: "mobile")
}
```
## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Auraine`
 - Enable the module by running `php bin/magento module:enable Auraine_MobileNumber`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration


## Specifications
- Make sure you use country code before mobile number in any request

## Attributes
- mobilenumber
