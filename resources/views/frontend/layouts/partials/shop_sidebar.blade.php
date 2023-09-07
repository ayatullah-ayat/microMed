<div class="accordion" id="accordionPanelsStayOpenExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                aria-controls="panelsStayOpen-collapseOne">
                Category
            </button>
        </h2>

        {{-- @dd($category) --}}
        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
            aria-labelledby="panelsStayOpen-headingOne">
            <div class="accordion-body">
                <!-- --------------------- category start ---------------------  -->
                @isset($categories)
                    @foreach ($categories as $category )
                        <div class="category category_container">
                            <div class="form-check parentCategory" type="button" data-category="{{$category->id}}">
                                <input name="category" type="checkbox" id="{{$category->id}}" value="{{$category->id}}">
                                <label  for="{{$category->id}}" class="form-check-label {{ isset($category->subCategories) && count($category->subCategories) ? 'hasSub' : '' }}" type="button" data-categoryid="{{$category->id}}">{{$category->category_name}}</label>
                            </div>
                            @foreach( $category->subCategories as $subCategory )
                                <div style="margin-left: 15px; font-size: 12px" class="form-check subCategory" type="button" data-category="{{$subCategory->id}}">
                                    <input name="sub_category" type="checkbox" id="{{$subCategory->id}}" value="{{$subCategory->id}}">
                                    <label  for="{{$subCategory->id}}" class="form-check-label hasSub" type="button" data-categoryid="{{$subCategory->id}}">{{$subCategory->subcategory_name}}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endisset
    
                <!-- ---------------- end of category --------------------  -->

            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                aria-controls="panelsStayOpen-collapseTwo">
                Color
            </button>
        </h2>
        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse"
            aria-labelledby="panelsStayOpen-headingTwo">
            <div class="accordion-body">
                <div class="single-prodect-color">
                    <div class=" ms-2 row color_container" style="margin-left: -0.5rem!important;">
                        @isset($productColor)
                            @foreach ($productColor as $indx => $colorItem)
                                <div type="button" data-color="{{ $colorItem->variant_name  ?? ''}}" class="col-md-2 col-1 color {{ matchColor($colorItem->variant_name) ? ' black' : '' }}" style="background-color: {{ $colorItem->variant_name }}; {{ matchColor($colorItem->variant_name) ? 'box-shadow: 0px 0px 2px #000;':''}}"> <i
                                        class="fa-solid fa-check"></i> 
                                </div>
                            @endforeach
                        @endisset
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                aria-controls="panelsStayOpen-collapseThree">
                Size
            </button>
        </h2>

        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse"
            aria-labelledby="panelsStayOpen-headingThree">
            <div class="accordion-body">
                <div class="single-prodect-size">
                    <div class="row size_container" style="margin-left: -0.5rem!important;">
                        @isset($productSize)
                            @foreach ($productSize as $indx=> $item)
                            <div type="button" data-size="{{ $item->variant_name  ?? ''}}" class=" col-md-2 col-1 size"><span>{{ $item->variant_name }}</span> </div>
                            @endforeach
                        @endisset

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false"
                aria-controls="panelsStayOpen-collapseThree">
                Price
            </button>
        </h2>
        <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse"
            aria-labelledby="panelsStayOpen-headingFour">
            <div class="accordion-body w-100">

                <div class="price-slider">
                    <div id="slider-range"
                        class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content">
                        <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                        <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                        <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span> 
                    </div>
                    <span id="min-price" data-currency="৳" class="slider-price">0</span> <span
                        class="seperator">-</span> <span id="max-price" data-currency="৳" data-max="{{$maxSalesPrice + 5}}"
                        class="slider-price">{{$maxSalesPrice + 4}}</span>
                </div>

            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingFive">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false"
                aria-controls="panelsStayOpen-collapseThree">
                Tag
            </button>
        </h2>
        <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse"
            aria-labelledby="panelsStayOpen-headingFive">
            <div class="accordion-body">
                <div class="single-prodect-category">
                    <h3 class="mb-2"> Tags: </h3>
                    <div class="d-flex flex-wrap  gap-1">
                        @isset($tags)
                            @foreach ($tags as $tag)
                            <div><button data-tag="{{$tag->tag_name}}" type="button" class="btn rounded btn-light me-2 filterTagName">{{$tag->tag_name}}</button></div>
                            @endforeach
                        @endisset 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>