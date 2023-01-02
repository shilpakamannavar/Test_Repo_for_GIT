Magento 2 Custom Module Auraine_SwatchData which allows to get swatch data in Layered navigation aggregations in graphql?.

##### Pre-requisits:

Created custom module Auraine_SwatchData.
Entended AggregationOptionInterface Interface For Graphql
For getting Swatch Details you can add below query in Graphql
aggregations {
          attribute_code
          label
          options{
            count
            label
            value
            swatch_data{
              value
              type
            }
          }
        }
