 <header class="header">
     <div class="header-top">
         <div class="container">

             <div class="header-right">


                 <div class="header-dropdown dropdown-expanded">
                     <a href="#">Links</a>
                     <div class="header-menu">
                         <ul>
                             @guest
                             <li><a href="{{route('tenant_ecommerce_login')}}" class="login-link">LOG IN</a></li>
                             @else
                             <li><a href="#">{{ Auth::user()->email }}</a></li>
                             <li>
                                 <a role="menuitem" href="{{ route('logout') }}"
                                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                     <i class="fas fa-power-off"></i> @lang('app.buttons.logout')
                                 </a>
                                 <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                     style="display: none;">
                                     @csrf
                                 </form>
                             </li>
                             @endguest
                         </ul>
                     </div><!-- End .header-menu -->
                 </div><!-- End .header-dropown -->
             </div><!-- End .header-right -->
         </div><!-- End .container -->
     </div><!-- End .header-top -->

     <div class="header-middle">
         <div class="container">
             <div class="header-left">
                 <a href="/ecommerce" class="logo">
                     <img src="{{ asset('porto-ecommerce/assets/images/logo.png') }}" alt="Porto Logo">
                 </a>
             </div><!-- End .header-left -->

             <div id="header_bar" class="header-center">


                 <div class="dropdown">
                     <input placeholder="Buscar..." v-model="value" v-on:keyup="autoComplete"
                         class="form-control dropdown-toggle form-control-lg" type="text" id="dropdownMenuLink"
                         data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                     <div class="dropdown-menu w-100" aria-labelledby="dropdownMenuLink">

                         <a @click="suggestionClick(result)" v-for="result in results" class="dropdown-item"
                             :href="'/ecommerce/item/' + result.id"> <img width="60" height="60"
                                 :src="result.image_url_small" alt="" style="margin-right:5px;"> <span
                                 style="font-size: 14px">@{{ result.description }} </span> </a>
                     </div>
                 </div>


             </div><!-- End .headeer-center -->

             <div class="header-right">
                 <button class="mobile-menu-toggler" type="button">
                     <i class="icon-menu"></i>
                 </button>
                 <div class="header-contact">
                     <span>Call us now</span>
                     <a href="tel:#"><strong>+999 111 888</strong></a>
                 </div><!-- End .header-contact -->

                 @include('tenant.layouts.partials_ecommerce.cart_dropdown')

             </div><!-- End .header-right -->
         </div><!-- End .container -->
     </div><!-- End .header-middle -->

     <div class="header-bottom sticky-header">
         <div class="container">
             <nav class="main-nav">
                 <ul class="menu sf-arrows">

                     @foreach ($items as $item)
                     <li><a href="#">{{ $item->name }}</a></li>
                     @endforeach

                 </ul>
             </nav>
         </div><!-- End .header-bottom -->
     </div><!-- End .header-bottom -->
 </header><!-- End .header -->
 @push('scripts')

  <script type="text/javascript">
      var app = new Vue({
          el: '#header_bar',
          data: {
              value: '',
              suggestions: [],
              resource: 'ecommerce',
              results: [],
          },
          created() {
              this.getItems()
          },
          methods: {
              autoComplete() {

                  if (this.value) {

                      this.results = this.suggestions.filter((obj) => {
                          let city = obj.description.toUpperCase()
                          let val = this.value.toUpperCase()
                          return city.includes(val)
                      })

                  } else {
                    this.results = this.suggestions
                  }


              },
              getItems() {
                  let contex = this
                  fetch(`/${this.resource}/items_bar`)
                      .then(function (response) {
                          return response.json();
                      })
                      .then(function (myJson) {
                          // console.log(myJson.data);
                          contex.suggestions = myJson.data
                          contex.results = contex.suggestions
                      });
              },
              suggestionClick(item) {
                  console.log(item)
                  this.results = []
                  this.value = item.description
              }

          }
      })

  </script>

  @endpush