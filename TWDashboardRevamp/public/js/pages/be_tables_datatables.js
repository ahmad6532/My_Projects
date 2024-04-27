/*
 *  Document   : be_tables_datatables.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in DataTables Page
 */

// DataTables, for more examples you can check out https://www.datatables.net/
class pageTablesDatatables {
  /*
   * Init DataTables functionality
   *
   */
  static initDataTables() {
    // Override a few default classes
    jQuery.extend(jQuery.fn.dataTable.ext.classes, {
      sWrapper: "dataTables_wrapper dt-bootstrap5",
      sFilterInput: "form-control",
      sLengthSelect: "form-select"
    });

    // Override a few defaults
    jQuery.extend(true, jQuery.fn.dataTable.defaults, {
      language: {
        lengthMenu: "_MENU_",
        search: "_INPUT_",
        searchPlaceholder: "Search..",
        info: "Page <strong>_PAGE_</strong> of <strong>_PAGES_</strong>",
        paginate: {
          first: '<i class="fa fa-angle-double-left"></i>',
          previous: '<i class="fa fa-angle-left"></i>',
          next: '<i class="fa fa-angle-right"></i>',
          last: '<i class="fa fa-angle-double-right"></i>'
        }
      }
    });

    // Override buttons default classes
    jQuery.extend(true, jQuery.fn.DataTable.Buttons.defaults, {
      dom: {
        button: {
          className: 'btn btn-sm btn-primary'
        },
      }
    });

      jQuery('.dataTablePackages').DataTable({

          language: {
              url:  language == "en"?"/lang/English.json":"/lang/Spanish.json",
          },
          fixedHeader: {
              header: true,
              footer: true
          },
          "columnDefs": [
              {
                  "targets": 'no-sort',
                  "orderable": false,
                  "scrollX": true,
              },
              { targets:"_all", orderable: true },
              { targets:[0,1,2], className: "desktop" },
              { targets:[0,1], className: "mobile" },
              { targets:[0,1,2], className: "tablet" }],
          pagingType: "full_numbers",
          pageLength: 10,
          lengthMenu: [[5, 10, 15], [5, 10, 15]],
          autoWidth: false,
          responsive: true,
          bFilter: true,
          bInfo: true,
          bLengthChange:false,
          drawCallback:function(){
              var $api = this.api();
              var pages = $api.page.info().pages;
              var rows = $api.data().length;
              if(rows<=10){
                  //alert(rows);
                  //document.getElementById("dataTables_paginate").style.display = "none";
                  var myNode = document.getElementById("dataTables_info");
                  myNode.innerHTML = '';
                  var pagination = document.getElementById("dataTables_paginate");
                  pagination.innerHTML = '';

              }
          }
      });

    // Init responsive DataTable
    jQuery('.dataTablePlayers').DataTable({

        language: {
            url:  language == "en"?"/lang/English.json":"/lang/Spanish.json",
        },
        fixedHeader: {
            header: true,
            footer: true
        },
        "columnDefs": [
            {
                "targets": 'no-sort',
                "orderable": false,
                "scrollX": true,
            },
            { targets:"_all", orderable: true },
            { targets:[0,1,2,3,4,5], className: "desktop" },
            { targets:[0,1], className: "mobile" },
            { targets:[0,1,2,3], className: "tablet" }],
        pagingType: "first_last_numbers",
        pageLength: 10,
        lengthMenu: [[5, 10, 15], [5, 10, 15]],
        autoWidth: false,
        responsive: true,
        bFilter: true,
        bInfo: true,
        bLengthChange:false,
        drawCallback:function(){
            var $api = this.api();
            var pages = $api.page.info().pages;
            var rows = $api.data().length;
            if(rows<=10){
                //alert(rows);
                //document.getElementById("dataTables_paginate").style.display = "none";
                var myNode = document.getElementById("dataTables_info");
                myNode.innerHTML = '';
                var pagination = document.getElementById("dataTables_paginate");
                pagination.innerHTML = '';

            }
        }
    });


      jQuery('.dataTableTransactions').DataTable({

          language: {
              url:  language == "en"?"/lang/English.json":"/lang/Spanish.json",
          },
          fixedHeader: {
              header: true,
              footer: true
          },
          "columnDefs": [
              {
                  "targets": 'no-sort',
                  "orderable": false,
                  "scrollX": true,
              },
              { targets:"_all", orderable: true },
              { targets:[0,1,2,3,4], className: "desktop" },
              { targets:[1], className: "mobile" },
              { targets:[0,1,2,3,4], className: "tablet" }],
          pagingType: "first_last_numbers",
          pageLength: 10,
          lengthMenu: [[5, 10, 15], [5, 10, 15]],
          autoWidth: false,
          responsive: true,
          bFilter: true,
          bInfo: true,
          bLengthChange:false,
          drawCallback:function(){
              var $api = this.api();
              var pages = $api.page.info().pages;
              var rows = $api.data().length;
              if(rows<=10){
                  //alert(rows);
                  //document.getElementById("dataTables_paginate").style.display = "none";
                  var myNode = document.getElementById("dataTables_info");
                  myNode.innerHTML = '';
                  var pagination = document.getElementById("dataTables_paginate");
                  pagination.innerHTML = '';

              }
          }
      });


  }

  /*
   * Init functionality
   *
   */
  static init() {
    this.initDataTables();
  }
}

// Initialize when page loads
Dashmix.onLoad(pageTablesDatatables.init());
