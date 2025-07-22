<x-layout>
  <x-slot name="header">
    Customers Page
  </x-slot>

<div class="col-11">
  <h3 class="mx-2 mt-2">Dine in</h3>
<div class="d-flex flex-wrap">
@foreach($order as $orders)
<div class="card rounded mx-2 mt-1" style="width: 13rem;height:13rem;" data-bs-toggle="modal" data-bs-target="#table{{ $orders->id}}">
  <div class="card-body">
    <h5 class="card-title">Table Number</h5>
    <h4 class="card-subtitle mb-2 text-muted">{{ $orders->customer->table_number}}</h4>
    <p>Click to view</p>
    <p class="text-danger paid-status" data-order-id="{{ $orders->id }}" >{{$orders->status}}</p>
  </div>
</div>

<x-modal id="table{{$orders->id}}" data-order-id="{{ $orders->id }}" class="modal-dialog modal-dialog-centered" method="POST" action="/addNewOrder">

  <div style="display: block; width: 100%;">
    <ul style="list-style-type:none; padding:5px; margin:0 auto;">
      <h3>{{ $orders->table }}</h3>
      <li>Customer id : {{ $orders->customer->id }}</li>
      <li>Customer Name : {{ $orders->customer->name }}</li>
      <li>Note : {{ $orders->note }}</li>
      <li>Tax : {{ $orders->tax }}</li>
      <li>Total : {{ $orders->total }}</li>
    </ul>

    <!-- menu form added -->
    <div class="mt-3 d-none" id="menu-form-container" style="width: 100%;"> <!-- full width block div -->

        @csrf
        <input type="hidden" name="customer_id" value="{{ $orders->customer->id }}">

        <div class="menu-group">
            <div class="menu-item border p-2 mb-2">
                <div class="row">
                    <div class="col-md-4">
                        <label>Product</label>
                        <select name="product_id[]" class="form-control" required>
                            @foreach($products as $product)
                               <option value="{{ $product->id }}" data-price="{{ $product->pro_price }}">{{ $product->pro_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity[]" min="1" value="1" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label>Price</label>
                        <input type="number" name="price[]" min="0" step="0.01" value="0" class="form-control" readonly>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-item">Remove</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary my-2" id="add-item">Add Another Menu</button>
        <button type="submit" class="btn btn-success">Add All Menus</button>
    </div>

  </div>
  <!-- end of menu form added -->
  <x-slot name="footer">
    <button type="button" id="add-order" onClick="showneworder()" class="btn btn-secondary me-2">Add Menu</button>
    <button class="btn btn-primary" type="button"
      onclick="markPaidAndPrint({{ $orders->id }}, 'receipt-{{ $orders->id }}')">Cash out</button>
  </x-slot>

</x-modal>


<!-- ------------------------------------------Reciept -->
 <x-reciept id="receipt-{{ $orders->id }}">
    <p class="mb-1"><strong>Customer:</strong> {{ $orders->customer->name }}</p>
    <p class="mb-1"><strong>Order ID:</strong> {{ $orders->id }}</p>

    <hr class="my-1">


    <div>
            @foreach ($orders->orderItems as $item)
          <div class="d-flex justify-content-between">
              <span>{{ $item->product->pro_name }} x{{ $item->quantity }}</span>
              <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
          </div>
          @endforeach
    </div>
    <hr class="my-1">

    <div class="d-flex justify-content-between">
        <strong>Subtotal:</strong>
        <span>${{ number_format($orders->subtotal, 2) }}</span>
    </div>
    <div class="d-flex justify-content-between">
        <strong>Tax (10%):</strong>
        <span>${{ number_format($orders->tax, 2) }}</span>
    </div>
    <div class="d-flex justify-content-between">
        <strong>Total:</strong>
        <span>${{ number_format($orders->total, 2) }}</span>
    </div>
</x-reciept>

<!-- End of Reciept -->
@endforeach

<div class="col-11">
  <hr> 
  <h3 class="mx-2">Delivery</h3>
<div class="d-flex flex-wrap">

@foreach($delivery as $deliverys)
<div class="card rounded mx-2 mt-1" style="width: 13rem;height:13rem;" data-bs-toggle="modal" data-bs-target="#table{{ $deliverys->id}}">
  <div class="card-body">
    <h5 class="card-title">Delivery</h5>
    <h4 class="card-subtitle mb-2 text-muted">{{ $deliverys->customer->name}}</h4>
    <p>Click to view</p>
    <p class="text-danger paid-status" data-order-id="{{ $deliverys->id }}" >unpaid</p>
  </div>
</div>

<x-modal id="table{{$deliverys->id}}" data-order-id="{{ $deliverys->id }}" class="modal-dialog modal-dialog-centered">
  <ul style="list-style-type:none;padding:5px;margin:0 auto;">
    <h3>{{ $deliverys->table}}</h3>
    <li>Customer id : {{ $deliverys->customer->id}}</li>
    <li>Customer Name : {{ $deliverys->customer->name}}</li>
    <li>Note : {{ $deliverys->note}}</li>
    <li>Tax : {{ $deliverys->tax}}</li>
    <li>Total : {{ $deliverys->total}}</li>
    <div class="d-flex">
      <div>
    <label>Status</label>
    </div>
    <div>
    <select name="payment"  data-order-id="{{ $orders->id }}" class="form-control mx-2 payment-select">
      <option value="unpaid">Unpaid</option>
      <option value="paid">paid</option>
    </select>
    </div>
    <div class="text-center">
   
  </div>
  </ul>
  
  
  
  
  
</x-modal>

@endforeach
</div>
</div>


</div>
</div>
</x-layout>



