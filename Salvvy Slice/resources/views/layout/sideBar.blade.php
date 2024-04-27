 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <span href="index3.html" class="brand-link">
      <img src="/theme/tech.png" alt="Techswivel Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">TechSwivel</span>
    </span>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          {{-- @if (auth()->user()->role=='admin') --}}
         <li class="nav-item">
            <a href="{{route('rider.index')}}" class="nav-link">
              <i class="nav-icon fa-solid fa-person-biking"></i>
              <p>
               Rider
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('customer.index')}}" class="nav-link">
              <i class="nav-icon fa-solid fa-user"></i>
              <p>
               Customer
              </p>
            </a>
          </li>
          
           <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link">
              <i class="nav-icon fa-solid fa-o"></i>
              <p>
               Order
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('feedback.index')}}" class="nav-link">
              <i class="nav-icon fa-solid fa-comment"></i>
              <p>
               Feedback
              </p>
            </a>
          </li>
           {{-- @endif --}}
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>