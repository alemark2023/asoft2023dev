<div class="dropdown cart-dropdown">
    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
        data-display="static">
        <span class="cart-count">0</span>
    </a>
    <div class="dropdown-menu">
        <div class="dropdownmenu-wrapper">

            <div class="dropdown-cart-products">

            </div><!-- End .cart-product -->

            <div class="dropdown-cart-total">
                <span>Total</span>

                <span class="cart-total-price">$1114.00</span>
            </div><!-- End .dropdown-cart-total -->

            <div class="dropdown-cart-action">
                <a  href="{{ route('tenant_detail_cart') }}" class="btn">View Cart</a>
                <!--<a href="#" class="btn">Checkout</a> -->
            </div><!-- End .dropdown-cart-total -->
        </div><!-- End .dropdownmenu-wrapper -->
    </div><!-- End .dropdown-menu -->
</div><!-- End .dropdown -->



@push('scripts')
<script type="text/javascript">

	function remove(id)
	{
		debugger
		let array = localStorage.getItem('products_cart');
		array = JSON.parse(array);
		let indexFound = array.findIndex( x=> x.id == id)
		array.splice(indexFound, 1);
		localStorage.setItem('products_cart', JSON.stringify( array ) );
		populate();
	
	}

	function populate()
	{
		$(".dropdown-cart-products").empty();
			$(".cart-count").empty();
			let count = 0;
			//get data local syrogare prodicts
			let array = localStorage.getItem('products_cart');
			array = JSON.parse(array)
			count = array.length;
				
			array.forEach(element => {
				
				$(".dropdown-cart-products").append( `
						<div class="product">
							<div class="product-details">
							<h4 class="product-title">
								<a href="$">${element.name}</a>
							</h4>
							<span class="cart-product-info">
								<span class="cart-product-qty">1</span> x ${element.sale_unit_price}
							</span>
							</div>
							<figure class="product-image-container">
								<a href="#" class="product-image">
									<img alt="product" src="/storage/uploads/items/${element.image_small}" />
								</a>
								<a href="#" onclick="remove(${element.id})" class="btn-remove" title="Remove Product">
									<i class="icon-cancel"></i>
								</a>
							</figure>
						</div>` 
					);
			});
			
			$(".cart-count").append(count);
	}

	
	$(function(){
    'use strict';
		populate();
    });
</script>
@endpush