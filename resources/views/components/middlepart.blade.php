<div class="container">
<div class="menu-container mt-2 shadow rounded-2 p-2 w-100">
  <div class="d-flex justify-content-center gap-4 flex-wrap flex-sm-nowrap overflow-auto">
    <div class="item-hover active" data-filter="all">All</div>
    @foreach($cate as $cates)
    <div class="item-hover" data-filter="{{ $cates->id }}">{{$cates->name}}</div>
    @endforeach
  </div>
</div>

  <div class="row justify-content-center gx-3 " id="menu">
    @foreach($pro as $pros)
      <div class="col-6 col-sm-4 col-md-3 d-flex mt-2 product-item" data-category="{{ $pros->cate_id ?? '' }}">
        <div class="card card-style w-100 h-100"onclick="addToCart('{{ $pros->id }}', '{{ $pros->pro_name }}', {{ $pros->pro_price }})">
          <!-- <img src="images/{{ $pros->pro_pic }}" class="card-img-top" alt="{{ $pros->pro_name }}"  style="height:10rem; object-fit:cover" /> -->
          <img src="{{ $pros->pro_pic }}"  class="card-img-top" alt="{{ $pros->pro_name }}"  style="height:10rem; object-fit:cover"  alt="Test Image" />

          <div class="card-body text-center">
            <h5 class="card-title">{{ $pros->pro_name }}</h5>
            <p class="card-text">{{ $pros->pro_price }}$</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
