@if( isset($products) || isset($customProducts))
<ul class="dropdown-menu" style="display:block;">

    @if(isset($products))
    @foreach($products as $product)
        @php
            if(!boolval($product->is_active) || !boolval($product->is_publish)){
                continue;
            }
        @endphp
        <li><a href="{{ route('searchResult')}}?key={{$query}}">
            <span class="content-search">
            @if(strlen($product->product_name) > 40)
                {!! substr(strip_tags($product->product_name), 0, 40) !!}...
            @else
            {{$product->product_name}} 
            @endif</span>
            </a>
        </li>
    @endforeach
    @endif 

    @if(isset($customProducts))
    @foreach($customProducts as $product)

        @php
            if(!boolval($product->is_active)){
                continue;
            }
        @endphp
        <li><a href="{{ route('customize.customorder_show',$product->id)}}">
{{-- 
            @if($product->product_thumbnail)
                <img src="{{ asset($product->product_thumbnail) }}" alt="" style="{{ strlen($product->product_name) > 18 ? 'float:left;' :'' }}">
            @endif --}}

            <span class="content-search">
                @if(strlen($product->product_name) > 40)
                {!! substr(strip_tags($product->product_name), 0, 40) !!}...
                @else
                {{$product->product_name}}
                @endif
            </span>
            </a>
        </li>
    @endforeach
    @endif

</ul>
@endif 