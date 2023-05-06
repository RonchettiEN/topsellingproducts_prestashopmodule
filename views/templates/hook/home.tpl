<div class="container">
  <h2>Products on sale</h2>
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      {foreach from=$products item=product key=key}
        <li data-target="#myCarousel" data-slide-to="{$product.id_product}" {if $key == 0} class="active"{/if}></li>
      {/foreach}
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      {foreach from=$products item=product key=key}
        <div class="item{if $key == 0} active{/if}">
          <img src="{$product.image_url}" alt="{$product.id_product}" style="width:25%;">
          <div class="carousel-caption">
            <h3>{$product.id_product}</h3>
            <p>{$product.price}</p>
          </div>
        </div>
      {/foreach}
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


