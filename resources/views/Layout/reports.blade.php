
<x-layout>
    <x-slot name="header">
    Report page
  </x-slot>

    <div class="col-11 mt-1">
  <div class="card w-100 shadow rounded-3">
    <div class="card-body p-2"> 
      <table class="table table-hover mb-0">
        <thead>
      <th>#</th>
      <th>Customer</th>
      <th>Table Number</th>
      <th>Subtotal</th>
      <th>Total</th>
      <th>Status</th>
      <th>Action</th>
      </thead>
      <tbody>
        <?php $i=1;
              $subtotalSum = 0;
              $totalSum = 0;
        ?>
        @foreach($report as $re)
              <?php 
                $subtotalSum += $re->subtotal; 
                $totalSum += $re->total;
              ?>
              
        <tr>
          <td>{{$i++}}</td>
          <td>{{$re->customer->name}}</td>
          <td>{{$re->customer->table_number}}</td>
          <td>{{$re->subtotal}}$</td>
          <td>{{$re->total}}$</td>
          <td class="text-success">{{$re->status}}</td>
          <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#table{{$re->id}}">View</button></td>
        </tr>


        <!-- ------------------------Modal -->
        <x-modal id="table{{$re->id}}" data-order-id="{{ $re->id }}" class="modal-dialog modal-dialog-centered">
            <ul style="list-style-type:none;padding:5px;margin:0 auto;">
              <h3>{{ $re->table}}</h3>
              <li>Customer id : {{ $re->customer->id}}</li>
              <li>Customer Name : {{ $re->customer->name}}</li>
              <li>Note : {{ $re->note}}</li>
              <li>Tax : {{ $re->tax}}</li>
              <li>Total : {{ $re->total}}</li>
              <x-slot name="footer">
              <div class="d-flex">

              <button class="btn btn-primary" type="button"
              onclick="markPaidAndPrint({{ $re->id }}, 'receipt-{{ $re->id }}')">Print Reciept</button>
              </div>
              </x-slot>
            </ul>

            


            
          </x-modal>

        <!-- ------------------------------------------Reciept -->
        <x-reciept id="receipt-{{ $re->id }}">
            <p class="mb-1"><strong>Customer:</strong> {{ $re->customer->name }}</p>
            <p class="mb-1"><strong>Order ID:</strong> {{ $re->id }}</p>

            <hr class="my-1">


            <div>
                    @foreach ($re->orderItems as $item)
                  <div class="d-flex justify-content-between">
                      <span>{{ $item->product->pro_name }} x{{ $item->quantity }}</span>
                      <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                  </div>
                  @endforeach
            </div>
            <hr class="my-1">

            <div class="d-flex justify-content-between">
                <strong>Subtotal:</strong>
                <span>${{ number_format($re->subtotal, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <strong>Tax (10%):</strong>
                <span>${{ number_format($re->tax, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <strong>Total:</strong>
                <span>${{ number_format($re->total, 2) }}</span>
            </div>
        </x-reciept>

        <!-- End of Reciept -->
  
        @endforeach
        <tr>
        <td>Total</td>
        <td></td>
        <td></td>
        <td class="text-success">{{ number_format($subtotalSum, 2) }}$</td>
        <td class="text-success">{{ number_format($totalSum, 2) }}$</td>
        <td></td>
        <td></td>
        </tr>
      </tbody>
      </table>
    </div>
  </div>
</div>
</x-layout>