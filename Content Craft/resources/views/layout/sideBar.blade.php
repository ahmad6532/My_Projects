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

             @role('ADMIN')
                 <li class="nav-item">
                     <a href="{{ route('admin.index') }}" class="nav-link">
                         <i class="nav-icon fa-solid fa-gauge"></i>
                         <p>
                             Dashboard
                         </p>
                     </a>
                 </li>

                 <li class="nav-item">
                     <a href="{{ route('admin.allManagers') }}" class="nav-link">
                         <i class="nav-icon fa-solid fa-users"></i>
                         <p>Managers</p>
                     </a>
                 </li>
             @endrole

             @role('USER')
                 <li class="nav-item">
                     <a href="{{ route('article.index') }}" class="nav-link">
                         <i class="nav-icon fa-regular fa-newspaper"></i>
                         <p>
                             Articles Management
                         </p>
                     </a>
                 </li>

                 <li class="nav-item">
                     <a href="{{ route('plan.index') }}" class="nav-link">
                         <i class="nav-icon fa-solid fa-file-circle-plus"></i>
                         <p>View Plans</p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="{{ route('plan.userPlan') }}" class="nav-link">
                         <i class="nav-icon fa-solid fa-file-lines"></i>
                         <p>Purchase Plans</p>
                     </a>
                 </li>
             @endrole


              @role('MANAGER')
                 <li class="nav-item">
                     <a href="{{ route('manager.index') }}" class="nav-link">
                         <i class="nav-icon fa-regular fa-newspaper"></i>
                         <p>
                             Dashboard
                         </p>
                     </a>
                 </li>

                 <li class="nav-item">
                     <a href="{{ route('manager.allUsers') }}" class="nav-link">
                         <i class="nav-icon fa-solid fa-file-circle-plus"></i>
                         <p>Users</p>
                     </a>
                 </li>
             @endrole
         </ul>
     </nav>
     <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
 </aside>
