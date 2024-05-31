/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: datatables init js
*/

document.addEventListener('DOMContentLoaded', function () {
    let table = new DataTable('#example',);
});


document.addEventListener('DOMContentLoaded', function () {
  let table = new DataTable('#scroll-vertical', {
      "scrollY":        "210px",
      "scrollCollapse": true,
      "paging":         false
    });

});

document.addEventListener('DOMContentLoaded', function () {
  let table = new DataTable('#scroll-horizontal', {
      "scrollX": true
    });
});

document.addEventListener('DOMContentLoaded', function () {
  let table = new DataTable('#alternative-pagination', {
      "pagingType": "full_numbers"
    });
});

$(document).ready(function() {
    var t = $('#add-rows').DataTable();
    var counter = 1;

    $('#addRow').on( 'click', function () {
        t.row.add( [
            counter +'.1',
            counter +'.2',
            counter +'.3',
            counter +'.4',
            counter +'.5',
            counter +'.6',
            counter +'.7',
            counter +'.8',
            counter +'.9',
            counter +'.10',
            counter +'.11',
            counter +'.12'
        ] ).draw( false );

        counter++;
    } );

    // Automatically add a first row of data
    $('#addRow').click();
});


$(document).ready(function() {
    $('#example').DataTable();
});

//fixed header
document.addEventListener('DOMContentLoaded', function () {
  let table = new DataTable('#fixed-header', {
      "fixedHeader": true
    });

});

//modal data datables
document.addEventListener('DOMContentLoaded', function () {
  let table = new DataTable('#model-datatables', {
      responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details for '+data[0]+' '+data[1];
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'table'
                } )
            }
        }
    });

});

//buttons exmples
document.addEventListener('DOMContentLoaded', function () {
  let table = new DataTable('#buttons-datatables', {
        dom: 'Bfrtip',

    });
});

//buttons exmples
document.addEventListener('DOMContentLoaded', function () {
  let table = new DataTable('#ajax-datatables', {
        "ajax": 'build/json/datatable.json'
    });

});


document.addEventListener('DOMContentLoaded', function() {
    // Select the pagination items for previous and next buttons
    const prevButton = document.querySelector('#buttons-datatables_previous a');
    const nextButton = document.querySelector('#buttons-datatables_next a');

    if (prevButton) {
        // Clear the existing text
        prevButton.innerHTML = '';
        // Add the Font Awesome left chevron icon
        prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
    }

    if (nextButton) {
        // Clear the existing text
        nextButton.innerHTML = '';
        // Add the Font Awesome right chevron icon
        nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
    }
});
