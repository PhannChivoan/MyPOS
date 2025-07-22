<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$header}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet"/>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/27dad13733.js" crossorigin="anonymous"></script>
    
  </head>
</head>
<body >
    <header class="d-flex align-items-center gap-3" style="padding: 10px;">
  <div class="logo-header">
    <h1>Pos System</h1>
  </div>

  <div class="search-wrapper d-none d-xl-block" style="position: relative; width: 50%;">
    <input type="text" 
           id="search"
           class="btn-search" 
           placeholder="Search products..." 
           style="border: none; border-bottom: 2px solid #ccc; outline: none; padding: 5px 40px 5px 10px; width: 100%;" />
    <i class="fas fa-search search-icon" style="
        position: absolute;
        right: 210px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        pointer-events: none;"></i>
  </div>

  <div class="button-logo">
    <button class="btn btn-warning">Account</button>
  </div>
</header>


    <div class="main-container" >
    <div class="row h-100 min-vh-100"> 

  <div class="col-auto col-md-1 d-none d-xl-block">
    <nav class=" d-flex">
        <div class="d-flex">
        <div class="shadow bg-light">
        <ul class="nav-ul d-flex p-3 text-center">
            
              <li>
                <div>
                <a href="/"  class="{{ Request::is('/') ? 'active' : '' }}"><i class="fa-solid fa-house box-icon"></i>
                <span class="d-none d-lg-inline">Home</span>
                </a>
              </div>
              </li>
            
              <li>
              <div>
              <a href="/customers" class="{{ Request::is('customers') ? 'active' : '' }}"><i class="fa-solid fa-user"></i>
              <span class="d-none d-lg-inline">Customers</span>
              </a>
              </div>
            </li>
            
            <li>
              <div>
              <a href="/table" class="{{ Request::is('table') ? 'active' : '' }}"><i class="fa-solid fa-table"></i>
              <span class="d-none d-lg-inline"><br>Tables</span>
              </a>
            </div>
            </li>
            
            <li>
              <div>
                <a href="/cashiers" class="{{ Request::is('cashiers') ? 'active' : '' }}  text-muted nav-link disabled"><i class="fa-solid fa-dollar-sign"></i>
              <span class="d-none d-lg-inline"><br>Cashiers</span>
              </a>
              </div>
            </li>
            
            <li>
              <div>
              <a href="/orders" class="{{ Request::is('orders') ? 'active' : '' }}  text-muted nav-link disabled"><i class="fa-solid fa-bag-shopping"></i>
              <span class="d-none d-lg-inline"><br>Orders</span>
              </a>
            </div>
            </li>
            
            <li>
              <div>
                <a href="/reports " class="{{ Request::is('reports') ? 'active' : '' }}"><i class="fa-solid fa-circle-exclamation"></i>
              <span class="d-none d-lg-inline"><br>Reports</span>
              </a></div>
            </li>
            
            <li>
              <div>
              <a href="/setting" class="{{ Request::is('settings') ? 'active' : '' }}  text-muted nav-link disabled">
                <i class="fa-solid fa-gear"></i>
              <span class="d-none d-lg-inline "><br>Setting</span>
              </a>
            </div>
            </li>
        </ul>
        </div>
        </div>
    </nav>
  </div>
    
  {{$slot}}
    
    



    </div>
    </div>
    <script src="js/cart.js"></script>
    <script src="js/event.js"></script>
    <script src="js/imagePreview.js"></script>
    <script src="js/jqueryTable.js"></script>
    
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>   
</body>
</html>








