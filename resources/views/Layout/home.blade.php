<x-layout>
  <x-slot name="header">
    Home Page
  </x-slot>
    <div class="col-12 col-md-7 overflow-auto" style="height: 100vh;">
      @include('components.middlepart')
    </div>
    
    <div class="col-12 col-md-4" style="height: 70vh;">
    @include('components.rightpart')
    </div>
</x-layout>

<x-modal id="exampleModal" action="/productCreate" method="post" enctype="multipart/form-data">
      <div class="col-5 px-2 d-flex flex-column justify-content-between">
        <label>Image</label>
        <img id="previewImage" src="#" alt="Image Preview" class="img-fluid mt-2 d-none" style="max-height: 200px;" />
        <input type="file" name="pic" id="imageInput" class="form-control"/>
         
      </div>

      <div class="col-7">
        <label>Name</label>
        <input type="text" class="form-control" name="name"/>
        <label>Price</label>
        <input type="text" class="form-control" name="price"/>
        <label>Category</label>
        <select class="form-control" name="category">
            @foreach($cate as $cates)
          <option value="{{$cates->id}}">{{$cates->name}}</option>
            @endforeach
        </select>
        </div>
        
        <x-slot name="footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create</button>
        </x-slot>
      
</x-modal>

<form action="/orders" id="cartForm"  method="post" class="d-none">
  <div class="container">
      <div class="row px-2 d-flex  justify-content-between">
         <!-- Hidden input for cart JSON -->
        <input type="hidden" name="cart" id="cartData" />
        </div>
        </div>
      
</form>


<!-- Create customer -->
<x-modal id="customerModalCreate" action="/customersCreate"  method="post">
  <div class="container">
      <div class="row px-2 d-flex  justify-content-between">

        <div class="col-md-6">
          <label>Customer Name</label>
          <input type="text" class="form-control" name="customer"/>
          <label>Table Number</label>
          <input type="text" class="form-control" name="table"/>
          
         
      </div>
      <div class="col-md-6">
        <label>Order Type</label>
        <select class="form-control" id="order" name="order">
          <option value="dine-in">Dine in</option>
          <option value="delivery">Delivery</option>
        </select>
        <label>Phone Number</label>
        <input type="text" class="form-control" name="phone" id="phone" disabled/>
        
      </div>

        <div class="col-12">
          <label>Address</label>
          <textarea name="address" id="add" class="form-control" disabled></textarea>
          <!-- <label>Note</label>
          <textarea name="note" class="form-control"></textarea> -->
        </div>

        </div>
        </div>
        
        <x-slot name="footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create</button>
        </x-slot>
      
</x-modal>