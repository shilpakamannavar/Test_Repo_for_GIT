Magento 2 extension which allows to filter categories by slider_id category attributes.

##### Pre-requisits:

Created Category Attribute "slider_id" with the help of InstallData.php.
Extended category_form.xml file in ui_component for displaying "slider_id" category attribute in adding and editing category module.
Added "slider_id" category attribute in schema.graphqls file for filtering custom attributes.
In graphql we can get slider_id added categories with the help of filters like

{
  categoryList(filters: {ids:{eq:"3"}}) {
    id
    uid
    name
    slider_id
  }
}


Based on "slider_id=sliderId" attribute, we can get all banner slider details like

{
  {
    BannerSlider(sliderId: 1) {
      additional_information
      banners {
        alt_text
        is_enabled
        link
        resource_path
        resource_type
        slider_id
        sort_order
        title
      }
      discover
      is_enabled
      is_show_title
      link
      product_id
      slider_id
      title
  }
}