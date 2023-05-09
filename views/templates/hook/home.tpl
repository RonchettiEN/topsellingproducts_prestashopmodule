<head>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>

<h1>Products on sale</h1>
<div class="swiper-container">
  <div class="swiper-wrapper">
  {foreach from=$products item=product key=key}
    <div class="swiper-slide">
      <a href='{$product.product_url}'>
        <img src="{$product.image_url}" alt="{$product.id_product}" >
        <div class="caption">
          <span class="price">{$product.price_with_reduction} ARS</span>
          <span class="old-price ">{$product.price} ARS</span>
          <span class="price-percent-reduction">-{$product.reduction}%</span>
        </div>
      </a>
    </div>
  {/foreach}
  </div>
</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
  var swiper = new Swiper('.swiper-container', {
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    slidesPerView: 5, 
    spaceBetween: 30, 
    breakpoints: { 
      768: {
        slidesPerView: 5,
        spaceBetween: 20,
      },
      576: {
        slidesPerView: 1,
        spaceBetween: 10,
      }
    }
  });
</script>