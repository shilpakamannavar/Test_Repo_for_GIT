Magento 2 extension which allows to filter categories by category_banner_slider_id,category_offers_slider_id,category_popular_brands_slider_id,category_our_exclusives_slider_id,category_blogs_slider_id category attributes.

##### Pre-requisits:

Created Category Attribute "category_banner_slider_id" with the help of InstallData.php.
Extended category_form.xml file in ui_component for displaying "category_banner_slider_id" category attribute in adding and editing category module.
Added "category_banner_slider_id,category_offers_slider_id,category_popular_brands_slider_id,category_our_exclusives_slider_id,category_blogs_slider_id" category attribute in schema.graphqls file for filtering custom attributes.
In graphql we can get category_banner_slider_id,category_offers_slider_id,category_popular_brands_slider_id,category_our_exclusives_slider_id,category_blogs_slider_id added categories with the help of filters like

{
  categoryList(filters: {ids:{eq:"3"}}) {
    id
    uid
    name
    category_banner_slider_id
    category_offers_slider_id
    category_popular_brands_slider_id
    category_our_exclusives_slider_id
    category_blogs_slider_id
  }
}


Based on "category_banner_slider_id=sliderId" or "category_offers_slider_id=sliderId" attribute, we can get all banner slider details like

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